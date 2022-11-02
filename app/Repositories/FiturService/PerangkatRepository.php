<?php

namespace App\Repositories\FiturService;

use App\Http\Resources\listDevicesResource;
use App\Models\ClientKey;
use App\Models\Devices_models;
use App\Models\Pairing_devices;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;

class PerangkatRepository extends BaseRepository
{
    // models devices
    protected $modelDevices;

    // models user
    protected $user;

    // models ciient key
    protected $clientKey;

    // models ciient key
    protected $modelPair;

    public function __construct(Devices_models $modelDevices, User $user, ClientKey $clientKey, Pairing_devices $modelPair)
    {
        $this->modelDevices = $modelDevices;
        $this->user = $user;
        $this->clientKey = $clientKey;
        $this->modelPair = $modelPair;
    }

    public function userAuth()
    {
        return $this->user->authentikasi()->id;
    }

    public function index($index)
    {
        $limit = 50;

        if ($limit >= $index->limit) {
            $limit = $index->limit;
        }
        $data = $this->modelDevices->when($index->name, function ($query) use ($index) {
            return $query->where('name', 'LIKE', "%{$index->name}%");
        })->when($index->id, function ($query) use ($index) {
            return $query->where('no', $index->id);
        })
            ->whereIn('table_users_id', [$this->userAuth()])
            ->orderBy('no')
            ->paginate($limit);
        return $this->responseCode(listDevicesResource::collection($data->items()));
    }

    public static function insert($checkKeyPair)
    {
        return array(
            'table_pairing_key' => $checkKeyPair->key,
            'volt' => $checkKeyPair->volt,
            'watt' => $checkKeyPair->watt,
            'ampere' => $checkKeyPair->ampere,
            'table_users_id' => User::authentikasi()->id,
            'table_status_devices_key_status_perangkat' => 1,
            'table_schedule_devices_key_status_table_perangkat' => 1,
        );
    }

    public function store($store)
    {
        $validate = Validator::make($store->all(), [
            'key' => 'required|numeric|unique:table_devices,table_pairing_key'
        ]);

        if (!$validate->fails()) {
            $checkClientKey = $this->clientKey->where('client_key', $store->header('IOT-CLIENT-KEY'))->first();
            if ($store->header('IOT-CLIENT-KEY') && $checkClientKey) {
                $result = $this->modelDevices->when($store->key, function ($query) use ($store) {
                    $checkKeyPair = $this->modelPair->where('key', $store->key)->first();
                    if ($checkKeyPair) {
                        $data = $this->insert($checkKeyPair);
                        $query->create($data);
                        return $this->responseCode(['message' => 'successfully add devices']);
                    } else {
                        return  $this->responseCode(['message' => 'key tidak ada'], 'Key Not Found', 422);
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



    public function update($update)
    {
        $validate = Validator::make($update->all(), [
            'key' => 'required|numeric',
            'name' => 'string',
            'schedule' => 'numeric',
            'saklar' => 'numeric'
        ]);

        if (!$validate->fails()) {
            $checkClientKey = $this->clientKey->where('client_key', $update->header('IOT-CLIENT-KEY'))->first();
            if ($update->header('IOT-CLIENT-KEY') && $checkClientKey) {
                $result = $this->modelDevices->when($update->name, function ($query) use ($update) {
                    $data = $update->only('name');
                    $query
                        ->where('table_pairing_key', $update->key)
                        ->update($data);
                    return $this->responseCode(['message' => 'successfully update nama']);
                }, function ($query) use ($update) {
                    $query
                        ->where('table_pairing_key', $update->key)
                        ->update(['table_status_devices_key_status_perangkat' => $update->saklar]);
                    return $this->responseCode(['message' => 'successfully update saklar']);
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
}
