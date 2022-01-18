<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodeIgniter\Config;

/**
 * Factories Configuration file.
 *
 * Provides overriding directives for how
 * Factories should handle discovery and
 * instantiation of specific components.
 * Each property should correspond to the
 * lowercase, plural component name.
 */
class Factory extends BaseConfig {
	/**
	 * Supplies a default set of options to merge for
	 * all unspecified factory components.
	 *
	 * @var array
	 */
	public static $default
		= [
			'component' => NULL, 'path' => NULL, 'instanceOf' => NULL, 'getShared' => TRUE, 'preferApp' => TRUE,
		];

	/**
	 * Specifies that Models should always favor child
	 * classes to allow easy extension of module Models.
	 *
	 * @var array
	 */
	public $models
		= [
			'preferApp' => TRUE,
		];
}
