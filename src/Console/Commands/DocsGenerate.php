<?php namespace Ill\Docs\Console\Commands;

use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Ill\Docs\Annotations\Info;
use Illuminate\Console\Command;
use Ill\Docs\Annotations\Title;
use Ill\Docs\Annotations\NoAuth;
use Illuminate\Config\Repository;
use Ill\Docs\Contracts\ApiRequestContract;
use Doctrine\Common\Annotations\AnnotationReader;

class DocsGenerate extends Command
{

    /**
     * {@inheritdoc}
     */
    protected $name = 'docs:generate';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Generate docs from routes';

    /**
     * @var Writer
     */
    private $writer;

    /**
     * @var AnnotationReader
     */
    private $reader;

    private $config;

    private $routes;

    public function __construct(Writer $writer, AnnotationReader $reader, Router $router, Repository $config)
    {
        parent::__construct();

        $this->writer = $writer;
        $this->reader = $reader;
        $this->routes = $router->getRoutes();
        $this->config = $config->get('docs.basic');
    }

    public function handle()
    {

        $this->guardEmptyRoutes();

        $routes = collect($this->routes)->filter(function (Route $route) {

            $name = $route->getName();

            return is_string($name) && substr($name, 0, 4) === "api.";
        })->all();

        $api = $this->constructApi($routes);

        $this->writer->write($api);

        return true;

    }

    /**
     * @param $routes
     *
     * @return array
     */
    private function constructApi($routes): array
    {
        $api = $this->buildApi($routes);

        $menu = $this->buildMenuFromGroups($routes);

        $groups = collect($api[ 'groups' ]);

        $api[ 'groups' ] = collect($menu)->map(function ($item, $key) use ($groups) {
            $api                = $groups->where('group', $key)->first();
            $api[ 'resources' ] = $item->all();

            return $api;
        })->values()->all();

        return $api;
    }

    /**
     * @param $routes
     *
     * @return array
     */
    private function buildApi($routes): array
    {
        return [
            'title'     => $this->config[ 'title' ],
            'info'      => $this->config[ 'info' ],
            'version'   => $this->config[ 'version' ],
            'domain'    => $this->config[ 'domain' ],
            'root'      => $this->config[ 'root' ],
            'groups'    => $this->buildGroups($routes),
            'resources' => $this->buildResources($routes)
        ];
    }

    /**
     * @param $routes
     *
     * @return mixed
     */
    private function buildMenuFromGroups($routes)
    {
        return collect($routes)->map(function (Route $route) {
            $title = $route->action[ 'collection' ] ?? 'other';

            return [
                'title'      => ucfirst($title),
                'group'      => $route->action[ 'group' ] ?? 'other',
                'collection' => $route->action[ 'collection' ] ?? 'other'
            ];
        })->unique('title')->values()->groupBy('group')->all();
    }

    /**
     * @param $routes
     *
     * @return mixed
     */
    private function buildGroups($routes)
    {
        return collect($routes)->map(function (Route $route) {
            return [
                'group'     => $route->action[ 'group' ] ?? 'other',
                'resources' => []
            ];
        })->unique('group')->values()->all();
    }

    /**
     * @param $routes
     *
     * @return mixed
     */
    private function buildResources($routes)
    {
        return collect($routes)->map(function (Route $route) {
            $group      = $route->action[ 'group' ] ?? 'other';
            $collection = $route->action[ 'collection' ] ?? 'other';

            return [
                'title'    => ucfirst($collection),
                'info'     => '',
                'group'    => $group,
                'resource' => [
                    'name'   => $collection,
                    'source' => $this->storeResource($collection)
                ]
            ];
        })->unique('title')->values()->all();
    }

    private function storeResource($collection): string
    {

        $groups = [];

        foreach ($this->routes as $route) {
            $groups[] = $this->buildGroupsFromRoute($route, $collection);
        }

        $shit = array_filter($groups);
        $shit = array_values($shit);

        $file = storage_path('docs/generated/resources/' . $collection . '.json');
        file_put_contents($file, json_encode($shit, JSON_UNESCAPED_SLASHES));

        return 'resources/' . $collection . '.json';
    }

    private function buildGroupsFromRoute(Route $route, $collection)
    {

        $name = $route->getName();

        $routeCollection = $route->action[ 'collection' ] ?? 'other';
        $group           = $route->action[ 'group' ] ?? 'other';

        if ($collection === $routeCollection) {

            try {
                return $this->apiRoutes($route, $name, $group, $collection);
            } catch (\ReflectionException $e) {
                $this->error('Reflection exception, some shit happened: ' . $e->getMessage());
            }
        }
    }

