<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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
$router->group(['prefix' => 'user'], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('/login', 'AuthController@login');
        $router->post('/register', 'AuthController@register');
        $router->group(['middleware' => 'client'], function () use ($router) {
            $router->post('/logout', 'AuthController@logout');
            $router->group(['prefix' => 'profile'], function () use ($router) {
                $router->post('', 'AuthController@profile');
                $router->put('', 'AuthController@update_profile');
            });
        });
    });


    $router->group(['prefix' => 'fitur', 'middleware' => 'client'], function () use ($router) {
        //Pairing
        $router->group(['prefix' => 'pairing'], function () use ($router) {
            $router->get('', 'PairingController@index');
            $router->post('', 'PairingController@store');
        });

        $router->group(['prefix' => 'devices'], function () use ($router) {
            $router->get('', 'PerangkatController@index');
            $router->post('', 'PerangkatController@store');
            $router->put('', 'PerangkatController@update');
        });

        $router->group(['prefix' => 'schedule'], function () use ($router) {
            $router->post('/', 'DevicesController@schedulePerangkat');
        });

        // hemat daya fitur users
        $router->group(['prefix' => 'hematDaya'], function () use ($router) {
            $router->post('/', 'DevicesController@hematDaya');
        });
    });

    $router->group(['prefix' => 'web', 'namespace' => 'Web'], function () use ($router) {
        $router->group(['prefix' => 'auth', 'namespace' => 'Auth'], function () use ($router) {
            $router->post('/login', 'WebAuthController@login');
            $router->post('/register', 'WebAuthController@register');
            $router->group(['prefix' => 'logout', 'middleware' => 'admin'], function () use ($router) {
                $router->post('/', 'WebAuthController@logout');
            });
        });
        $router->group(['prefix' => 'master', 'middleware' => ['admin'], 'namespace' => 'Master'], function () use ($router) {
            $router->group(['prefix' => 'schedule'], function () use ($router) {
                $router->get('', 'SchedulePerangkatController@index');
                $router->get('show/{id}', 'SchedulePerangkatController@detail');
                $router->post('', 'SchedulePerangkatController@store');
                $router->put('', 'SchedulePerangkatController@update');
                $router->delete('delete', 'SchedulePerangkatController@delete');
            });
            $router->group(['prefix' => 'role'], function () use ($router) {
                $router->get('', 'RoleUsersController@index');
                $router->get('show/{id}', 'RoleUsersController@show');
                $router->post('', 'RoleUsersController@store');
                $router->put('', 'RoleUsersController@update');
                $router->delete('delete', 'RoleUsersController@delete');
            });
            $router->group(['prefix' => 'otomatisasi'], function () use ($router) {
                $router->get('', 'OtomatisasiPerangkatController@index');
                $router->post('', 'OtomatisasiPerangkatController@store');
                $router->put('', 'OtomatisasiPerangkatController@update');
                $router->delete('delete', 'OtomatisasiPerangkatController@delete');
            });
            $router->group(['prefix' => 'perangkat'], function () use ($router) {
                $router->get('', 'PerangkatController@index');
                $router->get('show/{id}', 'PerangkatController@show');
            });
            $router->group(['prefix' => 'log'], function () use ($router) {
                $router->get('', 'LogController@index');
                $router->get('show/{id}', 'LogController@show');
            });
            $router->group(['prefix' => 'platform_version', 'namespace' => 'Platform'], function () use ($router) {
                $router->get('', 'PlatformController@index');
                $router->post('', 'PlatformController@store');
                $router->put('', 'PlatformController@update');
                $router->delete('delete', 'PlatformController@delete');
            });
            $router->group(['prefix' => 'user'], function () use ($router) {
                $router->get('', 'UserController@index');
                $router->get('show/{id}', 'UserController@show');
                $router->post('', 'UserController@store');
                $router->put('', 'UserController@update');
                $router->delete('delete', 'UserController@delete');
            });
            $router->group(['prefix' => 'admin'], function () use ($router) {
                $router->get('', 'AdminController@index');
                $router->get('show/{id}', 'AdminController@show');
                $router->post('', 'AdminController@store');
                $router->put('', 'AdminController@update');
                $router->delete('delete', 'AdminController@delete');
            });
        });
    });
});
