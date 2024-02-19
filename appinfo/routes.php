<?php

$requirements = [
    'apiVersion' => 'v1',
];


/**
 * Create your routes in here. The name is the lowercase name of the controller
 * without the controller part, the stuff after the hash is the method.
 * e.g. page#index -> OCA\Library\Controller\PageController->index()
 *
 * The controller class has to be registered in the application.php file since
 * it's instantiated in there
 */
return [
    'routes' => [
        ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
        ['name' => 'config#setConfig', 'url' => '/config', 'verb' => 'PUT'],
        ['name' => 'book#addUserBook', 'url' => '/book/add', 'verb' => 'POST'],
        ['name' => 'book#getUserBooks', 'url' => '/books', 'verb' => 'GET'],
    ],
    'ocs' => [
        ['name' => 'notes#getUserNotes', 'url' => '/api/{apiVersion}/notes', 'verb' => 'GET', 'requirements' => $requirements],
        ['name' => 'notes#exportUserNote', 'url' => '/api/{apiVersion}/notes/{id}/export', 'verb' => 'GET', 'requirements' => $requirements],
        ['name' => 'notes#addUserNote', 'url' => '/api/{apiVersion}/notes', 'verb' => 'POST', 'requirements' => $requirements],
        ['name' => 'notes#editUserNote', 'url' => '/api/{apiVersion}/notes/{id}', 'verb' => 'PUT', 'requirements' => $requirements],
        ['name' => 'notes#deleteUserNote', 'url' => '/api/{apiVersion}/notes/{id}', 'verb' => 'DELETE', 'requirements' => $requirements],
    ],
];
