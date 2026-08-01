<?php

namespace Leaf;

/**
 * Leaf Fetch
 * ---------
 * Plain simple PHP http client.
 */
class Fetch
{
    public const HEAD = 'HEAD';
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const PATCH = 'PATCH';
    public const DELETE = 'DELETE';
    public const OPTIONS = 'OPTIONS';
    public const OVERRIDE = '_METHOD';

    private static $handler = null;

    /**
     * Default fetch options. Every option listed here is honoured —
     * pass any of them per-request or set them app-wide with Fetch::config().
     */
    protected static $options = [
        // `url` is the server URL that will be used for the request
        'url' => null,

        // `method` is the request method to be used when making the request
        'method' => 'GET',

        // `baseUrl` is prepended to `url` unless `url` is absolute (starts with http:// or https://)
        'baseUrl' => '',

        // `headers` are custom headers to be sent with the request
        'headers' => [],

        // `params` are URL query parameters appended to the URL for ANY method.
        // On GET requests, `data` behaves the same way.
        'params' => [],

        // `data` is the data sent as the request body. Arrays are JSON encoded
        // by default; set a Content-Type of application/x-www-form-urlencoded
        // to send classic form encoding instead. On GET requests, `data` is
        // appended to the URL as query parameters.
        'data' => [],

        // `timeout` specifies the number of SECONDS before the request times out.
        // Default is 0 (no timeout).
        'timeout' => 0,

        // `auth` enables HTTP Basic auth: ['username' => ..., 'password' => ...]
        // For Bearer tokens, use an Authorization header instead.
        'auth' => [],

        // `maxRedirects` defines the maximum number of redirects to follow.
        // If set to 0, no redirects will be followed.
        'maxRedirects' => 5,

        // If true, fetch will NOT try to parse the response body as JSON
        'rawResponse' => false,

        // CURLOPT_SSL_VERIFYHOST accepts only 0 (false) or 2 (true)
        'verifyHost' => true,

        // CURLOPT_SSL_VERIFYPEER
        'verifyPeer' => true,

        // Additional raw curl options. These are applied last, so they can
        // override anything fetch sets up.
        'curl' => [],
    ];

    /**
     * Set a base url which is prepended to relative request urls
     * @param string $url
     */
    public static function baseUrl($url)
    {
        static::$options['baseUrl'] = $url;
    }

    /**
     * Update default options for all subsequent requests
     * @param array $options
     */
    public static function config(array $options)
    {
        static::$options = array_merge(static::$options, $options);
    }

    /**
     * Base method for network requests
     * @throws \Exception
     */
    public static function request($options)
    {
        $options = array_merge(static::$options, $options);

        return static::call($options);
    }

    /**
     * Make a get request
     * @throws \Exception
     */
    public static function get($url, $config = [])
    {
        return static::request(array_merge($config, ['url' => $url]));
    }

    /**
     * Make a post request
     * @throws \Exception
     */
    public static function post($url, $data = [], $config = [])
    {
        return static::request(array_merge($config, ['url' => $url, 'data' => $data, 'method' => static::POST]));
    }

    /**
     * Make a put request
     * @throws \Exception
     */
    public static function put($url, $data = [], $config = [])
    {
        return static::request(array_merge($config, ['url' => $url, 'data' => $data, 'method' => static::PUT]));
    }

    /**
     * Make a patch request
     * @throws \Exception
     */
    public static function patch($url, $data = [], $config = [])
    {
        return static::request(array_merge($config, ['url' => $url, 'data' => $data, 'method' => static::PATCH]));
    }

    /**
     * Make a delete request
     * @throws \Exception
     */
    public static function delete($url, $config = [])
    {
        return static::request(array_merge($config, ['url' => $url, 'method' => static::DELETE]));
    }

    /**
     * Make a head request
     * @throws \Exception
     */
    public static function head($url, $config = [])
    {
        return static::request(array_merge($config, ['url' => $url, 'method' => static::HEAD]));
    }

    /**
     * Make an options request
     * @throws \Exception
     */
    public static function options($url, $config = [])
    {
        return static::request(array_merge($config, ['url' => $url, 'method' => static::OPTIONS]));
    }

    /**
     * @throws \Exception
     */
    private static function call($request)
    {
        static::$handler = curl_init();

        $curlOptions = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => $request['maxRedirects'],
            CURLOPT_HEADER => true,
            CURLOPT_SSL_VERIFYPEER => $request['verifyPeer'],
            CURLOPT_SSL_VERIFYHOST => $request['verifyHost'] === false ? 0 : 2,
            // If an empty string, '', is set, a header containing all supported encoding types is sent
            CURLOPT_ENCODING => '',
        ];

        if (!empty($request['headers'])) {
            $formattedHeaders = [];

            foreach ($request['headers'] as $key => $value) {
                $formattedHeaders[] = $key . ': ' . $value;
            }

            $curlOptions[CURLOPT_HTTPHEADER] = $formattedHeaders;
        }

        $queryParams = $request['params'] ?? [];

        if ($request['method'] !== static::GET) {
            if ($request['method'] === static::POST) {
                $curlOptions[CURLOPT_POST] = true;
            } else {
                if ($request['method'] === static::HEAD) {
                    $curlOptions[CURLOPT_NOBODY] = true;
                }

                $curlOptions[CURLOPT_CUSTOMREQUEST] = $request['method'];
            }

            if (static::hasHeader($request['headers'], 'Content-Type', 'application/x-www-form-urlencoded')) {
                $curlOptions[CURLOPT_POSTFIELDS] = http_build_query($request['data']);
            } else {
                $curlOptions[CURLOPT_POSTFIELDS] = json_encode($request['data']);
            }
        } elseif (is_array($request['data']) && !empty($request['data'])) {
            $queryParams = array_merge($request['data'], $queryParams);
        }

