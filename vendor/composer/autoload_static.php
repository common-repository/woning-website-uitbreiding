<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita8e620da9d1084e970c32ce61da8fefa
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        'a4a119a56e50fbb293281d9a48007e0e' => __DIR__ . '/..' . '/symfony/polyfill-php80/bootstrap.php',
        '6e3fae29631ef280660b3cdad06f25a8' => __DIR__ . '/..' . '/symfony/deprecation-contracts/function.php',
        'a1105708a18b76903365ca1c4aa61b02' => __DIR__ . '/..' . '/symfony/translation/Resources/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Tussendoor\\Settings\\' => 20,
            'Tussendoor\\Rental\\' => 18,
            'Tussendoor\\PropertyWebsite\\Helpers\\' => 35,
            'Tussendoor\\PropertyWebsite\\Exceptions\\' => 38,
            'Tussendoor\\PropertyWebsite\\Controllers\\' => 39,
            'Tussendoor\\PropertyWebsite\\Contracts\\' => 37,
            'Tussendoor\\PropertyWebsite\\' => 27,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Php80\\' => 23,
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Contracts\\Translation\\' => 30,
            'Symfony\\Component\\Translation\\' => 30,
        ),
        'C' => 
        array (
            'Carbon\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Tussendoor\\Settings\\' => 
        array (
            0 => __DIR__ . '/..' . '/tussendoor/settings/src',
        ),
        'Tussendoor\\Rental\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests',
        ),
        'Tussendoor\\PropertyWebsite\\Helpers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/helpers',
        ),
        'Tussendoor\\PropertyWebsite\\Exceptions\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/exceptions',
        ),
        'Tussendoor\\PropertyWebsite\\Controllers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/controllers',
        ),
        'Tussendoor\\PropertyWebsite\\Contracts\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/contracts',
        ),
        'Tussendoor\\PropertyWebsite\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/models',
        ),
        'Symfony\\Polyfill\\Php80\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php80',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Contracts\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation-contracts',
        ),
        'Symfony\\Component\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation',
        ),
        'Carbon\\' => 
        array (
            0 => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon',
        ),
    );

    public static $classMap = array (
        'Attribute' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Attribute.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'PhpToken' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/PhpToken.php',
        'Stringable' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/Stringable.php',
        'UnhandledMatchError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/UnhandledMatchError.php',
        'ValueError' => __DIR__ . '/..' . '/symfony/polyfill-php80/Resources/stubs/ValueError.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita8e620da9d1084e970c32ce61da8fefa::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita8e620da9d1084e970c32ce61da8fefa::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita8e620da9d1084e970c32ce61da8fefa::$classMap;

        }, null, ClassLoader::class);
    }
}
