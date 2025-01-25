<?php

$geo = new Ijurij\Geolocation\Geolocation();
// url for getting data by locality
$geo->url_location_to_server = 'location_to_server';
// provider for getting locality by ip (can be not set, default GeoPlugin)
$geo->ip_provider = 'geoplugin'; // geoplugin, sypexgeo
// for language of provider answer
$geo->lang = 'ru';
// if you plan to use yandex geocoder (in most cases this is not necessary)
/*
$geo->yandex_api_key = '';
$geo->yandex_format = 'json';
$geo->yandex_kind = 'locality';
$geo->yandex_results = 1;
*/

$html = $geo->getHtml();
$locality = $geo->getLocality();
$param = [$html, $locality];

function controllerMethodBefore(array $args)
{
    return [
        'html' => $args[0],
        'dataFromModel' => model($args[1]['city']),
    ];
}

function controllerMethodAfter(array $args)
{
    // save input data to model or other action then
    // refresh page in order to prevent the form submitted again
    header("Refresh:0; url='/'");

    return controllerMethodBefore($args);
}

function controllerMethodAfterJs(array $args)
{
    header('Content-Type: application/json');
    echo json_encode([
        'city' => $args[1]['city'],
        'region' => $args[2]['region'],
        'dataFromModel' => model($args[1]['city']),
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$data = call_user_func($route, $param);
