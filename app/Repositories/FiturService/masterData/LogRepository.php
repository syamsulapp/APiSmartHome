<?php

namespace App\Repositories\FiturService\masterData;

use App\Models\LogModels;
use App\Repositories\BaseRepository;

class LogRepository extends BaseRepository
{
    protected $modelLog;

    public function __construct(LogModels $modelLog)
    {
        $this->modelLog = $modelLog;
    }
    public function index($index)
    {
        $limit = 50;

        if ($limit >= $index->limit) {
            $limit = $index->limit;
        }
        $data = $this->modelLog->when($index->aktivitas, function ($query) use ($index) {
            return $query->where('aktivitas', 'LIKE', "%{$index->aktivitas}%");
        })->when($index->id, function ($query) use ($index) {
            return $query->where('id', $index->id);
        })->when($index->ip, function ($query) use ($index) {
            return $query->where('ip', $index->ip);
        })
            ->orderBy('id')
            ->paginate($limit);
        return $this->responseCode($data->items());
    }
    public function show($id)
    {
        $data = $this->modelLog
            ->whereIn('id', [$id])
            ->get();
        return $this->responseCode($data);
    }
}
