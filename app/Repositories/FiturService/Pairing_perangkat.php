<?php

namespace App\Repositories\FiturService;

use App\Http\Resources\ListPairingResource;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Pairing_perangkat
{
    public function listPairing($param, $modelPairing, $builder, $user)
    {
        try {
            $user = $user->authentikasi();
            $modelPairing = DB::table('table_pairing')->where('table_users_id', $user->id)->get();
            $result = $builder->successOk(ListPairingResource::collection($modelPairing));
        } catch (Exception $error) {
            $result = $builder->error500(['message' => 'errors sistem'], $error);
        }

        return $result;
    }
    public function get_pairing($watt, $volt, $ampere, $key, $user, $modelPairing, $builder)
    {
        try {
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
                $pairing = $builder->error422(['message' => 'param di lengkapi']);
            }
        } catch (Exception $error) {
            $pairing = $builder->error500(['message' => 'request header invalid'], $error);
        }
        return $pairing;
    }
    public function pairingPerangkat($param, $modelPairing, $builder, $user)
    {
        try {
            $costum = [
                'required' => ':attribute jangan di kosongkan',
                'unique' => 'perangkat sudah terpairing',
                'numeric' => 'harus angka',
            ];
            $validator = Validator::make($param->all(), [
                'key' => 'unique:table_pairing,key|numeric'
            ], $costum);

            if ($validator->fails()) {
                $result = $builder->error422(['errros' => $validator->errors()]);
            } else {
                $user = $user->authentikasi();
                $result = $this->get_pairing($param->watt, $param->volt, $param->ampere, $param->key, $user, $modelPairing, $builder);
            }
        } catch (Exception $error) {
            $result = $builder->error500(['request header invalid'], $error);
        }

        return $result;
    }
}
