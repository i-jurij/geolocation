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

    public function default($params)
    {
        $data = $params;

        echo $this->view->generate($data, \Ijurij\Geolocation\Config::$template);
    }

    public function fromDb()
    {
        $this->sendJson((new Model())->getAll());
    }

    public function fromCoord($params)
    {
        $this->sendJson((new Model())->fromCoord($params));
    }

    public function fromCoordYg($params)
    {
        $this->sendJson((new YandexGeocoder())->fromCoordYD($params));
    }

    public function locationToServer($params)
    {
        $data = $params;

        echo $this->view->generate($data, \Ijurij\Geolocation\Config::$template);
    }

    public function sendJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
