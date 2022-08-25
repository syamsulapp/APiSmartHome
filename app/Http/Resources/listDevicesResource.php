<?php

namespace App\Http\Resources;

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
        return [
            'devices' => [
                'name_devices' => $this->name,
                'key_devices' => $this->table_pairing_key,
                // 'volt' => $this->volt,
                // 'ampere' => $this->ampere,
                // 'watt' => $this->watt,
                'table_users_id' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
                // 'status_perangkat' => $this->table_status_devices_key_status_perangkat,
                // 'schedule_perangkat' => $this->table_schedule_devices_key_status_table_perangkat,
                // 'table_pairing_key' => $this->table_pairing_key,

            ]
        ];
    }
}
