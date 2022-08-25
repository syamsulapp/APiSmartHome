<?php

namespace App\Repositories\FiturService;

use Illuminate\Support\Facades\Validator;

class List_devices
{
    public function listDevices($param, $model_devices, $builder)
    {
        $validasi = [
            'requried' => ':attribute jangan di kosongkan',
            'numeric' => 'harus angka',
        ];
        $validator = Validator::make($param->all(), [
            'key' => 'required|numeric'
        ], $validasi);

        if ($validator->fails()) {
            $result = $this->builder->responData(['errors' => $validator->errors()]);
        } else {
        }
    }
}
