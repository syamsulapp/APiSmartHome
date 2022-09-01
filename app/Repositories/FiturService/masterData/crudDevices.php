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
                $result = $builder->responData(['errors' => $validator->errors()], 422, 'failed request');
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
                                $result = $builder->responData(['message' => 'sukses add devices'], 200, 'Successfully Added Data');
                            } else {
                                $result = $builder->responData(['message' => 'perangkat ini sudah di tambahkan'], 422, 'failed pairing');
                            }
                        } else {
                            $result = $builder->responData(['message' => 'bukan perangkatmu'], 422, 'failed add devices');
                        }
                    } else {
                        $result = $builder->responData(['message' => 'key tidak di temukan'], 422, 'failed requuest');
                    }
                } catch (Exception $error) {
                    $result = $builder->responData(['message' => 'errors add devices'], 500, $error);
                }
            }
        } catch (Exception $error) {
            $result = $builder->responData(['message' => 'error api'], 500, $error);
        }

        return $result;
    }

    /** role method crud */
    public function get_role($modelRole, $builder)
    {
        return $builder->responData($modelRole::all());
    }
    public function add_role($param, $modelRole, $builder)
    {
        $Validasi = Validator::make($param->all(), [
            'role' => 'required'
        ]);

        if ($Validasi->fails()) {
            $result = $builder->responData(['errors' => $Validasi->errors()], 422, 'failed request');
        } else {
            $modelRole::create($param->all());
            $result = $builder->responData(['message' => 'success add role']);
        }

        return $result;
    }
    public function update_role($param, $modelRole, $builder)
    {
        $Validasi = Validator::make($param->all(), [
            'role' => 'required',
            'id' => 'required',
        ]);

        if ($Validasi->fails()) {
            $result = $builder->responData(['errors' => $Validasi->errors()], 422, 'failed request');
        } else {
            if (!$modelRole::where('idrole_user', $param->id)->first()) {
                $result = $builder->responData(['message' => 'id tidak di temukan'], 422, 'failed request');
            } else {
                $modelRole::where('idrole_user', $param->id)
                    ->update([
                        'role' => $param->role
                    ]);
                $result = $builder->responData(['message' => 'success update role']);
            }
        }

        return $result;
    }
    public function delete_role($param, $modelRole, $builder)
    {
        $Validasi = Validator::make($param->all(), [
            'id' => 'required',
        ]);
        if ($Validasi->fails()) {
            $result = $builder->responData(['errors' => $Validasi->errors()], 422, 'failed request');
        } else {
            if (!$modelRole::where('idrole_user', $param->id)->first()) {
                $result = $builder->responData(['message' => 'id tidak di temukan']);
            } else {
                $role = $modelRole::where('idrole_user', $param->id);
                $role->delete();
                $result = $builder->responData(['message' => 'success delete role']);
            }
        }

        return $result;
    }
    /** schedule method crud */
    public function get_schedule($modelSchedule, $builder)
    {
        return $builder->responData($modelSchedule::all());
    }
    public function add_schedule($param, $modelSchedule, $builder)
    {
        $Validasi = Validator::make($param->all(), [
            'start_at' => 'required',
            'end_at' => 'required',
        ]);

        if ($Validasi->fails()) {
            $result = $builder->responData(['errors' => $Validasi->errors()], 422, 'failed request');
        } else {
            $modelSchedule::create($param->all());
            $result = $builder->responData(['message' => 'success add schedule']);
        }

        return $result;
    }
    public function update_schedule($param, $modelSchedule, $builder)
    {
        $Validasi = Validator::make($param->all(), [
            'id' => 'required',
            'start_at' => 'required',
            'end_at' => 'required',
        ]);

        if ($Validasi->fails()) {
            $result = $builder->responData(['errors' => $Validasi->errors()], 422, 'failed request');
        } else {
            if (!$modelSchedule::where('key_status_table_perangkat', $param->id)->first()) {
                $result = $builder->responData(['message' => 'id tidak di temukan'], 422, 'failed request');
            } else {
                $modelSchedule::where('key_status_table_perangkat', $param->id)
                    ->update([
                        'start_at' => $param->start_at,
                        'end_at' => $param->end_at,
                    ]);
                $result = $builder->responData(['message' => 'success update schedule']);
            }
        }

        return $result;
    }
    public function delete_schedule($param, $modelSchedule, $builder)
    {
        $Validasi = Validator::make($param->all(), [
            'id' => 'required',
        ]);
        if ($Validasi->fails()) {
            $result = $builder->responData(['errors' => $Validasi->errors()], 422, 'failed request');
        } else {
            if (!$modelSchedule::where('key_status_table_perangkat', $param->id)->first()) {
                $result = $builder->responData(['message' => 'id tidak di temukan']);
            } else {
                $role = $modelSchedule::where('key_status_table_perangkat', $param->id);
                $role->delete();
                $result = $builder->responData(['message' => 'success delete schedule']);
            }
        }

        return $result;
    }
}
