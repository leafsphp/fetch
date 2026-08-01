<?php

// Tiny echo server used by the test suite. Run with:
// php -S 127.0.0.1:<port> tests/server.php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$headers = [];
foreach ($_SERVER as $key => $value) {
    if (strpos($key, 'HTTP_') === 0) {
        $headers[strtolower(str_replace('_', '-', substr($key, 5)))] = $value;
    }
}
if (isset($_SERVER['CONTENT_TYPE'])) {
    $headers['content-type'] = $_SERVER['CONTENT_TYPE'];
}

if ($path === '/text') {
    header('Content-Type: text/plain');
    echo 'plain text body';

    return;
}

if (preg_match('#^/status/(\d+)$#', $path, $m)) {
    http_response_code((int) $m[1]);
    header('Content-Type: application/json');
    echo json_encode(['status' => (int) $m[1]]);

    return;
}

if ($path === '/slow') {
    sleep((int) ($_GET['s'] ?? 2));
    header('Content-Type: application/json');
    echo json_encode(['slept' => true]);

    return;
}

if ($path === '/basic-auth') {
    header('Content-Type: application/json');
    echo json_encode([
        'user' => $_SERVER['PHP_AUTH_USER'] ?? null,
        'pass' => $_SERVER['PHP_AUTH_PW'] ?? null,
    ]);

    return;
}

if ($path === '/redirect') {
    http_response_code(302);
    header('Location: /echo');

    return;
}

// default: echo everything back
$rawBody = file_get_contents('php://input');

header('Content-Type: application/json');
header('X-Echo-Server: fetch-tests');
echo json_encode([
    'method' => $_SERVER['REQUEST_METHOD'],
    'path' => $path,
    'uri' => $_SERVER['REQUEST_URI'],
    'query' => $_GET,
    'headers' => $headers,
    'body' => $rawBody,
    'json' => json_decode($rawBody, true),
]);
