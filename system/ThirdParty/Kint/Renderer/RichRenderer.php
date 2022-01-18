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

namespace Kint\Renderer;

use Kint\Kint;
use Kint\Object\BasicObject;
use Kint\Object\BlobObject;
use Kint\Object\InstanceObject;
use Kint\Object\Representation\Representation;
use Kint\Utils;

class RichRenderer extends Renderer {
	/**
	 * RichRenderer object plugins should implement Kint\Renderer\Rich\ObjectPluginInterface.
	 */
	public static $object_plugins
		= array(
			'blacklist' => 'Kint\\Renderer\\Rich\\BlacklistPlugin', 'callable' => 'Kint\\Renderer\\Rich\\CallablePlugin', 'closure' => 'Kint\\Renderer\\Rich\\ClosurePlugin', 'color' => 'Kint\\Renderer\\Rich\\ColorPlugin', 'depth_limit' => 'Kint\\Renderer\\Rich\\DepthLimitPlugin', 'recursion' => 'Kint\\Renderer\\Rich\\RecursionPlugin', 'simplexml_element' => 'Kint\\Renderer\\Rich\\SimpleXMLElementPlugin', 'trace_frame' => 'Kint\\Renderer\\Rich\\TraceFramePlugin',
		);

	/**
	 * RichRenderer tab plugins should implement Kint\Renderer\Rich\TabPluginInterface.
	 */
	public static $tab_plugins
		= array(
			'binary' => 'Kint\\Renderer\\Rich\\BinaryPlugin', 'color' => 'Kint\\Renderer\\Rich\\ColorPlugin', 'docstring' => 'Kint\\Renderer\\Rich\\DocstringPlugin', 'microtime' => 'Kint\\Renderer\\Rich\\MicrotimePlugin', 'source' => 'Kint\\Renderer\\Rich\\SourcePlugin', 'table' => 'Kint\\Renderer\\Rich\\TablePlugin', 'timestamp' => 'Kint\\Renderer\\Rich\\TimestampPlugin',
		);

	public static $pre_render_sources
		= array(
			'script' => array(
				array('Kint\\Renderer\\RichRenderer', 'renderJs'), array('Kint\\Renderer\\Rich\\MicrotimePlugin', 'renderJs'),
			), 'style' => array(
				array('Kint\\Renderer\\RichRenderer', 'renderCss'),
			), 'raw' => array(),
		);

	/**
	 * Whether or not to render access paths.
	 *
	 * Access paths can become incredibly heavy with very deep and wide
	 * structures. Given mostly public variables it will typically make
	 * up one quarter of the output HTML size.
	 *
	 * If this is an unacceptably large amount and your browser is groaning
	 * under the weight of the access paths - your first order of buisiness
	 * should be to get a new browser. Failing that, use this to turn them off.
	 *
	 * @var bool
	 */
	public static $access_paths = TRUE;

	/**
	 * The maximum length of a string before it is truncated.
	 *
	 * Falsey to disable
	 *
	 * @var int
	 */
	public static $strlen_max = 80;

	/**
	 * Path to the CSS file to load by default.
	 *
	 * @var string
	 */
	public static $theme = 'original.css';

	/**
	 * Assume types and sizes don't need to be escaped.
	 *
	 * Turn this off if you use anything but ascii in your class names,
	 * but it'll cause a slowdown of around 10%
	 *
	 * @var bool
	 */
	public static $escape_types = FALSE;

	/**
	 * Move all dumps to a folder at the bottom of the body.
	 *
	 * @var bool
	 */
	public static $folder = TRUE;

	/**
	 * Sort mode for object properties.
	 *
	 * @var int
	 */
	public static $sort = self::SORT_NONE;

	public static $needs_pre_render = TRUE;
	public static $needs_folder_render = TRUE;

	public static $always_pre_render = FALSE;

	protected $plugin_objs = array();
	protected $expand = FALSE;
	protected $force_pre_render = FALSE;
	protected $pre_render;
	protected $use_folder;

	public function __construct()
	{
		$this->pre_render = self::$needs_pre_render;
		$this->use_folder = self::$folder;

		if (self::$always_pre_render)
		{
			$this->setForcePreRender();
		}
	}

