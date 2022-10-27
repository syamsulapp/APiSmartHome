<?php

namespace App\Repositories\FiturService\masterData;

use App\Models\ScheduleModels;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ShowScheduleResource;

class ScheduleRepository extends BaseRepository
{
    protected $modelSchedule;

    public function __construct(ScheduleModels $modelSchedule)
    {
        $this->modelSchedule = $modelSchedule;
    }

    public function index($index)
    {
        $limit = 50;
        if ($limit >= $index->limit) {
            $limit = $index->limit;
        }
        try {
            $data = $this->modelSchedule->when($index->start_at, function ($query) use ($index) {
                return $query->where('start_at', 'LIKE', "%{$index->start_at}%");
            })->when($index->id, function ($query) use ($index) {
                return $query->where('key_status_table_perangkat', $index->id);
            })->when($index->end_at, function ($query) use ($index) {
                return $query->where('end_at', $index->end_at);
            })
                ->orderBy('key_status_table_perangkat')
                ->paginate($limit);
            return $this->responseCode(ShowScheduleResource::collection($data->items()), 'SuccessFully Data');
        } catch (Exception $error) {
            return $this->responseCode(['message' => 'error sistem'], $error, 500);
        }
    }

    public function show($id)
    {
        $data = $this->modelSchedule
            ->where('key_status_table_perangkat', $id)
            ->get();
        return $this->responseCode($data);
    }

    public function store($store)
    {
        $validator = Validator::make($store->all(), [
            'start_at' => 'required|date_format:H:i:s',
            'end_at' => 'required|date_format:H:i:s'
        ]);

        if (!$validator->fails()) {
            try {
                $result = $this->modelSchedule->when($store->start_at, function ($query) use ($store) {
                    $data = $store->only('start_at', 'end_at');
                    $query->create($data);
                    return $this->responseCode($data, 'SuccessFully Created Schedule');
                });
            } catch (Exception $error) {
                $result = $this->responseCode(['message' => 'error sistem'], $error, 500);
            }
        } else {
            $collect = collect($validator->errors());
            $result = $this->customError($collect);
        }

        return $result;
    }

    public function update($update)
    {
        $validator = Validator::make($update->all(), [
            'id' => 'required',
            'start_at' => 'required|date_format:H:i:s',
            'end_at' => 'required|date_format:H:i:s'
        ]);

        if (!$validator->fails()) {
            try {
                $result = $this->modelSchedule->when($update->id, function ($query) use ($update) {
                    $data = $update->only('start_at', 'end_at');
                    if (!$query->where('key_status_table_perangkat', $update->id)
                        ->first()) {
                        return $this->responseCode(['message' => 'id tidak di temukan'], 'Id not found', 422);
                    } else {
                        $datas['last'] = $query->where('key_status_table_perangkat', $update->id)->first();
                        $datas['new'] = $data;
                        $query->where('key_status_table_perangkat', $update->id)->update($data);
                        return $this->responseCode($datas, 'SuccessFully Update Schedule');
                    }
                });
            } catch (Exception $error) {
                $result = $this->responseCode(['message' => 'error sistem'], $error, 500);
            }
        } else {
            $collect = collect($validator->errors());
            $result = $this->customError($collect);
        }

        return $result;
    }

    public function delete($delete)
    {
        $validator = Validator::make($delete->all(), [
            'id' => 'required|numeric',
        ]);

        if (!$validator->fails()) {
            try {
                $result = $this->modelSchedule->when($delete->id, function ($query) use ($delete) {
                    $data = $query->where('key_status_table_perangkat', $delete->id)->first();
                    if (!$data) {
                        $data = array('message' => 'data tidak di temukan');
                    } else {
                        $query->delete('key_status_table_perangkat', $delete->id);
                        return $this->responseCode($data, 'Successfully Delete Data');
                    }
                });
            } catch (Exception $error) {
                $result = $this->responseCode(['message' => 'Error Sistem'], $error, 500);
            }
        } else {
            $collect = collect($validator->errors());
            $result = $this->customError($collect);
        }

        return $result;
    }
}
