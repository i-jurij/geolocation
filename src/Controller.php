<?php

declare(strict_types=1);

namespace Ijurij\Geolocation;

final class Controller
{
    public function __construct()
    {
    }

    public function someMethod($parameters)
    {
        $data = $parameters;

        return $this->view($data, Config::$template);
    }
}
