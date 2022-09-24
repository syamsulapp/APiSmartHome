<?php


namespace App\Repositories\FiturService;

use App\Libraries\smarthomeLib;
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
                    $result = $builder->error422(['errors' => $validasi->errors()]);
                } else {
                    $user = $user->authentikasi();
                    if (!$devicesSchedule = $modelDevices::where('table_pairing_key', $param->key)->first()) {
                        $result = $builder->error422(['message' => 'key tidak di temukan']);
                    } else {
                        $modelJadwal = $modelSchedule::where('key_status_table_perangkat', $devicesSchedule->table_schedule_devices_key_status_table_perangkat)->first();
                        if ($date->format('H:i:s') >= $modelJadwal->start_at && $date->format('H:i:s') <= $modelJadwal->end_at) {
                            $modelDevices::where('table_pairing_key', $param->key)
                                ->update([
                                    'table_status_devices_key_status_perangkat' => smarthomeLib::$saklar_on
                                ]);
                            $result = $builder->successOk(['message' => 'perangkat on']);
                        } else {
                            $modelDevices::where('table_pairing_key', $param->key)
                                ->update([
                                    'table_status_devices_key_status_perangkat' => smarthomeLib::$saklar_off
                                ]);
                            $result = $builder->successOk(['message' => 'perangkat off']);
                        }
                    }
                }
            } catch (Exception $error) {
                $result = $builder->error500(['message' => 'errors sistem'], $error);
            }
        } catch (Exception $error) {
            $result = $builder->error500(['message' => 'error validasi'], $error);
        }

        return $result;
    }
}
