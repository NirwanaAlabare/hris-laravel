<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLabor extends Model
{
    protected $table = 'daily_labor';
    use HasFactory;
    protected $fillable = [
        'id',
        'tanggal_berjalan',
        'enroll_id',
        'department',
        'group_department',
        'net_wages',
        'overtime',
        'incentive',
        'bpjs_ks',
        'bpjs_tk',
        'thr',
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
