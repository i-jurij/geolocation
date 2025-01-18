<?php

declare(strict_types=1);

namespace Ijurij\Geolocation;

use Ijurij\Geolocation\Lib\Isbot;
use Ijurij\Geolocation\Lib\Session;
use Ijurij\Geolocation\Provider\Ipprovider;

/**
 * $geo = new Ijurij\Geolocation\Geolocation();
 * // url for getting data by locality
 * $geo->url_location_to_server = '/';
 * // provider for getting locality by ip (can be not set, default GeoPlugin)
 * $geo->ip_provider = 'GeoPlugin'; // GeoPlugin, SypexGeo, (make YandexMaps, GoogleMaps, OSM etc)
 * // for language of provider answer
 * $geo->lang = 'ru';
 * // if you plan to use yandex geocoder (in most cases this is not necessary)
 * $geo->yandex_api_key = '';
 * $geo->yandex_format = 'json';
 * $geo->yandex_kind = 'locality';
 * $geo->yandex_results = 1;.
 *
 * Then on page
 *  <!-- for outputting city and location -->
 * <div id="location_div"><?php print_r($geo->run()); ?></div>
 * <!-- for outputting data from server after sending location to server -->
 * <div id="data_by_location"><?php echo (isset($data)) ? $data : ''; ?></div>
 */
final class Geolocation
{
    private Session $session;

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
        $this->session = $this->router->session;
        // assign a value $this->locality
        $this->getLocality();
    }

    public function run()
    {
        if (Isbot::check()) {
            return;
        }

        call_user_func_array($this->getCM(), ['params' => $this->getP()]);
    }

    protected function getLocality(): void
    {
        if ($this->session->has('city')) {
            $this->locality = [
                'city' => $this->session->get('city'),
                'region' => ($this->session->has('region')) ? $this->session->get('region') : '',
                'id' => ($this->session->has('city_id')) ? $this->session->get('city_id') : '',
            ];
        } else {
            $this->provider->ip_provider = $this->ip_provider;
            $this->provider->lang = $this->lang;
            $this->locality = $this->provider->getLocality();
            // save locality to session
            $this->setSessionLocality();
        }
    }

    protected function getCM()
    {
        $controller = new $this->router->callback['controller']();
        if (\method_exists($controller, $this->router->callback['method'])) {
            $method = $this->router->callback['method'];
        }

        return [$controller, $method];
    }

    // get parameters
    private function getP()
    {
        $params = [];
        $params['locality'] = $this->locality;
        $params['url_location_to_server'] = $this->url_location_to_server;

        if (!empty($this->router->callback['parameters'])) {
            $params = $this->router->callback['parameters'];

            if ($this->router->callback['method'] === 'fromCoordYg') {
                $params['yandex_api_key'] = $this->yandex_api_key;
                $params['yandex_format'] = $this->yandex_format;
                $params['yandex_kind'] = $this->yandex_kind;
                $params['yandex_results'] = $this->yandex_results;
            }
        }

        return $params;
    }

    private function setSessionLocality()
    {
        if (!empty($this->locality['city'])) {
            $this->session->setArray([
                'city' => $this->locality['city'],
                'region' => (!empty($this->locality['region'])) ? $this->locality['region'] : '',
                'id' => (!empty($this->locality['city_id'])) ? $this->locality['city_id'] : '',
            ]);
        }
    }
}
