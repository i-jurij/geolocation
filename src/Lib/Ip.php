<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Lib;

/**
 * Get ip from request headers, call Ip->get().
 */
class Ip
{
    /**
     * Get ip from request headers.
     * Return array('ip' => $ip, 'suspected' => $ipSus, 'network' => $ipAll).
     */
    public static function get(): array
    {
        $ip = '';
        $ipAll = []; // networks IP
        $ipSus = []; // suspected IP
        $serverVariables = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_X_COMING_FROM',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'HTTP_COMING_FROM',
            'HTTP_CLIENT_IP',
            'HTTP_FROM',
            'HTTP_VIA',
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'REMOTE_ADDR',
        ];
        foreach ($serverVariables as $serverVariable) {
            $value = '';
            if (isset($_SERVER[$serverVariable])) {
                $value = $_SERVER[$serverVariable];
            } elseif (getenv($serverVariable)) {
                $value = getenv($serverVariable);
            }
            if (!empty($value)) {
                $tmp = explode(',', $value);
                $ipSus[] = $tmp[0];
                $ipAll = array_merge($ipAll, $tmp);
            }
        }
        $ipSus = array_unique($ipSus);
        $ipAll = array_unique($ipAll);
        $ip = (sizeof($ipSus) > 0) ? $ipSus[0] : $ip;

        return [
            'ip' => $ip,
            'suspected' => $ipSus,
            'network' => $ipAll,
        ];
    }
}
