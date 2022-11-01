<?php

namespace App\Http\Controllers;

use App\Repositories\FiturService\PerangkatRepository;
use Illuminate\Http\Request;

class PerangkatController extends Controller
{
    protected $perangkatRepository;

    public function __construct(PerangkatRepository $perangkatRepository)
    {
        $this->perangkatRepository = $perangkatRepository;
    }

    public function index(Request $index)
    {
        return $this->perangkatRepository->index($index);
    }

    public function store(Request $store)
    {
        return $this->perangkatRepository->store($store);
    }

    public function update(Request $update)
    {
        return $this->perangkatRepository->update($update);
    }
}
