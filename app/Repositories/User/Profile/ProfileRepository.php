<?php

namespace App\Repositories\User\Profile;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileRepository
{
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
    public function profile($profile, $user, $builder, $role)
    {
        $custom = [
            'required' => ':attribute jangan di kosongkan',
        ];
        $validator = Validator::make($profile->all(), [
            'id_users' => 'numeric',
        ], $custom);
        if ($validator->fails()) {
            $result = $builder->responData(['message' => $validator->errors()], 422, 'failed request');
        } else {
            $id = $user->authentikasi();
            if ($profile->id_users == null) {
                $profile = [
                    'users' => [
                        'id' => $id->id,
                        'nama' => $id->name,
                        'username' => $id->username,
                        'email' => $id->email,
                    ]
                ];
                $result = $builder->responData($profile);
            } else {
                if ($profile->id_users != $id->id) {
                    $result = $builder->responData(['message' => 'id tidak sesuai']);
                } else {
                    $result = $builder->responData($this->allProfile($id, $role));
                }
            }
        }
        return $result;
    }

    public function update_profile($update_profile, $builder, $user)
    {
        $validator = Validator::make($update_profile->all(), [
            'id_users' => 'required|numeric',
            'nama' => 'required|string',
            'username' => 'string|min:4',
            'password' => 'min:8',
            'email' => 'email',
        ]);
        if ($validator->fails()) {
            $result = $builder->responData(['message' => $validator->errors()], 422, 'failed request');
        } else {
            $id = $user->authentikasi();
            if ($update_profile->id_users == $id->id) {
                $user::where('id', $update_profile->id_users)
                    ->update([
                        'name' => $update_profile->nama,
                        'username' => $update_profile->username,
                        'password' => Hash::make($update_profile->password),
                        'email' => $update_profile->email,
                    ]);
                $result = $builder->responData(['message' => 'update profile sukses'], 200, 'update profile sucessfully');
            } else {
                $result = $builder->responData(['message' => 'id tidak sesuai'], 422, 'failed request');
            }
        }

        return $result;
    }
}
