<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Basic api information
    |--------------------------------------------------------------------------
    |
    | This is information is needed to get everything working.
    | Required fields are:
    | - title   // The displayed name of the api
    | - info    // Some info explaining the title
    | - version // the version of the api
    | - domain  // The domain on which the api lives
    | - root  // The prefix, if any.
    */
    'basic'      => [
        'title'   => 'ILL API',
        'info'    => 'This describes you api',
        'version' => 'v1',
        'domain'  => 'api.example.com',
        'root'    => 'api'
    ],
    /*
    |--------------------------------------------------------------------------
    | Directory
    |--------------------------------------------------------------------------
    |
    | Configurable directory options
    | Required fields are:
    | - directory   // The folder from which the documentation is served
    */
    'directory'  => env('DOCS_DIRECTORY', 'docs/generated'),
    /*
    |--------------------------------------------------------------------------
    | Validation type
    |--------------------------------------------------------------------------
    |
    | Configurable validation options. You may want to use the standard
    | laravel formrequest validation or you might want jsonschema validation.
    | JSON Schema validation uses an external package and does the actual
    | based on jsonschema files, which you have to create yourselve.
    | Required fields are:
    | - validation   // The type of validation (ex jsonschema | formrequest)
    */
    'validation' => 'formrequest'
];