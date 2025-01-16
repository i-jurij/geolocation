<?php

declare(strict_types=1);

namespace Ijurij\Geolocation\Mvc;

final class View
{
    public function generate($data, $template)
    {
        if (is_readable($template)) {
            include $template;
        }
    }
}
