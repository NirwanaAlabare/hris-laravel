<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherBazzar extends Model
{
    use HasFactory;


    protected $table = 'voucher_bazzar';


    protected $fillable = [
        'nomor_voucher',
        'id_pengajuan_bazzar',
        'enroll_id',
        'nominal',
    ];

    protected $casts = [
        'nomor_voucher',
        'id_pengajuan_bazzar',
        'enroll_id',
        'nominal',
    ];

    public function employee(){
        return $this->belongsTo(EmployeeAtribut::class,'enroll_id','enroll_id');
    }
}
