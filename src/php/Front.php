<?php

declare(strict_types=1);

namespace Geolocation\Php;

/**
 * @property GeoTemplate $template
 */
final class Front
{
    protected Model $geo;

    public function __construct()
    {
        $this->geo = new Model();
    }

    public function actionGetAll()
    {
        $this->sendJson($this->geo->getAll());
    }

    public function actionDistrict()
    {
        $this->sendJson($this->geo->district());
    }

    public function actionRegion($id)
    {
        $this->sendJson($this->geo->region($id));
    }

    public function actionCity($id)
    {
        $this->sendJson($this->geo->city($id));
    }

    // $id = long_lat from request (eg 44.000000_33.000000)
    public function actionLocationFromCoord($id)
    {
        $this->sendJson($this->geo->fromCoord($id));
    }
}
