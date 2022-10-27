<?php

namespace App\Repositories\FiturService\masterData;

use App\Repositories\BaseRepository;
use App\Models\ModelsRole;
use Exception;
use Illuminate\Support\Facades\Validator;

class RoleUsersRepository extends BaseRepository
{
    protected $modelRole;

    public function __construct(ModelsRole $modelRole)
    {
        $this->modelRole = $modelRole;
    }

    public function index($index)
    {
        $limit = 50; // limit default
        if ($limit >= $index->limit) {
            $limit = $index->limit;
        }
        $data = $this->modelRole->when($index->role, function ($query) use ($index) {
            $query->where('role', 'LIKE', "%{$index->role}%");
        })->when($index->id, function ($query) use ($index) {
            $query->where('idrole_user', $index->id);
        })
            ->orderBy('idrole_user')
            ->paginate($limit);
        return $this->responseCode($data->items());
    }

    public function show($id)
    {
        $data = $this->modelRole
            ->where('idrole_user', $id)
            ->get();
        return $this->responseCode($data);
    }

    public function store($store)
    {
        $validate = Validator::make($store->all(), [
            'role' => 'required|regex:/^[a-zA-Z ]+$/'
        ]);

        if (!$validate->fails()) {
            $result = $this->modelRole->when($store->role, function ($query) use ($store) {
                $data =  $store->only('role');
                $query->create($data);
                return $this->responseCode($data, 'Successfully Created Role');
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
            'role' => 'required|regex:/^[a-zA-Z ]+$/'
        ]);

        if (!$validate->fails()) {
            $result = $this->modelRole->when($update->role, function ($query) use ($update) {
                $data =  $update->only('role');
                $datas['last'] = $query->whereIn('idrole_user', [$update->id])->get();
                $datas['new'] = $data;
                $query
                    ->where('idrole_user', $update->id)
                    ->update($data);
                return $this->responseCode($datas, 'Successfully Update Role');
            });
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
            $result = $this->modelRole->when($delete->id, function ($query) use ($delete) {
                try {
                    $datas = $query
                        ->whereIn('idrole_user', [$delete->id])
                        ->get();
                    if (!$query->where('idrole_user', $delete->id)
                        ->first()) {
                        return $this->responseCode(['message' => 'data tidak ada'], 'data not found', 422);
                    }
                    $query->delete('idrole_user', $delete->id);
                    return $this->responseCode($datas, 'Successfully Delete Role');
                } catch (Exception $error) {
                    return $this->responseCode(['message' => 'error sistem '], $error, 500);
                }
            });
        } else {
            $collect = collect($validate->errors());
            $result = $this->customError($collect);
        }
        return $result;
    }
}
