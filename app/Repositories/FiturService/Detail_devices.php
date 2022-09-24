<?php

namespace App\Repositories\FiturService;

use Exception;
use Illuminate\Support\Facades\Validator;

class Detail_devices
{
    public function formatDetail($detail, $user, $role, $otomatisasi, $schedule, $builder)
    {
        try {
            /** role && ysers */
            $users = $user::where('id', $detail->table_users_id)->first();
            $roles = $role::where('idrole_user', $users->role_user_idrole_user)->first();
            $otomatisasiModel = $otomatisasi::where('key_status_perangkat', $detail->table_status_devices_key_status_perangkat)->first();
            $scheduleModel = $schedule::where('key_status_table_perangkat', $detail->table_schedule_devices_key_status_table_perangkat)->first();
            /** schedule && otomatisasi */
            $otomatisasi['status'] = $otomatisasiModel->status;
            $otomatisasi['keterangan'] = $otomatisasiModel->keterangan;
            $schedule['status'] = $scheduleModel->key_status_table_perangkat;
            $schedule['start'] = $scheduleModel->start_at;
            $schedule['end'] = $scheduleModel->end_at;
            $role['id']  = $roles->idrole_user;
            $role['role'] = $roles->role;
            /** simpan data di array */
            $user = array(
                'id' => $users->id,
                'nama' => $users->name,
                'username' => $users->username,
                'email' => $users->email,
                'role' => $role
            );
            // format balikan api detail devices
            $data = [
                'devices' => [
                    'table_pairing_key' => $detail->table_pairing_key,
                    'name' => $detail->name,
                    'volt' => $detail->volt,
                    'ampere' => $detail->ampere,
                    'watt' => $detail->watt,
                    'user' => $user,
                    'status' => [
                        'perangkat' => $otomatisasi,
                        'jadwal_perangkat' => $schedule,
                    ],
                ]
            ];
        } catch (Exception $error) {
            $data = $builder->error500(['message' => $error], $error);
        }

        return $data;
    }
    public function detailDevices($param, $modelDevices, $builder, $user, $role_users, $otomatisasi, $schedule)
    {
        try {
            $validasi = [
                'required' => 'jangan di kosongkan',
                'numeric' => 'harus angka',
            ];
            $validator = Validator::make($param->all(), [
                'key' => 'required|numeric'
            ], $validasi);
            if ($validator->fails()) {
                $result = $builder->error422(['errors' => $validator->errors()]);
            } else {
                try {
                    if ($param->saklar || $param->schedule || $param->name) {
                        try {
                            if (
                                !$schedule::where('key_status_table_perangkat', $param->schedule)->first() ||
                                !$otomatisasi::where('key_status_perangkat', $param->saklar)->first()
                            ) {
                                $result = $builder->successOk(['message' => 'id schedule dan id otomatisasi tidak di temukan']);
                            } else {
                                $modelDevices::where('table_pairing_key', $param->key)
                                    ->update([
                                        'table_status_devices_key_status_perangkat' => $param->saklar,
                                        'table_schedule_devices_key_status_table_perangkat' => $param->schedule,
                                        'name' => $param->name,
                                    ]);
                                $result = $builder->successOk(['message' => 'update status devices']);
                            }
                        } catch (Exception $error) {
                            $result = $builder->error500(['message' => 'error update status devices'], $error);
                        }
                    } else {
                        $detail = $modelDevices::where('table_pairing_key', $param->key)->first();
                        $result = $builder->successOk($this->formatDetail($detail, $user, $role_users, $otomatisasi, $schedule, $builder));
                    }
                } catch (Exception $error) {
                    $result = $builder->error500(['message' => $error], $error);
                }
            }
        } catch (Exception $errors) {
            $result = $builder->error500(['message' => $error], $error);
        }
        return $result;
    }
}
