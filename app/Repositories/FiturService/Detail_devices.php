<?php

namespace App\Repositories\FiturService;

use Exception;
use Illuminate\Support\Facades\Validator;

class Detail_devices
{
    public function formatDetail($detail, $user, $role, $otomatisasi, $schedule)
    {
        /** role && ysers */
        $users = $user::where('id', $detail->table_users_id)->first();
        $roles = $role::where('idrole_user', $users->role_user_idrole_user)->first();
        $otomatisasiModel = $otomatisasi::where('key_status_perangkat', $detail->table_status_devices_key_status_perangkat)->first();
        $scheduleModel = $schedule::where('key_status_table_perangkat', $detail->table_schedule_devices_key_status_table_perangkat)->first();
        /** schedule && otomatisasi */
        $otomatisasi['status'] = $otomatisasiModel->status;
        $otomatisasi['keterangan'] = $otomatisasiModel->keterangan;
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

        $data = [
            'devices' => [
                'table_pairing_key' => $detail->table_pairing_key,
                'name' => $detail->name,
                'volt' => $detail->volt,
                'ampere' => $detail->ampere,
                'watt' => $detail->watt,
                'table_users_id' => $user,
                'status' => [
                    'perangkat' => $otomatisasi,
                    'jadwal_perangkat' => $schedule,
                ],
            ]
        ];

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
                $result = $builder->responData(['errors' => $validator->errors()], 422, 'failed request');
            } else {
                try {
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
                    } else if ($param->name) {
                        $modelDevices::where('table_pairing_key', $param->key)
                            ->update([
                                'name' => $param->name,
                            ]);
                        $result = $builder->responData(['message' => 'nama perangkat di update']);
                    } else {
                        $detail = $modelDevices::where('table_pairing_key', $param->key)->first();
                        $result = $builder->responData($this->formatDetail($detail, $user, $role_users, $otomatisasi, $schedule));
                    }
                } catch (Exception $error) {
                    $result = $builder->responData(['message' => 'request header invalid'], 500, $error);
                }
            }
        } catch (Exception $errors) {
            $result = $builder->responData(['message' => 'error sistem']);
        }
        return $result;
    }
}
