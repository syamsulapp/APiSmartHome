<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Controller;
use App\Repositories\FiturService\masterData\OtomatisasiRepository;
use Illuminate\Http\Request;

class OtomatisasiPerangkatController extends Controller
{
    protected $otomatisasiRepository;

    public function __construct(OtomatisasiRepository $otomatisasiRepository)
    {
        $this->otomatisasiRepository = $otomatisasiRepository;
    }

    public function index(Request $index)
    {
        return $this->otomatisasiRepository->index($index);
    }

    public function store(Request $store)
    {
        return $this->otomatisasiRepository->store($store);
    }

    public function update(Request $update)
    {
        return $this->otomatisasiRepository->update($update);
    }

    public function delete(Request $delete)
    {
        return $this->otomatisasiRepository->delete($delete);
    }
}