	public function setForcePreRender()
	{
		$this->force_pre_render = TRUE;
		$this->pre_render = TRUE;
	}

	protected static function renderJs()
	{
		return \file_get_contents(KINT_DIR . '/resources/compiled/shared.js') . \file_get_contents(KINT_DIR . '/resources/compiled/rich.js');
	}

	protected static function renderCss()
	{
		if (\file_exists(KINT_DIR . '/resources/compiled/' . self::$theme))
		{
			return \file_get_contents(KINT_DIR . '/resources/compiled/' . self::$theme);
		}

		return \file_get_contents(self::$theme);
	}

	public function setCallInfo(array $info)
	{
		parent::setCallInfo($info);

		if (\in_array('!', $this->call_info['modifiers'], TRUE))
		{
			$this->setExpand(TRUE);
			$this->use_folder = FALSE;
		}

		if (\in_array('@', $this->call_info['modifiers'], TRUE))
		{
			$this->setForcePreRender();
		}
	}

	public function setStatics(array $statics)
	{
		parent::setStatics($statics);

		if ( ! empty($statics['expanded']))
		{
			$this->setExpand(TRUE);
		}

		if ( ! empty($statics['return']))
		{
			$this->setForcePreRender();
		}
	}

	public function render(BasicObject $o)
	{
		if ($plugin = $this->getPlugin(self::$object_plugins, $o->hints))
		{
			if (\strlen($output = $plugin->renderObject($o)))
			{
				return $output;
			}
		}

		$children = $this->renderChildren($o);
		$header = $this->renderHeaderWrapper($o, (bool)\strlen($children), $this->renderHeader($o));

		return '<dl>' . $header . $children . '</dl>';
	}

	protected function getPlugin(array $plugins, array $hints)
	{
		if ($plugins = $this->matchPlugins($plugins, $hints))
		{
			$plugin = \end($plugins);

			if ( ! isset($this->plugin_objs[$plugin]))
			{
				$this->plugin_objs[$plugin] = new $plugin($this);
			}

			return $this->plugin_objs[$plugin];
		}
	}

	public function renderChildren(BasicObject $o)
	{
		$contents = array();
		$tabs = array();

		foreach ($o->getRepresentations() as $rep)
		{
			$result = $this->renderTab($o, $rep);
			if (\strlen($result))
			{
				$contents[] = $result;
				$tabs[] = $rep;
			}
		}

		if (empty($tabs))
		{
			return '';
		}

		$output = '<dd>';

		if (1 === \count($tabs) && $tabs[0]->labelIsImplicit())
		{
			$output .= \reset($contents);
		} else
		{
			$output .= '<ul class="kint-tabs">';

			foreach ($tabs as $i => $tab)
			{
				if (0 === $i)
				{
					$output .= '<li class="kint-active-tab">';
				} else
				{
					$output .= '<li>';
				}

				$output .= $this->escape($tab->getLabel()) . '</li>';
			}

			$output .= '</ul><ul>';

			foreach ($contents as $tab)
			{
				$output .= '<li>' . $tab . '</li>';
			}

			$output .= '</ul>';
		}

		return $output . '</dd>';
	}

	protected function renderTab(BasicObject $o, Representation $rep)
	{
		if ($plugin = $this->getPlugin(self::$tab_plugins, $rep->hints))
		{
			if (\strlen($output = $plugin->renderTab($rep)))
			{
				return $output;
			}
		}

		if (\is_array($rep->contents))
		{
			$output = '';

			if ($o instanceof InstanceObject && 'properties' === $rep->getName())
			{
				foreach (self::sortProperties($rep->contents, self::$sort) as $obj)
				{
					$output .= $this->render($obj);
				}
			} else
			{
				foreach ($rep->contents as $obj)
				{
					$output .= $this->render($obj);
				}
			}

			return $output;
		}

		if (\is_string($rep->contents))
		{
			$show_contents = FALSE;

			// If it is the value representation of a string and its whitespace
			// was truncated in the header, always display the full string
			if ('string' !== $o->type || $o->value !== $rep)
			{
				$show_contents = TRUE;
			} else
			{
				if (\preg_match('/(:?[\\r\\n\\t\\f\\v]| {2})/', $rep->contents))
				{
					$show_contents = TRUE;
				} elseif (self::$strlen_max && BlobObject::strlen($o->getValueShort()) > self::$strlen_max)
				{
					$show_contents = TRUE;
				}

				if (empty($o->encoding))
				{
					$show_contents = FALSE;
				}
			}

			if ($show_contents)
			{
				return '<pre>' . $this->escape($rep->contents) . "\n</pre>";
			}
		}

		if ($rep->contents instanceof BasicObject)
		{
			return $this->render($rep->contents);
		}
	}

