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

*Fetch is still in its early stages and is being actively developed. If you have any issues or suggestions, please feel free to open an issue or a pull request.*

## Installation

You can quickly install leaf fetch with the Leaf CLI

```sh
leaf install fetch
```

Or with composer:

```sh
composer require leafs/fetch
```

## Stay In Touch

- [Twitter](https://twitter.com/leafphp)
- [Join the forum](https://github.com/leafsphp/leaf/discussions/37)
- [Chat on discord](https://discord.com/invite/Pkrm9NJPE3)

## Learning Leaf PHP

- Leaf has a very easy to understand [documentation](https://leafphp.dev) which contains information on all operations in Leaf.
- You can also check out our [youtube channel](https://www.youtube.com/channel/UCllE-GsYy10RkxBUK0HIffw) which has video tutorials on different topics
- You can also learn from [codelabs](https://leafphp.dev/codelabs/) and contribute as well.

## Contributing

We are glad to have you. All contributions are welcome! To get started, familiarize yourself with our [contribution guide](https://leafphp.dev/community/contributing.html) and you'll be ready to make your first pull request 🚀.

To report a security vulnerability, you can reach out to [@mychidarko](https://twitter.com/mychidarko) or [@leafphp](https://twitter.com/leafphp) on twitter. We will coordinate the fix and eventually commit the solution in this project.

## Sponsoring Leaf

We are committed to keeping Leaf open-source and free, but maintaining and developing new features now requires significant time and resources. As the project has grown, so have the costs, which have been mostly covered by the team. To sustain and grow Leaf, we need your help to support full-time maintainers.

You can sponsor Leaf and any of our packages on [open collective](https://opencollective.com/leaf) or check the [contribution page](https://leafphp.dev/support/) for a list of ways to contribute.

And to all our [existing cash/code contributors](https://leafphp.dev#sponsors), we love you all ❤️