        $url = $request['url'] ?? '';

        if (!empty($queryParams)) {
            $url .= (strpos($url, '?') !== false) ? '&' : '?';
            $url .= static::buildQueryString($queryParams);
        }

        // baseUrl only applies to relative urls
        if (!preg_match('/^https?:\/\//i', $url)) {
            $url = $request['baseUrl'] . $url;
        }

        $curlOptions[CURLOPT_URL] = $url;

        if (!empty($request['auth'])) {
            $curlOptions[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
            $curlOptions[CURLOPT_USERPWD] = ($request['auth']['username'] ?? '') . ':' . ($request['auth']['password'] ?? '');
        }

        if (($request['timeout'] ?? 0) > 0) {
            $curlOptions[CURLOPT_TIMEOUT] = $request['timeout'];
        }

        // user-supplied curl options are applied last so they win
        foreach ($request['curl'] as $key => $value) {
            $curlOptions[$key] = $value;
        }

        curl_setopt_array(static::$handler, $curlOptions);

        $requestStartedAt = microtime(true);
        $response = curl_exec(static::$handler);
        $error = curl_error(static::$handler);
        $info = self::getInfo();

        if (function_exists('crash')) {
            // outbound calls are prime crash-journey material: url + status
            // + duration only, never request or response bodies
            crash()->leaveCrumb("{$request['method']} $url", 'http', [
                'status' => $info['http_code'] ?? 0,
                'ms' => round((microtime(true) - $requestStartedAt) * 1000),
            ], false);
        }

        if ($error) {
            throw new \Exception("Fetch: $error [$url]");
        }

        // Split the full response in its headers and body
        $headerSize = $info['header_size'];
        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        $httpCode = $info['http_code'];

        if (!$request['rawResponse']) {
            if (trim($body) === '') {
                $body = null;
            } else {
                $decoded = json_decode($body);

                // keep the raw body if it isn't valid JSON
                if ($decoded !== null || strtolower(trim($body)) === 'null') {
                    $body = $decoded;
                }
            }
        }

        return (object) [
            // `data` is the response that was provided by the server
            'data' => $body,

            // `status` is the HTTP status code from the server response
            'status' => $httpCode,

            // `headers` the HTTP headers that the server responded with.
            // All header names are lower cased and can be accessed using the bracket notation.
            // Example: `response.headers['content-type']`
            'headers' => static::parseHeaders($header),

            // `request` is the request that generated this response
            'request' => $request,
        ];
    }

    /**
     * Case-insensitively check whether a header is set to a given value
     */
    private static function hasHeader(array $headers, string $name, string $value): bool
    {
        foreach ($headers as $key => $headerValue) {
            if (strcasecmp($key, $name) === 0) {
                return stripos($headerValue, $value) !== false;
            }
        }

        return false;
    }

    /**
     * Build a query string, keeping array brackets readable: ?page=2&tags[0]=php
     */
    private static function buildQueryString(array $params): string
    {
        return str_ireplace(['%5B', '%5D'], ['[', ']'], http_build_query(self::buildHTTPCurlQuery($params)));
    }

    /**
     * Parse raw response headers into an array with lower cased header names
     * @param string $raw_headers raw headers
     * @return array
     */
    private static function parseHeaders(string $raw_headers): array
    {
        $key = '';
        $headers = [];

        foreach (explode("\n", $raw_headers) as $i => $h) {
            $h = explode(':', $h, 2);

            if (isset($h[1])) {
                $headerName = strtolower($h[0]);

                if (!isset($headers[$headerName])) {
                    $headers[$headerName] = trim($h[1]);
                } elseif (is_array($headers[$headerName])) {
                    $headers[$headerName] = array_merge($headers[$headerName], [trim($h[1])]);
                } else {
                    $headers[$headerName] = array_merge([$headers[$headerName]], [trim($h[1])]);
                }

                $key = $headerName;
            } else {
                if (substr($h[0], 0, 1) == "\t") {
                    $headers[$key] .= "\r\n\t" . trim($h[0]);
                } elseif (!$key) {
                    $headers[0] = trim($h[0]);
                }
            }
        }

        return $headers;
    }

    public static function getInfo($opt = false)
    {
        if (!$opt) {
            return curl_getinfo(static::$handler);
        }

        return curl_getinfo(static::$handler, $opt);
    }

    /**
     * This function is useful for serializing multidimensional arrays, and avoid getting
     * the 'Array to string conversion' notice
     * @param array $data array to flatten.
     * @param bool|string $parent parent key or false if no parent
     * @return array
     *
     * @author Mashape (https://www.mashape.com)
     */
    public static function buildHTTPCurlQuery(array $data, $parent = false): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if ($parent) {
                $new_key = sprintf('%s[%s]', $parent, $key);
            } else {
                $new_key = $key;
            }

            if (!$value instanceof \CURLFile and (is_array($value) or is_object($value))) {
                $result = array_merge($result, self::buildHTTPCurlQuery($value, $new_key));
            } else {
                $result[$new_key] = $value;
            }
        }

        return $result;
    }
}
