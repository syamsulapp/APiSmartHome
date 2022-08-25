<?php

namespace App\Repositories\FiturService;

use Exception;
use Illuminate\Support\Facades\Validator;

class Pairing_perangkat
{
    public function get_pairing($watt, $volt, $ampere, $key, $user, $modelPairing, $builder)
    {
        if ($watt && $volt  && $ampere && $key && $user != null) {
            $pairing['key'] = $key;
            $pairing['watt']  = $watt;
            $pairing['ampere']  = $ampere;
            $pairing['volt'] = $volt;
            $pairing['table_users_id'] = $user->id;

            $data = $modelPairing::create($pairing);
            $pairing = $builder->responData([
                'devices' => [
                    'key' => $data['key'],
                    'ampere' => $data['ampere'],
                    'watt' => $data['watt'],
                    'volt' => $data['volt'],
                    'users' => [
                        'id' => $user->id,
                        'name' => $user->name,
                    ]
                ]
            ], 200, 'Successfully Pairing');
        } else {
            $pairing = $builder->responData(['message' => 'param di lengkapi'], 422, 'failed request');
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
                    $result = $this->get_pairing($param->watt, $param->volt, $param->ampere, $param->key, $user, $modelPairing, $builder);
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
