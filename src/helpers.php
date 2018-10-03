<?php

if (!function_exists('env')) {
    /**
     * @param string $envKey
     * @param mixed|null $default
     * @return mixed
     */
    function env(string $envKey, $default = null)
    {
        return ($value = getenv($envKey)) === false
            ? $default
            : $value;
    }
}

if (!function_exists('templates_dir')) {
    /**
     * @param string $path
     * @return string
     */
    function templates_dir(string $path = ''): string
    {
        return dirname(__DIR__) . '/data/templates' . $path;
    }
}

function contains($needle, $haystack)
{
    return strpos($haystack, $needle) !== false;
}
