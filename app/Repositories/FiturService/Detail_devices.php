<?php

namespace App\Repositories\FiturService;

use Exception;
use Illuminate\Support\Facades\Validator;

class Detail_devices
{
    public function formatDetail($detail, $user)
    {
        $data = [
            'devices' => [
                'table_pairing_key' => $detail->table_pairing_key,
                'name' => $detail->name,
                'volt' => $detail->volt,
                'ampere' => $detail->ampere,
                'watt' => $detail->watt,
                'table_users_id' => $user::where('id', $detail->table_users_id)->first(),
                'table_status_devices_key_status_perangkat' => $detail->table_status_devices_key_status_perangkat,
                'table_schedule_devices_key_status_table_perangkat' => $detail->table_schedule_devices_key_status_table_perangkat,
            ]
        ];

        return $data;
    }
    public function detailDevices($param, $modelDevices, $builder, $user)
    {
        $validasi = [
            'required' => 'jangan di kosongkan',
            'numeric' => 'harus angka',
        ];
        $validator = Validator::make($param->all(), [
            'key' => 'required|numeric'
        ], $validasi);
        if ($validator->fails()) {
            $result = $builder->responData(['errors' => $validator->errors()], 422, 'failed request');
        } else {
            if ($param->saklar && $param->schedule) {
                try {
                    $modelDevices::where('table_pairing_key', $param->key)
                        ->update([
                            'table_status_devices_key_status_perangkat' => $param->saklar,
                            'table_schedule_devices_key_status_table_perangkat' => $param->schedule,
                        ]);
                    $result = $builder->responData(['message' => 'update status device']);
                } catch (Exception $error) {
                    $result = $builder->responData(['message' => 'error sistem'], 500, $error);
                }
            } else {
                $detail = $modelDevices::where('table_pairing_key', $param->key)->first();
                $result = $builder->responData($this->formatDetail($detail, $user));
            }
        }
        return $result;
    }
}
