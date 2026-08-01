<?php

use Leaf\Fetch;

test('baseUrl is prepended to relative urls', function () {
    Fetch::baseUrl(serverUrl());

    $res = fetch()->get('/echo');

    expect($res->status)->toBe(200);
    expect($res->data->path)->toBe('/echo');
});

test('baseUrl is ignored for absolute urls', function () {
    Fetch::baseUrl('http://127.0.0.1:59999');

    $res = fetch(serverUrl('/echo'));

    expect($res->status)->toBe(200);
    expect($res->data->path)->toBe('/echo');
});

test('basic auth is config', function () {
    $res = fetch([
        'url' => serverUrl('/basic-auth'),
        'auth' => ['username' => 'mika', 'password' => 'secret'],
    ]);

    expect($res->data->user)->toBe('mika');
    expect($res->data->pass)->toBe('secret');
});

test('bearer is a header', function () {
    $res = fetch([
        'url' => serverUrl('/echo'),
        'headers' => ['Authorization' => 'Bearer token-123'],
    ]);

    expect($res->data->headers->authorization)->toBe('Bearer token-123');
});

test('rawResponse skips json parsing', function () {
    $res = fetch(['url' => serverUrl('/echo'), 'rawResponse' => true]);

    expect($res->data)->toBeString();
    expect(json_decode($res->data)->method)->toBe('GET');
});

test('non-json bodies are returned as-is instead of null', function () {
    $res = fetch(serverUrl('/text'));

    expect($res->data)->toBe('plain text body');
});

test('raw curl options are applied last and win', function () {
    $res = fetch([
        'url' => serverUrl('/echo'),
        'curl' => [CURLOPT_REFERER => 'https://fetch.leafphp.dev'],
    ]);

    expect($res->data->headers->referer)->toBe('https://fetch.leafphp.dev');
});

test('Fetch::config updates defaults for subsequent requests', function () {
    Fetch::config(['headers' => ['X-App' => 'fetch-tests']]);

    $res = fetch(serverUrl('/echo'));

    expect($res->data->headers->{'x-app'})->toBe('fetch-tests');

    Fetch::config(['headers' => []]);
});
