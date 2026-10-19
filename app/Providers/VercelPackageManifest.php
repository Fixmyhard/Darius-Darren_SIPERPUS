<?php

namespace App\Providers;

use Illuminate\Foundation\PackageManifest;

class VercelPackageManifest extends PackageManifest
{
    /**
     * Override jalur manifestPath agar selalu menulis ke /tmp yang berstatus writable
     */
    public function __construct(\Illuminate\Filesystem\Filesystem $files, $basePath, $manifestPath)
    {
        $vercelManifestPath = '/tmp/bootstrap/cache/packages.php';
        parent::__construct($files, $basePath, $vercelManifestPath);
    }
}
