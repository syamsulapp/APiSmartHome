<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelsRole extends Model
{
    use SoftDeletes;

    protected $table = 'role_user';

    protected $fillable = ['role', 'created_at', 'updated_at', 'deleted_at'];
}
