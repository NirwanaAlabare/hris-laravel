<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenPemeriksaanKendaraan extends Model
{
    use HasFactory;
    protected $table = 'komponen_pemeriksaan_kendaraan';
    protected $fillable = ['nama_item_pemeriksaan','sort'];
}
