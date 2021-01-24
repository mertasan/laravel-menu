<?php

namespace Mertasan\Menu\Helpers;

class Helpers {

    public static function checkPhpVersion ($requiredVersion, $operator = "<")
    {
        try {
            return version_compare(PHP_VERSION, $requiredVersion, $operator) ?? false;
        } catch (\Throwable $e) {
            return false;
        }
    }

}