	public function escape($string, $encoding = FALSE)
	{
		if (FALSE === $encoding)
		{
			$encoding = BlobObject::detectEncoding($string);
		}

		$original_encoding = $encoding;

		if (FALSE === $encoding || 'ASCII' === $encoding)
		{
			$encoding = 'UTF-8';
		}

		$string = \htmlspecialchars($string, ENT_NOQUOTES, $encoding);

		// this call converts all non-ASCII characters into numeirc htmlentities
		if (\function_exists('mb_encode_numericentity') && 'ASCII' !== $original_encoding)
		{
			$string = \mb_encode_numericentity($string, array(0x80, 0xffff, 0, 0xffff), $encoding);
		}

		return $string;
	}

	public function renderHeaderWrapper(BasicObject $o, $has_children, $contents)
	{
		$out = '<dt';

		if ($has_children)
		{
			$out .= ' class="kint-parent';

			if ($this->expand)
			{
				$out .= ' kint-show';
			}

			$out .= '"';
		}

		$out .= '>';

		if (self::$access_paths && $o->depth > 0 && $ap = $o->getAccessPath())
		{
			$out .= '<span class="kint-access-path-trigger" title="Show access path">&rlarr;</span>';
		}

		if ($has_children)
		{
			$out .= '<span class="kint-popup-trigger" title="Open in new window">&boxbox;</span>';

			if (0 === $o->depth)
			{
				$out .= '<span class="kint-search-trigger" title="Show search box">&telrec;</span>';
				$out .= '<input type="text" class="kint-search" value="">';
			}

			$out .= '<nav></nav>';
		}

		$out .= $contents;

		if ( ! empty($ap))
		{
			$out .= '<div class="access-path">' . $this->escape($ap) . '</div>';
		}

		return $out . '</dt>';
	}

	public function renderHeader(BasicObject $o)
	{
		$output = '';

		if (NULL !== ($s = $o->getModifiers()))
		{
			$output .= '<var>' . $s . '</var> ';
		}

		if (NULL !== ($s = $o->getName()))
		{
			$output .= '<dfn>' . $this->escape($s) . '</dfn> ';

			if ($s = $o->getOperator())
			{
				$output .= $this->escape($s, 'ASCII') . ' ';
			}
		}

		if (NULL !== ($s = $o->getType()))
		{
			if (self::$escape_types)
			{
				$s = $this->escape($s);
			}

			if ($o->reference)
			{
				$s = '&amp;' . $s;
			}

			$output .= '<var>' . $s . '</var> ';
		}

		if (NULL !== ($s = $o->getSize()))
		{
			if (self::$escape_types)
			{
				$s = $this->escape($s);
			}
			$output .= '(' . $s . ') ';
		}

		if (NULL !== ($s = $o->getValueShort()))
		{
			$s = \preg_replace('/\\s+/', ' ', $s);

			if (self::$strlen_max)
			{
				$s = Utils::truncateString($s, self::$strlen_max);
			}

			$output .= $this->escape($s);
		}

		return \trim($output);
	}

	public function renderNothing()
	{
		return '<dl><dt><var>No argument</var></dt></dl>';
	}

