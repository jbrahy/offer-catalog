<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodeIgniter\Pager\Exceptions;

use CodeIgniter\Exceptions\FrameworkException;

class PagerException extends FrameworkException {
	public static function forInvalidTemplate(string $template = NULL)
	{
		return new static(lang('Pager.invalidTemplate', [$template]));
	}

	public static function forInvalidPaginationGroup(string $group = NULL)
	{
		return new static(lang('Pager.invalidPaginationGroup', [$group]));
	}
}
