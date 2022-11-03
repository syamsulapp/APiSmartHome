<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleModels extends Model
{
    use SoftDeletes;

    protected $table = 'table_schedule_devices';
    protected $fillable = ['start_at', 'end_at', 'created_at', 'updated_at', 'deleted_at', 'table_users_id'];
}
