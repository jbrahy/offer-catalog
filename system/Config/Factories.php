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

use CodeIgniter\Model;
use Config\Services;

/**
 * Factories for creating instances.
 *
 * Factories allows dynamic loading of components by their path
 * and name. The "shared instance" implementation provides a
 * large performance boost and helps keep code clean of lengthy
 * instantiation checks.
 *
 * @method static Model models(...$arguments)
 * @method static BaseConfig config(...$arguments)
 */
class Factories {
	/**
	 * Store of component-specific options, usually
	 * from CodeIgniter\Config\Factory.
	 *
	 * @var array<string, array>
	 */
	protected static $options = [];
	/**
	 * Mapping of class basenames (no namespace) to
	 * their instances.
	 *
	 * @var array<string, string[]>
	 */
	protected static $basenames = [];
	/**
	 * Store for instances of any component that
	 * has been requested as "shared".
	 * A multi-dimensional array with components as
	 * keys to the array of name-indexed instances.
	 *
	 * @var array<string, array>
	 */
	protected static $instances = [];
	/**
	 * Explicit options for the Config
	 * component to prevent logic loops.
	 *
	 * @var array<string, mixed>
	 */
	private static $configOptions
		= [
			'component' => 'config', 'path' => 'Config', 'instanceOf' => NULL, 'getShared' => TRUE, 'preferApp' => TRUE,
		];

	//--------------------------------------------------------------------

	/**
	 * Loads instances based on the method component name. Either
	 * creates a new instance or returns an existing shared instance.
	 *
	 * @param string $component
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public static function __callStatic(string $component, array $arguments)
	{
		// First argument is the name, second is options
		$name = trim(array_shift($arguments), '\\ ');
		$options = array_shift($arguments) ?? [];

		// Determine the component-specific options
		$options = array_merge(self::getOptions(strtolower($component)), $options);

		if ( ! $options['getShared'])
		{
			if ($class = self::locateClass($options, $name))
			{
				return new $class(...$arguments);
			}

			return NULL;
		}

		$basename = self::getBasename($name);

		// Check for an existing instance
		if (isset(self::$basenames[$options['component']][$basename]))
		{
			$class = self::$basenames[$options['component']][$basename];

			// Need to verify if the shared instance matches the request
			if (self::verifyInstanceOf($options, $class))
			{
				return self::$instances[$options['component']][$class];
			}
		}

		// Try to locate the class
		if ( ! $class = self::locateClass($options, $name))
		{
			return NULL;
		}

		self::$instances[$options['component']][$class] = new $class(...$arguments);
		self::$basenames[$options['component']][$basename] = $class;

		return self::$instances[$options['component']][$class];
	}

	/**
	 * Returns the component-specific configuration
	 *
	 * @param string $component Lowercase, plural component name
	 *
	 * @return array<string, mixed>
	 */
	public static function getOptions(string $component): array
	{
		$component = strtolower($component);

		// Check for a stored version
		if (isset(self::$options[$component]))
		{
			return self::$options[$component];
		}

		$values = $component === 'config' // Handle Config as a special case to prevent logic loops
			? self::$configOptions // Load values from the best Factory configuration (will include Registrars)
			: config('Factory')->$component ?? [];

		return self::setOptions($component, $values);
	}

	//--------------------------------------------------------------------

	/**
	 * Normalizes, stores, and returns the configuration for a specific component
	 *
	 * @param string $component Lowercase, plural component name
	 * @param array $values
	 *
	 * @return array<string, mixed> The result after applying defaults and normalization
	 */
	public static function setOptions(string $component, array $values): array
	{
		// Allow the config to replace the component name, to support "aliases"
		$values['component'] = strtolower($values['component'] ?? $component);

		// Reset this component so instances can be rediscovered with the updated config
		self::reset($values['component']);

		// If no path was available then use the component
		$values['path'] = trim($values['path'] ?? ucfirst($values['component']), '\\ ');

		// Add defaults for any missing values
		$values = array_merge(Factory::$default, $values);

		// Store the result to the supplied name and potential alias
		self::$options[$component] = $values;
		self::$options[$values['component']] = $values;

		return $values;
	}

