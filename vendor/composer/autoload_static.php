<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit55081fc7a20f868c8f2d6698b15ce969
{
    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/../..' . '/src',
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->fallbackDirsPsr4 = ComposerStaticInit55081fc7a20f868c8f2d6698b15ce969::$fallbackDirsPsr4;
            $loader->classMap = ComposerStaticInit55081fc7a20f868c8f2d6698b15ce969::$classMap;

        }, null, ClassLoader::class);
    }
}
