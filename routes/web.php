<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Models\User;
use Illuminate\Http\Request;

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/login', 'AuthController@login');
    $router->post('/register', 'AuthController@register');
    $router->post('/forgot_pass', 'AuthController@forgot_pass');
    $router->post('/cek_token', 'AuthController@token');
    $router->post('/update_pass', 'AuthController@update_pass');
    $router->group(['prefix' => 'user', 'middleware' => 'client'], function () use ($router) {
        $router->post('/logout', 'AuthController@logout');
        $router->group(['prefix' => 'profile'], function () use ($router) {
            $router->post('/', 'AuthController@profile');
            $router->post('/update', 'AuthController@update_profile');
        });
    });
});

$router->group(['prefix' => 'fitur', 'middleware' => 'client'], function () use ($router) {
    $router->get('/devices', function (Request $request) {
        #code
    });
});
