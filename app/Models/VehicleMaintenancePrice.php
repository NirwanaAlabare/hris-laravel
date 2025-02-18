<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMaintenancePrice extends Model
{
    use HasFactory;
    protected $table = 'vehicle_maintenance_price';
    protected $fillable = ['id','vehicle_maintenance_id','vehicle_order','vehicle_item_id','quantity','price'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
