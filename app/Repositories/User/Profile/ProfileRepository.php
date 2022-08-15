<?php

namespace App\Repositories\User\Profile;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileRepository
{
    public function profile($profile, $user, $builder)
    {
        $validator = Validator::make($profile->all(), [
            'id' => 'numeric',
        ]);
        if ($validator->fails()) {
            $result = $builder->responData(['message' => $validator->errors()], 422, 'failed request');
        } else {
            $id = $user->authentikasi();
            if ($profile->id == null) {
                $profile = [
                    'nama' => $id->name,
                    'username' => $id->username,
                    'email' => $id->email,
                ];
                $result = $builder->responData($profile);
            } else {
                if ($profile->id != $id->id) {
                    $result = $builder->responData(['message' => 'id tidak sesuai']);
                } else {
                    $result = $builder->responData($id);
                }
            }
        }
        return $result;
    }

    public function update_profile($update_profile, $builder, $user)
    {
        $validator = Validator::make($update_profile->all(), [
            'id' => 'required|numeric',
            'nama' => 'required|string',
            'username' => 'string|min:4',
            'password' => 'min:8',
            'email' => 'email',
        ]);
        if ($validator->fails()) {
            $result = $builder->responData(['message' => $validator->errors()], 422, 'failed request');
        } else {
            $id = $user->authentikasi();
            if ($update_profile->id == $id->id) {
                $user::where('id', $update_profile->id)
                    ->update([
                        'name' => $update_profile->nama,
                        'username' => $update_profile->username,
                        'password' => Hash::make($update_profile->password),
                        'email' => $update_profile->email,
                    ]);
                $result = $builder->responData(['message' => 'update profile sukses']);
            } else {
                $result = $builder->responData(['message' => 'id tidak sesuai'], 422, 'failed request');
            }
        }

        return $result;
    }
}
