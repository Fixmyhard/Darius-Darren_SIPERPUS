<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// 1. Paksa jalur bootstrap/cache ke /tmp jika berjalan di Vercel
if (env('VERCEL_JOB_ID')) {
    $cachePath = '/tmp/bootstrap/cache';
    if (!is_dir($cachePath)) {
        mkdir($cachePath, 0755, true);
    }
    // Set environment variable agar dikenali sistem
    putenv("APP_BASE_PATH=" . realpath(__DIR__ . '/../'));
}

// 2. Inisialisasi Aplikasi Laravel
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// 3. Pindahkan folder cache secara permanen ke /tmp khusus di server Vercel
if (env('VERCEL_JOB_ID')) {
    $app->useStoragePath('/tmp/storage');
    $app->instance('path.bootstrap', '/tmp/bootstrap/cache');
}

return $app;
