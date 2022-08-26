<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ListPairingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::where('id', $this->table_users_id)->first();
        return [
            'devices' => [
                'key' => $this->key,
                'watt' => $this->watt,
                'ampere' => $this->ampere,
                'volt' => $this->volt,
                'users' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ]
            ]
        ];
    }
}
