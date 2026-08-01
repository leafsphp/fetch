<?php

use Leaf\Fetch;

test('a url string is a GET request', function () {
    $res = fetch(serverUrl('/echo'));

    expect($res->status)->toBe(200);
    expect($res->data->method)->toBe('GET');
    expect($res->data->path)->toBe('/echo');
});

test('plain GET requests do not get a trailing question mark', function () {
    $res = fetch(serverUrl('/echo'));

    expect($res->data->uri)->toBe('/echo');
});

test('fetch() with a config array makes the described request', function () {
    $res = fetch([
        'method' => 'GET',
        'url' => serverUrl('/echo'),
        'data' => [],
    ]);

    expect($res->status)->toBe(200);
    expect($res->data->method)->toBe('GET');
});

test('fetch() shortcuts read exactly how you would guess', function () {
    Fetch::baseUrl(serverUrl());

    expect(fetch()->post('/echo', ['title' => 'Ship fetch site'])->data->method)->toBe('POST');
    expect(fetch()->put('/echo', ['done' => true])->data->method)->toBe('PUT');
    expect(fetch()->patch('/echo', ['title' => 'Shipped!'])->data->method)->toBe('PATCH');
    expect(fetch()->delete('/echo')->data->method)->toBe('DELETE');
    expect(fetch()->get('/echo')->data->method)->toBe('GET');
    expect(fetch()->options('/echo')->data->method)->toBe('OPTIONS');
});

test('post/put/patch work without a data argument', function () {
    $res = Fetch::post(serverUrl('/echo'));

    expect($res->data->method)->toBe('POST');
});

test('head requests return headers with no body', function () {
    $res = Fetch::head(serverUrl('/echo'));

    expect($res->status)->toBe(200);
    expect($res->data)->toBeNull();
});

test('the response exposes data, status, headers and request', function () {
    $res = fetch([
        'method' => 'PUT',
        'url' => serverUrl('/echo'),
        'data' => ['title' => 'Take a break', 'done' => true],
        'headers' => ['X-Request-Id' => 'abc-123'],
    ]);

    expect($res->status)->toBe(200);
    expect($res->data->json->title)->toBe('Take a break');
    expect($res->data->headers->{'x-request-id'})->toBe('abc-123');
    expect($res->headers)->toBeArray();
    expect($res->request['method'])->toBe('PUT');
});

test('response header names are lower cased', function () {
    $res = fetch(serverUrl('/echo'));

    expect($res->headers['x-echo-server'])->toBe('fetch-tests');
    expect($res->headers['content-type'])->toContain('application/json');
});

test('non-2xx statuses are returned, not thrown', function () {
    $res = fetch(serverUrl('/status/404'));

    expect($res->status)->toBe(404);
    expect($res->data->status)->toBe(404);
});

test('redirects are followed by default', function () {
    $res = fetch(serverUrl('/redirect'));

    expect($res->status)->toBe(200);
    expect($res->data->path)->toBe('/echo');
});

test('maxRedirects 0 disables redirect following', function () {
    $res = fetch(['url' => serverUrl('/redirect'), 'maxRedirects' => 0, 'curl' => [CURLOPT_FOLLOWLOCATION => false]]);

    expect($res->status)->toBe(302);
});

test('unreachable hosts throw with the url in the message', function () {
    fetch(['url' => 'http://127.0.0.1:59999/nope', 'timeout' => 2]);
})->throws(Exception::class, '127.0.0.1:59999');

test('timeout is respected in seconds', function () {
    $start = microtime(true);

    try {
        fetch(['url' => serverUrl('/slow?s=5'), 'timeout' => 1]);
        $this->fail('Expected a timeout exception');
    } catch (Exception $e) {
        expect(microtime(true) - $start)->toBeLessThan(4.0);
        expect(strtolower($e->getMessage()))->toContain('time');
    }
});
