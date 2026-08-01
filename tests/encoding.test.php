<?php

use Leaf\Fetch;

test('arrays post as JSON by default', function () {
    $res = Fetch::post(serverUrl('/echo'), ['sku' => 'leaf-tee', 'qty' => 2]);

    expect($res->data->body)->toBe('{"sku":"leaf-tee","qty":2}');
    expect($res->data->json->sku)->toBe('leaf-tee');
});

test('a form-urlencoded content type switches to classic form encoding', function () {
    $res = fetch([
        'method' => 'POST',
        'url' => serverUrl('/echo'),
        'data' => ['sku' => 'leaf-tee'],
        'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
    ]);

    expect($res->data->body)->toBe('sku=leaf-tee');
});

test('the content type check is case-insensitive', function () {
    $res = fetch([
        'method' => 'POST',
        'url' => serverUrl('/echo'),
        'data' => ['sku' => 'leaf-tee'],
        'headers' => ['content-type' => 'application/x-www-form-urlencoded; charset=utf-8'],
    ]);

    expect($res->data->body)->toBe('sku=leaf-tee');
});

test('on GET, data becomes query parameters', function () {
    $res = fetch(['url' => serverUrl('/echo'), 'data' => ['page' => 2, 'tags' => ['php']]]);

    expect($res->data->query->page)->toBe('2');
    expect($res->data->query->tags)->toEqual(['php']);
    expect($res->data->uri)->toContain('tags[0]=php');
});

test('query values with special characters survive encoding', function () {
    $res = fetch(['url' => serverUrl('/echo'), 'data' => ['q' => 'a&b=c d']]);

    expect($res->data->query->q)->toBe('a&b=c d');
});

test('GET data merges into an existing query string', function () {
    $res = fetch(['url' => serverUrl('/echo?keep=1'), 'data' => ['page' => 2]]);

    expect($res->data->query->keep)->toBe('1');
    expect($res->data->query->page)->toBe('2');
});

test('params are appended to the url for any method', function () {
    $get = fetch(['url' => serverUrl('/echo'), 'params' => ['page' => 3]]);
    $post = fetch([
        'method' => 'POST',
        'url' => serverUrl('/echo'),
        'params' => ['notify' => 'yes'],
        'data' => ['sku' => 'leaf-tee'],
    ]);

    expect($get->data->query->page)->toBe('3');
    expect($post->data->query->notify)->toBe('yes');
    expect($post->data->json->sku)->toBe('leaf-tee');
});
