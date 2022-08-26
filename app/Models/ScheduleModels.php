<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleModels extends Model
{
    protected $table = 'table_schedule_devices';
    protected $fillable = ['start_at', 'end_at'];
}
