<?php

/*
 * Example
 * $geo = new Ijurij\Geolocation\Geolocation();
 * // url for getting data by locality
 * $geo->url_location_to_server = '/';
 * // provider for getting locality by ip (can be not set, default GeoPlugin)
 * $geo->ip_provider = 'geoplugin'; // geoplugin, sypexgeo
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
 * <div id="location_div"><?php print_r($geo->getHtml()); ?></div>
 * <!-- for outputting data from server after sending location to server -->
 * <div id="data_by_location"><?php echo (isset($data)) ? $data : ''; ?></div>
 */

declare(strict_types=1);

namespace Ijurij\Geolocation;

use Ijurij\Geolocation\Lib\Isbot;
use Ijurij\Geolocation\Lib\Locality;
use Ijurij\Geolocation\Lib\Router;
use Ijurij\Geolocation\Lib\Session;
use Ijurij\Geolocation\Mvc\Controller;

/**
 * Class display locality of users browser.
 * Class arguments that the user can set:
 * $ip_provider ('geoplugin' or 'sypexgeo') and $lang for ip_provider,
 * $url_location_to_server (to process the received location),
 * and params for yandex geocoder ($yandex_api_key, $yandex_format, $yandex_kind, $yandex_results).
 */
final class Geolocation
{
    public function __construct(
        private Locality $locality = new Locality(),
        private Router $router = new Router(),
        public string $ip_provider = 'geoplugin',// sypexgeo
        public string $lang = 'ru',

        public string $url_location_to_server = '/',

        public string $yandex_api_key = '',
        public string $yandex_format = 'json',
        public string $yandex_kind = 'locality',
        public int $yandex_results = 1,
    ) {
        if (Isbot::check()) {
            return;
        }
        Session::start();
    }

    public function getHtml()
    {
        // set provider and lang for class Locality
        $this->locality->ip_provider = $this->ip_provider;
        $this->locality->lang = $this->lang;

        return call_user_func_array($this->getControllerMethod(), ['params' => $this->getParams()]);
    }

    protected function getControllerMethod()
    {
        $controller = new Controller();
        if (\method_exists($controller, $this->router->callback['method'])) {
            $method = $this->router->callback['method'];
        }

        return [$controller, $method];
    }

    private function getParams()
    {
        $params = [];

        // add params from router
        if (!empty($this->router->callback['parameters'])) {
            $params = $this->router->callback['parameters'];
        }

        // get locality and receive to controller
        $params['locality'] = $this->getLocality();

        // url for processing locality after users city choice
        $params['url_location_to_server'] = $this->url_location_to_server;

        // receive config if it is js fetch request to yandex geocoder (get locality from coord)
        if ($this->router->callback['method'] === 'fromCoordYg') {
            $params['yandex_api_key'] = $this->yandex_api_key;
            $params['yandex_format'] = $this->yandex_format;
            $params['yandex_kind'] = $this->yandex_kind;
            $params['yandex_results'] = $this->yandex_results;
        }

        return $params;
    }

    public function getLocality()
    {
        return $this->locality->get();
    }
}
