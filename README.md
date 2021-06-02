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

Clean, simple, developer friendly interface for making network requests with PHP. Fetch is based on curl and uses elements from Unirest PHP and an API that closely resembles Axios. ALl of these combined makes Fetch the best and simplest way to make PHP network requests.

## fetch example

```php
use function Leaf\fetch;

$res = fetch([
  "method" => "GET",
  "url" => 'https://jsonplaceholder.typicode.com/todos/1',
]);

echo json_encode($res->data);
```

You can also use the fetch class

```php
use Leaf\Fetch;

$res = Fetch::request([
  "method" => "GET",
  "url" => 'https://jsonplaceholder.typicode.com/todos/1',
]);

echo json_encode($res->data);
```

## View Leaf's docs [here](https://leafphp.netlify.app/#/)

Built with ❤ by [**Mychi Darko**](https://mychi.netlify.app)
