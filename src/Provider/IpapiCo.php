<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Provider;

final class IpapiCo
{
    //public string $lang = 'ru';
    public function locate($ip)
    {
        $req = "https://ipapi.co/$ip/json/";
        $ch = curl_init();
        if ($ch !== FALSE) {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL, $req);
            $res = curl_exec($ch);
            curl_close($ch);
        } else {
            if (\filter_var(\ini_get('allow_url_fopen'), \FILTER_VALIDATE_BOOLEAN)) {
                $res = file_get_contents($req);
            }
        }
        if ($res) {
            $res = json_decode($res, true);
            return $res;
        }

        return false;
    }
}


