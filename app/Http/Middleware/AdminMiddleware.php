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
            $cekToken = $this->admin->where('token', $request->header('IOT-API-TOKEN'))->first();
            if ($cekToken) {
                if ($request->header('IOT-PLATFORM') && $request->header('IOT-VERSION')) {
                    $result = $this->version->when($request, function ($query) use ($request, $next) {
                        $checkPlatform = $query->where('platform', $request->header('IOT-PLATFORM'))->first();
                        if ($checkPlatform->platform == 'web') {
                            $checkVersion = $query->where('version', $checkPlatform->version)->first();
                            if ($checkVersion->version == $request->header('IOT-VERSION')) {
                                $result = $next($request);
                            } else {
                                $result = $this->responseCode(['message' => 'wrong version'], 'Invalid Platform', 422);
                            }
                        } else {
                            $result = $this->responseCode(['message' => 'wrong platform'], 'Invalid Platform', 422);
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
