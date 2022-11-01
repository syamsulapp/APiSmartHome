<?php

namespace App\Http\Resources;

use App\Models\ModelsRole;
use App\Models\Otomatisasi_perangkat;
use App\Models\ScheduleModels;
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
        return
            [
                'key' => $this->table_pairing_key,
                'name' => $this->name,
                'volt' => $this->volt,
                'ampere' => $this->ampere,
                'watt' => $this->watt,
                'user' => User::whereIn('id', [$this->table_users_id])->get(),
                'otomatisasi' => Otomatisasi_perangkat::whereIn('status', [$this->table_status_devices_key_status_perangkat])->get(),
                'schedule' => ScheduleModels::whereIn('key_status_table_perangkat', [$this->table_schedule_devices_key_status_table_perangkat])->get(),
            ];
    }
}