	/**
	 * Gets a basename from a class name, namespaced or not.
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	public static function getBasename(string $name): string
	{
		// Determine the basename
		if ($basename = strrchr($name, '\\'))
		{
			return substr($basename, 1);
		}

		return $name;
	}

	//--------------------------------------------------------------------

	/**
	 * Resets the static arrays, optionally just for one component
	 *
	 * @param string $component Lowercase, plural component name
	 */
	public static function reset(string $component = NULL)
	{
		if ($component)
		{
			unset(static::$options[$component]);
			unset(static::$basenames[$component]);
			unset(static::$instances[$component]);

			return;
		}

		static::$options = [];
		static::$basenames = [];
		static::$instances = [];
	}

	/**
	 * Helper method for injecting mock instances
	 *
	 * @param string $component Lowercase, plural component name
	 * @param string $name The name of the instance
	 * @param object $instance
	 */
	public static function injectMock(string $component, string $name, object $instance)
	{
		// Force a configuration to exist for this component
		$component = strtolower($component);
		self::getOptions($component);

		$class = get_class($instance);
		$basename = self::getBasename($name);

		self::$instances[$component][$class] = $instance;
		self::$basenames[$component][$basename] = $class;
	}

	/**
	 * Finds a component class
	 *
	 * @param array $options The array of component-specific directives
	 * @param string $name Class name, namespace optional
	 *
	 * @return string|null
	 */
	protected static function locateClass(array $options, string $name): ?string
	{
		// Check for low-hanging fruit
		if (class_exists($name, FALSE) && self::verifyPreferApp($options, $name) && self::verifyInstanceOf($options, $name))
		{
			return $name;
		}

		// Determine the relative class names we need
		$basename = self::getBasename($name);
		$appname = $options['component'] === 'config' ? 'Config\\' . $basename : rtrim(APP_NAMESPACE, '\\') . '\\' . $options['path'] . '\\' . $basename;

		// If an App version was requested then see if it verifies
		if ($options['preferApp'] && class_exists($appname) && self::verifyInstanceOf($options, $name))
		{
			return $appname;
		}

		// If we have ruled out an App version and the class exists then try it
		if (class_exists($name) && self::verifyInstanceOf($options, $name))
		{
			return $name;
		}

		// Have to do this the hard way...
		$locator = Services::locator();

		// Check if the class was namespaced
		if (strpos($name, '\\') !== FALSE)
		{
			if ( ! $file = $locator->locateFile($name, $options['path']))
			{
				return NULL;
			}
			$files = [$file];
		}
		// No namespace? Search for it
		// Check all namespaces, prioritizing App and modules
		elseif ( ! $files = $locator->search($options['path'] . DIRECTORY_SEPARATOR . $name))
		{
			return NULL;
		}

		// Check all files for a valid class
		foreach ($files as $file)
		{
			$class = $locator->getClassname($file);

			if ($class && self::verifyInstanceOf($options, $class))
			{
				return $class;
			}
		}

		return NULL;
	}

	/**
	 * Verifies that a class & config satisfy the "preferApp" option
	 *
	 * @param array $options The array of component-specific directives
	 * @param string $name Class name, namespace optional
	 *
	 * @return boolean
	 */
	protected static function verifyPreferApp(array $options, string $name): bool
	{
		// Anything without that restriction passes
		if ( ! $options['preferApp'])
		{
			return TRUE;
		}

		// Special case for Config since its App namespace is actually \Config
		if ($options['component'] === 'config')
		{
			return strpos($name, 'Config') === 0;
		}

		return strpos($name, APP_NAMESPACE) === 0;
	}

	/**
	 * Verifies that a class & config satisfy the "instanceOf" option
	 *
	 * @param array $options The array of component-specific directives
	 * @param string $name Class name, namespace optional
	 *
	 * @return boolean
	 */
	protected static function verifyInstanceOf(array $options, string $name): bool
	{
		// Anything without that restriction passes
		if ( ! $options['instanceOf'])
		{
			return TRUE;
		}

		return is_a($name, $options['instanceOf'], TRUE);
	}
}
