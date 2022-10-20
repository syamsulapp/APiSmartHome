<?php

namespace App\Repositories\User;

use App\Models\ClientKey;
use App\Models\ModelsRole;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;

class LogoutRepository extends BaseRepository
{
    protected $user;

    protected $key;

    protected $role;

    public function __construct(User $user, ClientKey $key, ModelsRole $role)
    {
        $this->user = $user;
        $this->key = $key;
        $this->role = $role;
    }
    public function logout($logout)
    {
        $id = $this->user->authentikasi();
        $data = $this->user->when($logout, function ($query) use ($logout, $id) {
            $checkKey = $this->key->where('client_key', $logout->header('IOT-CLIENT-KEY'));
            if (!$logout->header('IOT-CLIENT-KEY')) {
                $result = $this->responseCode(['message' => 'Please Upgrade You App'], 'Upgrade Your App', 426);
            } else {
                if (!$checkKey) {
                    $result = $this->responseCode(['key' => $logout->header('IOT-CLIENT-KEY')], 'Client key wrong', 422);
                } else {
                    $dtString = $id;
                    $dtString['role_user_idrole_user'] = $this->role->where('idrole_user', $dtString->role_user_idrole_user)->first();
                    $result = $this->responseCode(['user' => $dtString], 'Successfully Logout');
                    $query->where('id', $id->id)->update(['api_token' => null]);
                }
            }
            return $result;
        });

        return $data;
    }
}
