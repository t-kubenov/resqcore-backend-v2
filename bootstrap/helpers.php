<?php

if (!function_exists('get_class_name')) {
    function get_class_name($classname): string
    {
        if ($pos = strrpos($classname, '\\')) return substr($classname, $pos + 1);
        return $pos;
    }
}
