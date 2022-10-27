<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogModels extends Model
{
    protected $table = 'table_log';

    protected $fillable = ['aktivitas', 'ip', 'table_users_id', 'created_at', 'updated_at'];
}
