<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if ( ! function_exists('is_cli'))
{
	/**
	 * Is CLI?
	 *
	 * Test to see if a request was made from the command line.
	 * You can set the return value for testing.
	 *
	 * @param boolean $newReturn return value to set
	 *
	 * @return boolean
	 */
	function is_cli(bool $newReturn = NULL): bool
	{
		// PHPUnit always runs via CLI.
		static $returnValue = TRUE;

		if ($newReturn !== NULL)
		{
			$returnValue = $newReturn;
		}

		return $returnValue;
	}
}
