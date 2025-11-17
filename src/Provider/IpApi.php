<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Provider;

final class IpApi
{
    public string $lang = 'ru';
    public function locate($ip)
    {
        $ch = curl_init("http://ip-api.com/json/$ip?lang=$this->lang");
        if ($ch !== FALSE) {
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($ch);
            curl_close($ch);
            return json_decode($res, true);
        } else {
            if (\filter_var(\ini_get('allow_url_fopen'), \FILTER_VALIDATE_BOOLEAN)) {
                $res = file_get_contents("http://ip-api.com/json/$ip?lang=$this->lang");

                if ($res && $res['status'] == 'success') {
                    return json_decode($res, true);
                }

                return false;
            }
        }
        return false;
    }
}


