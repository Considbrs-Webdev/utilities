<?php

namespace Consid\Helpers;

// phpcs:disable
if (! defined('ABSPATH')) {
    exit;
}
// phpcs:enable // phpcs:ignore

use Consid\Utilities\General;
use Consid\Helpers\AttributeFactory;
use Consid\Helpers\ClassFactory;

class ViewController
{

    public $default_class = 'block';

    /**
     * Functions to autoload
     *
     * @author Adam Alexandersson
     * @since 1.0.0
     */
    public $autoloads = [
        'buildAttributes',
    ];

    /**
     * Data to return with the get method
     *
     * @author Adam Alexandersson
     * @since 1.0.0
     */
    public $data = [];

    /**
     * Data to return with the get method
     *
     * @author Adam Alexandersson
     * @since 1.0.0
     */
    // public $atts = [];

    /**
     * Default classes for a block
     *
     * @author Adam Alexandersson
     * @since 1.0.0
     */
    private $defaults = [
        'attributes' => false,
        'class' => false,
        'id' => false,
        'role' => false,
        'aria_label' => false,
    ];


    /**
     * Reserved object properties
     *
     * @author Adam Alexandersson
     * @since 1.0.0
     */
    private $reserved = [
        'atts',
        'defaults',
        'data',
        'autoloads',
        'default_class',
    ];

    public function __construct($defaults)
    {
        $this->handleAtts($defaults);
        $this->data['default_class'] = $this->default_class;

        foreach ($this->autoloads as $function) {
            call_user_func_array([$this, $function], []);
        }
    }

    public function handleAtts($defaults = false)
    {
        try {
            $defaults = $defaults ? $defaults : $this->defaults;

            General::parseBool($this->atts);
            $this->atts = wp_parse_args($this->atts, $defaults);

            foreach ($this->atts as $key => $a) {
                if (gettype($key) === 'string' && in_array($key, $this->reserved)) {
                    throw new \Exception(sprintf(__('Object/Class property name `%1$s` is reserved, please choose a different name.', 'ascella'), $key));
                    break;
                }

                $this->{$key} = $a;
            }
        } catch (\Exception $e) {
            // if (defined('WP_DEBUG') && WP_DEBUG) {
            //     trigger_error($e->getMessage(), E_USER_ERROR);
            // }
        }
    }

    /**
     * Get method to return the data
     *
     * @author Adam Alexandersson
     * @since 1.0.0
     */

    public function get()
    {
        return $this->data;
    }

    /**
     * Get method to return the data
     *
     * @author Adam Alexandersson
     * @since 1.0.0
     */

    public function set($key, $value)
    {
        return $this->data[$key] = $value;
    }

    /**
     * Build attributes for default block
     *
     * @author Adam Alexandersson
     * @since 1.0.0
     */

    public function buildAttributes()
    {
        $classes = new ClassFactory();
        $classes->add($this->default_class);

        if ($this->class) {
            foreach (explode(' ', $this->class) as $c) {
                $classes->add($c);
            }
        }

        $attr = new AttributeFactory();
        $attr->add('class', $classes->get());

        if ($this->role) {
            $attr->add('role', $this->role);
        }

        if ($this->id) {
            $attr->add('id', $this->id);
        }

        if ($this->aria_label) {
            $attr->add('aria-label', $this->aria_label);
        }

        if ($this->attributes) {
            foreach ($this->attributes as $key => $attribute) {
                $attr->add($key, $attribute);
            }
        }

        $this->data['attr'] = $attr->get();
    }
}
