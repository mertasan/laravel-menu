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

    /**
     * Merge multiple arrays in one.
     *
     * @param $arrays
     * @return array
     */
    public static function arrayExtend(&$arrays): array
    {
        if (!is_array($arrays)) {
            $arrays = array();
        }

        $args = func_get_args();
        $argsCount = count($args);

        for ($i = 1; $i < $argsCount; $i++) {
            // we only work on array parameters:
            if (!is_array($args[$i])) continue;

            // extend current result:
            foreach ($args[$i] as $k => $v) {
                if (!isset($arrays[$k])) {
                    $arrays[$k] = $v;
                }
                else {
                    if (is_array($arrays[$k]) && is_array($v)) {
                        self::arrayExtend($arrays[$k], $v);
                    }
                    else {
                        $arrays[$k] = $v;
                    }
                }
            }
        }

        return $arrays;
    }

}
