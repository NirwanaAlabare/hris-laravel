<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $table = 'cities';
    protected $fillable = ['city_id','city_name','prov_id'];
    
    public function prov(){
        return $this->belongsTo(Province::class, 'prov_id', 'prov_id');
    }
}
