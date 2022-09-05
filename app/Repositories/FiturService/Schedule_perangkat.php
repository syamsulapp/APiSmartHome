<?php


namespace App\Repositories\FiturService;

use DateTime;
use Exception;
use Illuminate\Support\Facades\Validator;

class Schedule_perangkat
{
    public function schedulePerangkat($param, $user, $builder, $modelDevices, $modelSchedule)
    {
        try {
            $date = new DateTime();
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
                    if (!$devicesSchedule = $modelDevices::where('table_pairing_key', $param->key)->first()) {
                        $result = $builder->responData(['message' => 'key tidak di temukan'], 422, 'failed request');
                    } else {
                        $modelJadwal = $modelSchedule::where('key_status_table_perangkat', $devicesSchedule->table_schedule_devices_key_status_table_perangkat)->first();
                        if ($date->format('H:i:s') >= $modelJadwal->start_at && $date->format('H:i:s') <= $modelJadwal->end_at) {
                            $result = $builder->responData(['message' => 'perangkat menyala']);
                        } else {
                            $result = $builder->responData(['message' => 'perangkat off']);
                        }
                    }
                }
            } catch (Exception $error) {
                $result = $builder->responData(['message' => 'errors siste'], 500, $error);
            }
        } catch (Exception $error) {
            $result = $builder->responData(['message' => 'error validasi'], 500, $error);
        }

        return $result;
    }
}
