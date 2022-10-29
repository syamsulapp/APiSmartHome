<?php

namespace App\Repositories\FiturService\masterData;

use App\Http\Resources\AdminDataResource;
use App\Models\ModelsAdmin;
use App\Models\ModelsRole;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminRepository extends BaseRepository
{
    protected $modelAdmin;

    protected $modelRole;

    public function __construct(ModelsAdmin $modelAdmin, ModelsRole $modelRole)
    {
        $this->modelAdmin = $modelAdmin;
        $this->modelRole = $modelRole;
    }

    public function index($index)
    {
        $limit = 50;
        if ($limit >= $index->limit) {
            $limit = $index->limit;
        }
        $data = $this->modelAdmin->when($index->username, function ($query) use ($index) {
            return $query->where('username', 'LIKE', "%{$index->username}%");
        })->when($index->id, function ($query) use ($index) {
            return $query->where('id', $index->id);
        })->when($index->email, function ($query) use ($index) {
            return $query->where('email', $index->email);
        })
            ->orderBy('id')
            ->paginate($limit);
        return $this->responseCode(AdminDataResource::collection($data->items()));
    }
    public function show($id)
    {
        $data = $this->modelAdmin
            ->where('id', $id)
            ->first();
        $data['role_user_idrole_user'] = $this->modelRole
            ->whereIn('idrole_user', [$id])
            ->get();
        return $this->responseCode($data);
    }
    public function store($store)
    {
        $validate = Validator::make($store->all(), [
            'username' => 'required',
            'password' => 'required',
            'email' => 'required|email|unique:admin,email'
        ]);
        if (!$validate->fails()) {
            $result = $this->modelAdmin->when($store->username, function ($query) use ($store) {
                $data = $store->only('username', 'password', 'email', 'role_user_idrole_user');
                $data['role_user_idrole_user'] = 1;
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
            'username' => 'string',
            'password' => 'string',
            'email' => 'email'
        ]);
        if (!$validate->fails()) {
            if (!$this->modelAdmin
                ->whereIn('id', [$update->id])
                ->get()) {
                return $this->responseCode(['message' => 'id tidak di temukan'], 'Id Not Found', 422);
            } else {
                $result = $this->modelAdmin->when($update->username, function ($query) use ($update) {
                    $data = $update->only('username', 'password', 'email');
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
                    return $this->responseCode(['message' => 'update password success']);
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
            $result = $this->modelAdmin->when($delete->id, function ($query) use ($delete) {
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
