<?php

namespace App\Repositories\FiturService\masterData;

use Exception;
use Illuminate\Support\Facades\Validator;

class crudDevices
{
    public function add_devices($param, $modelPairing, $builder, $modelDevices, $user)
    {
        try {
            $costum = [
                'required' => ':attribute jangan di kosongkan'
            ];
            $validator = Validator::make($param->all(), [
                'key' => 'required',
            ], $costum);
            if ($validator->fails()) {
                $result = $builder->error422(['errors' => $validator->errors()]);
            } else {
                try {
                    $user = $user->authentikasi();
                    if ($pair = $modelPairing::where('key', $param->key)->first()) {
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
                                $result = $builder->successOk(['message' => 'sukses add devices'], 'Successfully Added Data');
                            } else {
                                $result = $builder->error422(['message' => 'perangkat ini sudah di tambahkan'], 'failed pairing');
                            }
                        } else {
                            $result = $builder->error422(['message' => 'bukan perangkatmu'], 'failed add devices');
                        }
                    } else {
                        $result = $builder->error422(['message' => 'key tidak di temukan'], 'failed requuest');
                    }
                } catch (Exception $error) {
                    $result = $builder->error500(['message' => 'errors add devices'], $error);
                }
            }
        } catch (Exception $error) {
            $result = $builder->error500(['message' => 'error api'], $error);
        }

        return $result;
    }
}
