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
        $user = User::authentikasi();
        return
            [
                'key' => $this->key,
                'watt' => $this->watt,
                'ampere' => $this->ampere,
                'volt' => $this->volt,
                'users' => $user
            ];
    }
}
