<?php

namespace App\Providers;

use App\Http\JsonBuilder\ReturnResponse;
use App\Models\User;
use Dusterio\LumenPassport\LumenPassport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**platform sent to header is mobile  */
    public $platform = 'mobile';
    /** iot service version api 120 */
    public $api_iot_service_version = 120;
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
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        LumenPassport::routes($this->app);
        $this->app['auth']->viaRequest('api', function (Request $request) {
            $this->builder = new ReturnResponse;
            try {
                $result = User::where('api_token', $request->header('IOT_API_TOKEN'))->first();
            } catch (Exception $error) {
                $result = $this->builder->error401(['errors' => 'id salah'], $error);
            }
            return $result;
        });
    }
}
