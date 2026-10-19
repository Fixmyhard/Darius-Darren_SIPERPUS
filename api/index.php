<?php

use Illuminate\Http\Request;

// 1. Load Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// 2. Siapkan folder writable /tmp untuk Vercel
$storagePath = '/tmp/storage';
$cachePath = '/tmp/bootstrap/cache';

$dirs = [
    $storagePath . '/logs',
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/sessions',
    $cachePath
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 3. Atur Environment Variables penting
putenv("APP_STORAGE={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("SESSION_DRIVER=cookie");

// 4. Inisialisasi Aplikasi Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 5. Paksa Laravel memindahkan jalur internal bootstrap dan storage ke /tmp
$app->useStoragePath($storagePath);
$app->instance('path.bootstrap', $cachePath);

// Tambahan khusus untuk bypass PackageManifest error
$app->bootstrapWith([
    \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
    \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
    \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
    \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
    \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
    \Illuminate\Foundation\Bootstrap\BootProviders::class,
]);

// 6. Jalankan Aplikasi
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
