<?php

/*
 * Inconel Building - Application Constants
 */

defined('SHOW_DEBUG_BACKTRACE') || define('SHOW_DEBUG_BACKTRACE', true);

// Application roles
defined('ROLE_ADMIN')    || define('ROLE_ADMIN', 'admin');
defined('ROLE_TECNICO')  || define('ROLE_TECNICO', 'tecnico');

// Warranty periods (in years)
defined('WARRANTY_LABOR_YEARS')     || define('WARRANTY_LABOR_YEARS', 1);
defined('WARRANTY_EQUIPMENT_YEARS') || define('WARRANTY_EQUIPMENT_YEARS', 10);

// Warranty status
defined('WARRANTY_ACTIVE')   || define('WARRANTY_ACTIVE', 'activa');
defined('WARRANTY_EXPIRED')  || define('WARRANTY_EXPIRED', 'vencida');
defined('WARRANTY_EXPIRING') || define('WARRANTY_EXPIRING', 'por_vencer');

// App info
defined('APP_NAME')    || define('APP_NAME', 'Inconel Building');
defined('APP_VERSION') || define('APP_VERSION', '1.0.0');
