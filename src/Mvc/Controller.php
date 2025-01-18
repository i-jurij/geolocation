<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Mvc;

use Ijurij\Geolocation\Lib\Session;
use Ijurij\Geolocation\Provider\YandexGeocoder;

final class Controller
{
    private View $view;
    private Session $session;

    public function __construct()
    {
        $this->view = new View();
        $this->session = new Session();
        $this->session->start();
    }

    // for php html
    /**
     * Send full html template in response.
     */
    public function default($params)
    {
        $data = $params;

        echo $this->view->generate($data, \Ijurij\Geolocation\Config::$template);
    }

    /**
     * Get all location for manual user choice if js disabled.
     * Send full html template in response.
     */
    public function fromDbPhp()
    {
        $locations = (new Model())->getAll();
        echo $this->view->generate($locations, \Ijurij\Geolocation\Config::$template);
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
    public function fromDb()
    {
        $this->sendJson((new Model())->getAll());
    }

    /**
     * Get location from db by coord after js fetch.
     * Send json response with object {city: name, adress: region, id: city_id}.
     *
     * @param array $params, $params['parameters']['lat'], $params['parameters']['long']
     */
    public function fromCoord($params)
    {
        $this->sendJson((new Model())->fromCoord($params));
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
        // set session locality
        $this->session->setArray([
            'city' => $params['city'],
            'region' => (!empty($this->locality['region'])) ? $this->locality['region'] : '',
            'id' => (!empty($this->locality['city_id'])) ? $this->locality['city_id'] : '',
        ]);
        // html out
        echo $this->view->generate($params, \Ijurij\Geolocation\Config::$template);
    }

    public function sendJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
