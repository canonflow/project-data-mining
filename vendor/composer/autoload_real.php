<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitb8ac2680c825bfb08ee985c8ba5f484c
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitb8ac2680c825bfb08ee985c8ba5f484c', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitb8ac2680c825bfb08ee985c8ba5f484c', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitb8ac2680c825bfb08ee985c8ba5f484c::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
