<?php

namespace App\Repositories\FiturService\masterData;

use Illuminate\Support\Facades\Validator;

class crudDevices
{
    public function add($param, $modelPairing, $builder, $modelDevices, $user)
    {
        $validator = Validator::make($param->all(), [
            'key' => 'required',
        ]);
        if ($validator->fails()) {
            $result = $builder->responData(['errors' => $validator->errors()], 422, 'failed request');
        } else {
            $user = $user->authentikasi();
            $pair = $modelPairing::where('key', $param->key)->first();
            if ($pair->table_users_id == $user->id) {
                if (!$modelDevices::where('table_pairing_key', $pair->key)->first()) {
                    $modelDevices::create([
                        'table_pairing_key' => $pair->key,
                        'watt' => $pair->watt,
                        'ampere' => $pair->ampere,
                        'volt' => $pair->volt,
                        'table_users_id' => $pair->table_users_id,
                        'table_status_devices_key_status_perangkat' => 1,
                        'table_schedule_devices_key_status_table_perangkat' => 2,
                    ]);
                    $result = $builder->responData(['message' => 'sukses add devices'], 200, 'Successfully Added Data');
                } else {
                    $result = $builder->responData(['message' => 'perangkat ini sudah di tambahkan'], 422, 'failed pairing');
                }
            } else {
                $result = $builder->responData(['message' => 'bukan perangkatmu'], 422, 'failed add devices');
            }
        }

        return $result;
    }

    public function update($param)
    {
    }

    public function delete($param)
    {
    }
}
