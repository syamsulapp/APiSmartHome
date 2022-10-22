<?php

namespace App\Repositories\Web\Auth;

use App\Models\ModelsAdmin;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash as credential;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str as createToken;

class WebAuthRepository extends BaseRepository
{
    protected $modelAdmin;

    public function __construct(ModelsAdmin $modelAdmin)
    {
        $this->modelAdmin = $modelAdmin;
    }

    public function login($login)
    {
        $validator = Validator::make($login->all(), [
            'username' => 'required|string|min:2',
            'password' => 'required|string|min:2',
        ]);

        if (!$validator->fails()) {
            $result = $this->modelAdmin->when($login->username, function ($query) use ($login) {
                $checkUsername = $query->where('username', $login->username)->first();
                if (!$checkUsername) {
                    $result = $this->responseCode(['message' => 'username salah'], 'Failed Login', 422);
                } else {
                    $checkPassword = credential::check($login->password, $checkUsername->password);
                    if (!$checkPassword) {
                        $result = $this->responseCode(['message' => 'username salah'], 'Failed Login', 422);
                    } else {
                        $user = $query->where('id', $checkUsername->id)->first();
                        $createToken = array('token' => base64_encode(createToken::random(128)));
                        $user->update(['token' => $createToken['token']]);
                        $authSuccess = array('user' => $user, 'token' => $createToken);
                        $result = $this->responseCode($authSuccess, 'SuccessFully Login');
                    }
                }
                return $result;
            });
        } else {
            $collect = collect($validator->errors());
            $result = $this->customError($collect);
        }
        return $result;
    }

    public function register($register)
    {
        $validator = Validator::make($register->all(), [
            'username' => 'required|string|min:2',
            'password' => 'required|string|min:2',
            'email' => 'required|email|min:2|unique:admin',
        ]);
        if (!$validator->fails()) {
            $result = $this->modelAdmin->when($register->username, function ($query) use ($register) {
                $submitData = $register->only('username', 'password', 'email', 'role_user_idrole_user');
                $submitData['password'] = credential::make($register->password);
                $submitData['role_user_idrole_user'] = 1;
                $query->create($submitData);
                return $this->responseCode($submitData, 'Successfully Register');
            });
        } else {
            $collect = collect($validator->errors());
            $result = $this->customError($collect);
        }

        return $result;
    }

    public function logout($logout)
    {
    }
}
