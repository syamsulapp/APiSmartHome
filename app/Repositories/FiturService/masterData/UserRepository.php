<?php

namespace App\Repositories\FiturService\masterData;

use App\Http\Resources\UsersDataResource;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserRepository extends BaseRepository
{
    protected $modelUser;

    public function __construct(User $modelUser)
    {
        $this->modelUser = $modelUser;
    }

    public function index($index)
    {
        $limit = 50;
        if ($limit >= $index->limit) {
            $limit = $index->limit;
        }
        $data = $this->modelUser->when($index->username, function ($query) use ($index) {
            return $query->where('username', 'LIKE', "%{$index->username}%");
        })->when($index->id, function ($query) use ($index) {
            return $query->where('id', $index->id);
        })->when($index->email, function ($query) use ($index) {
            return $query->where('email', $index->email);
        })
            ->orderBy('id')
            ->paginate($limit);
        return $this->responseCode(UsersDataResource::collection($data->items()));
    }
    public function show($id)
    {
        $data = $this->modelUser
            ->whereIn('id', [$id])
            ->get();
        return $this->responseCode($data);
    }
    public function store($store)
    {
        $validate = Validator::make($store->all(), [
            'name' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|email|unique:table_users,email'
        ]);
        if (!$validate->fails()) {
            $result = $this->modelUser->when($store->username, function ($query) use ($store) {
                $data = $store->only('name', 'username', 'password', 'email', 'role_user_idrole_user');
                $data['role_user_idrole_user'] = 2;
                $data['password'] = Hash::make($store->password);
                $query->create($data);
                return $this->responseCode($data, 'Successfully Created Data');
            });
        } else {
            $collect = collect($validate->errors());
            $result = $this->customError($collect);
        }
        return $result;
    }
    public function update($update)
    {
        $validate = Validator::make($update->all(), [
            'id' => 'required|numeric',
            'name' => 'string',
            'email' => 'email',
            'username' => 'string',
            'password' => 'string'
        ]);
        if (!$validate->fails()) {
            if (!$this->modelUser
                ->where('id', $update->id)
                ->first()) {
                $result = $this->responseCode(['message' => 'id tidak di temukan'], 'Id not found', 422);
            } else {
                $result = $this->modelUser->when($update->name, function ($query) use ($update) {
                    $data = $update->only('name', 'username', 'email');
                    $datas['last'] = $query
                        ->whereIn('id', [$update->id])
                        ->get();
                    $datas['new'] = $data;
                    $query
                        ->where('id', $update->id)
                        ->update($data);
                    return $this->responseCode($datas, 'Successfully Update Data');
                }, function ($query) use ($update) {
                    $data = $update->only('password');
                    $data['password'] = Hash::make($update->password);
                    $query
                        ->where('id', $update->id)
                        ->update($data);
                    return $this->responseCode($data, 'Successfully Update Password');
                });
            }
        } else {
            $collect = collect($validate->errors());
            $result = $this->customError($collect);
        }
        return $result;
    }
    public function delete($delete)
    {
        $validate = Validator::make($delete->all(), [
            'id' => 'required|numeric',
        ]);
        if (!$validate->fails()) {
            $result = $this->modelUser->when($delete->id, function ($query) use ($delete) {
                $data = $query
                    ->whereIn('id', [$delete->id])
                    ->get();
                if (!$query
                    ->where('id', $delete->id)
                    ->first()) {
                    return $this->responseCode(['message' => 'id tidak di temukan'], 'Id Not Found', 422);
                } else {
                    $query->delete('id', $delete->id);
                    return $this->responseCode($data, 'Successfully Delete Data');
                }
            });
        } else {
            $collect = collect($validate->errors());
            $result = $this->customError($collect);
        }
        return $result;
    }
}
