<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Controller;
use App\Repositories\FiturService\masterData\ScheduleRepository;
use Illuminate\Http\Request;

class SchedulePerangkatController extends Controller
{
    protected $scheduleRepository;

    public function __construct(ScheduleRepository $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function index(Request $index)
    {
        return $this->scheduleRepository->index($index);
    }

    public function store(Request $store)
    {
        return $this->scheduleRepository->store($store);
    }

    public function update(Request $update)
    {
        return $this->scheduleRepository->update($update);
    }

    public function delete(Request $delete)
    {
        return $this->scheduleRepository->delete($delete);
    }
}
