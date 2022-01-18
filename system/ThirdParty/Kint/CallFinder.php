<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2013 Jonathan Vollebregt (jnvsor@gmail.com), Rokas Šleinius (raveren@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Kint;

class CallFinder {
	private static $ignore
		= array(
			T_CLOSE_TAG => TRUE, T_COMMENT => TRUE, T_DOC_COMMENT => TRUE, T_INLINE_HTML => TRUE, T_OPEN_TAG => TRUE, T_OPEN_TAG_WITH_ECHO => TRUE, T_WHITESPACE => TRUE,
		);

	/**
	 * Things we need to do specially for operator tokens:
	 * - Refuse to strip spaces around them
	 * - Wrap the access path in parentheses if there
	 *   are any of these in the final short parameter.
	 */
	private static $operator
		= array(
			T_AND_EQUAL => TRUE, T_BOOLEAN_AND => TRUE, T_BOOLEAN_OR => TRUE, T_ARRAY_CAST => TRUE, T_BOOL_CAST => TRUE, T_CLONE => TRUE, T_CONCAT_EQUAL => TRUE, T_DEC => TRUE, T_DIV_EQUAL => TRUE, T_DOUBLE_CAST => TRUE, T_INC => TRUE, T_INCLUDE => TRUE, T_INCLUDE_ONCE => TRUE, T_INSTANCEOF => TRUE, T_INT_CAST => TRUE, T_IS_EQUAL => TRUE, T_IS_GREATER_OR_EQUAL => TRUE, T_IS_IDENTICAL => TRUE, T_IS_NOT_EQUAL => TRUE, T_IS_NOT_IDENTICAL => TRUE, T_IS_SMALLER_OR_EQUAL => TRUE, T_LOGICAL_AND => TRUE, T_LOGICAL_OR => TRUE, T_LOGICAL_XOR => TRUE, T_MINUS_EQUAL => TRUE, T_MOD_EQUAL => TRUE, T_MUL_EQUAL => TRUE, T_NEW => TRUE, T_OBJECT_CAST => TRUE, T_OR_EQUAL => TRUE, T_PLUS_EQUAL => TRUE, T_REQUIRE => TRUE, T_REQUIRE_ONCE => TRUE, T_SL => TRUE, T_SL_EQUAL => TRUE, T_SR => TRUE, T_SR_EQUAL => TRUE, T_STRING_CAST => TRUE, T_UNSET_CAST => TRUE, T_XOR_EQUAL => TRUE, '!' => TRUE, '%' => TRUE, '&' => TRUE, '*' => TRUE, '+' => TRUE, '-' => TRUE, '.' => TRUE, '/' => TRUE, ':' => TRUE, '<' => TRUE, '=' => TRUE, '>' => TRUE, '?' => TRUE, '^' => TRUE, '|' => TRUE, '~' => TRUE,
		);

	private static $strip
		= array(
			'(' => TRUE, ')' => TRUE, '[' => TRUE, ']' => TRUE, '{' => TRUE, '}' => TRUE, T_OBJECT_OPERATOR => TRUE, T_DOUBLE_COLON => TRUE, T_NS_SEPARATOR => TRUE,
		);

	public static function getFunctionCalls($source, $line, $function)
	{
		static $up = array(
			'(' => TRUE, '[' => TRUE, '{' => TRUE, T_CURLY_OPEN => TRUE, T_DOLLAR_OPEN_CURLY_BRACES => TRUE,
		);
		static $down = array(
			')' => TRUE, ']' => TRUE, '}' => TRUE,
		);
		static $modifiers = array(
			'!' => TRUE, '@' => TRUE, '~' => TRUE, '+' => TRUE, '-' => TRUE,
		);
		static $identifier = array(
			T_DOUBLE_COLON => TRUE, T_STRING => TRUE, T_NS_SEPARATOR => TRUE,
		);

		if (KINT_PHP56)
		{
			self::$operator[T_POW] = TRUE;
			self::$operator[T_POW_EQUAL] = TRUE;
		}

		if (KINT_PHP70)
		{
			self::$operator[T_SPACESHIP] = TRUE;
		}

		if (KINT_PHP74)
		{
			self::$operator[T_COALESCE_EQUAL] = TRUE;
		}

		$tokens = \token_get_all($source);
		$cursor = 1;
		$function_calls = array();
		/** @var array<int, null|array|string> Performance optimization preventing backwards loops */
		$prev_tokens = array(NULL, NULL, NULL);

		if (\is_array($function))
		{
			$class = \explode('\\', $function[0]);
			$class = \strtolower(\end($class));
			$function = \strtolower($function[1]);
		} else
		{
			$class = NULL;
			$function = \strtolower($function);
		}

		// Loop through tokens
		foreach ($tokens as $index => $token)
		{
			if ( ! \is_array($token))
			{
				continue;
			}

			// Count newlines for line number instead of using $token[2]
			// since certain situations (String tokens after whitespace) may
			// not have the correct line number unless you do this manually
			$cursor += \substr_count($token[1], "\n");
			if ($cursor > $line)
			{
				break;
			}

			// Store the last real tokens for later
			if (isset(self::$ignore[$token[0]]))
			{
				continue;
			}

			$prev_tokens = array($prev_tokens[1], $prev_tokens[2], $token);

			// Check if it's the right type to be the function we're looking for
			if (T_STRING !== $token[0] || \strtolower($token[1]) !== $function)
			{
				continue;
			}

			// Check if it's a function call
			$nextReal = self::realTokenIndex($tokens, $index);
			if ( ! isset($nextReal, $tokens[$nextReal]) || '(' !== $tokens[$nextReal])
			{
				continue;
			}

			// Check if it matches the signature
			if (NULL === $class)
			{
				if ($prev_tokens[1] && \in_array($prev_tokens[1][0], array(T_DOUBLE_COLON, T_OBJECT_OPERATOR), TRUE))
				{
					continue;
				}
			} else
			{
				if ( ! $prev_tokens[1] || T_DOUBLE_COLON !== $prev_tokens[1][0])
				{
					continue;
				}

				if ( ! $prev_tokens[0] || T_STRING !== $prev_tokens[0][0] || \strtolower($prev_tokens[0][1]) !== $class)
				{
					continue;
				}
			}

			$inner_cursor = $cursor;
			$depth = 1;             // The depth respective to the function call
			$offset = $nextReal + 1; // The start of the function call
			$instring = FALSE;         // Whether we're in a string or not
			$realtokens = FALSE;         // Whether the current scope contains anything meaningful or not
			$paramrealtokens = FALSE;         // Whether the current parameter contains anything meaningful
			$params = array();       // All our collected parameters
			$shortparam = array();       // The short version of the parameter
			$param_start = $offset;       // The distance to the start of the parameter

			// Loop through the following tokens until the function call ends
			while (isset($tokens[$offset]))
			{
				$token = $tokens[$offset];

				// Ensure that the $inner_cursor is correct and
				// that $token is either a T_ constant or a string
				if (\is_array($token))
				{
					$inner_cursor += \substr_count($token[1], "\n");
				}

				if ( ! isset(self::$ignore[$token[0]]) && ! isset($down[$token[0]]))
				{
					$paramrealtokens = $realtokens = TRUE;
				}

				// If it's a token that makes us to up a level, increase the depth
				if (isset($up[$token[0]]))
				{
					if (1 === $depth)
					{
						$shortparam[] = $token;
						$realtokens = FALSE;
					}

					++$depth;
				} elseif (isset($down[$token[0]]))
				{
					--$depth;

					// If this brings us down to the parameter level, and we've had
					// real tokens since going up, fill the $shortparam with an ellipsis
					if (1 === $depth)
					{
						if ($realtokens)
						{
							$shortparam[] = '...';
						}
						$shortparam[] = $token;
					}
				} elseif ('"' === $token[0])
				{
					// Strings use the same symbol for up and down, but we can
					// only ever be inside one string, so just use a bool for that
					if ($instring)
					{
						--$depth;
						if (1 === $depth)
						{
							$shortparam[] = '...';
						}
					} else
					{
						++$depth;
					}

					$instring = ! $instring;

					$shortparam[] = '"';
				} elseif (1 === $depth)
				{
					if (',' === $token[0])
					{
						$params[] = array(
							'full' => \array_slice($tokens, $param_start, $offset - $param_start), 'short' => $shortparam,
						);
						$shortparam = array();
						$paramrealtokens = FALSE;
						$param_start = $offset + 1;
					} elseif (T_CONSTANT_ENCAPSED_STRING === $token[0] && \strlen($token[1]) > 2)
					{
						$shortparam[] = $token[1][0] . '...' . $token[1][0];
					} else
					{
						$shortparam[] = $token;
					}
				}

				// Depth has dropped to 0 (So we've hit the closing paren)
				if ($depth <= 0)
				{
					if ($paramrealtokens)
					{
						$params[] = array(
							'full' => \array_slice($tokens, $param_start, $offset - $param_start), 'short' => $shortparam,
						);
					}

					break;
				}

				++$offset;
			}

			// If we're not passed (or at) the line at the end
			// of the function call, we're too early so skip it
			if ($inner_cursor < $line)
			{
				continue;
			}

			// Format the final output parameters
			foreach ($params as &$param)
			{
				$name = self::tokensFormatted($param['short']);
				$expression = FALSE;
				foreach ($name as $token)
				{
					if (self::tokenIsOperator($token))
					{
						$expression = TRUE;
						break;
					}
				}

				$param = array(
					'name' => self::tokensToString($name), 'path' => self::tokensToString(self::tokensTrim($param['full'])), 'expression' => $expression,
				);
			}

			// Get the modifiers
			--$index;

			while (isset($tokens[$index]))
			{
				if ( ! isset(self::$ignore[$tokens[$index][0]]) && ! isset($identifier[$tokens[$index][0]]))
				{
					break;
				}

				--$index;
			}

			$mods = array();

			while (isset($tokens[$index]))
			{
				if (isset(self::$ignore[$tokens[$index][0]]))
				{
					--$index;
					continue;
				}

				if (isset($modifiers[$tokens[$index][0]]))
				{
					$mods[] = $tokens[$index];
					--$index;
					continue;
				}

				break;
			}

			$function_calls[] = array(
				'parameters' => $params, 'modifiers' => $mods,
			);
		}

		return $function_calls;
	}

	private static function realTokenIndex(array $tokens, $index)
	{
		++$index;

		while (isset($tokens[$index]))
		{
			if ( ! isset(self::$ignore[$tokens[$index][0]]))
			{
				return $index;
			}

			++$index;
		}

		return NULL;
	}

	private static function tokensFormatted(array $tokens)
	{
		$space = FALSE;

		$tokens = self::tokensTrim($tokens);

		$output = array();
		$last = NULL;

		foreach ($tokens as $index => $token)
		{
			if (isset(self::$ignore[$token[0]]))
			{
				if ($space)
				{
					continue;
				}

				$next = $tokens[self::realTokenIndex($tokens, $index)];

				if (isset(self::$strip[$last[0]]) && ! self::tokenIsOperator($next))
				{
					continue;
				}

				if (isset(self::$strip[$next[0]]) && $last && ! self::tokenIsOperator($last))
				{
					continue;
				}

				$token = ' ';
				$space = TRUE;
			} else
			{
				$space = FALSE;
				$last = $token;
			}

			$output[] = $token;
		}

		return $output;
	}

	private static function tokensTrim(array $tokens)
	{
		foreach ($tokens as $index => $token)
		{
			if (isset(self::$ignore[$token[0]]))
			{
				unset($tokens[$index]);
			} else
			{
				break;
			}
		}

		$tokens = \array_reverse($tokens);

		foreach ($tokens as $index => $token)
		{
			if (isset(self::$ignore[$token[0]]))
			{
				unset($tokens[$index]);
			} else
			{
				break;
			}
		}

		return \array_reverse($tokens);
	}

	/**
	 * We need a separate method to check if tokens are operators because we
	 * occasionally add "..." to short parameter versions. If we simply check
	 * for `$token[0]` then "..." will incorrectly match the "." operator.
	 *
	 * @param array|string $token The token to check
	 *
	 * @return bool
	 */
	private static function tokenIsOperator($token)
	{
		return '...' !== $token && isset(self::$operator[$token[0]]);
	}

	private static function tokensToString(array $tokens)
	{
		$out = '';

		foreach ($tokens as $token)
		{
			if (\is_string($token))
			{
				$out .= $token;
			} elseif (\is_array($token))
			{
				$out .= $token[1];
			}
		}

		return $out;
	}
}
