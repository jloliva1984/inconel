<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /**
     * Base Site URL — change this to your domain before deploying.
     */
    public string $baseURL = 'http://localhost/inconel/public/';

    /**
     * Allowed Hostnames in the Site URL other than the hostname in the baseURL.
     * Required by CI4.5+.
     *
     * @var list<string>
     */
    public array $allowedHostnames = [];

    /**
     * Index File — leave empty if mod_rewrite removes index.php
     */
    public string $indexPage = '';

    /**
     * URI Protocol
     */
    public string $uriProtocol = 'REQUEST_URI';

    /**
     * Allowed URL Characters
     */
    public string $permittedURIChars = 'a-z 0-9~%.:_\-';

    /**
     * Default Locale
     */
    public string $defaultLocale = 'es';

    /**
     * Negotiate Locale
     */
    public bool $negotiateLocale = false;

    /**
     * Supported Locales
     *
     * @var list<string>
     */
    public array $supportedLocales = ['es', 'en'];

    /**
     * Application Timezone
     */
    public string $appTimezone = 'America/New_York';

    /**
     * Default Character Set
     */
    public string $charset = 'UTF-8';

    /**
     * Force Global Secure Requests
     */
    public bool $forceGlobalSecureRequests = false;

    /**
     * Reverse Proxy IPs
     *
     * @var array<string, string>
     */
    public array $proxyIPs = [];

    /**
     * Content Security Policy
     */
    public bool $CSPEnabled = false;
}
