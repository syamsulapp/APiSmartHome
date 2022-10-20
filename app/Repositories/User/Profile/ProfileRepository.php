<?php

namespace App\Repositories\User\Profile;

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

    public function __construct(ModelsRole $role, User $user)
    {
        $this->role = $role;
        $this->user = $user;
    }

    public function allProfile($id, $role)
    {
        $data = $role::where('idrole_user', $id->role_user_idrole_user)->first();
        $role['id'] = $data->idrole_user;
        $role['role'] = $data->role;
        $userAll = array(
            'users' => [
                'id' => $id->id,
                'name' => $id->name,
                'username' => $id->username,
                'email' => $id->email,
                'created_at' => $id->created_at,
                'updated_at' => $id->updated_at,
                'role' => $role,
            ]
        );
        return $userAll;
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
                } else {
                    $view_detail = $data;
                }
                return $view_detail;
            });
        }
        return $result;
    }

    public function update_profile($update_profile)
    {
        $validator = Validator::make($update_profile->all(), [
            'id_users' => 'required|numeric',
            'nama' => 'required|string',
            'username' => 'string|min:4',
            'password' => 'min:8',
            'email' => 'email',
        ]);
        if ($validator->fails()) {
            $collect = collect($validator->errors());
            $result = $this->customError($collect);
        } else {
            $id = $this->user->authentikasi();
            if ($update_profile->id_users == $id->id) {
                $this->user::where('id', $update_profile->id_users)
                    ->update([
                        'name' => $update_profile->nama,
                        'username' => $update_profile->username,
                        'password' => Hash::make($update_profile->password),
                        'email' => $update_profile->email,
                    ]);
                $result = $this->responseCode(['message' => 'update profile sukses'], 'Update Profile Sucessfully');
            } else {
                $result = $this->responseCode(['message' => 'id tidak sesuai'], 'not found id', 422);
            }
        }

        return $result;
    }
}
