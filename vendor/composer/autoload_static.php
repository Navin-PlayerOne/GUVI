<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita70693fa1f7330344763383f8d4a098c
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita70693fa1f7330344763383f8d4a098c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita70693fa1f7330344763383f8d4a098c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita70693fa1f7330344763383f8d4a098c::$classMap;

        }, null, ClassLoader::class);
    }
}
