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
    public function __construct(Hemat_daya $hematDaya, ScheduleModels $schedule, ModelsRole $role_model, crudDevices $crudDevices, User $user, ReturnResponse $builder, ModelsOtomatisasi_perangkat $modelOtomatisasi, ModelsSchedule_perangkat $modelSchedule, Pairing_devices $pairing, Devices_models $modelDevices, Detail_devices $detailDevices, Otomatisasi_perangkat $otomatisasiPerangkat, Schedule_perangkat $schedulePerangkat, Pairing_perangkat $pairingPerangkat, List_devices $listDevices)
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
        $this->role = $role_model;
        $this->schedule = $schedule;
        $this->hemat_daya = $hematDaya;
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
    /** method role Users */
    public function get_role()
    {
        return $this->crudDevices->get_role($this->role, $this->respon);
    }
    public function add_role(Request $param)
    {
        return $this->crudDevices->add_role($param, $this->role, $this->respon);
    }
    public function update_role(Request $param)
    {
        return $this->crudDevices->update_role($param, $this->role, $this->respon);
    }
    public function delete_role(Request $param)
    {
        return $this->crudDevices->delete_role($param, $this->role, $this->respon);
    }

    /** method schedule perangkat */
    public function get_schedule()
    {
        return $this->crudDevices->get_schedule($this->schedule, $this->respon);
    }
    public function add_schedule(Request $param)
    {
        return $this->crudDevices->add_schedule($param, $this->schedule, $this->respon);
    }
    public function update_schedule(Request $param)
    {
        return $this->crudDevices->update_schedule($param, $this->schedule, $this->respon);
    }
    public function delete_schedule(Request $param)
    {
        return $this->crudDevices->delete_schedule($param, $this->schedule, $this->respon);
    }

    /** method otomatisasi perangkat */

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
