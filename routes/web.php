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
        $router->group(['prefix' => 'master'], function () use ($router) {
            $router->group(['prefix' => 'otomatisasiPerangkat'], function () use ($router) {
                /** upcoming */
                $router->get('/add_otomatisasi', 'DevicesController@add_otomatisasi');
                $router->get('/update_otomatisasi', 'DevicesController@update_otomatisasi');
                $router->get('/delete_otomatisasi', 'DevicesController@delete_otomatisasi');
            });
            $router->group(['prefix' => 'schedulePerangkat'], function () use ($router) {
                $router->get('/get_schedule', 'DevicesController@get_schedule');
                $router->post('/add_schedule', 'DevicesController@add_schedule');
                $router->put('/update_schedule', 'DevicesController@update_schedule');
                $router->delete('/delete_schedule', 'DevicesController@delete_schedule');
            });
            $router->group(['prefix' => 'devices'], function () use ($router) {
                $router->post('/add_devices', 'DevicesController@add_devices');
            });
            $router->group(['prefix' => 'role_users'], function () use ($router) {
                $router->get('/get_role', 'DevicesController@get_role');
                $router->post('/add_role', 'DevicesController@add_role');
                $router->put('/update_role', 'DevicesController@update_role');
                $router->delete('/delete_role', 'DevicesController@delete_role');
            });
        });
    });
});
