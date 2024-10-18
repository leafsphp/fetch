<?php

use Leaf\Fetch;

if (!function_exists('fetch')) {
    /**
     * Shortcut method for making network requests.
     *
     * @param array|string $data The url or request to hit.
     * @throws \Exception
     */
    function fetch($data = null)
    {
        if (is_string($data)) {
            $data = ['url' => $data];
        }

        if (!$data) {
            return new Fetch;
        }

        return Fetch::request($data);
    }
}
