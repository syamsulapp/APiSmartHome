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
            'id' => 'required'
        ], $costum);

        if ($validator->fails()) {
            $result = $builder->responData(['message' => $validator->errors()]);
        } else {
            $user = $user->authentikasi();
            $result = $logout->id != $user->id ? $builder->responData(['message' => 'id tidak sesuai'], 422, 'failed request')
                : [$user::where('id', $user->id)->update(['api_token' => null]), $builder->responData(['message' => 'berhasil logout'])];
        }

        return $result;
    }
}
