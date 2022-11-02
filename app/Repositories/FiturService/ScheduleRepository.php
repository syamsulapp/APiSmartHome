<?php

namespace App\Repositories\FiturService;

use App\Models\ClientKey;
use App\Models\Devices_models;
use App\Models\ScheduleModels;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;

class ScheduleRepository extends BaseRepository
{
    protected $modelschedule;

    protected $clientKey;

    protected $modelDevices;

    public function __construct(ScheduleModels $modelschedule, ClientKey $clientKey, Devices_models $modelDevices)
    {
        $this->modelschedule = $modelschedule;

        $this->clientKey = $clientKey;

        $this->modelDevices = $modelDevices;
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
        $validator = Validator::make($set->all(), [
            'key' => 'required|numeric',
            'id_schedule' => 'required|numeric'
        ]);
        if (!$validator->fails()) {
            $result = $this->modelDevices->when($set->id_schedule, function ($query) use ($set) {
                $idSchedule = $this->modelschedule
                    ->where('key_status_table_perangkat', $set->id_schedule)
                    ->first();
                $checkClientKey = $this->clientKey->where('client_key', $set->header('IOT-CLIENT-KEY'))->first();
                if ($set->header('IOT-CLIENT-KEY') && $checkClientKey) {
                    if ($idSchedule && $query->where('table_pairing_key', $set->key)->first()) {
                        $query
                            ->where('table_pairing_key', $set->key)
                            ->update(
                                [
                                    'table_schedule_devices_key_status_table_perangkat' => $idSchedule->key_status_table_perangkat,
                                ]
                            );
                    } else {
                        return $this->responseCode(['message' => 'key atau schedule salah'], 'not found', 4222);
                    }
                } else {
                    return $this->responseCode(['message' => 'wrong client key'], 'invalid client key', 422);
                }
                return $this->responseCode(['message' => 'Schedule is sets']);
            });
        } else {
            $collect = collect($validator->errors());
            $result = $this->customError($collect);
        }
        return $result;
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
                $result = $this->modelschedule->when($update->id, function ($query) use ($update) {
                    $checkId = $query->where('key_status_table_perangkat', $update->id)->first();
                    if ($checkId) {
                        $data = $update->only('start_at', 'end_at');
                        $query
                            ->where('key_status_table_perangkat', $update->id)
                            ->update($data);
                        return $this->responseCode(['message' => 'successfully update schedule']);
                    } else {
                        return $this->responseCode(['message' => 'id tidak ada'], 'id not found', 422);
                    }
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
            $result = $this->modelschedule->when($delete->id, function ($query) use ($delete) {
                $checkId = $query->where('key_status_table_perangkat', $delete->id)->first();
                if ($checkId) {
                    $query->delete('key_status_table_perangkat', $delete->id);
                } else {
                    return $this->responseCode(['message' => 'id tidak ada '], 'id not found', 422);
                }
                return $this->responseCode(['message' => 'successfully delete schedule']);
            });
        } else {
            $collect = collect($validate->errors());
            $result = $this->customError($collect);
        }

        return $result;
    }
}
