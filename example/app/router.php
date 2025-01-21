<?php

if ($_SERVER['REQUEST_URI'] == '/') {
    $route = 'controllerMethodBefore';
}

if ($_SERVER['REQUEST_URI'] == '/location_to_server') {
    $route = 'controllerMethodAfter';
}

if ($_SERVER['REQUEST_URI'] == '/location_to_server_js') {
    $route = 'controllerMethodAfterJs';
}
