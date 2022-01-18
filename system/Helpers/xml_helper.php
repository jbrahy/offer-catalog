<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * CodeIgniter XML Helpers
 */

if ( ! function_exists('xml_convert'))
{
	/**
	 * Convert Reserved XML characters to Entities
	 *
	 * @param string $str
	 * @param boolean $protectAll
	 *
	 * @return string
	 */
	function xml_convert(string $str, bool $protectAll = FALSE): string
	{
		$temp = '__TEMP_AMPERSANDS__';

		// Replace entities to temporary markers so that
		// ampersands won't get messed up
		$str = preg_replace('/&#(\d+);/', $temp . '\\1;', $str);

		if ($protectAll === TRUE)
		{
			$str = preg_replace('/&(\w+);/', $temp . '\\1;', $str);
		}

		$original = [
			'&', '<', '>', '"', "'", '-',
		];

		$replacement = [
			'&amp;', '&lt;', '&gt;', '&quot;', '&apos;', '&#45;',
		];

		$str = str_replace($original, $replacement, $str);

		// Decode the temp markers back to entities
		$str = preg_replace('/' . $temp . '(\d+);/', '&#\\1;', $str);

		if ($protectAll === TRUE)
		{
			return preg_replace('/' . $temp . '(\w+);/', '&\\1;', $str);
		}

		return $str;
	}
}
