<?php

namespace App\Repositories\FiturService;

use Illuminate\Support\Facades\Validator;

class Hemat_daya
{
    public function hemat($param, $builder)
    {
        $costum = [
            'integer' => 'harus angka'
        ];
        $validasi = Validator::make($param->all(), [
            'beban' => 'integer',
            'watt' => 'integer',
            'ampere' => 'integer',
            'volt' => 'integer',
            'idDevices' => 'integer'
        ], $costum);

        if ($validasi->fails()) {
            $result = $builder->responData(['errors' => $validasi->errors()], 422, 'failed request');
        } else {
            $data = $param->only('beban', 'watt', 'ampere', 'volt');
            if ($param->beban == null) {
                $data['beban'] = '';
            }
            $result = $builder->responData($data);
        }

        return $result;
    }
}
