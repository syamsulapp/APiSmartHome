<?php

namespace App\Repositories\User;

use Illuminate\Support\Facades\Validator;

class LogoutRepository
{
    public function logout($logout, $user, $builder)
    {
        $costum = [
            'required' => ':attribute jangan di kosongkan'
        ];
        $validator = Validator::make($logout->all(), [
            'id_users' => 'required'
        ], $costum);

        if ($validator->fails()) {
            $result = $builder->error422(['message' => $validator->errors()]);
        } else {
            $user = $user->authentikasi();
            $result = $logout->id_users != $user->id ? $builder->error422(['message' => 'id tidak sesuai'])
                : [$user::where('id', $user->id)->update(['api_token' => null]), $builder->successOk(['message' => 'berhasil logout'], 'Succesfully Logout')];
        }

        return $result;
    }
}
