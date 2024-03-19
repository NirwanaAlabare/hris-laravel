<?php
namespace App\Models;

class DasarPotBpjs extends \Eloquent
{
    protected $table = 'dasar_pot_bpjs';

    // Don't forget to fill this array
    protected $fillable = [
        'kode_dasar_pot_bpjs',
        'nama_dasar_pot_bpjs',
        'dasar_pot_bpjs_rupiah',
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
        'kode_dasar_pot_bpjs',
        'nama_dasar_pot_bpjs',
        'dasar_pot_bpjs_rupiah',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['kode_dasar_pot_bpjs'];

}
