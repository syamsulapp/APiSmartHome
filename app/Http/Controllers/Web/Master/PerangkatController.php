<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Controller;
use App\Repositories\FiturService\masterData\PerangkatRepository;
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

    public function show($id)
    {
        return $this->perangkatRepository->show($id);
    }
}
