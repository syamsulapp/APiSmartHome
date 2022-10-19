<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginRepository extends BaseRepository
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
            $collect = collect($validasi_login->errors());
            $result = $this->customError($collect);
        } else {
            if ($user = $user::where('username', $login->username)->first()) {
                if (Hash::check($login->password, $user->password)) {
                    $data = [
                        'api_token' => base64_encode(Str::random(32))
                    ];
                    $user->update(['api_token' => $data['api_token']]);
                    $auth['user'] = $user;
                    $auth['token'] = $data;
                    $result = $builder->successOk($auth, 200, 'Succesfully Login');
                } else {
                    $result = $builder->error422(['message' => 'password anda salah']);
                }
            } else {
                $result = $builder->error422(['message' => 'username salah']);
            }
        }

        return $result;
    }
}
