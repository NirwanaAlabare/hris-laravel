<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleItem extends Model
{
    use HasFactory;
    protected $table = 'vehicle_item';
    protected $fillable = ['id','nama_barang','quantity_pemeliharaan','satuan_pemeliharaan'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
