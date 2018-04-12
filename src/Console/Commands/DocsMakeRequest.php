<?php namespace Ill\Docs\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class SysMakeRequest extends GeneratorCommand
{

    /**
     * {@inheritdoc}
     */
    protected $name = 'docs:make:request';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a form request object for the api';

    /**
     * {@inheritdoc}
     */
    protected $type = 'Request';

    /**
     * {@inheritdoc}
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/request.stub';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Requests';
    }
}