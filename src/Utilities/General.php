<?php

namespace Consid\Utilities;

class General
{
    public static function pp($print, $wp_die = false)
    {
        echo '<pre>';
        print_r($print);
        echo '</pre>';

        if (function_exists('wp_die') && $wp_die) {
            wp_die();
        }
    }

    /**
     * Parse string like "title:Hello world|weekday:Monday" to array( 'title' => 'Hello World', 'weekday' => 'Monday' )
     *
     * @param $value
     * @param array $default
     *
     * @since 4.2
     * @return object
     */
    public static function buildLink($value, $default = ['url' => false, 'title' => false, 'target' => '_self', 'rel' => '']) {
        $result = $default;

        if (gettype($value) === 'string') {
            $params_pairs = explode('|', $value);

            if (! empty($params_pairs)) {
                foreach ($params_pairs as $pair) {
                    $param = preg_split('/\:/', $pair);

                    if (! empty($param[0]) && isset($param[1])) {
                        switch ($param[0]) {
                            case 'url':
                                $result['href'] = rawurldecode($param[1]);
                                unset($result['url']);
                                break;

                            case 'title':
                                $result['aria-label'] = rawurldecode($param[1]);
                                $result[ $param[0] ] = rawurldecode($param[1]);
                                break;

                            default:
                                $result[ $param[0] ] = rawurldecode($param[1]);
                                break;
                        }
                    }
                }
            }
        }

        return (object) $result;
    }

    /**
     * Parse array, all string values that are "true" or "false" will be returned as booleans.
     * IMPORTANT: Keeping this here for backward compatibility, function moved to Arr class.
     *
     * @param array &$array
     *
     * @return void
     */
    public static function parseBool(&$array)
    {
        Arr::parseBool($array);
    }

    /**
     * Check if JSON string is valid.
     * IMPORTANT: Keeping this here for backward compatibility, function moved to JSON class.
     *
     * @param string $string
     * @return boolean
     */
    public static function isJson($string)
    {
        return JSON::isValid($string);
    }
}
