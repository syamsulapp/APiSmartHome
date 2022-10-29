<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Platform_version extends Model
{
    use SoftDeletes;

    protected $table = 'version';
    protected $fillable = ['platform', 'version'];
}
