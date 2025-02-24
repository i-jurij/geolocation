<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Mvc;

use Ijurij\Geolocation\Lib\Csrf;
use Ijurij\Geolocation\Provider\YandexGeocoder;

final class Controller
{
    public function __construct(
        private View $view = new View()
    ) {
    }

    /**
     * Send full html template in response.
     */
    public function default($params)
    {
        return $this->view->generate($params, \Ijurij\Geolocation\Config::$template);
    }

    // ONLY FOR PHP
    /**
     * Get all location for manual user choice if js disabled.
     * Send full html template in response.
     */
    public function fromDbPhp($params)
    {
        if (Csrf::isValid() && Csrf::isRecent()) {
            $params['locations'] = (new Model())->getAll();
        } else {
            $params['locations'] = 'Check if cookies enabled.';
        }

        return $this->default($params);
    }

    // ONLY FOR JS FETCH ------------------------------
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
        $data = [];
        if (Csrf::isValid() && Csrf::isRecent()) {
            $data = (new Model())->getAll();
        }
        $this->sendJson($data);
    }

    /**
     * Get location from db by coord after js fetch.
     * Send json response with object {city: name, adress: region, id: city_id}.
     *
     * @param array $params, $params['parameters']['lat'], $params['parameters']['long']
     */
    public function fromCoord($params)
    {
        $data = [];
        if (Csrf::isValid() && Csrf::isRecent()) {
            $long = $params['long'];
            $lat = $params['lat'];
            $data = (new Model())->fromCoord($long, $lat);
        }
        $this->sendJson($data);
    }

    /**
     * Get location from yandex geocoder by coord after js fetch.
     *  Send json response with object {city: name, region: name}.
     *
     * @param array $params, $params['parameters']['lat'], $params['parameters']['long'], $params['parameters']['yg']
     */
    public function fromCoordYg($params)
    {
        $data = [];
        if (Csrf::isValid() && Csrf::isRecent()) {
            $long = $params['long'];
            $lat = $params['lat'];
            $yandex_api_key = $params['yandex_api_key'];
            $format = $params['yandex_format'];
            $results = $params['yandex_results'];
            $kind = $params['yandex_kind'];
            $data = (new YandexGeocoder())->getLocation($yandex_api_key, $long, $lat, $format, $results, $kind);
        }
        $this->sendJson($data);
    }
    // END ONLY FOR JS FETCH -----------------------------

    /**
     *  Processing location after receiving it from front.
     */
    public function afterUserCityChoice($params)
    {
        // check not js fetch request (post var 'js' must be set in form on page or into formdata from js)
        if (!isset($params['js'])) {
            return $this->default($params);
        }
        // if js then nothing send, locality can be get from php session or (new Geolocation)->getLocality(),
        // data can be send from other controller by url for js fetch
    }

    /**
     * set header('Content-Type: application/json');
     * echo json_encode($data, JSON_UNESCAPED_UNICODE);
     * exit from script.
     */
    public function sendJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
