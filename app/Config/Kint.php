<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use Kint\Renderer\Renderer;

/**
 * --------------------------------------------------------------------------
 * Kint
 * --------------------------------------------------------------------------
 *
 * We use Kint's `RichRenderer` and `CLIRenderer`. This area contains options
 * that you can set to customize how Kint works for you.
 *
 * @see https://kint-php.github.io/kint/ for details on these settings.
 */
class Kint extends BaseConfig {
	/*
	|--------------------------------------------------------------------------
	| Global Settings
	|--------------------------------------------------------------------------
	*/

	public $plugins = NULL;

	public $maxDepth = 6;

	public $displayCalledFrom = TRUE;

	public $expanded = FALSE;

	/*
	|--------------------------------------------------------------------------
	| RichRenderer Settings
	|--------------------------------------------------------------------------
	*/
	public $richTheme = 'aante-light.css';

	public $richFolder = FALSE;

	public $richSort = Renderer::SORT_FULL;

	public $richObjectPlugins = NULL;

	public $richTabPlugins = NULL;

	/*
	|--------------------------------------------------------------------------
	| CLI Settings
	|--------------------------------------------------------------------------
	*/
	public $cliColors = TRUE;

	public $cliForceUTF8 = FALSE;

	public $cliDetectWidth = TRUE;

	public $cliMinWidth = 40;
}
