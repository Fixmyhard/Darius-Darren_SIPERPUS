<?php

// Buat folder temporary yang diperlukan Vercel jika belum ada
if (env('VERCEL_JOB_ID')) {
    $dirs = [
        '/tmp/storage/logs', 
        '/tmp/storage/framework/views', 
        '/tmp/storage/framework/cache', 
        '/tmp/storage/framework/sessions',
        '/tmp/bootstrap/cache'
    ];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    putenv("APP_STORAGE=/tmp/storage");
    putenv("VIEW_COMPILED_PATH=/tmp/storage/framework/views");
}

require __DIR__ . '/../public/index.php';
