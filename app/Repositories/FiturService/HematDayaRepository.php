<?php

namespace App\Repositories\FiturService;

use App\Models\Devices_models;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class HematDayaRepository extends BaseRepository
{
    // model devices
    protected $modelDevices;

    // value beban Max
    protected $bebanMax = 414;

    //value beban middle
    protected $bebanMiddle = 313;

    // carbon property
    protected $carbon;


    public function __construct(Devices_models $modelDevices, Carbon $carbon)
    {
        $this->modelDevices = $modelDevices;
        $this->carbon = $carbon;
    }

    public function save($save)
    {
        $validator = Validator::make($save->all(), [
            'key' => 'numeric|required'
        ]);
        if (!$validator->fails()) {
            $result = $this->modelDevices->when($save->key, function ($query) use ($save) {
                $keyDevices = $query
                    ->where('table_pairing_key', $save->key)
                    ->first();
                if ($keyDevices) {
                    if ($keyDevices->watt <= $this->bebanMax && $this->bebanMiddle >= $keyDevices->watt) {
                        $query
                            ->where('table_pairing_key', $save->key)
                            ->update(['table_status_devices_key_status_perangkat' => 1]);
                        return $this->responseCode(['message' => 'nyala']);
                    } else if ($keyDevices->watt >= $this->bebanMiddle && $keyDevices->watt <= $this->bebanMax) {
                        $minute = $this->carbon->now();
                        // focus fix code
                        if (!$minute->addMinute(10)) {
                            $query
                                ->where('table_pairing_key', $save->key)
                                ->update(['table_status_devices_key_status_perangkat' => 1]);
                            return $this->responseCode(['message' => 'nyala']);
                        } else {
                            $query
                                ->where('table_pairing_key', $save->key)
                                ->update(['table_status_devices_key_status_perangkat' => 2]);
                            return $this->responseCode(['message' => 'mati']);
                        }
                    } else {
                        $query
                            ->where('table_pairing_key', $save->key)
                            ->update(['table_status_devices_key_status_perangkat' => 2]);
                        return $this->responseCode(['message' => 'mati']);
                    }
                } else {
                    return $this->responseCode(['message' => 'id tidak di temukan'], 'id not found', 422);
                }
            });
        } else {
            $collect = collect($validator->errors());
            $result = $this->customError($collect);
        }
        return $result;
    }
}
