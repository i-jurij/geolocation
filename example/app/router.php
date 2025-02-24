<?php

if ($_SERVER['REQUEST_URI'] == '/') {
    $route = 'controllerMethodBefore';
}
if (str_contains($_SERVER['REQUEST_URI'], '/url_js_fetch')
    && (isset($_GET['db']) || isset($_GET['yg']) || isset($_POST['all_loc']))) {
    $route = 'url_js_fetch';
}
if ($_SERVER['REQUEST_URI'] == '/location_to_server') {
    $route = 'controllerMethodAfter';
}
if ($_SERVER['REQUEST_URI'] == '/location_to_server_js') {
    $route = 'controllerMethodAfterJs';
}
