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
        //devices
        $router->group(['prefix' => 'devices'], function () use ($router) {
            $router->post('/', 'DevicesController@listDevices');
            $router->post('detail', 'DevicesController@detailDevices');
        });

        //pairing perangkat
        $router->group(['prefix' => 'pairing'], function () use ($router) {
            $router->post('/', 'DevicesController@listPairing');
            $router->post('/devices', 'DevicesController@pairingPerangkat');
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
                $router->get('{id}', 'SchedulePerangkatController@show');
                $router->post('', 'SchedulePerangkatController@store');
                $router->put('', 'SchedulePerangkatController@update');
                $router->delete('delete', 'SchedulePerangkatController@delete');
            });
            $router->group(['prefix' => 'role'], function () use ($router) {
                $router->get('', 'RoleUsersController@index');
                $router->get('{id}', 'RoleUsersController@show');
                $router->post('', 'RoleUsersController@store');
                $router->put('', 'RoleUsersController@update');
                $router->delete('delete', 'RoleUsersController@delete');
            });
            $router->group(['prefix' => 'otomatisasi'], function () use ($router) {
                $router->get('', 'OtomatisasiPerangkatController@index');
                $router->get('{id}', 'OtomatisasiPerangkatController@show');
                $router->post('', 'OtomatisasiPerangkatController@store');
                $router->put('', 'OtomatisasiPerangkatController@update');
                $router->delete('delete', 'OtomatisasiPerangkatController@delete');
            });
            $router->group(['prefix' => 'perangkat'], function () use ($router) {
                $router->get('', 'PerangkatController@index');
                $router->get('{id}', 'PerangkatController@show');
                $router->post('', 'PerangkatController@store');
                $router->put('', 'PerangkatController@update');
                $router->delete('delete', 'PerangkatController@delete');
            });
            $router->group(['prefix' => 'log'], function () use ($router) {
                $router->get('/', 'LogController@index');
            });
            $router->group(['prefix' => 'plaform'], function () use ($router) {
                $router->get('', 'PlatformController@index');
                $router->get('{id}', 'PlatformController@show');
                $router->post('', 'PlatformController@index');
                $router->put('', 'PlatformController@index');
                $router->delete('delete', 'PlatformController@index');
                $router->group(['prefix' => 'version'], function () use ($router) {
                    $router->get('', 'VersionController@index');
                    $router->get('{id}', 'VersionController@show');
                    $router->post('', 'VersionController@store');
                    $router->put('', 'VersionController@update');
                    $router->delete('delete', 'VersionController@delete');
                });
            });
            $router->group(['prefix' => 'user'], function () use ($router) {
                $router->group(['prefix' => 'admin'], function () use ($router) {
                    $router->get('', 'AdminController@index');
                    $router->get('{id}', 'AdminController@show');
                    $router->post('', 'AdminController@index');
                    $router->put('', 'AdminController@index');
                    $router->delete('delete', 'AdminController@index');
                });
                $router->get('', 'UserController@index');
                $router->get('{id}', 'UserController@show');
                $router->post('', 'UserController@index');
                $router->put('', 'UserController@index');
                $router->delete('delete', 'UserController@index');
            });
        });
    });
});
