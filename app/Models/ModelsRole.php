<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelsRole extends Model
{
    protected $table = 'role_user';

    protected $fillable = ['role', 'idrole_user'];
}
