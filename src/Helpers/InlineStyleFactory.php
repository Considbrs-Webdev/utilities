<?php

namespace Consid\Helpers;

use Consid\Utilities\Arr;

// phpcs:disable
if (! defined('ABSPATH')) {
    exit;
}
// phpcs:enable

class InlineStyleFactory
{

    /**
     * List of all classes
     * @author Adam Alexandersson
     * @since 1.0.0
     */
    private $style = [];

    /**
     * List of all classes ( combined string )
     * @author Adam Alexandersson
     * @since 1.0.0
     */
    private $style_string;

    public function __construct()
    {
    }

    public function add($property, $value)
    {
        if ($property !== '' && $value !== '') {
            $this->style[] = array(
                'prop' => $property,
                'value' => $value,
            );

            $this->join();
        }
    }

    public function get()
    {
        return $this->style_string;
    }

    public function isset($search)
    {
        $res = Arr::search('prop', $search, $this->style);
        $res = reset($res);

        return $res !== false;
    }

    private function join()
    {
        if (! empty($this->style)) {
            $this->style = array_filter($this->style);
            $this->style_string = join(' ', array_map(function ($key) {
                $prop = $this->style[ $key ]['prop'];
                $value = $this->style[ $key ]['value'];

                if ($prop == 'background-image') {
                    return $prop . ':url(' . $value . ');';
                } else {
                    return $prop . ': ' . $value . ';';
                }
            }, array_keys($this->style)));
        }
    }
}
