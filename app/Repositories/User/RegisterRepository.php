<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterRepository extends BaseRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function register($register)
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
        ], $costum);

        if ($validasi->fails()) {
            $collect = collect($validasi->errors());
            $result = $this->customError($collect);
        } else {
            if ($register->name == 'bot' || $register->username == 'bot') {
                $result = $this->responseCode(['message' => 'di ban'], 'Banned Your name and username', 422);
            } else {
                $data = $register->only('name', 'username', 'password', 'confirm_password', 'email');
                $data['role_user_idrole_user'] = 2;
                $this->user::create($data);
                $result = $this->responseCode(['User' => $data]);
            }
        }
        return $result;
    }
}
