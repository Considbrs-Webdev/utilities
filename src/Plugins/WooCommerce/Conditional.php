<?php

namespace Consid\Plugins\WooCommerce;

class Conditional
{
    public static function hasWoocommerce()
    {
        return defined('WC_ABSPATH');
    }

    /**
     * Conditional function to determine if the current page is any sort of archive page or has product loops.
     *
     * @return boolean
     */
    public static function isArchive()
    {
        return ( self::hasWoocommerce() && ( is_shop() || is_product_category() || is_product_tag() ) );
    }

    /**
     * Helper function to determine if it's any WooCommerce page.
     * @return type
     */
    public static function isWoocommercePage()
    {
        return ( self::hasWoocommerce() && ( is_woocommerce() || is_checkout() || is_cart() || is_account_page() ) );
    }
}
