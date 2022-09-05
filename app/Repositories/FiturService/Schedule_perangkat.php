<?php


namespace App\Repositories\FiturService;

use DateTime;
use Exception;
use Illuminate\Support\Facades\Validator;

class Schedule_perangkat
{
    public function schedulePerangkat($param, $user, $builder, $modelDevices)
    {
        $costum = [
            'required' => ':attribute jangan di kosongkan'
        ];
        $validasi = Validator::make($param->all(), [
            'key' => 'required'
        ], $costum);
        try {
            if ($validasi->fails()) {
                $result = $builder->responData(['errors' => $validasi->errors()], 422, 'failed request');
            } else {
                $user = $user->authentikasi();
                $devicesSchedule = $modelDevices::where('table_pairing_key', $user->id)->first();
                $result = $devicesSchedule;
                // $date = new DateTime();
                // $result = $builder->responData($date->format('H:i:s'));
            }
        } catch (Exception $error) {
            $result = $builder->responData(['message' => 'errors siste'], 500, $error);
        }

        return $result;
    }
}
