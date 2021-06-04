<!-- markdownlint-disable no-inline-html -->
<p align="center">
  <br><br>
  <img src="https://leaf-docs.netlify.app/images/logo.png" height="100"/>
  <h1 align="center">Fetch</h1>
  <br><br>
</p>

# Leaf Fetch

<!-- [![Latest Stable Version](https://poser.pugx.org/leafs/leaf/v/stable)](https://packagist.org/packages/leafs/leaf)
[![Total Downloads](https://poser.pugx.org/leafs/leaf/downloads)](https://packagist.org/packages/leafs/leaf)
[![License](https://poser.pugx.org/leafs/leaf/license)](https://packagist.org/packages/leafs/leaf) -->

Clean, simple, developer friendly interface for making network requests with PHP. Fetch is based on curl and uses elements from Unirest PHP and an API that closely resembles Axios. All of these combined makes Fetch the best and simplest way to make PHP network requests.

## fetch example

```php
use function Leaf\fetch;

$res = fetch("https://jsonplaceholder.typicode.com/todos/");

echo json_encode($res->data);
```

You can also use the fetch class

```php
use Leaf\Fetch;

$res = Fetch::request([
  "url" => 'https://jsonplaceholder.typicode.com/todos/1',
]);

echo json_encode($res->data);
```

## Installation

You can quickly install leaf fetch with composer.

```sh
composer require leafs/fetch
```

If you want to keep up to date with all the changes with leaf fetch you can follow the main branch

```sh
composer require leafs/fetch dev-main
```

## The `fetch` method

Leaf fetch provides the fetch method as an easy way to make HTTP requests. This allows you to quickly make requests without bringing up the whole fetch class and without even having to build up your own request array.

```php
// make a get request
$res = fetch("https://jsonplaceholder.typicode.com/todos/");

// make a post request
$res = fetch("https://jsonplaceholder.typicode.com/posts", [
  "title" => "foo",
  "body" => "bar",
  "userId" => 1,
]);

// build a custom request array
$res = fetch([
  "method" => "GET",
  "url" => 'https://jsonplaceholder.typicode.com/todos/1',
  "data" => [
    "firstName" => 'Fred',
    "lastName" => 'Flintstone'
  ]
]);

// get response body
echo json_encode($res->data);
```

## The `Fetch` class

The fetch class contains all the options and methods needed to make a network request.

### baseUrl

You might have noticed that all the requests above needed us to type a long URL to make the requests, however, we can add a base url so we don't have to type it over and over again.

```php
Fetch::baseUrl("https://jsonplaceholder.typicode.com");
```

And from there you can make requests like this:

```php
// make a get request
$res = fetch("/todos");

// make a post request
$res = fetch("/posts", [
  "title" => "foo",
  "body" => "bar",
  "userId" => 1,
]);

// use the get shortcut method
$res = Fetch::get("/todos/10");

// echo response
echo json_encode($res);
```

### shortcut methods

The fetch class comes with shortcut methods named after http methods `get`, `post`, `put`, `patch`, ...

```php
$res = Fetch::post("/posts", [
  "title" => "foo",
  "body" => "bar",
  "userId" => 2,
]);

$res = Fetch::get("/todos/10");

Fetch::delete("/todos/10");

// ...
```

### request

As you've seen earlier, the fetch class also provides a `request` method which is also used under the hood by the `fetch` function. `request` allows you to manually build up your request object with whatever data you need.

```php
use Leaf\Fetch;

$res = Fetch::request([
  "method" => "GET",
  "url" => "https://jsonplaceholder.typicode.com/todos",
]);

echo json_encode($res->data);
```

### Request object

This is the array which is used to construct the request to be sent. The available fields are:

```php
[
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
```

## View Leaf's docs [here](https://leafphp.netlify.app/#/)

Built with ❤ by [**Mychi Darko**](https://mychi.netlify.app)
