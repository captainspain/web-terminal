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
        return dirname(__DIR__) . '/resources/templates' . $path;
    }
}

if (!function_exists('asset')) {
    /**
     * @param string $path
     * @return string
     */
    function asset(string $path = '') : string
    {
        return '../../resources/assets' . $path;
    }
}

/**
 * @param $data
 * @param bool $fromData
 * @return string
 */
function makeInputLine($data, $fromData = true) : string
{
    $command = '';
    $response = '';
    $operator = $data['isSudo'] ? '#' : '$';
    if ($fromData) {
        $command = $data['command'];
        if (!contains('cd ', $command)) {
            $command .= '<br />';
        }
        $command = '<span class="command-executed">' . $command . '</span>';
        $responseData = $data['response'] ?? [];
        $response = '';
        foreach ($responseData as $line) {
            $line = $line === '' ? '&nbsp;' : htmlentities($line);
            $response .= '<span class="new-line" style="display:block">' . $line . '</span>';
        }
        $response = '<span class="response">' . $response . '</span>';
    }

    return <<<HTML
<span class="inputLine"><span class="user">{$data['user']}</span><span class="atHost">@</span><span class="host">{$data['host']}</span>:<span class="root">{$data['root']}</span><span class="operator">{$operator}</span>&nbsp;</span>{$command}{$response}
HTML;
}

/**
 * @param $needle
 * @param $haystack
 * @return bool
 */
function contains($needle, $haystack)
{
    return strpos($haystack, $needle) !== false;
}
