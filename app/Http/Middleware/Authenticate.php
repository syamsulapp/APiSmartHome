<?php

namespace App\Http\Middleware;

use App\Http\JsonBuilder\ReturnResponse;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth, ReturnResponse $builder, User $user)
    {
        $this->user = $user;
        $this->auth = $auth;
        $this->builder = $builder;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $data['IOT_API_TOKEN']  = $request->header('IOT_API_TOKEN');
        $data['IOT_SERVICE_VERSION']  = $request->header('IOT_SERVICE_VERSION');
        $data['IOT_PLATFORM']  = $request->header('IOT_PLATFORM');

        foreach ($data as $key => $value) {
            if (!$request->header('IOT_API_TOKEN')) {
                $result = $this->builder->error401(['field' => 'masukan token'], 'Authorization Null');
            } else {
                if ($key == 'IOT_API_TOKEN') {
                    $token = $this->user->where('api_token', $value)->first();
                    if (!$token) {
                        $result = $this->builder->error401(['message' => 'token invalid']);
                    } else {
                        return $next($request);
                    }
                }
            }
            return $result;
        }
    }
}
