<?php

namespace App\Repositories\FiturService\masterData;

use App\Models\Platform_version;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;

class PlatformVersionRepository extends BaseRepository
{
    protected $model;

    public function __construct(Platform_version $model)
    {
        $this->model = $model;
    }

    public function index($index)
    {
        $limit = 50;

        if ($limit >= $index->limit) {
            $limit = $index->limit;
        }
        $data = $this->model->when($index->platform, function ($query) use ($index) {
            return $query->where('platform', 'LIKE', "%{$index->platform}%");
        })->when($index->version, function ($query) use ($index) {
            return $query->where('version', $index->version);
        })->when($index->id, function ($query) use ($index) {
            return $query->where('idversion', $index->id);
        })
            ->orderBy('idversion')
            ->paginate($limit);
        return $this->responseCode($data->items());
    }

    public function show($id)
    {
        $data = $this->model
            ->whereIn('idversion', $id)
            ->get();
        return $this->responseCode($data);
    }
    public function store($store)
    {
        $validator = Validator::make($store->all(), [
            'platform' => 'required',
            'version' => 'required'
        ]);

        if (!$validator->fails()) {
            $result = $this->model->when($store->platform, function ($query) use ($store) {
                $data = $store->only('platform', 'version');
                $query->create($data);
                return $this->responseCode($data, 'Successfully Created Data');
            });
        } else {
            $collect = collect($validator->errors());
            $result = $this->customError($collect);
        }
        return $result;
    }
    public function update($update)
    {
        $validator = Validator::make($update->all(), [
            'id' => 'required|numeric',
            'platform' => 'required',
            'version' => 'required'
        ]);

        if (!$validator->fails()) {
            $result = $this->model->when($update->platform, function ($query) use ($update) {
                $data = $update
                    ->only('platform', 'version');
                $datas['last'] = $this->model
                    ->whereIn('idversion', [$update->id])
                    ->get();
                $datas['new'] = $data;
                $query
                    ->where('idversion', $update->id)
                    ->update($data);
                return $this->responseCode($datas, 'Successfully Update Data');
            });
        } else {
            $collect = collect($validator->errors());
            $result = $this->customError($collect);
        }
        return $result;
    }
    public function delete($delete)
    {
        $validator = Validator::make($delete->all(), [
            'id' => 'required|numeric'
        ]);

        if (!$validator->fails()) {
            $result = $this->model->when($delete->id, function ($query) use ($delete) {
                $data = $query
                    ->whereIn('idversion', [$delete->id])
                    ->get();
                if (!$query
                    ->where('idversion', $delete->id)
                    ->first()) {
                    return $this->responseCode(['message' => 'id tidak di temukan'], 'Id not found', 422);
                } else {
                    $query->delete('idversion', $delete->id);
                    return $this->responseCode($data, 'Successfully Delete Data');
                }
            });
        } else {
            $collect = collect($validator->errors());
            $result = $this->customError($collect);
        }
        return $result;
    }
}
