<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Lib;

class Isbot
{
    // прочие боты
    public const BOTS = [
        'yandex', 'google', 'bot', 'spider', 'crawler', 'curl',
        'Accoona', 'ia_archiver', 'Ask Jeeves', 'W3C_Validator', 'WebAlta', 'YahooFeedSeeker',
        'Yahoo!', 'Ezooms', 'SiteStatus', 'Nigma.ru', 'Baiduspider', 'SISTRIX', 'findlinks',
        'proximic', 'OpenindexSpider', 'statdom.ru', 'Spider', 'Snoopy', 'heritrix', 'Yeti',
        'DomainVader', 'StackRambler',
    ];

    /**
     * function for bots check.
     */
    public static function check(): bool
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (empty($user_agent)) {
            return false;
        }
        foreach (self::BOTS as $bot) {
            if (stripos($user_agent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }
}
