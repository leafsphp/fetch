<?php

namespace Leaf;

/**
 * Leaf Fetch
 * ---------
 * Plain simple PHP http client.
 */
class Fetch
{
    const HEAD = 'HEAD';
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const DELETE = 'DELETE';
    const OPTIONS = 'OPTIONS';
    const OVERRIDE = '_METHOD';

    private static $handler = null;

    /**
     * Default fetch options
     */
    protected static $options = [
        // `url` is the server URL that will be used for the request
        "url" => null,

        // `method` is the request method to be used when making the request
        "method" => "GET", // default

        // `baseURL` will be prepended to `url` unless `url` is absolute.
        // It can be convenient to set `baseURL` for an instance of axios to pass relative URLs
        // to methods of that instance.
        "baseUrl" => "",

        // `transformRequest` allows changes to the request data before it is sent to the server
        // This is only applicable for request methods 'PUT', 'POST', 'PATCH' and 'DELETE'
        // The last function in the array must return a string or an instance of Buffer, ArrayBuffer,
        // FormData or Stream
        // You may modify the headers object.
        // "transformRequest" => function ($data, $headers) {
        //     // Do whatever you want to transform the data

        //     return $data;
        // },

        // `transformResponse` allows changes to the response data to be made before
        // it is passed to then/catch
        // "transformResponse" => function ($data) {
        //     // Do whatever you want to transform the data

        //     return $data;
        // },

        // `headers` are custom headers to be sent
        "headers" => [],

        // `params` are the URL parameters to be sent with the request
        // Must be a plain object or a URLSearchParams object
        "params" => [],

        // `paramsSerializer` is an optional function in charge of serializing `params`
        // (e.g. https://www.npmjs.com/package/qs, http://api.jquery.com/jquery.param/)
        // "paramsSerializer" => function ($params) {
        //     return Qs.stringify($params, ["arrayFormat" => "brackets"]);
        // },

        // `data` is the data to be sent as the request body
        // Only applicable for request methods 'PUT', 'POST', 'DELETE , and 'PATCH'
        // When no `transformRequest` is set, must be of one of the following types:
        // - string, plain object, ArrayBuffer, ArrayBufferView, URLSearchParams
        // - Browser "only" => FormData, File, Blob
        // - Node "only" => Stream, Buffer
        "data" => [],

        // `timeout` specifies the number of milliseconds before the request times out.
        // If the request takes longer than `timeout`, the request will be aborted.
        "timeout" => 0, // default is `0` (no timeout)

        // `withCredentials` indicates whether or not cross-site Access-Control requests
        // should be made using credentials
        "withCredentials" => false, // default

        // `adapter` allows custom handling of requests which makes testing easier.
        // Return a promise and supply a valid response (see lib/adapters/README.md).
        // "adapter" => function ($config) {
        //     /* ... */
        // },

        // `auth` indicates that HTTP Basic auth should be used, and supplies credentials.
        // This will set an `Authorization` header, overwriting any existing
        // `Authorization` custom headers you have set using `headers`.
        // Please note that only HTTP Basic auth is configurable through this parameter.
        // For Bearer tokens and such, use `Authorization` custom headers instead.
        "auth" => [],

        // `responseType` indicates the type of data that the server will respond with
        // options "are" => 'arraybuffer', 'document', 'json', 'text', 'stream'
        //   browser "only" => 'blob'
        "responseType" => "json", // default

        // `responseEncoding` indicates encoding to use for decoding responses (Node.js only)
        // "Note" => Ignored for `responseType` of 'stream' or client-side requests
        "responseEncoding" => "utf8", // default

        // `xsrfCookieName` is the name of the cookie to use as a value for xsrf token
        "xsrfCookieName" => "XSRF-TOKEN", // default

        // `xsrfHeaderName` is the name of the http header that carries the xsrf token value
        "xsrfHeaderName" => "X-XSRF-TOKEN", // default

        // `onUploadProgress` allows handling of progress events for uploads
        // browser only
        // "onUploadProgress" => function ($progressEvent) {
        //     // Do whatever you want with the native progress event
        // },

        // `onDownloadProgress` allows handling of progress events for downloads
        // browser only
        // "onDownloadProgress" => function ($progressEvent) {
        //     // Do whatever you want with the native progress event
        // },

        // `maxContentLength` defines the max size of the http response content in bytes allowed in node.js
        "maxContentLength" => 2000,

        // `maxBodyLength` (Node only option) defines the max size of the http request content in bytes allowed
        "maxBodyLength" => 2000,

        // `validateStatus` defines whether to resolve or reject the promise for a given
        // HTTP response status code. If `validateStatus` returns `true` (or is set to `null`
        // or `undefined`), the promise will be resolved; otherwise, the promise will be
        // rejected.
        // "validateStatus" => function ($status) {
        //     return $status >= 200 && $status < 300; // default
        // },

        // `maxRedirects` defines the maximum number of redirects to follow in node.js.
        // If set to 0, no redirects will be followed.
        "maxRedirects" => 5, // default

        // `socketPath` defines a UNIX Socket to be used in node.js.
        // e.g. '/var/run/docker.sock' to send requests to the docker daemon.
        // Only either `socketPath` or `proxy` can be specified.
        // If both are specified, `socketPath` is used.
        "socketPath" => null, // default

        // `proxy` defines the hostname, port, and protocol of the proxy server.
        // You can also define your proxy using the conventional `http_proxy` and
        // `https_proxy` environment variables. If you are using environment variables
        // for your proxy configuration, you can also define a `no_proxy` environment
        // variable as a comma-separated list of domains that should not be proxied.
        // Use `false` to disable proxies, ignoring environment variables.
        // `auth` indicates that HTTP Basic auth should be used to connect to the proxy, and
        // supplies credentials.
        // This will set an `Proxy-Authorization` header, overwriting any existing
        // `Proxy-Authorization` custom headers you have set using `headers`.
        // If the proxy server uses HTTPS, then you must set the protocol to `https`. 
        "proxy" => [],

        // `decompress` indicates whether or not the response body should be decompressed 
        // automatically. If set to `true` will also remove the 'content-encoding' header 
        // from the responses objects of all decompressed responses
        // - Node only (XHR cannot turn off decompression)
        "decompress" => true, // default

        // If false, fetch will try to parse json responses
        "rawResponse" => false,

        // CURLOPT_SSL_VERIFYHOST accepts only 0 (false) or 2 (true).
        // Future versions of libcurl will treat values 1 and 2 as equals
        "verifyHost" => true, // default

        "verifyPeer" => true, // default

        // Set additional options for curl.
        "curl" => [],
    ];

