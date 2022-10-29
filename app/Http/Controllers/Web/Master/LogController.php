<?php

namespace App\Http\Controllers\Web\Master;

use App\Http\Controllers\Controller;
use App\Repositories\FiturService\masterData\LogRepository;
use Illuminate\Http\Request;

class LogController extends Controller
{
    protected $logRepository;

    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    public function index(Request $index)
    {
        return $this->logRepository->index($index);
    }
    public function show($id)
    {
        return $this->logRepository->show($id);
    }
}
