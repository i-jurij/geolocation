<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Provider;

final class SypexGeo
{
    public function locate($ip)
    {
        if (\filter_var(\ini_get('allow_url_fopen'), \FILTER_VALIDATE_BOOLEAN)) {
            $json = file_get_contents('http://api.sypexgeo.net/json/'.$ip);
            if ($json !== false) {
                $geo = json_decode($json, true);
                $city_name = $geo['city']['name_ru'] ?? null;
                $region = $geo['region']['name_ru'] ?? null;

                return ['city' => $city_name, 'region' => $region];
            }
        }
    }
}
