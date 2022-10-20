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
            'required' => ':attribute jangan di kosongkan',
        ];
        $validator = Validator::make($profile->all(), [
            'id_users' => 'numeric',
        ], $custom);
        if ($validator->fails()) {
            $collect = collect($validator->errors());
            $result = $this->customError($collect);
        } else {
            $id = $this->user->authentikasi();
            $result = $id;
            // $id = $user->authentikasi();
            // if ($profile->id_users == null) {
            //     $profile = [
            //         'users' => [
            //             'id' => $id->id,
            //             'nama' => $id->name,
            //             'username' => $id->username,
            //             'email' => $id->email,
            //         ]
            //     ];
            //     $result = $builder->successOk($profile);
            // } else {
            //     if ($profile->id_users != $id->id) {
            //         $result = $builder->error422(['message' => 'id tidak sesuai']);
            //     } else {
            //         $result = $builder->successOK($this->allProfile($id, $role));
            //     }
            // }
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
            $result = $builder->error422(['message' => $validator->errors()]);
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
                $result = $builder->successOk(['message' => 'update profile sukses'], 'Update Profile Sucessfully');
            } else {
                $result = $builder->error422(['message' => 'id tidak sesuai']);
            }
        }

        return $result;
    }
}