    public static function baseUrl($url)
    {
        static::$options["baseUrl"] = $url;
    }

    /**
     * Base method for network requests
     */
    public static function request($options)
    {
        $options = array_merge(static::$options, $options);

        return static::call($options);
    }

    /**
     * Make a get request
     */
    public static function get($url, $config = [])
    {
        return static::request(array_merge($config, ["url" => $url]));
    }

    /**
     * Make a post request
     */
    public static function post($url, $data, $config = [])
    {
        return static::request(array_merge($config, ["url" => $url, "data" => $data, "method" => static::POST]));
    }

    /**
     * Make a put request
     */
    public static function put($url, $data, $config = [])
    {
        return static::request(array_merge($config, ["url" => $url, "data" => $data, "method" => static::PUT]));
    }

    /**
     * Make a patch request
     */
    public static function patch($url, $data, $config = [])
    {
        return static::request(array_merge($config, ["url" => $url, "data" => $data, "method" => static::PATCH]));
    }

    /**
     * Make a delete request
     */
    public static function delete($url, $config = [])
    {
        return static::request(array_merge($config, ["url" => $url, "method" => static::DELETE]));
    }

    /**
     * Make an options request
     */
    public static function options($url, $config = [])
    {
        return static::request(array_merge($config, ["url" => $url, "method" => static::OPTIONS]));
    }

