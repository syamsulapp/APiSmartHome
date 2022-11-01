<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\JsonBuilder\ReturnResponse;
use App\Models\Devices_models;
use App\Models\ModelsRole;
use App\Models\Otomatisasi_perangkat as ModelsOtomatisasi_perangkat;
use App\Models\Pairing_devices;
use App\Models\Schedule_perangkat as ModelsSchedule_perangkat;
use App\Models\ScheduleModels;
use App\Models\User;
use App\Repositories\FiturService\Detail_devices;
use App\Repositories\FiturService\Hemat_daya;
use App\Repositories\FiturService\List_devices;
use App\Repositories\FiturService\masterData\crudDevices;
use App\Repositories\FiturService\Otomatisasi_perangkat;
use App\Repositories\FiturService\Pairing_perangkat;
use App\Repositories\FiturService\Schedule_perangkat;
use Illuminate\Http\Request;

class DevicesController extends Controller
{
    public function __construct(Hemat_daya $hematDayaRepository, ScheduleModels $scheduleRepository, ModelsRole $role_model, crudDevices $crudDevicesRepository, User $user, ReturnResponse $builder, ModelsOtomatisasi_perangkat $modelOtomatisasi, ModelsSchedule_perangkat $modelSchedule, Pairing_devices $pairing, Devices_models $modelDevices, Detail_devices $detailDevicesRepository, Otomatisasi_perangkat $otomatisasiPerangkatRepository, Schedule_perangkat $schedulePerangkat, Pairing_perangkat $pairingPerangkatRepository, List_devices $listDevicesRepository)
    {
        $this->detail_devices = $detailDevicesRepository;
        $this->otomatisasi_perangkat = $otomatisasiPerangkatRepository;
        $this->schedule_perangkat = $schedulePerangkat;
        $this->pairing_perangkat = $pairingPerangkatRepository;
        $this->list_devices = $listDevicesRepository;
        $this->modelDevices = $modelDevices;
        $this->modelPairing = $pairing;
        $this->modelSchedule = $modelSchedule;
        $this->modelOtomatisasi = $modelOtomatisasi;
        $this->respon = $builder;
        $this->user = $user;
        $this->crudDevices = $crudDevicesRepository;
        $this->role = $role_model;
        $this->schedule = $scheduleRepository;
        $this->hemat_daya = $hematDayaRepository;
    }

    /**
     * begin master data API
     * (otomatisasi perangkat, schedule perangkat, role user, devices)
     */
    /** method crud devices */
    public function add_devices(Request $param)
    {
        return $this->crudDevices->add_devices($param, $this->modelPairing, $this->respon, $this->modelDevices, $this->user);
    }

    /**
     * END (routes api master data)
     */

    /** fitur api */
    public function listDevices(Request $param)
    {
        return $this->list_devices->listDevices($param, $this->modelDevices, $this->respon, $this->user);
    }

    public function detailDevices(Request $param)
    {
        return $this->detail_devices->detailDevices($param, $this->modelDevices, $this->respon, $this->user, $this->role, $this->modelOtomatisasi, $this->modelSchedule);
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
        return $this->schedule_perangkat->schedulePerangkat($param, $this->user, $this->respon, $this->modelDevices, $this->modelSchedule);
    }

    public function otomatisasiPerangkat(Request $param)
    {
        return $this->otomatisasi_perangkat->otomatisasiPerangkat($param, $this->modelOtomatisasi);
    }

    public function hematDaya(Request $param)
    {
        return $this->hemat_daya->hemat($param, $this->respon);
    }
}
