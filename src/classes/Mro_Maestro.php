<?php

namespace Maestro;

class Mro_Maestro
{
    private static $publicBase = null;
    private static $siteBase = null;

    public static function getPublicBase(): string
    {
        if (self::$publicBase == null) {
            $base = __DIR__ . '/../public';
            self::$publicBase = realpath($base);
        }
        return self::$publicBase;
    }

    public static function getSiteBase(): string
    {
        if (self::$siteBase == null) {
            $base = __DIR__ . '/../public/' . $_SERVER['maestro_site'];
            self::$siteBase = realpath($base);
        }
        return self::$siteBase;
    }

    public static function getSite(): string
    {
        return $_SERVER['maestro_site'];
    }

    public static function getSitePath(string $relativePath): string
    {
        return realpath(__DIR__ . '/../public/' . $_SERVER['maestro_site'] . '/' . $relativePath);
    }
}