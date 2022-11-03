<?php

namespace App\Http\Controllers;

use App\Repositories\FiturService\HematDayaRepository;
use Illuminate\Http\Request;

class HematDayaController extends Controller
{
    // hemat daya
    protected $repoHematDaya;

    public function __construct(HematDayaRepository $repoHematDaya)
    {
        $this->repoHematDaya = $repoHematDaya;
    }

    public function save(Request $save)
    {
        return $this->repoHematDaya->save($save);
    }
}
