<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModelsAdmin extends Model
{
    use SoftDeletes;
    protected $table = 'admin';
    protected $fillable = ['username', 'password', 'email', 'created_at', 'updated_at', 'role_user_idrole_user'];
}
