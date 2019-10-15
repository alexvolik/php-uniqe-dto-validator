<?php

namespace App\Helper;

class ArrayHelper
{
    public static function isAssocArray(array $array): bool
    {
        if (empty($array)) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }

    public static function getFirstStringKeyInAssocArray(array $array): ?string
    {
        return array_reduce(array_keys($array), function ($carry, $key) {
            return is_string($key) ? $key : null;
        });
    }
}