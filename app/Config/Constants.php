<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6);
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);

// Application roles
defined('ROLE_ADMIN')    || define('ROLE_ADMIN', 'admin');
defined('ROLE_TECNICO')  || define('ROLE_TECNICO', 'tecnico');

// Warranty periods (in years)
defined('WARRANTY_LABOR_YEARS')     || define('WARRANTY_LABOR_YEARS', 1);
defined('WARRANTY_EQUIPMENT_YEARS') || define('WARRANTY_EQUIPMENT_YEARS', 10);

// App info
defined('APP_NAME')    || define('APP_NAME', 'Inconel Building');
defined('APP_VERSION') || define('APP_VERSION', '1.0.0');
