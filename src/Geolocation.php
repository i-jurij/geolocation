<?php

declare(strict_types=1);

namespace Ijurij\Geolocation;

use Ijurij\Geolocation\Lib\Router;

final class Geolocation
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function run()
    {
        // return $this->view();
        return $this->router->callback;
    }
}
