<?php

declare(strict_types=1);

namespace Ijurij\Geolocation;

class Config
{
    public const SRC_DIR = __DIR__;
    public static string $db = 'geolocation.db';
    public const REGEX_LAT = '^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$';
    public const REGEX_LONG = '^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$';
    public static string $template = __DIR__.DIRECTORY_SEPARATOR.'Template'.DIRECTORY_SEPARATOR.'template.php';
    public static string $style = __DIR__.DIRECTORY_SEPARATOR.'Template'.DIRECTORY_SEPARATOR.'style.css';

    /*
    public static function getSrcDir()
    {
        $reflector = new \ReflectionClass('Geolocation\Geolocation');
        $file = realpath($reflector->getFileName());

        return dirname($file, 1);
    }
    */
}
