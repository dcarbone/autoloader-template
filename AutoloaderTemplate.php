<?php

/**
 * Class AutoloaderTemplate
 * 
 * Change class name as you see fit.
 */
class AutoloaderTemplate
{
    // Modify this constant to be the root of the namespace of the classes you wish to autoload.
    const ROOT_NAMESPACE_OF_CONCERN = 'Change\\Me';
    
    // Modify this constant to be the root directory containing namespaced classes
    const ROOT_DIRECTORY_OF_CONCERN = __DIR__;

    /** @var bool */
    private static $registered = false;

    /**
     * @return bool
     * @throws Exception
     */
    public static function register()
    {
        if (self::$registered)
            return self::$registered;

        return self::$registered = spl_autoload_register(array(__CLASS__, 'loadClass'), true);
    }

    /**
     * @return bool
     */
    public static function unregister()
    {
        self::$registered = !spl_autoload_unregister(array(__CLASS__, 'loadClass'));
        return !self::$registered;
    }

    /**
     * Please see associated documentation for more information on what this method looks for.
     *
     * @param string $class
     * @return bool|null
     */
    public static function loadClass($class)
    {
        if (0 === strpos($class, self::ROOT_NAMESPACE_OF_CONCERN))
        {
            // First, attempt to find where all namespace sections correspond to a directory
            $psr0 = vsprintf('%s%s.php', array(
                self::ROOT_DIRECTORY_OF_CONCERN,
                str_replace('\\', '/', $class)
            ));
            if (file_exists($psr0))
            {
                require $psr0;
                return true;
            }
            
            // Otherwise, attempt to load from PSR-4 style namespace
            $psr4 = vsprintf('%s%s.php', array(
                self::ROOT_DIRECTORY_OF_CONCERN,
                str_replace(array(self::ROOT_NAMESPACE_OF_CONCERN, '\\'), array('', '/'), $class)
            ));
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
