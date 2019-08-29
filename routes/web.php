<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () {
    return 'hi';
});


/**
 * Routes for resource book
 */
$router->get('/books', 'BookController@list');
$router->get('/books/{id:\d+}', 'BookController@one');
$router->post('/books', 'BookController@create');
$router->put('/books/{id:\d+}', 'BookController@update');
$router->delete('/books/{id:\d+}', 'BookController@remove');


/**
 * Routes for resource author
 */
$router->get('/authors', 'AuthorController@list');
$router->get('/authors/{id:\d+}', 'AuthorController@one');
$router->post('/authors', 'AuthorController@create');
$router->put('/authors/{id:\d+}', 'AuthorController@update');
$router->delete('/authors/{id:\d+}', 'AuthorController@remove');

$router->get('/authors/{id:\d+}/books', 'AuthorController@books');
