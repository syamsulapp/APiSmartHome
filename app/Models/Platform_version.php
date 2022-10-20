<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Platform_version extends Model
{
    protected $table = 'version';
    protected $fillable = ['platform', 'version'];
}
