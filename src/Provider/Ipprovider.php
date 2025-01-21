<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Provider;

use Ijurij\Geolocation\Lib\Ip;

final class Ipprovider
{
    private string $ip;

    public function __construct(
        public string $ip_provider = 'geoplugin',
        public string $lang = 'ru',
    ) {
        $ip = (new Ip())->get()['ip'];
        $userIP = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);

        $this->ip = ($userIP != false) ? $userIP : '';
    }

    public function getLocality(): array
    {
        if (\method_exists($this, strtolower($this->ip_provider))) {
            return $this->{$this->ip_provider}();
        }

        return [];
    }

    public function geoplugin(): array
    {
        $geoplugin = new GeoPlugin();
        $geoplugin->lang = $this->lang;
        $geoplugin->locate($this->ip);
        // Simferopol
        // $geoplugin->locate('2.63.182.224');

        $city_name = $geoplugin->city ?? '';
        $region = $geoplugin->region ?? '';

        return ['city' => $city_name, 'region' => $region];
    }

    public function sypexgeo(): array
    {
        $res = ['city' => '', 'region' => ''];
        $geoplugin = new SypexGeo();

        $res = $geoplugin->locate($this->ip);
        if ($res !== false) {
            $city_name = $geo['city']['name_ru'] ?? '';
            $region = $geo['region']['name_ru'] ?? '';
            $res = ['city' => $city_name, 'region' => $region];
        }

        return $res;
    }
}
