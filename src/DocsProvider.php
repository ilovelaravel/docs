<?php namespace Ill\Docs;

use File;
use Illuminate\Support\ServiceProvider;
use Ill\Docs\Console\Commands\DocsRoutes;
use Ill\Docs\Console\Commands\DocsGenerate;
use Ill\Docs\Console\Commands\DocsMakeRequest;
use Doctrine\Common\Annotations\AnnotationRegistry;

class DocsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     * @throws \RuntimeException
     */
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'docs');

        $this->bootCommands();
        $this->bootPublishes();
        $this->bootAnnotations();
    }

    /**
     * Register the application services.
     *
     * @return void
     * @throws \RuntimeException
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/docs.php', 'docs');

        foreach ($this->baseDirectories() as $directory) {
            $this->createDirectories(storage_path($directory[ 'path' ]));
        }

        foreach ($this->baseFiles() as $file) {
            $path = "{$file['path']}/{$file['name']}";
            $data = (string)File::get(__DIR__ . '/../stubs/' . $file[ 'data' ]);
            $this->createFiles(storage_path($path), json_decode($data, true));
        }

        $this->app->make('Ill\Docs\Controllers\DocsController');

    }

    private function bootAnnotations()
    {
        AnnotationRegistry::registerLoader(function ($class) {
            $namespace = 'Ill\Docs\Annotations\\';
            if (strtolower(substr($class, 0, strlen($namespace))) === strtolower($namespace)) {
                $loaded = class_exists($class);

                return $loaded;
            }

            return false;
        });
    }

    private function bootCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DocsRoutes::class,
                DocsGenerate::class,
                DocsMakeRequest::class
            ]);
        }
    }

    private function bootPublishes()
    {
        $this->publishes([
            __DIR__ . '/../config/docs.php' => config_path('docs.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/../views' => resource_path('views/docs'),
        ], 'views');
    }

    private function createDirectories($directory)
    {
        if ( ! is_dir($directory)) {
            if ( ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
            }
        }
    }

    private function baseDirectories()
    {
        return [
            ['path' => 'docs'],
            ['path' => 'docs/examples'],
            ['path' => 'docs/headers'],
            ['path' => 'docs/resources'],
            ['path' => 'docs/generated'],
            ['path' => 'docs/generated/resources'],
            ['path' => 'docs/generated/examples'],
            ['path' => 'docs/generated/headers'],
        ];
    }

    private function baseFiles()
    {
        return [
            ['path' => 'docs', 'name' => 'api.json', 'data' => 'empty.json'],
            ['path' => 'docs/generated', 'name' => 'api.json', 'data' => 'empty.json'],
            ['path' => 'docs/generated/examples', 'name' => '200.json', 'data' => '200.json'],
            ['path' => 'docs/generated/examples', 'name' => '401.json', 'data' => '401.json'],
            ['path' => 'docs/generated/examples', 'name' => 'empty.json', 'data' => 'empty.json'],
            ['path' => 'docs/generated/headers', 'name' => 'auth.json', 'data' => 'auth.json'],
            ['path' => 'docs/generated/headers', 'name' => 'no-auth.json', 'data' => 'no-auth.json'],
            ['path' => 'docs/examples', 'name' => '200.json', 'data' => '200.json'],
            ['path' => 'docs/examples', 'name' => '401.json', 'data' => '401.json'],
            ['path' => 'docs/examples', 'name' => 'empty.json', 'data' => 'empty.json'],
            ['path' => 'docs/headers', 'name' => 'auth.json', 'data' => 'auth.json'],
            ['path' => 'docs/headers', 'name' => 'no-auth.json', 'data' => 'no-auth.json']
        ];
    }

    private function createFiles($file, $data)
    {
        if ( ! is_file($file)) {
            // Save our content to the file.
            file_put_contents($file, json_encode($data, JSON_UNESCAPED_SLASHES));
        }
    }
}
