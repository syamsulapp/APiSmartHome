<?php

namespace App\Repositories\FiturService;

use App\Models\Hemat_Daya_Models;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Hemat_daya
{
    protected $hematDaya;

    public function __construct(Hemat_Daya_Models $hematDaya)
    {
        $this->hematDaya = $hematDaya;
    }

    public function store(Request $param)
    {
        $req = Validator::make($param->all(), [
            'key' => 'required|integer'
        ]);

        if (!$req->fails()) {
        } else {
        }
    }

    public function hemat($param, $builder)
    {
        $data = $this->hematDaya->when($param->search, function ($query) use ($param) {
        });
    }
}
