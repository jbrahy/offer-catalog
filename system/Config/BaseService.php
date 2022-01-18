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

use CodeIgniter\Autoloader\Autoloader;
use CodeIgniter\Autoloader\FileLocator;
use CodeIgniter\Cache\CacheInterface;
use CodeIgniter\CLI\Commands;
use CodeIgniter\CodeIgniter;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Database\MigrationRunner;
use CodeIgniter\Debug\Exceptions;
use CodeIgniter\Debug\Iterator;
use CodeIgniter\Debug\Timer;
use CodeIgniter\Debug\Toolbar;
use CodeIgniter\Email\Email;
use CodeIgniter\Encryption\EncrypterInterface;
use CodeIgniter\Filters\Filters;
use CodeIgniter\Format\Format;
use CodeIgniter\Honeypot\Honeypot;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\CURLRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\Negotiate;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\URI;
use CodeIgniter\Images\Handlers\BaseHandler;
use CodeIgniter\Language\Language;
use CodeIgniter\Log\Logger;
use CodeIgniter\Pager\Pager;
use CodeIgniter\Router\RouteCollection;
use CodeIgniter\Router\RouteCollectionInterface;
use CodeIgniter\Router\Router;
use CodeIgniter\Security\Security;
use CodeIgniter\Session\Session;
use CodeIgniter\Throttle\Throttler;
use CodeIgniter\Typography\Typography;
use CodeIgniter\Validation\Validation;
use CodeIgniter\View\Cell;
use CodeIgniter\View\Parser;
use CodeIgniter\View\RendererInterface;
use CodeIgniter\View\View;
use Config\App;
use Config\Autoload;
use Config\Cache;
use Config\Encryption;
use Config\Exceptions as ConfigExceptions;
use Config\Filters as ConfigFilters;
use Config\Format as ConfigFormat;
use Config\Honeypot as ConfigHoneyPot;
use Config\Images;
use Config\Migrations;
use Config\Modules;
use Config\Pager as ConfigPager;
use Config\Services as AppServices;
use Config\Toolbar as ConfigToolbar;
use Config\Validation as ConfigValidation;
use Config\View as ConfigView;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This is used in place of a Dependency Injection container primarily
 * due to its simplicity, which allows a better long-term maintenance
 * of the applications built on top of CodeIgniter. A bonus side-effect
 * is that IDEs are able to determine what class you are calling
 * whereas with DI Containers there usually isn't a way for them to do this.
 *
 * Warning: To allow overrides by service providers do not use static calls,
 * instead call out to \Config\Services (imported as AppServices).
 *
 * @see http://blog.ircmaxell.com/2015/11/simple-easy-risk-and-change.html
 * @see http://www.infoq.com/presentations/Simple-Made-Easy
 *
 * @method static CacheInterface cache(Cache $config = NULL, $getShared = TRUE)
 * @method static CLIRequest clirequest(App $config = NULL, $getShared = TRUE)
 * @method static CodeIgniter codeigniter(App $config = NULL, $getShared = TRUE)
 * @method static Commands commands($getShared = TRUE)
 * @method static CURLRequest curlrequest($options = [], ResponseInterface $response = NULL, App $config = NULL,
 *         $getShared = TRUE)
 * @method static Email email($config = NULL, $getShared = TRUE)
 * @method static EncrypterInterface encrypter(Encryption $config = NULL, $getShared = FALSE)
 * @method static Exceptions exceptions(ConfigExceptions $config = NULL, IncomingRequest $request = NULL, Response
 *         $response = NULL, $getShared = TRUE)
 * @method static Filters filters(ConfigFilters $config = NULL, $getShared = TRUE)
 * @method static Format format(ConfigFormat $config = NULL, $getShared = TRUE)
 * @method static Honeypot honeypot(ConfigHoneyPot $config = NULL, $getShared = TRUE)
 * @method static BaseHandler image($handler = NULL, Images $config = NULL, $getShared = TRUE)
 * @method static Iterator iterator($getShared = TRUE)
 * @method static Language language($locale = NULL, $getShared = TRUE)
 * @method static Logger logger($getShared = TRUE)
 * @method static MigrationRunner migrations(Migrations $config = NULL, ConnectionInterface $db = NULL, $getShared =
 *         TRUE)
 * @method static Negotiate negotiator(RequestInterface $request = NULL, $getShared = TRUE)
 * @method static Pager pager(ConfigPager $config = NULL, RendererInterface $view = NULL, $getShared = TRUE)
 * @method static Parser parser($viewPath = NULL, ConfigView $config = NULL, $getShared = TRUE)
 * @method static View renderer($viewPath = NULL, ConfigView $config = NULL, $getShared = TRUE)
 * @method static IncomingRequest request(App $config = NULL, $getShared = TRUE)
 * @method static Response response(App $config = NULL, $getShared = TRUE)
 * @method static RedirectResponse redirectresponse(App $config = NULL, $getShared = TRUE)
 * @method static RouteCollection routes($getShared = TRUE)
 * @method static Router router(RouteCollectionInterface $routes = NULL, Request $request = NULL, $getShared = TRUE)
 * @method static Security security(App $config = NULL, $getShared = TRUE)
 * @method static Session session(App $config = NULL, $getShared = TRUE)
 * @method static Throttler throttler($getShared = TRUE)
 * @method static Timer timer($getShared = TRUE)
 * @method static Toolbar toolbar(ConfigToolbar $config = NULL, $getShared = TRUE)
 * @method static URI uri($uri = NULL, $getShared = TRUE)
 * @method static Validation validation(ConfigValidation $config = NULL, $getShared = TRUE)
 * @method static Cell viewcell($getShared = TRUE)
 * @method static Typography typography($getShared = TRUE)
 */
