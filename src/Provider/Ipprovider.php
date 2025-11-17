<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Provider;

use Ijurij\Geolocation\Lib\Ip;

final class Ipprovider
{
    private string $ip;

    public function __construct(
        public string $ip_provider = 'ipapi',
        public string $lang = 'ru',
    ) {
        $ip = (new Ip())->get()['ip'];
        // $ip = '188.127.239.183'; // Москва, Москва
        // $ip = '78.128.211.35'; // Прага, Прага
        // $ip = '2.63.182.224';// Simferopol

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
        $city_name = $geoplugin->city ?? '';
        $region = $geoplugin->region ?? '';

        return ['city' => $city_name, 'region' => $region];
    }

    public function sypexgeo(): array
    {
        $res = ['city' => '', 'region' => ''];
        $geoplugin = new SypexGeo();
        $ar = $geoplugin->locate($this->ip);
        if ($ar !== false) {
            $city_name = !empty($ar['city']['name_ru']) ? $ar['city']['name_ru'] : '';
            $region = !empty($ar['region']['name_ru']) ? $ar['region']['name_ru'] : '';
            $res = ['city' => $city_name, 'region' => $region];
        }

        return $res;
    }

    public function ipapi(): array
    {
        $res = ['city' => '', 'region' => ''];
        $geoplugin = new IpApi();
        $geoplugin->lang = $this->lang;
        $ar = $geoplugin->locate($this->ip);
        if ($ar !== false) {
            $city_name = $ar['city'] ?? '';
            $region = $ar['regionName'] ?? '';
            $res = ['city' => $city_name, 'region' => $region];
        }

        return $res;
    }
}
