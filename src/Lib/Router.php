<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Lib;

final class Router
{
    public array $callback;

    public function __construct()
    {
        $this->callback = [];

        $methods = [
            'fromDb' => 'fromDb',
            'fromCoord' => 'fromCoord',
            'fromCoordYg' => 'fromCoordYg',
            'locationToServer' => 'locationToServer',
            'default' => 'default',
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // default callback (first request or empty GET request)
            $this->callback = [
                'controller' => 'Geolocation\Controller',
                'method' => 'default',
                'parameters' => [],
            ];

            // js fetch for getting location from db by coord
            if (isset($_GET['coord'])
                && $_SERVER['HTTP_SEC_FETCH_SITE'] == 'same-origin') {
                $this->callback['method'] = $methods['fromCoord'];
                $this->callback['parameters']['coord'] = filter_input(INPUT_GET, 'coord', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            // js fetch for getting location from yandex geocoder by coord
            if (isset($_GET['long'])
                    && isset($_GET['lat'])
                    && $_SERVER['HTTP_SEC_FETCH_SITE'] == 'same-origin') {
                $this->callback['method'] = $methods['fromCoordYg'];
                $this->callback['parameters']['long'] = filter_input(INPUT_GET, 'long', FILTER_SANITIZE_SPECIAL_CHARS);
                $this->callback['parameters']['lat'] = filter_input(INPUT_GET, 'lat', FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // get all locations from db for manual user choice
            if (filter_input(INPUT_POST, 'all_loc') == 'fromdb'
                    && $_SERVER['HTTP_SEC_FETCH_SITE'] == 'same-origin'
                    && isset($_SERVER['HTTP_X_FROMDB'])
                    && strtolower($_SERVER['HTTP_X_FROMDB']) == 'shoosefromdb'
            ) {
                $this->callback['method'] = $methods['fromDb'];
            }

            // if get location from front
            if (!empty($_POST['region'])
                    && !empty($_POST['city'])
                    && $_SERVER['HTTP_SEC_FETCH_SITE'] == 'same-origin') {
                $this->callback['method'] = $methods['locationToServer'];
                $this->callback['parameters']['region'] = filter_input(INPUT_POST, 'region', FILTER_SANITIZE_SPECIAL_CHARS);
                $this->callback['parameters']['city'] = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS);
                if (filter_input(INPUT_POST, 'id') !== false) {
                    $this->callback['parameters']['id'] = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                }
            }
        }
    }
}
