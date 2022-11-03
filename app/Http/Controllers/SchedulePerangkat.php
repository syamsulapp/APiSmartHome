<?php

namespace App\Http\Controllers;

use App\Repositories\FiturService\ScheduleRepository;
use Illuminate\Http\Request;

class SchedulePerangkat extends Controller
{
    protected $repoSchedule;

    public function __construct(ScheduleRepository $repoSchedule)
    {
        $this->repoSchedule = $repoSchedule;
    }

    public function index(Request $index)
    {
        return $this->repoSchedule->index($index);
    }

    public function store(Request $store)
    {
        return $this->repoSchedule->store($store);
    }

    public function update(Request $update)
    {
        return $this->repoSchedule->update($update);
    }

    public function delete(Request $delete)
    {
        return $this->repoSchedule->delete($delete);
    }

    public function set(Request $set)
    {
        return $this->repoSchedule->set($set);
    }

    public function log(Request $log)
    {
        return $this->repoSchedule->log($log);
    }
}
