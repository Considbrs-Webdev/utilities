<?php

namespace Consid\Utilities;

class Color
{
    /**
     * Convert RGB color to HSL color.
     * @param array $rgb
     * @return array
     */
    public static function rgbToHsl($rgb)
    {
        // Fill variables $r, $g, $b by array given.
        list( $r, $g, $b ) = $rgb;

        // Determine lowest & highest value and chroma
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $chroma = $max - $min;

        // Calculate Luminosity
        $l = ( $max + $min ) / 2;

        // If chroma is 0, the given color is grey
        // therefore hue and saturation are set to 0
        if ($chroma == 0) {
            $h = 0;
            $s = 0;
        } else {
            // Else calculate hue and saturation.
            // Check http://en.wikipedia.org/wiki/HSL_and_HSV for details
            switch ($max) {
                case $r:
                    $h_ = fmod(( ( $g - $b ) / $chroma ), 6);
                    if ($h_ < 0) {
                        $h_ = ( 6 - fmod(abs($h_), 6) ); // Bugfix: fmod() returns wrong values for negative numbers
                    }
                    break;

                case $g:
                    $h_ = ( $b - $r ) / $chroma + 2;
                    break;

                case $b:
                    $h_ = ( $r - $g ) / $chroma + 4;
                    break;
                default:
                    break;
            }

            $h = $h_ / 6;
            $s = 1 - abs(2 * $l - 1);
        }

        // Return HSL Color as array
        return [$h, $s, $l];
    }


    /**
     * Convert 6-character hex to RGB color.
     *
     * @param string $hex
     * @param string $return
     * @return string|array
     */
    public static function hexToRgb($hex, $return = 'array')
    {
        if ($return == 'array') {
            return sscanf($hex, "#%02x%02x%02x");
        }

        list( $r, $g, $b ) = sscanf($hex, "#%02x%02x%02x");
        return "$r, $g, $b";
    }


    /**
     * Convert 6-character hex to HSL.
     *
     * @param string $hex
     * @return array
     */
    public static function hexToHsl($hex)
    {
        $rgb = self::hexToRgb($hex, 'array');
        list( $r, $g, $b ) = $rgb;

        $oldR = $r;
        $oldG = $g;
        $oldB = $b;

        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $h;
        $s;
        $l = ( $max + $min ) / 2;
        $d = $max - $min;
        if ($d == 0) {
            $h = $s = 0; // achromatic
        } else {
            $s = $d / ( 1 - abs(2 * $l - 1) );
            switch ($max) {
                case $r:
                    $h = 60 * fmod(( ( $g - $b ) / $d ), 6);
                    if ($b > $g) {
                        $h += 360;
                    }
                    break;
                case $g:
                    $h = 60 * ( ( $b - $r ) / $d + 2 );
                    break;
                case $b:
                    $h = 60 * ( ( $r - $g ) / $d + 4 );
                    break;
            }
        }

        return [ round($h, 0), ( round($s, 3) * 100 ), ( round($l, 3) * 100 ) ];
    }


    /**
     * Darken HSL color
     *
     * @param array $hsl
     * @param int|string $percent
     * @return array
     */
    public static function darkenHsl($hsl, $value = 8, $exact = false)
    {
        if ($exact) {
            $hsl[2] = $value;
        } else {
            $hsl[2] = ( $hsl[2] - $value );
        }

        // $hsl[1] = $hsl[1] < 0 ? 0 : $hsl[1];
        $hsl[2] = $hsl[2] < 0 ? 0 : $hsl[2];

        return $hsl;
    }


    /**
     * Darken HSL color
     *
     * @param array $hsl
     * @param int|string $percent
     * @return array
     */
    public static function lightenHsl($hsl, $value = 8, $exact = false)
    {
        if ($exact) {
            $hsl[2] = $value;
        } else {
            $hsl[2] = ( $hsl[2] + $value );
        }

        // $hsl[1] = $hsl[1] < 0 ? 0 : $hsl[1];
        $hsl[2] = $hsl[2] > 100 ? 100 : $hsl[2];

        return $hsl;
    }


    /**
     * Convert HSL array to CSS compatible string
     *
     * @param array $hsl
     * @param bool $wrap wether to wrap with hsla() for CSS.
     * @return string
     */
    public static function hslToCss($hsl, $wrap = false)
    {
        $hsl[1] = $hsl[1] . '%';
        $hsl[2] = $hsl[2] . '%';

        if ($wrap) {
            return 'hsla(' . join(', ', $hsl) . ', 1 )';
        }

        return join(', ', $hsl);
    }
}
