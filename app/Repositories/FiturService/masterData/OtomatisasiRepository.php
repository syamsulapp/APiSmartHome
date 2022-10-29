<?php

namespace App\Repositories\FiturService\masterData;

use App\Models\Otomatisasi_perangkat;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Support\Facades\Validator;

class OtomatisasiRepository extends BaseRepository
{
    protected $model;

    public function __construct(Otomatisasi_perangkat $model)
    {
        $this->model = $model;
    }

    public function index($index)
    {
        $limit = 50;
        if ($limit >= $index->limit) {
            $limit = $index->limit;
        }
        try {
            $data = $this->model->when($index->status, function ($query) use ($index) {
                return $query->where('status', 'LIKE', "%{$index->status}%");
            })->when($index->id, function ($query) use ($index) {
                return $query->where('key_status_perangkat', $index->id);
            })->when($index->keterangan, function ($query) use ($index) {
                return $query->where('keterangan', $index->keterangan);
            })
                ->orderBy('key_status_perangkat')
                ->paginate($limit);
            return $this->responseCode($data->items());
        } catch (Exception $error) {
            return $this->responseCode(['message' => 'error sistem'], $error, 500);
        }
    }

    public function store($store)
    {
        $validate = Validator::make($store->all(), [
            'status' => 'required|numeric',
            'keterangan' => 'required|regex:/^[a-zA-Z ]+$/'
        ]);

        if (!$validate->fails()) {
            try {
                $result = $this->model->when($store->status, function ($query) use ($store) {
                    $data = $store->only('status', 'keterangan');
                    $query->create($data);
                    return $this->responseCode($data, 'Successfully Created Data');
                });
            } catch (Exception $error) {
                $result = $this->responseCode(['message' => 'error sistem'], $error, 500);
            }
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
            'status' => 'required|numeric',
            'keterangan' => 'required|regex:/^[a-zA-Z ]+$/'
        ]);

        if (!$validate->fails()) {
            try {
                $result = $this->model->when($update->id, function ($query) use ($update) {
                    $data = $update->only('status', 'keterangan');
                    if (!$query->where('key_status_perangkat', $update->id)->first()) {
                        return $this->responseCode(['message' => 'id tidak ada'], 'Id not found', 422);
                    } else {
                        $datas['last'] = $this->model
                            ->whereIn('key_status_perangkat', [$update->id])
                            ->get();
                        $datas['new'] = $data;
                        $query->where('key_status_perangkat', $update->id)
                            ->update($data);
                        return $this->responseCode($datas, 'Successfully Update Data');
                    }
                });
            } catch (Exception $error) {
                $result = $this->responseCode(['message' => 'error sistem'], $error, 500);
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
            try {
                $result = $this->model->when($delete->id, function ($query) use ($delete) {
                    $data = $query
                        ->whereIn('key_status_perangkat', [$delete->id])
                        ->get();
                    if (!$query->where('key_status_perangkat', $delete->id)->first()) {
                        return $this->responseCode(['message' => 'id tidak ada'], 'Id not found', 422);
                    } else {
                        $query->delete('key_status_perangkat', $delete->id);
                        return $this->responseCode($data, 'Successfully Delete Data');
                    }
                });
            } catch (Exception $error) {
                $result = $this->responseCode(['message' => 'error sistem'], $error, 500);
            }
        } else {
            $collect = collect($validate->errors());
            $result = $this->customError($collect);
        }
        return $result;
    }
}
