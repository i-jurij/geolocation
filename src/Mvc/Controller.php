<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Mvc;

use Ijurij\Geolocation\Provider\YandexGeocoder;

final class Controller
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
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
        $this->sendJson((new YandexGeocoder())->getLocation($params));
    }

    // if get location from front
    /*
    public function afterUserCityChoice($params)
    {
        $data = $params;

        echo $this->view->generate($data, \Ijurij\Geolocation\Config::$template);
    }
    */

    public function sendJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
