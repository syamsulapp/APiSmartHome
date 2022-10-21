<?php

namespace App\Repositories\User\Profile;

use App\Models\ClientKey;
use App\Models\ModelsRole;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileRepository extends BaseRepository
{
    // store role models
    protected $role;

    // store user models
    protected $user;

    //client key
    protected $key;

    public function __construct(ModelsRole $role, User $user, ClientKey $key)
    {
        $this->role = $role;
        $this->user = $user;
        $this->key = $key;
    }

    public function profile($profile)
    {
        $custom = [
            'numeric' => 'harus angka'
        ];
        $validator = Validator::make($profile->all(), [
            'id_users' => 'numeric',
        ], $custom);
        if ($validator->fails()) {
            $collect = collect($validator->errors());
            $result = $this->customError($collect);
        } else {
            $id = $this->user->authentikasi();
            $data['id'] = $id->id;
            $data['name'] = $id->name;
            $data['username'] = $id->username;
            $data['email'] = $id->email;
            $result = $this->user->when($profile, function ($query) use ($profile, $id, $data) {
                $detail = $query->where('id', $id->id)->first();
                if ($profile->id_users == $detail->id) {
                    $view_detail =  $detail;
                    $view_detail['role_user_idrole_user'] = $this->role->where('idrole_user', $view_detail->role_user_idrole_user)->first();
                } else {
                    $view_detail = $data;
                }
                return $this->responseCode($view_detail, 'Profile Successfully Data');
            });
        }
        return $result;
    }

    public function update_profile($update_profile)
    {
        $validator = Validator::make($update_profile->all(), [
            'name' => 'required|string',
            'username' => 'string|min:4',
            'password' => 'min:8',
            'email' => 'email|required',
        ]);
        if ($validator->fails()) {
            $collect = collect($validator->errors());
            $result = $this->customError($collect);
        } else {
            $id = $this->user->authentikasi();
            $result = $this->key->when($update_profile, function ($query) use ($update_profile, $id) {
                $checkClientKey = $query->where('client_key', $update_profile->header('IOT-CLIENT-KEY'))->first();
                if (!$update_profile->header('IOT-CLIENT-KEY')) {
                    $update_data = $this->responseCode(['message' => 'Please Contact Administrator'], 'Failed Request', 422);
                } else {
                    if (!$checkClientKey) {
                        $update_data = $this->responseCode(['key' => $update_profile->header('IOT-CLIENT-KEY')], 'Client key wrong', 422);
                    } else {
                        $change_data = $update_profile->only('name', 'email');
                        $this->user->where('id', $id->id)->update($change_data);
                        $update_data = $this->responseCode(['user' => $change_data], 'SuccessFully Update Data');
                    }
                }
                return $update_data;
            });
        }

        return $result;
    }
}
