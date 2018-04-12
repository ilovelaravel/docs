<?php namespace Ill\Docs\Transformers;

use File;

final class DocsTransformer extends Transformer
{

    /**
     * Transform a collection item
     *
     * @param  $item
     *
     * @return array
     */
    public function transform($item)
    {
        $baseDir = config('docs.directory');

        $request  = (string)File::get(storage_path($baseDir . '/' . $item[ 'example' ][ 'request' ]));

        $response = (string)File::get(storage_path($baseDir . '/' . $item[ 'example' ][ 'response' ]));

        $headers  = (string)File::get(storage_path($baseDir . '/' . $item[ 'headers' ]));

        return [
            'url'        => $item[ 'url' ],
            'type'       => $item[ 'type' ],
            'title'      => $item[ 'title' ],
            'info'       => $item[ 'info' ],
            'params'     => $item[ 'params' ],
            'needs_auth' => $item[ 'needs_auth' ],
            'permission' => $item[ 'permissions' ],
            'schema'     => $item[ 'schema' ],
            'example'    => [
                'request'  => json_decode($request, true),
                'response' => json_decode($response, true)
            ],
            'headers'    => json_decode($headers, true)
        ];
    }

}