<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Otomatisasi_perangkat extends Model
{
    use SoftDeletes;

    protected $table = 'table_status_devices';

    protected $fillable = ['status', 'keterangan', 'created_at', 'updated_at', 'deleted_at'];
}
