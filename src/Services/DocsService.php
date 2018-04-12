<?php namespace Ill\Docs\Services;

use File;
use Ill\Docs\Transformers\DocsTransformer;

final class DocsService
{
    public $api;
    private $baseDir;

    /**
     * @var DocsTransformer
     */
    private $transformer;

    public function __construct(DocsTransformer $transformer)
    {
        $this->baseDir     = config('docs.directory');
        $api               = (string)File::get(storage_path($this->baseDir . '/api.json'));
        $this->api         = json_decode($api, true);
        $this->transformer = $transformer;
    }

    /**
     * Retrieve the grouped menu items
     * @return array
     */
    public function resources(): array
    {
        return array_merge($this->pageInfo(), ['groups' => $this->api[ 'groups' ]]);
    }

    /**
     * Retrieve the page resource items
     * @param $resource
     * @return array
     */
    public function resource($resource): array
    {

        $source = collect($this->api[ 'resources' ])->filter(function ($item) use ($resource) {
            return $item[ 'resource' ][ 'name' ] === $resource;
        })->first();

        $sources = $this->transformer->transformEntities($this->baseFile($source));

        return array_merge($this->pageInfo($source[ 'title' ], $source[ 'info' ]), ['resources' => $sources]);

    }

    private function apiInfo(): array
    {
        return [
            'version' => $this->api[ 'version' ],
            'domain'  => $this->api[ 'domain' ],
            'root'    => $this->api[ 'root' ]
        ];
    }

    private function pageInfo($title = null, $info = null): array
    {
        return array_merge($this->apiInfo(), [
            'title' => $title ?? $this->api[ 'title' ],
            'info'  => $info ?? $this->api[ 'info' ]
        ]);
    }

    private function baseFile($source): array
    {
        $path = "{$this->baseDir}/{$source[ 'resource' ][ 'source' ]}";
        $file = (string)File::get(storage_path($path));

        return json_decode($file, true);
    }
}