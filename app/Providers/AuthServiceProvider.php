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
            $builder = new ReturnResponse();
            if ($token = $request->header('IOT_API_TOKEN')) {
                try {
                    $data['IOT_API_TOKEN'] = $request->header('IOT_API_TOKEN');
                    $data['IOT_PLATFORM'] = $request->header('IOT_PLATFORM');
                    $data['IOT_SERVICE_VERSION'] = $request->header('IOT_SERVICE_VERSION');
                    if ($data['IOT_PLATFORM'] == 'mobile' && $data['IOT_SERVICE_VERSION'] == '01') {
                        $result = User::where('api_token', $token)->first();
                    } else {
                        $result = $builder->responData(['message' => 'input header failed'], 426, 'header invalid');
                    }
                } catch (Exception $e) {
                    $result = $builder->responData($e, 426, 'failed HEADER REQUEST');
                }
                return $result;
            }
        });
    }
}
