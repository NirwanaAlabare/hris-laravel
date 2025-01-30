<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'districts';
    protected $fillable = ['dis_id','dis_name','city_id'];
    
    public function city(){
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }
}
