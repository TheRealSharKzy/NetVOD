<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite124eb20742a010f4a6e2c6dee4bd092
{
    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/../..' . '/',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->fallbackDirsPsr4 = ComposerStaticInite124eb20742a010f4a6e2c6dee4bd092::$fallbackDirsPsr4;
            $loader->classMap = ComposerStaticInite124eb20742a010f4a6e2c6dee4bd092::$classMap;

        }, null, ClassLoader::class);
    }
}
