<?php

declare(strict_types=1);

namespace Ijurij\Geolocation;

class Config
{
    public const SRC_DIR = __DIR__;
    public static string $db = 'geolocation.db';
    public static string $template = __DIR__.DIRECTORY_SEPARATOR.'Mvc'.DIRECTORY_SEPARATOR.'template.php';
    /*
    public static function getSrcDir()
    {
        $reflector = new \ReflectionClass('Geolocation\Geolocation');
        $file = realpath($reflector->getFileName());

        return dirname($file, 1);
    }
    */
}
