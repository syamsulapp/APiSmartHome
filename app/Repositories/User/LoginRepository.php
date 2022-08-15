<?php

namespace App\Repositories\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginRepository
{
    public function login($login, $builder, $user)
    {
        $costum_validsai = [
            'required' => ':attribute jangan di kosongkan',
        ];

        $validasi_login = Validator::make($login->all(), [
            'username' => 'required|min:2',
            'password' => 'required|min:2',
        ], $costum_validsai);

        if ($validasi_login->fails()) {
            $result = $builder->responData(['errors' => $validasi_login->errors()], 422, 'failed request');
        } else {
            if ($user = $user::where('username', $login->username)->first()) {
                if (Hash::check($login->password, $user->password)) {
                    $data = [
                        'api_token' => base64_encode(Str::random(32))
                    ];
                    $user->update(['api_token' => $data['api_token']]);
                    $result = $builder->responData(['user' => $user, 'token' => $data], 200, 'sukses login');
                } else {
                    $result = $builder->responData(['message' => 'password anda salah'], 422, 'failed request');
                }
            } else {
                $result = $builder->responData(['message' => 'username salah'], 422, 'failed request');
            }
        }

        return $result;
    }
}
