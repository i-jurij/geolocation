<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Provider;

use Ijurij\Geolocation\Config;

class YandexGeocoder
{
    public function getLocation($yandex_api_key, $long, $lat, $format, $results, $kind): array
    {
        $locality = [];

        if (!empty($yandex_api_key) && \is_string($yandex_api_key)) {
            if (\preg_match('/'.Config::REGEX_LAT.'/', $lat) && \preg_match('/'.Config::REGEX_LONG.'/', $long)) {
                $ch = curl_init('https://geocode-maps.yandex.ru/1.x/?apikey='.$yandex_api_key.'&geocode='.$long.','.$lat.'&format='.$format.'&results='.$results.'&kind='.$kind);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // ???
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // ???
                curl_setopt($ch, CURLOPT_HEADER, false); // ??? Header nadoo nastroit
                $res = curl_exec($ch);
                curl_close($ch);

                $res = json_decode($res, true);

                if (!empty($res['response']['GeoObjectCollection']['featureMember'][0])) {
                    $name = $res['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['name'];
                    $description = $res['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['description'];
                }

                if (isset($name) && isset($description)) {
                    $locality = ['city' => $name, 'region' => $description];
                } else {
                    return ['error' => 'City name not received. Check structure of yandex geocoders response.'];
                }
            } else {
                return ['error' => 'Requests longitude or latitude has wrong value'];
            }
        } else {
            return ['error' => 'API key not isset'];
        }

        return $locality;
    }
}
