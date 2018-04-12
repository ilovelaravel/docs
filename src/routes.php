<?php

$router = app('router');

// DOCS
$router->get('/docs', 'Ill\Docs\Controllers\DocsController@docs')->name('docs');
$router->get('/docs/{resource}', 'Ill\Docs\Controllers\DocsController@resource')->name('docs.resource');