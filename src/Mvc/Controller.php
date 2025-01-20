<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Mvc;

use Ijurij\Geolocation\Lib\Csrf;
use Ijurij\Geolocation\Lib\Session;
use Ijurij\Geolocation\Provider\YandexGeocoder;

final class Controller
{
    public function __construct(
        private View $view = new View(),
        private Csrf $csrf = new Csrf(),
        private Session $session = new Session()
    ) {
        $this->session->start();
    }

    // for php html
    /**
     * Send full html template in response.
     */
    public function default($params)
    {
        echo $this->view->generate($params, \Ijurij\Geolocation\Config::$template);
    }

    /**
     * Get all location for manual user choice if js disabled.
     * Send full html template in response.
     */
    public function fromDbPhp($params)
    {
        $params['locations'] = (new Model())->getAll();
        echo $this->view->generate($params, \Ijurij\Geolocation\Config::$template);
    }

    // for js fetch
    /**
     * Get all location for manual user choice after js fetch.
     * Send json in response
     * Response is object of nested data: object['district'] -> nested regions -> nested cities
     * response['district'] = {{id: id, name: name, regions: {...}}, ..., {id: id, name: name, regions: {...}}}
     * response['district'][$n]['regions'] = {{id: id, name: name, cities: {...}}, ..., {id: id, name: name, cities: {...}}}
     * response['district'][$n]['regions'][$k] = cities {{id: id, name: name}, ..., {id: id, name: name}}.
     */
    public function fromDb($params)
    {
        if ($this->csrf->check_valid('post')) {
            $this->sendJson((new Model())->getAll());
        }
    }

    /**
     * Get location from db by coord after js fetch.
     * Send json response with object {city: name, adress: region, id: city_id}.
     *
     * @param array $params, $params['parameters']['lat'], $params['parameters']['long']
     */
    public function fromCoord($params)
    {
        $long = $params['long'];
        $lat = $params['lat'];
        $this->sendJson((new Model())->fromCoord($long, $lat));
    }

    /**
     * Get location from yandex geocoder by coord after js fetch.
     *  Send json response with object {city: name, region: name}.
     *
     * @param array $params, $params['parameters']['lat'], $params['parameters']['long'], $params['parameters']['yg']
     */
    public function fromCoordYg($params)
    {
        $long = $params['long'];
        $lat = $params['lat'];
        $yandex_api_key = $params['yandex_api_key'];
        $format = $params['yandex_format'];
        $results = $params['yandex_results'];
        $kind = $params['yandex_kind'];
        $this->sendJson((new YandexGeocoder())->getLocation($yandex_api_key, $long, $lat, $format, $results, $kind));
    }

    // if get location from front
    public function afterUserCityChoice($params)
    {
        if ($this->csrf->check_valid('post')) {
            // set session locality
            $this->session->setArray([
                'city' => $params['locality']['city'],
                'region' => (!empty($params['locality']['region'])) ? $params['locality']['region'] : '',
                'id' => (!empty($params['locality']['id'])) ? $params['locality']['id'] : '',
            ]);
            // html out
            echo $this->view->generate($params, \Ijurij\Geolocation\Config::$template);
        }
    }

    public function sendJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
