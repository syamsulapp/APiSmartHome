<?php

namespace App\Http\Controllers;

use App\Repositories\FiturService\PairingRepository;
use Illuminate\Http\Request;

class PairingController extends Controller
{
    protected $pairingRepository;

    public function __construct(PairingRepository $pairingRepository)
    {
        $this->pairingRepository = $pairingRepository;
    }

    public function index(Request $index)
    {
        return $this->pairingRepository->index($index);
    }

    public function store(Request $store)
    {
        return $this->pairingRepository->store($store);
    }
}