    private static function call($request)
    {
        static::$handler = curl_init();

        if ($request["method"] !== static::GET) {
            if ($request["method"] === static::POST) {
                curl_setopt(static::$handler, CURLOPT_POST, true);
            } else {
                if ($request["method"] === static::HEAD) {
                    curl_setopt(static::$handler, CURLOPT_NOBODY, true);
                }
                curl_setopt(static::$handler, CURLOPT_CUSTOMREQUEST, $request["method"]);
            }

            curl_setopt(static::$handler, CURLOPT_POSTFIELDS, $request["data"]);
        } else if (is_array($request["data"])) {
            if (strpos($request["url"], '?') !== false) {
                $request["url"] .= '&';
            } else {
                $request["url"] .= '?';
            }

            $request["url"] .= urldecode(http_build_query(self::buildHTTPCurlQuery($request["data"])));
        }

        $curl_base_options = [
            CURLOPT_URL => $request["baseUrl"] . $request["url"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => $request["maxRedirects"],
            CURLOPT_HTTPHEADER => $request["headers"],
            CURLOPT_HEADER => true,
            CURLOPT_SSL_VERIFYPEER => $request["verifyPeer"],
            CURLOPT_SSL_VERIFYHOST => $request["verifyHost"] === false ? 0 : 2,
            // If an empty string, '', is set, a header containing all supported encoding types is sent
            CURLOPT_ENCODING => ""
        ];

        foreach ($request["curl"] as $key => $value) {
            $curl_base_options[$key] = $value;
        }

        curl_setopt_array(static::$handler, $curl_base_options);

        if ($request["timeout"] !== null) {
            curl_setopt(static::$handler, CURLOPT_TIMEOUT, $request["timeout"]);
        }

        // if (self::$cookie) {
        //     curl_setopt(static::$handler, CURLOPT_COOKIE, self::$cookie);
        // }

        // if (self::$cookieFile) {
        //     curl_setopt(static::$handler, CURLOPT_COOKIEFILE, self::$cookieFile);
        //     curl_setopt(static::$handler, CURLOPT_COOKIEJAR, self::$cookieFile);
        // }

        // supporting deprecated http auth method
        // if (!empty($username)) {
        //     curl_setopt_array(static::$handler, array(
        //         CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        //         CURLOPT_USERPWD => $username . ':' . $password
        //     ));
        // }

        // if (!empty($request["auth"]["user"])) {
        //     curl_setopt_array(static::$handler, [
        //         CURLOPT_HTTPAUTH    => $request["auth"]["method"],
        //         CURLOPT_USERPWD     => $request["auth"]["user"] . ":" . $request["auth"]["pass"]
        //     ]);
        // }

        // if ($request["proxy"]["address"] !== false) {
        //     curl_setopt_array(static::$handler, [
        //         CURLOPT_PROXYTYPE       => $request["proxy"]["type"],
        //         CURLOPT_PROXY           => $request["proxy"]["address"],
        //         CURLOPT_PROXYPORT       => $request["proxy"]["port"],
        //         CURLOPT_HTTPPROXYTUNNEL => $request["proxy"]["tunnel"],
        //         CURLOPT_PROXYAUTH       => $request["proxy"]["auth"]["method"],
        //         CURLOPT_PROXYUSERPWD    => $request["proxy"]["auth"]["user"] . ":" . $request["proxy"]["auth"]["pass"]
        //     ]);
        // }

        $response   = curl_exec(static::$handler);
        $error      = curl_error(static::$handler);
        $info       = self::getInfo();

        if ($error) {
            throw new \Exception($error);
        }

        // Split the full response in its headers and body
        $header_size = $info['header_size'];
        $header      = substr($response, 0, $header_size);
        $body        = substr($response, $header_size);
        $httpCode    = $info['http_code'];

        if (!$request["rawResponse"]) {
            $body = json_decode($body);
        }

        return (object) [
            // `data` is the response that was provided by the server
            "data" => $body,

            // `status` is the HTTP status code from the server response
            "status" => $httpCode,

            // `headers` the HTTP headers that the server responded with
            // All header names are lower cased and can be accessed using the bracket notation.
            // Example: `response.headers['content-type']`
            "headers" => static::parseHeaders($header),

            // `request` is the request that generated this response
            "request" => $request,
        ];

        // return new Response($httpCode, $body, $header, self::$jsonOpts);
    }

    /**
     * if PECL_HTTP is not available use a fall back function
     *
     * thanks to ricardovermeltfoort@gmail.com
     * http://php.net/manual/en/function.http-parse-headers.php#112986
     * @param string $raw_headers raw headers
     * @return array
     * 
     * @author Mashape (https://www.mashape.com)
     */
    private static function parseHeaders($raw_headers)
    {
        if (function_exists('http_parse_headers')) {
            return \http_parse_headers($raw_headers);
        } else {
            $key = '';
            $headers = array();

            foreach (explode("\n", $raw_headers) as $i => $h) {
                $h = explode(':', $h, 2);

                if (isset($h[1])) {
                    if (!isset($headers[$h[0]])) {
                        $headers[$h[0]] = trim($h[1]);
                    } elseif (is_array($headers[$h[0]])) {
                        $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
                    } else {
                        $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
                    }

                    $key = $h[0];
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
     * @param array|object $data array to flatten.
     * @param bool|string $parent parent key or false if no parent
     * @return array
     * 
     * @author Mashape (https://www.mashape.com)
     */
    public static function buildHTTPCurlQuery($data, $parent = false)
    {
        $result = array();

        if (is_object($data)) {
            $data = get_object_vars($data);
        }

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

/**
 * Shortcut method for making network requests.
 * 
 * @param array|string $options The url or request to hit.
 */
function fetch($options, $params = [])
{
    if (is_string($options)) {
        $options = ["url" => $options];

        if (count($params) > 0) {
            $options["method"] = Fetch::POST;
            $options["data"] = $params;
        }
    }

    return Fetch::request($options);
}
