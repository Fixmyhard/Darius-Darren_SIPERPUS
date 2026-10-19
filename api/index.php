<?php

// 1. Definisikan folder /tmp Vercel sebagai tempat penyimpanan temporary
$storagePath = '/tmp/storage';
$cachePath = '/tmp/bootstrap/cache';

// 2. Buat folder-folder yang dibutuhkan jika belum ada
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

// 3. Set Environment Variable agar dibaca oleh konfigurasi Laravel
putenv("APP_STORAGE={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("SESSION_DRIVER=cookie");

// 4. Load aplikasi Laravel
$app = require __DIR__ . '/../bootstrap/app.php';

// 5. Paksa Laravel menggunakan jalur folder baru yang writable di Vercel
$app->useStoragePath($storagePath);
$app->instance('path.bootstrap', '/tmp/bootstrap');

// 6. Jalankan aplikasi melalui Kernel Web
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
