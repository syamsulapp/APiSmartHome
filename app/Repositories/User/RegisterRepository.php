<?php

namespace App\Repositories\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterRepository
{
    public function register($register, $user, $builder)
    {
        $costum = [
            'required' => ':attribute jangan dikosongkan',
            'same' => 'password tidak sama',
            'email' => 'penulisan email tidak tepat',
            'unique' => 'email sdh ada',
        ];
        $validasi = Validator::make($register->all(), [
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'email' => 'required|email|unique:table_users',
        ],$costum);

        if ($validasi->fails()) {
            $result = $builder->responData(['errors' => $validasi->errors()], 422, 'failed request');
        } else {
            $user::create([
                'name' => $register->name,
                'username' => $register->username,
                'password' => Hash::make($register->password),
                'email' => $register->email,
                'role_user_idrole_user' => 2,
            ]);
            $result = $builder->responData(['message' => 'sukses register'], 201);
        }
        return $result;
    }
}