class BaseService {
	/**
	 * Cache for instance of any services that
	 * have been requested as a "shared" instance.
	 * Keys should be lowercase service names.
	 *
	 * @var array
	 */
	protected static $instances = [];

	/**
	 * Mock objects for testing which are returned if exist.
	 *
	 * @var array
	 */
	protected static $mocks = [];

	/**
	 * Have we already discovered other Services?
	 *
	 * @var boolean
	 */
	protected static $discovered = FALSE;

	/**
	 * A cache of other service classes we've found.
	 *
	 * @var array
	 */
	protected static $services = [];

	/**
	 * A cache of the names of services classes found.
	 *
	 * @var array<string>
	 */
	private static $serviceNames = [];

	/**
	 * Provides the ability to perform case-insensitive calling of service
	 * names.
	 *
	 * @param string $name
	 * @param array $arguments
	 *
	 * @return mixed
	 */
	public static function __callStatic(string $name, array $arguments)
	{
		$service = static::serviceExists($name);

		if ($service === NULL)
		{
			return NULL;
		}

		return $service::$name(...$arguments);
	}

	/**
	 * Check if the requested service is defined and return the declaring
	 * class. Return null if not found.
	 *
	 * @param string $name
	 *
	 * @return string|null
	 */
	public static function serviceExists(string $name): ?string
	{
		static::buildServicesCache();
		$services = array_merge(self::$serviceNames, [Services::class]);
		$name = strtolower($name);

		foreach ($services as $service)
		{
			if (method_exists($service, $name))
			{
				return $service;
			}
		}

		return NULL;
	}

	/**
	 * The file locator provides utility methods for looking for non-classes
	 * within namespaced folders, as well as convenience methods for
	 * loading 'helpers', and 'libraries'.
	 *
	 * @param boolean $getShared
	 *
	 * @return FileLocator
	 */
	public static function locator(bool $getShared = TRUE)
	{
		if ($getShared)
		{
			if (empty(static::$instances['locator']))
			{
				static::$instances['locator'] = new FileLocator(static::autoloader());
			}

			return static::$mocks['locator'] ?? static::$instances['locator'];
		}

		return new FileLocator(static::autoloader());
	}

