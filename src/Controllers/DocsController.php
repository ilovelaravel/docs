<?php namespace Ill\Docs\Controllers;

use Ill\Docs\Services\DocsService;

final class DocsController
{

    /**
     * @var DocsService
     */
    protected $service;

    /**
     * @param DocsService $service
     */
    public function __construct(DocsService $service)
    {
        $this->service = $service;
    }

    public function docs()
    {
        $data = $this->service->resources();
        return view('docs::index', ['api' => $data]);
    }

    public function resource($resource)
    {

        try {

            $data = $this->service->resource($resource);
            return view('docs::resource', array_merge(['api' => $this->service->api], $data));

        } catch (\Exception $e) {
            return abort(404, "Typo or resource '{$resource}' does not exist (yet)");
        }
    }
}