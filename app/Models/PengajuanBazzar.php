<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanBazzar extends Model
{
    use HasFactory;


    protected $table = 'pengajuan_bazzar';


    protected $fillable = [
        'id',
        'enroll_id',
        'jumlah',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id',
        'enroll_id',
        'jumlah',
        'status',
        'created_at',
        'updated_at',
    ];


}
