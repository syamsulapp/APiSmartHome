<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginRepository extends BaseRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function login($login)
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
            return $this->customError($collect);
        } else {
            $data = $this->user->when($login->username, function ($query) use ($login) {
                $user = $query->where('username', $login->username)->first();
                if ($user) {
                    $checkPassword = Hash::check($login->password, $user->password);
                    if ($checkPassword) {
                        $token = array('api_token' => base64_encode(Str::random(40)));
                        $user->update(['api_token' => $token['api_token']]);
                        $authSuccess = array('user' => $user, 'token' => $token);
                        $result = $this->responseCode($authSuccess, 'SuccessFully Login');
                    } else {
                        $result = $this->responseCode(['message' => 'Password Salah'], 'Login Failed', 422);
                    }
                } else {
                    $result = $this->responseCode(['message' => 'Username Salah'], 'Login Failed', 422);
                }
                return $result;
            });
            return $data;
        }
    }
}
