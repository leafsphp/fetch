<!-- markdownlint-disable no-inline-html -->
<p align="center">
  <br><br>
  <img src="https://leaf-docs.netlify.app/images/logo.png" height="100"/>
  <h1 align="center">Leaf HTTP Fetch</h1>
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

## View Leaf's docs [here](https://leafphp.netlify.app/#/)

Built with ❤ by [**Mychi Darko**](https://mychi.netlify.app)
