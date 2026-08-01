<?php

use Leaf\Fetch;

const FETCH_TEST_HOST = '127.0.0.1';
const FETCH_TEST_PORT = 8973;

function serverUrl(string $path = ''): string
{
    return 'http://' . FETCH_TEST_HOST . ':' . FETCH_TEST_PORT . $path;
}

/**
 * Boot the echo server once for the whole suite and kill it on shutdown.
 */
function canConnect(string $host, int $port): bool
{
    // pest's error handler reports fsockopen's connection-refused warning
    // even when suppressed, so we mute it properly for the probe
    set_error_handler(fn () => true);
    $connection = fsockopen($host, $port, $errno, $errstr, 0.2);
    restore_error_handler();

    if (is_resource($connection)) {
        fclose($connection);

        return true;
    }

    return false;
}

function bootEchoServer(): void
{
    static $booted = false;

    if ($booted) {
        return;
    }

    $booted = true;

    if (canConnect(FETCH_TEST_HOST, FETCH_TEST_PORT)) {
        // a previous run left the server up — just reuse it
        return;
    }

    $process = proc_open(
        [PHP_BINARY, '-S', FETCH_TEST_HOST . ':' . FETCH_TEST_PORT, __DIR__ . '/server.php'],
        [1 => ['file', sys_get_temp_dir() . '/fetch-test-server.log', 'a'], 2 => ['file', sys_get_temp_dir() . '/fetch-test-server.log', 'a']],
        $pipes
    );

    if (!is_resource($process)) {
        throw new RuntimeException('Could not start the test echo server');
    }

    register_shutdown_function(function () use ($process) {
        if (is_resource($process)) {
            proc_terminate($process);
        }
    });

    // wait for the server to accept connections
    $deadline = microtime(true) + 10;

    while (microtime(true) < $deadline) {
        if (canConnect(FETCH_TEST_HOST, FETCH_TEST_PORT)) {
            return;
        }

        usleep(100000);
    }

    throw new RuntimeException('Test echo server did not come up on port ' . FETCH_TEST_PORT);
}

uses()
    ->beforeEach(function () {
        bootEchoServer();
        Fetch::baseUrl('');
    })
    ->in(__DIR__);
