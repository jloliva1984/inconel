<?php

/**
 * CodeIgniter
 *
 * Inconel Building - Sistema de Gestión de Viviendas y Garantías
 *
 * @link    https://codeigniter.com
 * @since   Version 4.0.0
 */

/*
 *---------------------------------------------------------------
 * SETUP OUR PATH CONSTANTS
 *---------------------------------------------------------------
 *
 * The path constants provide convenient access to the folders
 * throughout the application. We have to setup them up here,
 * so they are available in all of the config files.
 */

// The path to the `app` directory.
if (! defined('APPPATH')) {
    define('APPPATH', realpath(__DIR__ . '/../app') . DIRECTORY_SEPARATOR);
}

// The path to the `system` directory.
if (! defined('SYSTEMPATH')) {
    define('SYSTEMPATH', realpath(__DIR__ . '/../vendor/codeigniter4/framework/system') . DIRECTORY_SEPARATOR);
}

// The path to the `writable` directory.
if (! defined('WRITEPATH')) {
    define('WRITEPATH', realpath(__DIR__ . '/../writable') . DIRECTORY_SEPARATOR);
}

// The path to the `tests` directory
if (! defined('TESTPATH')) {
    define('TESTPATH', realpath(__DIR__ . '/../tests') . DIRECTORY_SEPARATOR);
}

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our Routes file,
 * and gets us going.
 */

// Ensure the current directory is pointing to the front controller's directory
// This is needed for security reasons as CodeIgniter.
if (getcwd() . DIRECTORY_SEPARATOR !== __DIR__ . DIRECTORY_SEPARATOR) {
    chdir(__DIR__);
}

// Load our paths config file.
// This is the line that might need to be changed, depending on your folder structure.
$pathsConfig = APPPATH . 'Config/Paths.php';
// ^^^ Change this if you move your application folder

require realpath($pathsConfig) ?: $pathsConfig;

// @phpstan-ignore-next-line
$app = require realpath(SYSTEMPATH . 'bootstrap.php') ?: SYSTEMPATH . 'bootstrap.php';

// @phpstan-ignore-next-line
$app->run();
