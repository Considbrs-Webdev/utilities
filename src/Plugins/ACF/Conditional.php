<?php

namespace Consid\Plugins\ACF;

class Conditional
{
    public static function hasAcf()
    {
        return defined('ACF_BASENAME');
    }
}