	/**
	 * The Autoloader class is the central class that handles our
	 * spl_autoload_register method, and helper methods.
	 *
	 * @param boolean $getShared
	 *
	 * @return Autoloader
	 */
	public static function autoloader(bool $getShared = TRUE)
	{
		if ($getShared)
		{
			if (empty(static::$instances['autoloader']))
			{
				static::$instances['autoloader'] = new Autoloader();
			}

			return static::$instances['autoloader'];
		}

		return new Autoloader();
	}

	/**
	 * Reset shared instances and mocks for testing.
	 *
	 * @param boolean $initAutoloader Initializes autoloader instance
	 */
	public static function reset(bool $initAutoloader = FALSE)
	{
		static::$mocks = [];
		static::$instances = [];

		if ($initAutoloader)
		{
			static::autoloader()->initialize(new Autoload(), new Modules());
		}
	}

	/**
	 * Resets any mock and shared instances for a single service.
	 *
	 * @param string $name
	 */
	public static function resetSingle(string $name)
	{
		unset(static::$mocks[$name], static::$instances[$name]);
	}

	/**
	 * Inject mock object for testing.
	 *
	 * @param string $name
	 * @param mixed $mock
	 */
	public static function injectMock(string $name, $mock)
	{
		static::$mocks[strtolower($name)] = $mock;
	}

	protected static function buildServicesCache(): void
	{
		if ( ! static::$discovered)
		{
			$config = config('Modules');

			if ($config->shouldDiscover('services'))
			{
				$locator = static::locator();
				$files = $locator->search('Config/Services');

				// Get instances of all service classes and cache them locally.
				foreach ($files as $file)
				{
					$classname = $locator->getClassname($file);

					if ($classname !== 'CodeIgniter\\Config\\Services')
					{
						self::$serviceNames[] = $classname;
						static::$services[] = new $classname();
					}
				}
			}

			static::$discovered = TRUE;
		}
	}

	/**
	 * Returns a shared instance of any of the class' services.
	 *
	 * $key must be a name matching a service.
	 *
	 * @param string $key
	 * @param mixed ...$params
	 *
	 * @return mixed
	 */
	protected static function getSharedInstance(string $key, ...$params)
	{
		$key = strtolower($key);

		// Returns mock if exists
		if (isset(static::$mocks[$key]))
		{
			return static::$mocks[$key];
		}

		if ( ! isset(static::$instances[$key]))
		{
			// Make sure $getShared is false
			$params[] = FALSE;

			static::$instances[$key] = AppServices::$key(...$params);
		}

		return static::$instances[$key];
	}

	/**
	 * Will scan all psr4 namespaces registered with system to look
	 * for new Config\Services files. Caches a copy of each one, then
	 * looks for the service method in each, returning an instance of
	 * the service, if available.
	 *
	 * @param string $name
	 * @param array $arguments
	 *
	 * @return mixed
	 *
	 * @deprecated
	 *
	 * @codeCoverageIgnore
	 */
	protected static function discoverServices(string $name, array $arguments)
	{
		if ( ! static::$discovered)
		{
			$config = config('Modules');

			if ($config->shouldDiscover('services'))
			{
				$locator = static::locator();
				$files = $locator->search('Config/Services');

				if (empty($files))
				{
					// no files at all found - this would be really, really bad
					return NULL;
				}

				// Get instances of all service classes and cache them locally.
				foreach ($files as $file)
				{
					$classname = $locator->getClassname($file);

					if ( ! in_array($classname, ['CodeIgniter\\Config\\Services'], TRUE))
					{
						static::$services[] = new $classname();
					}
				}
			}

			static::$discovered = TRUE;
		}

		if ( ! static::$services)
		{
			// we found stuff, but no services - this would be really bad
			return NULL;
		}

		// Try to find the desired service method
		foreach (static::$services as $class)
		{
			if (method_exists($class, $name))
			{
				return $class::$name(...$arguments);
			}
		}

		return NULL;
	}
}
