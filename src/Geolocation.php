<?php

declare(strict_types=1);

namespace Ijurij\Geolocation;

final class Geolocation
{
    private Router $router;
    public string $url_location_to_server;

    public function __construct()
    {
        $this->router = new Router();
        $this->url_location_to_server = '/';
    }

    public function run()
    {
        $contr = new $this->router->callback['controller']();
        if (\method_exists($contr, $this->router->callback['method'])) {
            $method = $this->router->callback['method'];
        }

        $params = ['url_location_to_server' => $this->url_location_to_server];
        if (!empty($this->router->callback['parameters'])) {
            $params['parameters'] = $this->router->callback['parameters'];
        }

        call_user_func_array([$contr, $method], ['params' => $params]);
    }
}
