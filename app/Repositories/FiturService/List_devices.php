<?php

namespace App\Repositories\FiturService;

use App\Http\Resources\listDevicesResource;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class List_devices
{
    public function listDevices($param, $model_devices, $builder, $user)
    {
        try {
            $user = $user->authentikasi();
            $data = DB::table('table_devices')->where('table_users_id', $user->id)->get();
            $result = $builder->successOk(listDevicesResource::collection($data));
        } catch (Exception $error) {
            $result = $builder->error422(['message' => 'request header invalid'], $error);
        }

        return $result;
    }
}
