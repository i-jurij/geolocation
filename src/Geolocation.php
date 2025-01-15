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
        return $this->router->callback;

        $contr = new $this->router->callback['controller']();
        if (\method_exists($contr, $this->router->callback['method'])) {
            $method = $this->router->callback['method'];
        }
        if (!empty($this->router->callback['parameters'])) {
            $params = $this->router->callback['parameters'];
        }

        call_user_func_array([$contr, $method], $params);
    }
}
