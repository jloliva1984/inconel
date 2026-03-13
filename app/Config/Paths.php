<?php

namespace Config;

/**
 * Paths
 *
 * Holds the paths used by the system to locate the main directories.
 * NOTE: This class is required prior to Autoloader instantiation,
 *       and does NOT extend BaseConfig.
 */
class Paths
{
    /**
     * Path to the system directory.
     */
    public string $systemDirectory = __DIR__ . '/../../vendor/codeigniter4/framework/system';

    /**
     * Path to the application directory.
     */
    public string $appDirectory = __DIR__ . '/..';

    /**
     * Path to the writable directory.
     */
    public string $writableDirectory = __DIR__ . '/../../writable';

    /**
     * Path to the tests directory.
     */
    public string $testsDirectory = __DIR__ . '/../../tests';

    /**
     * Path to the views directory.
     */
    public string $viewDirectory = __DIR__ . '/../Views';

    /**
     * Path to the directory containing .env file.
     */
    public string $envDirectory = __DIR__ . '/../../';
}
