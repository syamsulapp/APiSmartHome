<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Devices_models extends Model
{
    use SoftDeletes;

    protected $table = 'table_devices';

    protected $fillable = ['name', 'volt', 'ampere', 'watt', 'table_users_id', 'table_status_devices_key_status_perangkat', 'table_schedule_devices_key_status_table_perangkat', 'table_pairing_key'];

    protected $guarded = ['no'];
}
