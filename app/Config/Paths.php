<?php

namespace Config;

/**
 * -----------------------------------------------------------------------
 * SYSTEM FOLDER NAME
 * -----------------------------------------------------------------------
 */
class Paths
{
    /**
     * -----------------------------------------------------------------------
     * SYSTEM FOLDER NAME
     * -----------------------------------------------------------------------
     *
     * This variable must contain the name of your "system" folder.
     * Set the path if it is not in the same folder as this file.
     */
    public string $systemDirectory = __DIR__ . '/../../vendor/codeigniter4/framework/system';

    /**
     * -----------------------------------------------------------------------
     * APPLICATION FOLDER NAME
     * -----------------------------------------------------------------------
     *
     * If you want this front controller to use a different "app" folder
     * than the default, set its full server path here. This MUST have
     * a trailing slash.
     */
    public string $appDirectory = __DIR__ . '/..';

    /**
     * -----------------------------------------------------------------------
     * WRITABLE DIRECTORY NAME
     * -----------------------------------------------------------------------
     *
     * This is the directory that CodeIgniter will use for "writable" files.
     * As you can see, it is grouped with all of the other paths.
     */
    public string $writableDirectory = __DIR__ . '/../../writable';

    /**
     * -----------------------------------------------------------------------
     * TESTS DIRECTORY NAME
     * -----------------------------------------------------------------------
     *
     * This is where all of the system tests for CodeIgniter itself are stored.
     */
    public string $testsDirectory = __DIR__ . '/../../tests';

    /**
     * -----------------------------------------------------------------------
     * PROJECT ROOT
     * -----------------------------------------------------------------------
     *
     * The path to the project root. This is where the `composer.json` file
     * lives.
     */
    public string $projectDirectory = __DIR__ . '/../..';
}
