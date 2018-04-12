<?php namespace Ill\Docs\Console\Commands;

use ReflectionClass;
use ReflectionParameter;
use Illuminate\Routing\Route;
use Illuminate\Foundation\Console\RouteListCommand;

class DocsRoutes extends RouteListCommand
{

    private $permissions = [];
    /**
     * {@inheritdoc}
     */
    protected $name = 'docs:routes';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Table of all named api routes';

    /**
     * {@inheritdoc}
     */
    protected $headers = ['method', 'uri', 'name', 'controller', 'action', 'permission'];

    /**
     * {@inheritdoc}
     */
    protected function getRouteInformation(Route $route)
    {

        $method  = array_first($route->methods());
        $actions = explode('@', $route->getActionName());
        if (is_string($route->getName()) && substr($route->getName(), 0, 4) === "api.") {

            if ($method === 'GET' || $method === 'DELETE') {
                $this->alles[] = [$method];
            }

            if ($method === 'PUT' || $method === 'POST') {
                $parameter = new ReflectionParameter([$actions[ 0 ], $actions[ 1 ]], 'request');
                $class     = $parameter->getType()->getName();
                $request   = new ReflectionClass($class);
                if ($request->hasMethod('schema')) {
                    $rules         = (new $class)->schema();
                    $this->alles[] = [
                        'method' => $method,
                        'class'  => $class
                    ];
                }
            }

            $actions = explode('@', $route->getActionName());

            return $this->filterRoute([
                'method'     => "<fg=red>$method</>",
                'uri'        => $route->uri(),
                'name'       => is_string($route->getName()) ? "<fg=green>{$route->getName()}</>" : "-",
                'controller' => isset($actions[ 0 ]) ? "<fg=cyan>$actions[0]</>" : "-",
                'action'     => isset($actions[ 1 ]) ? "<fg=red>$actions[1]</>" : "-",
                'permission' => $this->permissions($route->middleware())
            ]);
        }
    }

    // Get the permissions from a middleware array
    private function permissions($middleware = [])
    {
        $data = array_filter($middleware, function ($item) {
            return substr($item, 0, 4) === "can:";
        });

        if (count($data)) {
            return implode(',', array_map([$this, 'permission'], $data));
        }

        return '<fg=red>none</>';
    }

    // Remove can from permission string
    private function permission($data)
    {
        return str_replace("can:", "", $data);
    }
}