	public function preRender()
	{
		$output = '';

		if ($this->pre_render)
		{
			foreach (self::$pre_render_sources as $type => $values)
			{
				$contents = '';
				foreach ($values as $v)
				{
					$contents .= \call_user_func($v, $this);
				}

				if ( ! \strlen($contents))
				{
					continue;
				}

				switch ($type)
				{
					case 'script':
						$output .= '<script class="kint-rich-script">' . $contents . '</script>';
						break;
					case 'style':
						$output .= '<style class="kint-rich-style">' . $contents . '</style>';
						break;
					default:
						$output .= $contents;
				}
			}

			// Don't pre-render on every dump
			if ( ! $this->force_pre_render)
			{
				self::$needs_pre_render = FALSE;
			}
		}

		$output .= '<div class="kint-rich';

		if ($this->use_folder)
		{
			$output .= ' kint-file';

			if (self::$needs_folder_render || $this->force_pre_render)
			{
				$output = $this->renderFolder() . $output;

				if ( ! $this->force_pre_render)
				{
					self::$needs_folder_render = FALSE;
				}
			}
		}

		$output .= '">';

		return $output;
	}

	protected static function renderFolder()
	{
		return '<div class="kint-rich kint-folder"><dl><dt class="kint-parent"><nav></nav>Kint</dt><dd class="kint-folder"></dd></dl></div>';
	}

	public function postRender()
	{
		if ( ! $this->show_trace)
		{
			return '</div>';
		}

		$output = '<footer>';
		$output .= '<span class="kint-popup-trigger" title="Open in new window">&boxbox;</span> ';

		if ( ! empty($this->call_info['trace']) && \count($this->call_info['trace']) > 1)
		{
			$output .= '<nav></nav>';
		}

		if (isset($this->call_info['callee']['file']))
		{
			$output .= 'Called from ' . $this->ideLink($this->call_info['callee']['file'], $this->call_info['callee']['line']);
		}

		if (isset($this->call_info['callee']['function']) && ( ! empty($this->call_info['callee']['class']) || ! \in_array($this->call_info['callee']['function'], array('include', 'include_once', 'require', 'require_once'), TRUE)))
		{
			$output .= ' [';
			if (isset($this->call_info['callee']['class']))
			{
				$output .= $this->call_info['callee']['class'];
			}
			if (isset($this->call_info['callee']['type']))
			{
				$output .= $this->call_info['callee']['type'];
			}
			$output .= $this->call_info['callee']['function'] . '()]';
		}

		if ( ! empty($this->call_info['trace']) && \count($this->call_info['trace']) > 1)
		{
			$output .= '<ol>';
			foreach ($this->call_info['trace'] as $index => $step)
			{
				if ( ! $index)
				{
					continue;
				}

				$output .= '<li>' . $this->ideLink($step['file'], $step['line']); // closing tag not required
				if (isset($step['function']) && ! \in_array($step['function'], array('include', 'include_once', 'require', 'require_once'), TRUE))
				{
					$output .= ' [';
					if (isset($step['class']))
					{
						$output .= $step['class'];
					}
					if (isset($step['type']))
					{
						$output .= $step['type'];
					}
					$output .= $step['function'] . '()]';
				}
			}
			$output .= '</ol>';
		}

		$output .= '</footer></div>';

		return $output;
	}

	public function ideLink($file, $line)
	{
		$path = $this->escape(Kint::shortenPath($file)) . ':' . $line;
		$ideLink = Kint::getIdeLink($file, $line);

		if ( ! $ideLink)
		{
			return $path;
		}

		$class = '';

		if (\preg_match('/https?:\\/\\//i', $ideLink))
		{
			$class = 'class="kint-ide-link" ';
		}

		return '<a ' . $class . 'href="' . $this->escape($ideLink) . '">' . $path . '</a>';
	}

	public function getExpand()
	{
		return $this->expand;
	}

	public function setExpand($expand)
	{
		$this->expand = $expand;
	}

	public function getPreRender()
	{
		return $this->pre_render;
	}

	public function setPreRender($pre_render)
	{
		$this->setForcePreRender(); // TODO: Remove line in next major version
		$this->pre_render = $pre_render;
	}

	public function getUseFolder()
	{
		return $this->use_folder;
	}

	public function setUseFolder($use_folder)
	{
		$this->use_folder = $use_folder;
	}
}
