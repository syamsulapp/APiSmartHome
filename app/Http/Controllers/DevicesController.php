<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\FiturService\Detail_devices;
use App\Repositories\FiturService\List_devices;
use App\Repositories\FiturService\Otomatisasi_perangkat;
use App\Repositories\FiturService\Pairing_perangkat;
use App\Repositories\FiturService\Schedule_perangkat;
use Illuminate\Http\Request;

class DevicesController extends Controller
{
    public function __construct(Detail_devices $detailDevices, Otomatisasi_perangkat $otomatisasiPerangkat, Schedule_perangkat $schedulePerangkat, Pairing_perangkat $pairingPerangkat, List_devices $listDevices)
    {
        $this->detail_devices = $detailDevices;
        $this->otomatisasi_perangkat = $otomatisasiPerangkat;
        $this->schedule_perangkat = $schedulePerangkat;
        $this->pairing_perangkat = $pairingPerangkat;
        $this->list_devices = $listDevices;
    }

    public function listDevices(Request $param)
    {
        return $this->list_devices->listDevices($param);
    }

    public function detailDevices(Request $param)
    {
        return $this->detail_devices->detailDevices($param);
    }

    public function pairingPerangkat(Request $param)
    {
        return $this->pairing_perangkat->pairingPerangkat($param);
    }

    public function schedulePerangkat(Request $param)
    {
        return $this->schedule_perangkat->schedulePerangkat($param);
    }

    public function otomatisasiPerangkat(Request $param)
    {
        return $this->otomatisasi_perangkat->otomatisasiPerangkat($param);
    }
}
