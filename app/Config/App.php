<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * App Configuration
 */
class App extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Base Site URL
     * --------------------------------------------------------------------------
     */
    public string $baseURL = 'http://localhost/inconel/public/';

    /**
     * --------------------------------------------------------------------------
     * Index File
     * --------------------------------------------------------------------------
     */
    public string $indexPage = '';

    /**
     * --------------------------------------------------------------------------
     * URI PROTOCOL
     * --------------------------------------------------------------------------
     */
    public string $uriProtocol = 'REQUEST_URI';

    /**
     * --------------------------------------------------------------------------
     * Default Locale
     * --------------------------------------------------------------------------
     */
    public string $defaultLocale = 'es';

    /**
     * --------------------------------------------------------------------------
     * Negotiate Locale
     * --------------------------------------------------------------------------
     */
    public bool $negotiateLocale = false;

    /**
     * --------------------------------------------------------------------------
     * Supported Locales
     * --------------------------------------------------------------------------
     */
    public array $supportedLocales = ['es', 'en'];

    /**
     * --------------------------------------------------------------------------
     * Application Timezone
     * --------------------------------------------------------------------------
     */
    public string $appTimezone = 'America/New_York';

    /**
     * --------------------------------------------------------------------------
     * Default Character Set
     * --------------------------------------------------------------------------
     */
    public string $charset = 'UTF-8';

    /**
     * --------------------------------------------------------------------------
     * Force Global Secure Requests
     * --------------------------------------------------------------------------
     */
    public bool $forceGlobalSecureRequests = false;

    /**
     * --------------------------------------------------------------------------
     * Reverse Proxy IPs
     * --------------------------------------------------------------------------
     */
    public array $proxyIPs = [];

    /**
     * --------------------------------------------------------------------------
     * Content Security Policy
     * --------------------------------------------------------------------------
     */
    public bool $CSPEnabled = false;

    /**
     * Session driver
     */
    public string $sessionDriver = 'CodeIgniter\Session\Handlers\FileHandler';

    /**
     * Session cookie name
     */
    public string $sessionCookieName = 'ci_session';

    /**
     * Session expiration
     */
    public int $sessionExpiration = 7200;

    /**
     * Session save path
     */
    public string $sessionSavePath = WRITEPATH . 'session';

    /**
     * Session match IP
     */
    public bool $sessionMatchIP = false;

    /**
     * Session time to update
     */
    public int $sessionTimeToUpdate = 300;

    /**
     * Session regenerate destroy
     */
    public bool $sessionRegenerateDestroy = false;

    /**
     * Cookie prefix
     */
    public string $cookiePrefix = '';

    /**
     * Cookie domain
     */
    public string $cookieDomain = '';

    /**
     * Cookie path
     */
    public string $cookiePath = '/';

    /**
     * Cookie secure
     */
    public bool $cookieSecure = false;

    /**
     * Cookie HTTPOnly
     */
    public bool $cookieHTTPOnly = true;

    /**
     * Cookie SameSite
     */
    public ?string $cookieSameSite = 'Lax';
}
