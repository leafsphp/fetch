<!-- markdownlint-disable no-inline-html -->
<p align="center">
  <br><br>
  <img src="https://leafphp.dev/logo-circle.png" height="100"/>
  <h1 align="center">Fetch</h1>
  <br><br>
</p>

[![Latest Stable Version](https://poser.pugx.org/leafs/fetch/v/stable)](https://packagist.org/packages/leafs/leaf)
[![Total Downloads](https://poser.pugx.org/leafs/fetch/downloads)](https://packagist.org/packages/leafs/leaf)
[![License](https://poser.pugx.org/leafs/fetch/license)](https://packagist.org/packages/leafs/leaf)

When building your applications, you will probably end up needing to call APIs or fetch data from external sources. Leaf provides a simple and easy way to do this using Fetch. Fetch provides a clean and modern interface for making network requests in PHP. It is inspired by JavaScript's Fetch API, Axios and uses elements from Unirest PHP.

## fetch example

```php
$res = fetch('https://jsonplaceholder.typicode.com/todos/');

// data returned is saved in the $data property just like axios
response()->json($res->data);
```

You can also use the entire fetch object to make requests:

```php
$res = fetch()->post('https://jsonplaceholder.typicode.com/posts', [
  'title' => 'foo',
  'body' => 'bar',
  'userId' => 1
]);

fetch()->put(...);
fetch()->patch(...);
fetch()->delete(...);
fetch()->options(...);

response()->json($res->data);
```

## Installation

You can quickly install leaf fetch with the Leaf CLI

```sh
leaf install fetch
```

Or with composer:

```sh
composer require leafs/fetch
```

## Options

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

  // `timeout` specifies the number of seconds before the request times out.
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

## 💬 Stay In Touch

- [Twitter](https://twitter.com/leafphp)
- [Join the forum](https://github.com/leafsphp/leaf/discussions/37)
- [Chat on discord](https://discord.com/invite/Pkrm9NJPE3)

## 📓 Learning Leaf 3

- Leaf has a very easy to understand [documentation](https://leafphp.dev) which contains information on all operations in Leaf.
- You can also check out our [youtube channel](https://www.youtube.com/channel/UCllE-GsYy10RkxBUK0HIffw) which has video tutorials on different topics
- We are also working on codelabs which will bring hands-on tutorials you can follow and contribute to.

## 😇 Contributing

We are glad to have you. All contributions are welcome! To get started, familiarize yourself with our [contribution guide](https://leafphp.dev/community/contributing.html) and you'll be ready to make your first pull request 🚀.

To report a security vulnerability, you can reach out to [@mychidarko](https://twitter.com/mychidarko) or [@leafphp](https://twitter.com/leafphp) on twitter. We will coordinate the fix and eventually commit the solution in this project.

### Code contributors

<table>
	<tr>
		<td align="center">
			<a href="https://github.com/mychidarko">
				<img src="https://avatars.githubusercontent.com/u/26604242?v=4" width="120px" alt=""/>
				<br />
				<sub>
					<b>Michael Darko</b>
				</sub>
			</a>
		</td>
	</tr>
</table>

## 🤩 Sponsoring Leaf

Your cash contributions go a long way to help us make Leaf even better for you. You can sponsor Leaf and any of our packages on [open collective](https://opencollective.com/leaf) or check the [contribution page](https://leafphp.dev/support/) for a list of ways to contribute.

And to all our existing cash/code contributors, we love you all ❤️

### Cash contributors

<table>
	<tr>
		<td align="center">
			<a href="https://opencollective.com/aaron-smith3">
				<img src="https://images.opencollective.com/aaron-smith3/08ee620/avatar/256.png" width="120px" alt=""/>
				<br />
				<sub><b>Aaron Smith</b></sub>
			</a>
		</td>
		<td align="center">
			<a href="https://opencollective.com/peter-bogner">
				<img src="https://images.opencollective.com/peter-bogner/avatar/256.png" width="120px" alt=""/>
				<br />
				<sub><b>Peter Bogner</b></sub>
			</a>
		</td>
		<td align="center">
			<a href="#">
				<img src="https://images.opencollective.com/guest-32634fda/avatar.png" width="120px" alt=""/>
				<br />
				<sub><b>Vano</b></sub>
			</a>
		</td>
	</tr>
</table>

## 🤯 Links/Projects

- [Aloe CLI](https://leafphp.dev/aloe-cli/)
- [Leaf Docs](https://leafphp.dev)
- [Leaf MVC](https://mvc.leafphp.dev)
- [Leaf API](https://api.leafphp.dev)
- [Leaf CLI](https://cli.leafphp.dev)
