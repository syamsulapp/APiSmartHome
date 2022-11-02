<?php

namespace App\Repositories\FiturService;

use App\Models\ClientKey;
use App\Models\ScheduleModels;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;

class ScheduleRepository extends BaseRepository
{
    protected $modelschedule;

    protected $clientKey;

    public function __construct(ScheduleModels $modelschedule, ClientKey $clientKey)
    {
        $this->modelschedule = $modelschedule;

        $this->clientKey = $clientKey;
    }

    public function userAuth()
    {
        return User::authentikasi()->id;
    }

    public function index($index)
    {
        $limit = 50;
        if ($limit >= $index->limit) {
            $limit = $index->limit;
        }
        $data = $this->modelschedule->when($index->start_at, function ($query) use ($index) {
            return $query->where('start_at', 'LIKE', "%{$index->start_at}%");
        })->when($index->end_at, function ($query) use ($index) {
            return $query->where('end_at', $index->end_at);
        })
            ->whereIn('table_users_id', [$this->userAuth()])
            ->orderBy('key_status_table_perangkat')
            ->paginate($limit);
        return $this->responseCode($data->items());
    }

    public function set($set)
    {
    }

    public function store($store)
    {
        $validate = Validator::make($store->all(), [
            'start_at' => 'required|date_format:H:i:s|unique:table_schedule_devices,key_status_table_perangkat',
            'end_at' => 'required|date_format:H:i:s|unique:table_schedule_devices,key_status_table_perangkat'
        ]);

        if (!$validate->fails()) {
            $checkClientKey = $this->clientKey->where('client_key', $store->header('IOT-CLIENT-KEY'))->first();
            if ($store->header('IOT-CLIENT-KEY') && $checkClientKey) {
                $result = $this->modelschedule->when($store->start_at, function ($query) use ($store) {
                    $data = $store->only('start_at', 'end_at', 'table_users_id');
                    $data['table_users_id'] = $this->userAuth();
                    $query->create($data);
                    return $this->responseCode(['message' => 'successfully created schedule']);
                });
            } else {
                $result = $this->responseCode(['message' => 'wrong client key'], 'Please Upgrade Your App', 422);
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
            'start_at' => 'required|date_format:H:i:s',
            'end_at' => 'required|date_format:H:i:s'
        ]);

        if (!$validate->fails()) {
            $checkClientKey = $this->clientKey->where('client_key', $update->header('IOT-CLIENT-KEY'))->first();
            if ($update->header('IOT-CLIENT-KEY') && $checkClientKey) {
                $result = $this->modelschedule->when($update->start_at, function ($query) use ($update) {
                    $data = $update->only('start_at', 'end_at');
                    $query
                        ->where('key_status_table_perangkat', $update->id)
                        ->update($data);
                    return $this->responseCode(['message' => 'successfully update schedule']);
                });
            } else {
                $result = $this->responseCode(['message' => 'wrong client key'], 'Please Upgrade Your App', 422);
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
            'id' => 'required|numeric'
        ]);

        if (!$validate->fails()) {
            $result = $this->modelschedule->when($delete->start_at, function ($query) use ($delete) {
                $query->delete('key_status_table_perangkat', $delete->id);
                return $this->responseCode(['message' => 'successfully delete schedule']);
            });
        } else {
            $collect = collect($validate->errors());
            $result = $this->customError($collect);
        }

        return $result;
    }
}
