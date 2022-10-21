<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelsAdmin extends Model
{
    protected $table = 'admin';
    protected $fillable = ['username', 'password', 'email', 'created_at', 'updated_at', 'role_user_idrole_user'];
}
