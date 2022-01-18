<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodeIgniter\Test\Mock;

use Config\App;

class MockAppConfig extends App {
	public $baseURL = 'http://example.com/';

	public $uriProtocol = 'REQUEST_URI';

	public $cookiePrefix = '';
	public $cookieDomain = '';
	public $cookiePath = '/';
	public $cookieSecure = FALSE;
	public $cookieHTTPOnly = FALSE;
	public $cookieSameSite = 'Lax';

	public $proxyIPs = '';

	public $CSRFProtection = FALSE;
	public $CSRFTokenName = 'csrf_test_name';
	public $CSRFHeaderName = 'X-CSRF-TOKEN';
	public $CSRFCookieName = 'csrf_cookie_name';
	public $CSRFExpire = 7200;
	public $CSRFRegenerate = TRUE;
	public $CSRFExcludeURIs = ['http://example.com'];
	public $CSRFRedirect = FALSE;
	public $CSRFSameSite = 'Lax';

	public $CSPEnabled = FALSE;

	public $defaultLocale = 'en';
	public $negotiateLocale = FALSE;
	public $supportedLocales
		= [
			'en', 'es',
		];
}
