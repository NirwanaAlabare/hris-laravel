<?php
namespace App\Models;

class DataClosingPayroll extends \Eloquent
{
    protected $table = 'data_closing_payroll';

    // Don't forget to fill this array
    protected $fillable = [
        'kode_closing',
        'nama_closing',
        'periode_payroll',
        'istemp',
        'ispermanent',
        'start_periode',
        'end_periode',
        'catatan',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $guarded = [];

    protected $hidden = [];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'kode_closing',
        'nama_closing',
        'periode_payroll',
        'istemp',
        'ispermanent',
        'start_periode',
        'end_periode',
        'catatan',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['kode_closing'];

}
