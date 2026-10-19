<?php

use Illuminate\Http\Request;

// 1. Daftarkan Composer Autoloader bawaan proyek
require __DIR__ . '/../vendor/autoload.php';

// 2. Definisikan folder /tmp Vercel sebagai tempat penyimpanan yang writable
$storagePath = '/tmp/storage';
$cachePath = '/tmp/bootstrap/cache';

$directories = [
    $storagePath . '/logs',
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/sessions',
    $cachePath
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 3. Set Environment Variable agar dibaca oleh konfigurasi internal Laravel
putenv("APP_STORAGE={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("SESSION_DRIVER=cookie");

// 4. Load dan paksa bootstrap Laravel menggunakan jalur folder /tmp
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($storagePath);
$app->instance('path.bootstrap', $cachePath);

// 5. Jalankan aplikasi langsung lewat HTTP Kernel (tanpa lewat public/index.php)
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
