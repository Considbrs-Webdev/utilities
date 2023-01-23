<?php

namespace Consid\Utilities;

class JSON
{
    /**
     * Check if string is JSON
     *
     * @param string $string
     * @return boolean
     */
    public static function isValid($string)
    {
        self::decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    /**
     * Encode Array or Object to JSON string.
     *
     * @param $arr_obj
     *
     * @return false|int|string
     */
    public static function encode($arr_obj)
    {
        return json_encode($arr_obj);
    }

    /**
     * Decode JSON string to Array or Object.
     *
     * @param string $json_string
     * @param bool $associative
     * @param int  $depth
     * @param int  $flags
     *
     * @return array|object
     */
    public static function decode($json_string, $associative = true, $depth = 512, $flags = 0)
    {
        return json_decode($json_string, $associative, $depth, $flags);
    }
}
