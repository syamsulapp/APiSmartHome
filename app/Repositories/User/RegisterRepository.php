<?php

namespace App\Repositories\User;

use App\Http\JsonBuilder\ReturnResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterRepository
{
    public function __construct(ReturnResponse $respon, User $user)
    {
        $this->respon = $respon;
        $this->user = $user;
    }
    public function register(Request $register)
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
            $result = $this->respon->responData(['errors' => $validasi->errors()], 422, 'failed request');
        } else {
            $this->user::create([
                'name' => $register->name,
                'username' => $register->username,
                'password' => Hash::make($register->password),
                'email' => $register->email,
                'role' => 2,
            ]);
            $result = $this->respon->responData(['message' => 'sukses register'], 201);
        }
        return $result;
    }
}
