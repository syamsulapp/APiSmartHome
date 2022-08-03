<?php

namespace App\Repositories\User;

use App\Http\JsonBuilder\ReturnResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginRepository
{
    public function __construct(User $user, ReturnResponse $respon)
    {
        $this->user = $user;
        $this->respon = $respon;
    }
    public function login(Request $login)
    {
        $costum_validsai = [
            'required' => ':attribute jangan di kosongkan',
        ];

        $validasi_login = Validator::make($login->all(), [
            'username' => 'required|min:2',
            'password' => 'required|min:2',
        ], $costum_validsai);

        if ($validasi_login->fails()) {
            $result = $this->respon->responData(['errros' => $validasi_login->errors()], 422, 'failed request');
        } else {
            // jika validasi dari input login nya sudah benar , maka jalankan proses login

            $credential = $login->only('username', 'password');
            if (Auth::attempt($credential, true)) {
                $user['user'] = Auth::guard('client')->user();
                $user['token'] = $login->createToken('smartHome')->accessToken;
                $result = $this->respon->responData(['user' => $user, 'message' => 'sukses login']);
            } else {
                $result = $this->respon->responData(['message' => 'username atau password anda salah'], 401, 'authorization');
            }
        }
        return $result;
    }
}
