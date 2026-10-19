<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// 1. Inisialisasi Aplikasi Laravel
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

// 2. Trik Utama: Tukar objek PackageManifest bawaan dengan Versi Vercel kita
if (env('VERCEL_JOB_ID')) {
    $app->instance(\Illuminate\Foundation\PackageManifest::class, new \App\Providers\VercelPackageManifest(
        new \Illuminate\Filesystem\Filesystem(),
        $app->basePath(),
        '/tmp/bootstrap/cache/packages.php'
    ));
    
    // Set storage path ke /tmp
    $app->useStoragePath('/tmp/storage');
}

return $app;
