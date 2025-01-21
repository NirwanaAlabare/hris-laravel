<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * Tabel yang terkait dengan model.
     */
    protected $table = 'activity_logs';

    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = [
        'action_by_id',
        'action_by_name',
        'log_name',
        'table_name',
        'record_id',
        'action',
        'old_data',
        'new_data',
        'created_at',
        'updated_at',
    ];

    /**
     * Tipe data untuk atribut.
     */
    protected $casts = [
        'action_by_id',
        'action_by_name',
        'log_name',
        'table_name',
        'record_id',
        'action',
        'old_data',
        'new_data',
        'created_at',
        'updated_at',
    ];


}
