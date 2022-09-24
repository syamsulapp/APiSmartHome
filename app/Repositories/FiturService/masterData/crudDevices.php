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

    /** role method crud */
    public function get_role($modelRole, $builder)
    {
        return $builder->successOk($modelRole::all());
    }
    public function add_role($param, $modelRole, $builder)
    {
        $Validasi = Validator::make($param->all(), [
            'role' => 'required'
        ]);

        if ($Validasi->fails()) {
            $result = $builder->error422(['errors' => $Validasi->errors()]);
        } else {
            $modelRole::create($param->all());
            $result = $builder->successOk(['message' => 'success add role']);
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
            $result = $builder->error422(['errors' => $Validasi->errors()]);
        } else {
            if (!$modelRole::where('idrole_user', $param->id)->first()) {
                $result = $builder->error422(['message' => 'id tidak di temukan']);
            } else {
                $modelRole::where('idrole_user', $param->id)
                    ->update([
                        'role' => $param->role
                    ]);
                $result = $builder->successOk(['message' => 'success update role']);
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
            $result = $builder->error422(['errors' => $Validasi->errors()]);
        } else {
            if (!$modelRole::where('idrole_user', $param->id)->first()) {
                $result = $builder->error422(['message' => 'id tidak di temukan']);
            } else {
                $role = $modelRole::where('idrole_user', $param->id);
                $role->delete();
                $result = $builder->successOk(['message' => 'success delete role']);
            }
        }

        return $result;
    }
    /** schedule method crud */
    public function get_schedule($modelSchedule, $builder)
    {
        return $builder->successOk($modelSchedule::all());
    }
    public function add_schedule($param, $modelSchedule, $builder)
    {
        $costum = [
            'unique' => 'sudah di buat'
        ];
        $Validasi = Validator::make($param->all(), [
            'start_at' => 'required|unique:table_schedule_devices,start_at',
            'end_at' => 'required|unique:table_schedule_devices,end_at',
        ], $costum);

        if ($Validasi->fails()) {
            $result = $builder->error422(['errors' => $Validasi->errors()]);
        } else {
            $modelSchedule::create($param->all());
            $result = $builder->successOk(['message' => 'success add schedule']);
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
            $result = $builder->error422(['errors' => $Validasi->errors()]);
        } else {
            if (!$modelSchedule::where('key_status_table_perangkat', $param->id)->first()) {
                $result = $builder->error422(['message' => 'id tidak di temukan']);
            } else {
                $modelSchedule::where('key_status_table_perangkat', $param->id)
                    ->update([
                        'start_at' => $param->start_at,
                        'end_at' => $param->end_at,
                    ]);
                $result = $builder->successOk(['message' => 'success update schedule']);
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
            $result = $builder->error422(['errors' => $Validasi->errors()]);
        } else {
            if (!$modelSchedule::where('key_status_table_perangkat', $param->id)->first()) {
                $result = $builder->error422(['message' => 'id tidak di temukan']);
            } else {
                $role = $modelSchedule::where('key_status_table_perangkat', $param->id);
                $role->delete();
                $result = $builder->successOk(['message' => 'success delete schedule']);
            }
        }

        return $result;
    }
}
