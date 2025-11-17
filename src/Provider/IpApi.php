<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Provider;

final class IpApi
{
    public string $lang = 'ru';
    public function locate($ip)
    {
        if (!$this->getUserGeoLimits()) {
            $ch = curl_init();
            if ($ch !== FALSE) {
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/$ip?lang=$this->lang");
                curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, $header) {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) {
                        return $len;
                    }
                    if (trim($header[0]) == 'X-Rl') {
                        @file_put_contents('./X-Rl.txt', \intval(trim($header[1])));
                    } elseif (trim($header[0]) == 'X-Ttl') {
                        @file_put_contents('./X-Ttl.txt', time() + \intval(trim($header[1])));
                    }
                    return $len;
                });

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
                return false;
            }
        } else {
            $data = ['status' => false, 'message' => 'Достигнут лимит сервиса'];
            return $data;
        }
    }

    public function getUserGeoLimits()
    {
        if (file_exists('./X-Rl.txt') && file_exists('./X-Ttl.txt')) {
            $X_Rl = @file_get_contents('./X-Rl.txt');
            $X_Ttl = @file_get_contents('./X-Ttl.txt');
            if ($X_Rl == 0 && time() < $X_Ttl) {
                return true;
            }
            return false;
        } else {
            return false;
        }

    }
}


