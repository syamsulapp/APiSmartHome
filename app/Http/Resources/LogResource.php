<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
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
                'id' => $this->id,
                'aktivitas' => $this->id,
                'table_users_id' => User::whereIn('id', [$this->table_users_id])->get(),
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ];
    }
}
