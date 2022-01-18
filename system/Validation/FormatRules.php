<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodeIgniter\Validation;

use DateTime;

/**
 * Format validation Rules.
 */
class FormatRules {
	/**
	 * Alpha
	 *
	 * @param string|null $str
	 *
	 * @return boolean
	 */
	public function alpha(?string $str = NULL): bool
	{
		return ctype_alpha($str);
	}

	/**
	 * Alpha with spaces.
	 *
	 * @param string|null $value Value.
	 *
	 * @return boolean True if alpha with spaces, else false.
	 */
	public function alpha_space(?string $value = NULL): bool
	{
		if ($value === NULL)
		{
			return TRUE;
		}

		// @see https://regex101.com/r/LhqHPO/1
		return (bool)preg_match('/\A[A-Z ]+\z/i', $value);
	}

	/**
	 * Alphanumeric with underscores and dashes
	 *
	 * @param string|null $str
	 *
	 * @return boolean
	 */
	public function alpha_dash(?string $str = NULL): bool
	{
		// @see https://regex101.com/r/XfVY3d/1
		return (bool)preg_match('/\A[a-z0-9_-]+\z/i', $str);
	}

	/**
	 * Alphanumeric, spaces, and a limited set of punctuation characters.
	 * Accepted punctuation characters are: ~ tilde, ! exclamation,
	 * # number, $ dollar, % percent, & ampersand, * asterisk, - dash,
	 * _ underscore, + plus, = equals, | vertical bar, : colon, . period
	 * ~ ! # $ % & * - _ + = | : .
	 *
	 * @param string $str
	 *
	 * @return boolean
	 */
	public function alpha_numeric_punct($str)
	{
		// @see https://regex101.com/r/6N8dDY/1
		return (bool)preg_match('/\A[A-Z0-9 ~!#$%\&\*\-_+=|:.]+\z/i', $str);
	}

	/**
	 * Alphanumeric
	 *
	 * @param string|null $str
	 *
	 * @return boolean
	 */
	public function alpha_numeric(?string $str = NULL): bool
	{
		return ctype_alnum($str);
	}

	/**
	 * Alphanumeric w/ spaces
	 *
	 * @param string|null $str
	 *
	 * @return boolean
	 */
	public function alpha_numeric_space(?string $str = NULL): bool
	{
		// @see https://regex101.com/r/0AZDME/1
		return (bool)preg_match('/\A[A-Z0-9 ]+\z/i', $str);
	}

	/**
	 * Any type of string
	 *
	 * Note: we specifically do NOT type hint $str here so that
	 * it doesn't convert numbers into strings.
	 *
	 * @param string|null $str
	 *
	 * @return boolean
	 */
	public function string($str = NULL): bool
	{
		return is_string($str);
	}

	/**
	 * Decimal number
	 *
	 * @param string|null $str
	 *
	 * @return boolean
	 */
	public function decimal(?string $str = NULL): bool
	{
		// @see https://regex101.com/r/HULifl/1/
		return (bool)preg_match('/\A[-+]?[0-9]{0,}\.?[0-9]+\z/', $str);
	}

	/**
	 * String of hexidecimal characters
	 *
	 * @param string|null $str
	 *
	 * @return boolean
	 */
	public function hex(?string $str = NULL): bool
	{
		return ctype_xdigit($str);
	}

	/**
	 * Integer
	 *
	 * @param string|null $str
	 *
	 * @return boolean
	 */
	public function integer(?string $str = NULL): bool
	{
		return (bool)preg_match('/\A[\-+]?[0-9]+\z/', $str);
	}

	/**
	 * Is a Natural number  (0,1,2,3, etc.)
	 *
	 * @param string|null $str
	 *
	 * @return boolean
	 */
	public function is_natural(?string $str = NULL): bool
	{
		return ctype_digit($str);
	}

	/**
	 * Is a Natural number, but not a zero  (1,2,3, etc.)
	 *
	 * @param string|null $str
	 *
	 * @return boolean
	 */
	public function is_natural_no_zero(?string $str = NULL): bool
	{
		return ($str !== '0' && ctype_digit($str));
	}

	/**
	 * Numeric
	 *
	 * @param string|null $str
	 *
	 * @return boolean
	 */
	public function numeric(?string $str = NULL): bool
	{
		// @see https://regex101.com/r/bb9wtr/1
		return (bool)preg_match('/\A[\-+]?[0-9]*\.?[0-9]+\z/', $str);
	}

