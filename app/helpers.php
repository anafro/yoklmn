<?php

declare(strict_types=1);

if (! function_exists('app_version')) {
    function app_version(string $by_default = "dev build"): string
    {
        return trim(shell_exec('git describe --tags --abbrev=0 2>/dev/null') ?? $by_default);
    }
}
