<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BMasterCC extends Model
{
    protected $table = 'b_master_cc';
    protected $fillable = [
        'id','no_cc','cc_name','id_cc','group1','id_group1','group2','id_group2','group21','id_group3','profit_center','id_pc','status','coa_gaji','coa_tunj','coa_lembur','coa_bonus'
    ];
}
