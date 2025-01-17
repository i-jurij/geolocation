<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Provider;

use Ijurij\Geolocation\Config;

class YandexGeocoder
{
    public function __construct(
    ) {
    }

    public function getLocation($params)
    {
        $locality = [];

        $long = $params['parameters']['long'];
        $lat = $params['parameters']['lat'];
        $yandex_api_key = $params['parameters']['yandex_api_key'];
        $format = $params['parameters']['yandex_format'];
        $results = $params['parameters']['yandex_results'];
        $kind = $params['parameters']['yandex_kind'];

        if (!empty($yandex_api_key) && \is_string($yandex_api_key)) {
            if (\preg_match('/'.Config::REGEX_LAT.'/', $lat) && \preg_match('/'.Config::REGEX_LONG.'/', $long)) {
                $ch = curl_init('https://geocode-maps.yandex.ru/1.x/?apikey='.$yandex_api_key.'&geocode='.$long.','.$lat.'&format='.$format.'&results='.$results.'&kind='.$kind);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // ???
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // ???
                curl_setopt($ch, CURLOPT_HEADER, false); // ??? Header nadoo nastroit
                $res = curl_exec($ch);
                curl_close($ch);

                $res = json_decode($res, true);

                $name = $res['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['name'];
                $description = $res['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['description'];

                if (isset($name) && isset($description)) {
                    $locality = ['city' => $name, 'region' => $description];
                }
            }
        }

        return $locality;
    }
}
