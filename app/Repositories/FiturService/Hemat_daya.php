<?php

namespace App\Repositories\FiturService;

use Illuminate\Support\Facades\Validator;

class Hemat_daya
{
    public function hemat($param, $builder)
    {
        $costum = [
            'required' => 'jangan di kosongkan'
        ];
        $validasi = Validator::make($param->all(), [
            'beban' => 'required'
        ], $costum);

        if ($validasi->fails()) {
            $result = $builder->responData(['errors' => $validasi->errors()], 422, 'failed request');
        } else {
            $result = $builder->responData($param->all());
        }

        return $result;
    }
}
