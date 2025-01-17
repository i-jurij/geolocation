<?php

declare(strict_types=1);

namespace Ijurij\Geolocation;

use Ijurij\Geolocation\Lib\Isbot;
use Ijurij\Geolocation\Provider\Ipprovider;

final class Geolocation
{
    public function __construct(
        private Router $router = new Router(),
        public string $ip_provider = 'GeoPlugin',// SypexGeo, (make YandexMaps, GoogleMaps, OSM etc)
        public string $lang = 'ru',
        private Ipprovider $provider = new Ipprovider(),
        private array $locality = [],
        public string $url_location_to_server = '/',
        public string $yandex_api_key = '',
        public string $yandex_format = 'json',
        public string $yandex_kind = 'locality',
        public int $yandex_results = 1,
    ) {
    }

    public function run()
    {
        if (Isbot::check()) {
            return;
        }
        // get city, region from ip provider
        $this->provider->ip_provider = $this->ip_provider;
        $this->provider->lang = $this->lang;
        $this->locality = $this->provider->getLocality();
        /*
                print_r($this->locality);
                exit;
        */
        // get controller, method, parameters
        $contr = new $this->router->callback['controller']();
        if (\method_exists($contr, $this->router->callback['method'])) {
            $method = $this->router->callback['method'];
        }

        $params = ['url_location_to_server' => $this->url_location_to_server];

        if (!empty($this->router->callback['parameters'])) {
            $params['parameters'] = $this->router->callback['parameters'];

            if ($this->router->callback['method'] === 'fromCoordYg') {
                $params['parameters']['yandex_api_key'] = $this->yandex_api_key;
                $params['parameters']['yandex_format'] = $this->yandex_format;
                $params['parameters']['yandex_kind'] = $this->yandex_kind;
                $params['parameters']['yandex_results'] = $this->yandex_results;
            }
        }

        call_user_func_array([$contr, $method], ['params' => $params]);
    }
}
