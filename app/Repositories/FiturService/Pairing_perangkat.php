<?php

namespace App\Repositories\FiturService;

use Exception;
use Illuminate\Support\Facades\Validator;

class Pairing_perangkat
{
    public function get_pairing($watt, $volt, $ampere, $key, $user, $modelPairing)
    {
        if ($watt && $volt  && $ampere && $key && $user != null) {
            $pairing['key'] = $key;
            $pairing['watt']  = $watt;
            $pairing['ampere']  = $ampere;
            $pairing['volt'] = $volt;
            $pairing['table_users_id'] = $user->id;

            $modelPairing::create($pairing);
        } else {
            $pairing = 'param harus di lengkapi';
        }
        return $pairing;
    }
    public function pairingPerangkat($param, $modelPairing, $builder, $user)
    {
        try {
            $costum = [
                'required' => ':attribute jangan di kosongkan',
                'unique' => 'perangkat sudah terpairing',
            ];
            $validator = Validator::make($param->all(), [
                'id' => 'required',
                'key' => 'unique:table_pairing,key'
            ], $costum);

            if ($validator->fails()) {
                $result = $builder->responData(['errros' => $validator->errors()], 422, 'failed request');
            } else {
                $user = $user->authentikasi();
                if ($param->id == $user->id) {
                    $data = $this->get_pairing($param->watt, $param->volt, $param->ampere, $param->key, $user, $modelPairing);
                    $result = $builder->responData(['devices' => $data], 200, 'Successfully Pairing');
                } else {
                    $result = $builder->responData(['errors' => 'id salah'], 422, 'id salah');
                }
            }
        } catch (Exception $error) {
            $result = $builder->responData(['errors response'], 500, $error);
        }

        return $result;
    }
}
