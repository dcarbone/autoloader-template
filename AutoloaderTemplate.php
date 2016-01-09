<?php

/*
 * Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Class AutoloaderTemplate
 * 
 * Change class name as you see fit.
 */
class AutoloaderTemplate
{
    // Modify this constant to be the root of the namespace of the classes you wish to autoload.
    public static $rootNamespaceOfConcern = 'Change\\Me';

    // Modify this constant to be the root directory containing namespaced classes
    public static $rootDirectoryOfConcern = __DIR__;

    /** @var bool */
    private static $_registered = false;

    /**
     * @return bool
     * @throws Exception
     */
    public static function register()
    {
        if (self::$_registered)
            return self::$_registered;

        return self::$_registered = spl_autoload_register(array(__CLASS__, 'loadClass'), true);
    }

    /**
     * @return bool
     */
    public static function unregister()
    {
        self::$_registered = !spl_autoload_unregister(array(__CLASS__, 'loadClass'));
        return !self::$_registered;
    }

    /**
     * Please see associated documentation for more information on what this method looks for.
     *
     * @param string $class
     * @return bool|null
     */
    public static function loadClass($class)
    {
        if (0 === strpos($class, static::$rootNamespaceOfConcern))
        {
            // First, attempt to find where all namespace sections correspond to a directory
            $psr0 = sprintf('%s%s.php', static::$rootDirectoryOfConcern, str_replace('\\', '/', $class));
            if (file_exists($psr0))
            {
                require $psr0;
                return true;
            }
            
            // Otherwise, attempt to load from PSR-4 style namespace
            $psr4 = sprintf(
                '%s%s.php',
                static::$rootDirectoryOfConcern,
                str_replace(array(static::$rootNamespaceOfConcern, '\\'), array('', '/'), $class)
            );
            if (file_exists($psr4))
            {
                require $psr4;
                return true;
            }
            
            // Otherwise, mooooove on!
        }
        return null;
    }
}
