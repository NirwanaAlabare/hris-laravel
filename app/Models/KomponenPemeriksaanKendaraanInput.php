<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenPemeriksaanKendaraanInput extends Model
{
    use HasFactory;
    protected $table = 'komponen_pemeriksaan_kendaraan_input';
    protected $fillable = ['nama_item_pemeriksaan_detail','tipe','sort','nama_item_list','id_komponen_pemeriksaan_kendaraan'];
}
