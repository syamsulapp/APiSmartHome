<?php

namespace App\Repositories\User;

use App\Models;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginRepository
{
    public function login(Request $login)
    {
        $validator = Validator::make($login->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" =>  $validator->errors()]);
        }

        return response()->json([
            'content' => [
                'username' => $login->username,
                'password' => $login->password,
            ]
        ]);
    }
}
