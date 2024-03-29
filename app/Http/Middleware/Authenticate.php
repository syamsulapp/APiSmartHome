<?php

namespace App\Http\Middleware;

use App\Http\JsonBuilder\ReturnResponse;
use App\Models\ClientKey;
use App\Models\Platform_version;
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
    public function __construct(Auth $auth, ReturnResponse $builder, User $user, Platform_version $version, ClientKey $key)
    {
        $this->user = $user;
        $this->auth = $auth;
        $this->builder = $builder;
        $this->version = $version;
        $this->key = $key;
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
        if ($request->header('IOT-ORIGINAL-CLIENT-KEY')) {
            $data = $this->key->when($request, function ($query) use ($request) {
                $key = $request->header('IOT-ORIGINAL-CLIENT-KEY');
                if ($key != 'true') {
                    $result = $this->builder->error422(['message' => 'invalid client key'], 'Invalid client key');
                } else {
                    $result = $query->select('client_key')->get();
                }
                return $result;
            });
        } else {
            $data = $this->user->when($request, function ($query) use ($request, $next) {
                if ($request->header('IOT-API-TOKEN')) {
                    $user = $query->where('api_token', $request->header('IOT-API-TOKEN'))->first();
                    if ($user) {
                        $platform = $this->version->where('platform', $request->header('IOT-PLATFORM'))->first();
                        switch ($platform && $user) {
                            case $platform->platform == 'web':
                                $result = $this->builder->error426(['message' => 'platform wrong'], 'Please Upgrade App');
                                break;
                            case $platform->version != $request->header('IOT-VERSION'):
                                $result = $this->builder->error426(['message' => 'version wrong'], 'Please Upgrade App');
                                break;
                            case $user->role_user_idrole_user != 2:
                                $result = $this->builder->error426(['message' => 'your not users'], 'Invalid role');
                                break;
                            default:
                                $result = $next($request);
                                break;
                        }
                    } else {
                        $result = $this->builder->error401(['message' => 'Invalid Token'], 'Unauthorized');
                    }
                } else {
                    $result = $this->builder->error401(['message' => 'Session Over Please Login'], 'Unauthorized');
                }
                return $result;
            });
        }
        return $data;
    }
}
