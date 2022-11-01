<?php

namespace App\Repositories\FiturService;

use App\Models\Pairing_devices;
use App\Models\User;
use App\Repositories\BaseRepository;

class PairingRepository extends BaseRepository
{
    // model pairing
    protected $model;

    // session user
    protected $user;

    public function __construct(Pairing_devices $model, User $user)
    {
        $this->model = $model;
        $this->user = $user;
    }

    public function index($index)
    {
        $limit = 50;

        if ($limit >= $index->limit) {
            $limit = $index->limit;
        }
        $data = $this->model->when($index->key, function ($query) use ($index) {
            return $query->where('key', 'LIKE', "%{$index->key}%");
        })->when($index->user, function ($query) use ($index) {
            return $query->where('table_users_id', $index->user);
        })
            ->orderBy('key')
            ->paginate($limit);
    }

    public function store($store)
    {
    }
}
