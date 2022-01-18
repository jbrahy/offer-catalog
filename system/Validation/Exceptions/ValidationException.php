<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodeIgniter\Validation\Exceptions;

use CodeIgniter\Exceptions\FrameworkException;

class ValidationException extends FrameworkException {
	public static function forRuleNotFound(string $rule = NULL)
	{
		return new static(lang('Validation.ruleNotFound', [$rule]));
	}

	public static function forGroupNotFound(string $group = NULL)
	{
		return new static(lang('Validation.groupNotFound', [$group]));
	}

	public static function forGroupNotArray(string $group = NULL)
	{
		return new static(lang('Validation.groupNotArray', [$group]));
	}

	public static function forInvalidTemplate(string $template = NULL)
	{
		return new static(lang('Validation.invalidTemplate', [$template]));
	}

	public static function forNoRuleSets()
	{
		return new static(lang('Validation.noRuleSets'));
	}
}
