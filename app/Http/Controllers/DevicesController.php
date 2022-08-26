<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\JsonBuilder\ReturnResponse;
use App\Models\Devices_models;
use App\Models\Otomatisasi_perangkat as ModelsOtomatisasi_perangkat;
use App\Models\Pairing_devices;
use App\Models\Schedule_perangkat as ModelsSchedule_perangkat;
use App\Models\User;
use App\Repositories\FiturService\Detail_devices;
use App\Repositories\FiturService\List_devices;
use App\Repositories\FiturService\masterData\crudDevices;
use App\Repositories\FiturService\Otomatisasi_perangkat;
use App\Repositories\FiturService\Pairing_perangkat;
use App\Repositories\FiturService\Schedule_perangkat;
use Illuminate\Http\Request;

class DevicesController extends Controller
{
    public function __construct(crudDevices $crudDevices, User $user, ReturnResponse $builder, ModelsOtomatisasi_perangkat $modelOtomatisasi, ModelsSchedule_perangkat $modelSchedule, Pairing_devices $pairing, Devices_models $modelDevices, Detail_devices $detailDevices, Otomatisasi_perangkat $otomatisasiPerangkat, Schedule_perangkat $schedulePerangkat, Pairing_perangkat $pairingPerangkat, List_devices $listDevices)
    {
        $this->detail_devices = $detailDevices;
        $this->otomatisasi_perangkat = $otomatisasiPerangkat;
        $this->schedule_perangkat = $schedulePerangkat;
        $this->pairing_perangkat = $pairingPerangkat;
        $this->list_devices = $listDevices;
        $this->modelDevices = $modelDevices;
        $this->modelPairing = $pairing;
        $this->modelSchedule = $modelSchedule;
        $this->modelOtomatisasi = $modelOtomatisasi;
        $this->respon = $builder;
        $this->user = $user;
        $this->crudDevices = $crudDevices;
    }

    /** method crud devices */
    public function add_devices(Request $param)
    {
        return $this->crudDevices->add_devices($param, $this->modelPairing, $this->respon, $this->modelDevices, $this->user);
    }
    public function update_devices(Request $param)
    {
        return $this->crudDevices->update_devices($param);
    }
    public function delete_devices(Request $param)
    {
        return $this->crudDevices->delete_devices($param);
    }
    /** method role Users */


    /** method schedule perangkat */


    /** method otomatisasi perangkat */

    public function listDevices(Request $param)
    {
        return $this->list_devices->listDevices($param, $this->modelDevices, $this->respon, $this->user);
    }

    public function detailDevices(Request $param)
    {
        return $this->detail_devices->detailDevices($param, $this->modelDevices, $this->respon, $this->user);
    }

    public function pairingPerangkat(Request $param)
    {
        return $this->pairing_perangkat->pairingPerangkat($param, $this->modelPairing, $this->respon, $this->user);
    }

    public function listPairing(Request $param)
    {
        return $this->pairing_perangkat->listPairing($param, $this->modelPairing, $this->respon, $this->user);
    }

    public function schedulePerangkat(Request $param)
    {
        return $this->schedule_perangkat->schedulePerangkat($param, $this->modelSchedule);
    }

    public function otomatisasiPerangkat(Request $param)
    {
        return $this->otomatisasi_perangkat->otomatisasiPerangkat($param, $this->modelOtomatisasi);
    }
}
