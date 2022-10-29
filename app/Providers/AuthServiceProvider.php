<?php

namespace App\Providers;

use App\Http\JsonBuilder\ReturnResponse as jsonBuilder;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to bse authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        $this->app['auth']->viaRequest('api', function (Request $request) {
            $builder = new jsonBuilder;
            $user = new User;
            try {
                $result = $user->when($request, function ($query) use ($request) {
                    return $query->where('api_token', $request->header('IOT-API-TOKEN'))->first();
                });
            } catch (Exception) {
                $result = $builder->error401(['message' => 'invalid token'], 'Invalid Token');
            }

            return $result;
        });
    }
}