    /**
     * Shitload of shit
     *
     * @param Route $route
     * @param       $name
     * @param       $group
     * @param       $collection
     *
     * @return array
     * @throws \ReflectionException
     */
    private function apiRoutes(Route $route, $name, $group, $collection)
    {
        list($uri, $method, $permissions, $controller, $action) = $this->createBaseParameters($route);

        list($noAuth, $titleAnnotation, $infoAnnotation) = $this->createAnnotations($controller, $action);

        $schema = [];

        if ($method === 'PUT' || $method === 'POST') {
            $schema = $this->schemafromFormRequest($name, $controller, $action);
        }

        return [
            'url'         => $uri,
            'type'        => $method,
            'title'       => null === $titleAnnotation ? 'Title' : $titleAnnotation->getTitle(),
            'info'        => null === $infoAnnotation ? 'info' : $infoAnnotation->getInfo(),
            'needs_auth'  => null === $noAuth,
            'name'        => $name,
            'group'       => $group,
            'collection'  => $collection,
            'permissions' => $permissions,
            'headers'     => $this->createHeaders($noAuth),
            'example'     => $this->createExamples($name, $collection),
            'schema'      => $schema,
            'params'      => []
        ];

    }

    /**
     * Return the permissions or none
     *
     * @param array $middleware
     *
     * @return string
     */
    private function permissions($middleware = []): string
    {
        $permissions = array_filter($middleware, function ($item) {
            return 0 === strpos($item, "can:");
        });

        return count($permissions) ? implode(',', str_replace('can:', '', $permissions)) : 'none';
    }

    /**
     * Guard against empty routes;
     */
    private function guardEmptyRoutes()
    {
        if (count($this->routes) === 0) {
            return $this->error("Your application doesn't have any routes.");
        }
    }

    private function createFile($path, $file)
    {
        $this->writer->saveEmpty($path, $file);

        return "{$path}/{$file}";
    }

    /**
     * @param $name
     * @param $collection
     *
     * @return array
     */
    private function createExamples($name, $collection): array
    {
        return [
            'request'  => $this->createFile("examples/{$collection}/requests", "{$name}.json"),
            'response' => $this->createFile("examples/{$collection}/responses", "{$name}.json")
        ];
    }

    /**
     * @param $noAuth
     *
     * @return string
     */
    private function createHeaders($noAuth): string
    {
        return null === $noAuth ? 'headers/auth.json' : 'headers/no-auth.json';
    }

    /**
     * @throws \ReflectionException
     */
    private function createAnnotations($controller, $action): array
    {

        $reflectionMethod = new ReflectionMethod($controller, $action);
        $noAuth           = $this->reader->getMethodAnnotation($reflectionMethod, NoAuth::class);
        $titleAnnotation  = $this->reader->getMethodAnnotation($reflectionMethod, Title::class);
        $infoAnnotation   = $this->reader->getMethodAnnotation($reflectionMethod, Info::class);

        return [$noAuth, $titleAnnotation, $infoAnnotation];
    }

    private function createBaseParameters(Route $route): array
    {
        $uri         = $route->uri();
        $method      = array_first($route->methods());
        $middleware  = $route->middleware();
        $permissions = $this->permissions($middleware);

        list($controller, $action) = explode('@', $route->getActionName());

        return [$uri, $method, $permissions, $controller, $action];
    }

    /**
     * @throws \ReflectionException
     */
    private function schemafromFormRequest($name, $controller, $method): array
    {
        list($class, $request) = $this->retrieveFormRequest($controller, $method);

        if ( ! in_array(ApiRequestContract::class, $request->getInterfaceNames(), true)) {
            $this->info("Please add ApiRequestContract to request object for route {$name}");
            exit;
        }

        $schema = (new $class)->schema();

        return is_array($schema) ? array_map([$this, 'schema'], $schema) : [];
    }

    /**
     * @throws \ReflectionException
     */
    private function retrieveFormRequest($controller, $method): array
    {
        $parameter = new ReflectionParameter([$controller, $method], 'request');

        if ($parameter->getType() === null) {
            $this->error("Add a FormRequest to '{$controller} @ {$method}'");
            exit;
        }

        $class   = $parameter->getType()->getName();
        $request = new ReflectionClass($class);

        return [$class, $request];

    }

    private function schema($item)
    {
        return [
            'key'     => $item[ 0 ],
            'type'    => $item[ 1 ],
            'example' => $item[ 2 ]
        ];
    }


}