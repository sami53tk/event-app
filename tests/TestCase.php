<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un faux manifeste Vite pour les tests
        $this->createViteManifest();
    }

    /**
     * Crée un faux manifeste Vite pour les tests.
     */
    protected function createViteManifest(): void
    {
        $buildDirectory = public_path('build');

        if (!File::exists($buildDirectory)) {
            File::makeDirectory($buildDirectory, 0755, true);
        }

        $manifest = [
            'resources/js/app.js' => [
                'file' => 'assets/app-4a5acb5d.js',
                'src' => 'resources/js/app.js',
                'isEntry' => true,
                'css' => ['assets/app-3e5c568f.css']
            ],
            'resources/css/app.css' => [
                'file' => 'assets/app-3e5c568f.css',
                'src' => 'resources/css/app.css',
                'isEntry' => true
            ]
        ];

        // Créer le répertoire assets s'il n'existe pas
        if (!File::exists($buildDirectory . '/assets')) {
            File::makeDirectory($buildDirectory . '/assets', 0755, true);
        }

        // Créer des fichiers vides pour les assets
        File::put($buildDirectory . '/assets/app-4a5acb5d.js', 'console.log("Test JS file");');
        File::put($buildDirectory . '/assets/app-3e5c568f.css', 'body { background: #fff; }');

        // Écrire le manifeste
        File::put(
            $buildDirectory . '/manifest.json',
            json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }
}
