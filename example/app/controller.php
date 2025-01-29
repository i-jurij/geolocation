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
    $data = [
        'html' => $args[0],
        'dataFromModel' => model($args[1]['city']),
    ];
    require_once realpath('../app/view.php');
}

function controllerMethodAfter(array $args)
{
    // save input data to model or other action then
    // redirect in order to prevent the form submitted again
    header("Refresh:0; url='/'");

    return controllerMethodBefore($args);
}
// return nothing, json send from Geolocation
function url_js_fetch(array $args)
{
}

function controllerMethodAfterJs(array $args)
{
    header('Content-Type: application/json');
    echo json_encode(model($args[1]['city']), JSON_UNESCAPED_UNICODE);
    exit;
}

return call_user_func($route, $param);
