<?php

namespace App\Http\Resources;

use App\Models\ModelsRole;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class listDevicesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user  = User::where('id', $this->table_users_id)->first();
        $role = ModelsRole::where('idrole_user', $user->role_user_idrole_user)->first();
        $user = array(
            'id' => $user->id,
            'nama' => $user->name,
            'email' => $user->email,
            'role' => [
                'id' => $role->idrole_user,
                'role' => $role->role,
            ]
        );
        return [
            'devices' => [
                'name_devices' => $this->name,
                'key_devices' => $this->table_pairing_key,
                // 'volt' => $this->volt,
                // 'ampere' => $this->ampere,
                // 'watt' => $this->watt,
                'user' => $user
                // 'status_perangkat' => $this->table_status_devices_key_status_perangkat,
                // 'schedule_perangkat' => $this->table_schedule_devices_key_status_table_perangkat,
                // 'table_pairing_key' => $this->table_pairing_key,

            ]
        ];
    }
}
