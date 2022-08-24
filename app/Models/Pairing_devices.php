<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pairing_devices extends Model
{
    protected $table = 'table_pairing';

    protected $fillable = ['key', 'watt', 'ampere', 'volt', 'table_users_id'];
}
