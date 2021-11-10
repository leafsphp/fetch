<?php

use function Leaf\fetch as LeafFetch;

if (!function_exists('fetch')) {
    /**
     * Shortcut method for making network requests.
     * 
     * @param array|string $options The url or request to hit.
     */
    function fetch($options, $params = [])
    {
        return LeafFetch($options, $params);
    }
}
