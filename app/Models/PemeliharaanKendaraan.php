<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemeliharaanKendaraan extends Model
{
    use HasFactory;
    protected $table = 'jenis_pemeliharaan';
    protected $fillable = ['id','jenis_pemeliharaan','jadwal_pemeliharaan','km'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
