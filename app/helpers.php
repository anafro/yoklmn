<?php

declare(strict_types=1);

if (! function_exists('app_version')) {
    function app_version(): string
    {
        return "v0.0.0-dev";
    }
}
