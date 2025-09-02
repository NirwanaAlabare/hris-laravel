<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterKendaraan extends Model
{
    use HasFactory;

    protected $table = 'ga_master_kendaraan';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'plat_no',
        'thn_pembuatan',
        'no_mesin',
        'no_rangka',
        'merk',
        'tipe',
        'warna',
        'jns_bhn_bakar',
        'isi_silinder',
        'status',
        'cancel',
        'created_by',
    ];

}
