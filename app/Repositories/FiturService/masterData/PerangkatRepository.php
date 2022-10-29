<?php

namespace App\Repositories\FiturService\masterData;

use App\Http\Resources\ReadPerangkatResource;
use App\Models\Devices_models;
use App\Repositories\BaseRepository;

class PerangkatRepository extends BaseRepository
{
    protected $model;

    public function __construct(Devices_models $model)
    {
        $this->model = $model;
    }

    public function index($index)
    {
        $limit = 50;
        if ($limit >= $index->limit) {
            $limit = $index->limit;
        }
        $data = $this->model->when($index->name, function ($query) use ($index) {
            return $query->where('name', 'LIKE', "%{$index->name}%");
        })->when($index->key, function ($query) use ($index) {
            return $query->where('tabel_pairing_key', $index->key);
        })->when($index->id, function ($query) use ($index) {
            return $query->where('no', $index->id);
        })
            ->orderBy('no')
            ->paginate($limit);
        return $this->responseCode(ReadPerangkatResource::collection($data->items()));
    }

    public function show($id)
    {
        $data = $this->model
            ->whereIn('no', [$id])
            ->get();
        return $this->responseCode($data);
    }
}
