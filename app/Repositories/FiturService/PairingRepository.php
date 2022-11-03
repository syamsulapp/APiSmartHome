<?php

namespace App\Repositories\FiturService;

use App\Http\Resources\ListPairingResource;
use App\Models\ClientKey;
use App\Models\LogModels;
use App\Models\Pairing_devices;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;

class PairingRepository extends BaseRepository
{
    // model pairing
    protected $model;

    // session user
    protected $user;

    //client key
    protected $clientKey;

    //log
    protected $log;

    public function __construct(Pairing_devices $model, User $user, ClientKey $clientKey, LogModels $log)
    {
        $this->model = $model;
        $this->user = $user;
        $this->clientKey = $clientKey;
        $this->log = $log;
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
        $data = $this->model->when($index->key, function ($query) use ($index) {
            return $query->where('key', 'LIKE', "%{$index->key}%");
        })->when($index->id, function ($query) use ($index) {
            return $query->where('table_users_id', $index->id);
        })
            ->whereIn('table_users_id', [$this->userAuth()])
            ->orderBy('key')
            ->paginate($limit);
        return $this->responseCode(ListPairingResource::collection($data->items()));
    }

    public static function logPairing($store, $key, $auth)
    {
        return array(
            'ip' => $store->ip(),
            'aktivitas' => 'Pairing devices ' . $key,
            'table_users_id' => $auth
        );
    }

    public function store($store)
    {
        $validate = Validator::make($store->all(), [
            'key' => 'numeric|required',
            'watt' => 'numeric|required',
            'volt' => 'numeric|required',
            'ampere' => 'numeric|required',
        ]);

        if (!$validate->fails()) {
            $checkClientKey = $this->clientKey->where('client_key', $store->header('IOT-CLIENT-KEY'))->first();
            if ($store->header('IOT-CLIENT-KEY') && $checkClientKey) {
                $result = $this->model->when($store->key, function ($query) use ($store) {
                    $dataPairing = $store->only('key', 'watt', 'ampere', 'volt', 'table_users_id');
                    $dataPairing['table_users_id'] = $this->userAuth();
                    $checkPair = $query->where('key', $store->key)->first();
                    if (!$checkPair) {
                        $query->create($dataPairing);
                        $data = $this->logPairing($store, $store->key, $this->userAuth());
                        $this->log->create($data);
                    } else {
                        return $this->responseCode(['message' => 'has benn pairing'], 'Has been paired', 422);
                    }
                    return $this->responseCode(['message' => 'successfully pairing devices']);
                });
            } else {
                $result = $this->responseCode(['message' => 'Wrong client key'], 'Please Upgreade Your App', 422);
            }
        } else {
            $collect = collect($validate->errors());
            $result = $this->customError($collect);
        }
        return $result;
    }
}
