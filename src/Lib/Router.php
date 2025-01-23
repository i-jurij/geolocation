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
            'default' => 'default',
            'fromDbPhp' => 'fromDbPhp',
            'fromDb' => 'fromDb',
            'fromCoord' => 'fromCoord',
            'fromCoordYg' => 'fromCoordYg',
            'afterUserCityChoice' => 'afterUserCityChoice',
        ];

        // default callback (any request except requests after this definitions)
        $this->callback = [
            'method' => 'default',
            'parameters' => [],
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // js fetch for getting location from db by coord
            if (isset($_GET['long']) && isset($_GET['lat']) && isset($_GET['db'])) {
                $this->callback['method'] = $methods['fromCoord'];
                $this->callback['parameters']['long'] = filter_input(INPUT_GET, 'long', FILTER_SANITIZE_SPECIAL_CHARS);
                $this->callback['parameters']['lat'] = filter_input(INPUT_GET, 'lat', FILTER_SANITIZE_SPECIAL_CHARS);
            }
            // js fetch for getting location from yandex geocoder by coord
            if (isset($_GET['long']) && isset($_GET['lat']) && isset($_GET['yg'])) {
                $this->callback['method'] = $methods['fromCoordYg'];
                $this->callback['parameters']['long'] = filter_input(INPUT_GET, 'long', FILTER_SANITIZE_SPECIAL_CHARS);
                $this->callback['parameters']['lat'] = filter_input(INPUT_GET, 'lat', FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['all_loc'])) {
                // http request for get all locations from db for manual user choice if js disabled
                if (filter_input(INPUT_POST, 'all_loc') != 'fromdb') {
                    $this->callback['method'] = $methods['fromDbPhp'];
                }
                // js fetch for get all locations from db for manual user choice
                if (filter_input(INPUT_POST, 'all_loc') == 'fromdb') {
                    $this->callback['method'] = $methods['fromDb'];
                }
            }

            // if get location from front
            if (!empty($_POST['region']) && !empty($_POST['city'])) {
                $this->callback['method'] = $methods['afterUserCityChoice'];
                // check js fetch (post var 'js' must be set in form on page)
                if (!empty($_POST['js'])) {
                    $this->callback['parameters']['js'] = 'js';
                }
            }
        }
    }
}
