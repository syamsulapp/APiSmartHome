<?php

namespace App\Http\Middleware;

use App\Models\ModelsAdmin;
use App\Models\Platform_version;
use App\Repositories\BaseRepository;
use Closure;

class AdminMiddleware extends BaseRepository
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param string|null $guard
     * @return mixed
     */

    protected $admin;

    protected $version;

    public function __construct(ModelsAdmin $admin, Platform_version $version)
    {
        $this->admin = $admin;
        $this->version = $version;
    }
    public function handle($request, Closure $next)
    {

        if (!$request->header('IOT-API-TOKEN')) {
            $result = $this->responseCode(['message' => 'Session Over Please Login Again'], 'Unauthorized', 401);
        } else {
            $cekToken = $this->admin->where('token', $request->header('IOT-WEB-TOKEN'))->first();
            if ($cekToken) {
                if ($request->header('IOT-PLATFORM') && $request->header('IOT-VERSION')) {
                    $result = $this->version->when($request, function ($query) use ($request, $next, $cekToken) {
                        $checkPlatform = $query->where('platform', $request->header('IOT-PLATFORM'))->first();
                        switch ($checkPlatform) {
                            case $checkPlatform->platform != 'web':
                                $result = $this->responseCode(['message' => 'wrong platform'], 'Invalid Platform', 422);
                                break;
                            case $checkPlatform->version != $request->header('IOT-VERSION'):
                                $result = $this->responseCode(['message' => 'wrong version'], 'Invalid Version', 422);
                                break;
                            case $cekToken->role_user_idrole_user != 1:
                                $result = $this->responseCode(['message' => 'your not admin'], 'Invalid Role', 422);
                                break;
                            default:
                                $result = $next($request);
                                break;
                        }
                        return $result;
                    });
                } else {
                    $result = $this->responseCode(['message' => 'Please Update Your App'], 'Update Your Apps', 426);
                }
            } else {
                $result = $this->responseCode(['message' => 'Invalid Token'], 'Unauthorized', 401);
            }
        }
        return $result;
    }
}
