<?php
namespace App\Models;

class RefAbsenIjin extends \Eloquent
{
    protected $table = 'ref_absen_ijin';

    // Don't forget to fill this array
    protected $fillable = [
        'kode_absen_ijin',
        'nama_absen_ijin',
        'nama_ijin_payroll',
        'kode_ijin_payroll'
    ];
    protected $guarded = ['kode_absen_ijin'];

    protected $hidden = [''];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'kode_absen_ijin',
        'nama_absen_ijin',
        'nama_ijin_payroll',
        'kode_ijin_payroll'
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['kode_absen_ijin'];


}