	/**
	 * Compares value against a regular expression pattern.
	 *
	 * @param string|null $str
	 * @param string $pattern
	 *
	 * @return boolean
	 */
	public function regex_match(?string $str, string $pattern): bool
	{
		if (strpos($pattern, '/') !== 0)
		{
			$pattern = "/{$pattern}/";
		}

		return (bool)preg_match($pattern, $str);
	}

	/**
	 * Validates that the string is a valid timezone as per the
	 * timezone_identifiers_list function.
	 *
	 * @see http://php.net/manual/en/datetimezone.listidentifiers.php
	 *
	 * @param string $str
	 *
	 * @return boolean
	 */
	public function timezone(string $str = NULL): bool
	{
		return in_array($str, timezone_identifiers_list(), TRUE);
	}

	/**
	 * Valid Base64
	 *
	 * Tests a string for characters outside of the Base64 alphabet
	 * as defined by RFC 2045 http://www.faqs.org/rfcs/rfc2045
	 *
	 * @param string $str
	 *
	 * @return boolean
	 */
	public function valid_base64(string $str = NULL): bool
	{
		return (base64_encode(base64_decode($str, TRUE)) === $str);
	}

	/**
	 * Valid JSON
	 *
	 * @param string $str
	 *
	 * @return boolean
	 */
	public function valid_json(string $str = NULL): bool
	{
		json_decode($str);
		return json_last_error() === JSON_ERROR_NONE;
	}

	/**
	 * Validate a comma-separated list of email addresses.
	 *
	 * Example:
	 *     valid_emails[one@example.com,two@example.com]
	 *
	 * @param string $str
	 *
	 * @return boolean
	 */
	public function valid_emails(string $str = NULL): bool
	{
		foreach (explode(',', $str) as $email)
		{
			$email = trim($email);
			if ($email === '')
			{
				return FALSE;
			}

			if ($this->valid_email($email) === FALSE)
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * Checks for a correctly formatted email address
	 *
	 * @param string $str
	 *
	 * @return boolean
	 */
	public function valid_email(string $str = NULL): bool
	{
		// @see https://regex101.com/r/wlJG1t/1/
		if (function_exists('idn_to_ascii') && defined('INTL_IDNA_VARIANT_UTS46') && preg_match('#\A([^@]+)@(.+)\z#', $str, $matches))
		{
			$str = $matches[1] . '@' . idn_to_ascii($matches[2], 0, INTL_IDNA_VARIANT_UTS46);
		}

		return (bool)filter_var($str, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * Validate an IP address (human readable format or binary string - inet_pton)
	 *
	 * @param string $ip IP Address
	 * @param string $which IP protocol: 'ipv4' or 'ipv6'
	 *
	 * @return boolean
	 */
	public function valid_ip(string $ip = NULL, string $which = NULL): bool
	{
		if (empty($ip))
		{
			return FALSE;
		}
		switch (strtolower($which))
		{
			case 'ipv4':
				$which = FILTER_FLAG_IPV4;
				break;
			case 'ipv6':
				$which = FILTER_FLAG_IPV6;
				break;
			default:
				$which = NULL;
				break;
		}

		return (bool)filter_var($ip, FILTER_VALIDATE_IP, $which) || ( ! ctype_print($ip) && (bool)filter_var(inet_ntop($ip), FILTER_VALIDATE_IP, $which));
	}

	/**
	 * Checks a URL to ensure it's formed correctly.
	 *
	 * @param string $str
	 *
	 * @return boolean
	 */
	public function valid_url(string $str = NULL): bool
	{
		if (empty($str))
		{
			return FALSE;
		}

		if (preg_match('/^(?:([^:]*)\:)?\/\/(.+)$/', $str, $matches))
		{
			if ( ! in_array($matches[1], ['http', 'https'], TRUE))
			{
				return FALSE;
			}

			$str = $matches[2];
		}

		$str = 'http://' . $str;

		return (filter_var($str, FILTER_VALIDATE_URL) !== FALSE);
	}

	/**
	 * Checks for a valid date and matches a given date format
	 *
	 * @param string $str
	 * @param string $format
	 *
	 * @return boolean
	 */
	public function valid_date(string $str = NULL, string $format = NULL): bool
	{
		if (empty($format))
		{
			return (bool)strtotime($str);
		}

		$date = DateTime::createFromFormat($format, $str);

		return (bool)$date && DateTime::getLastErrors()['warning_count'] === 0 && DateTime::getLastErrors()['error_count'] === 0;
	}
}
