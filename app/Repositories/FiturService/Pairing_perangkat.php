<?php

namespace App\Repositories\FiturService;

use Exception;
use Illuminate\Support\Facades\Validator;

class Pairing_perangkat
{
    public function get_pairing($watt, $volt, $ampere, $user)
    {
        if ($watt && $volt  && $ampere  && $user != null) {
            $pairing['watt']  = $watt;
            $pairing['ampere']  = $volt;
            $pairing['volts'] = $ampere;
            $pairing['users'] = [
                'id' => $user->id,
                'nama' => $user->name,
            ];
        } else {
            $pairing = 'tidak boleh kosong';
        }
        return $pairing;
    }
    public function pairingPerangkat($param, $modelPairing, $builder, $user)
    {
        try {
            $costum = [
                'required' => ':attribute jangan di kosongkan'
            ];
            $validator = Validator::make($param->all(), [
                'id' => 'required'
            ], $costum);

            if ($validator->fails()) {
                $result = $builder->responData(['errros' => $validator->errors()], 422, 'failed request');
            } else {
                $user = $user->authentikasi();
                if ($param->id == $user->id) {
                    $data = $this->get_pairing($param->watt, $param->volt, $param->ampere, $user);
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
