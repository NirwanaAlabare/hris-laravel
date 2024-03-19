<?php
namespace App\Models;

class RefHariLibur extends \Eloquent
{
    protected $table = 'ref_hari_libur';

    // Don't forget to fill this array
    protected $fillable = [
        'id',
        'nama_hari_libur',
        'tanggal_libur',
        'status_absen'
    ];
    protected $guarded = ['id'];

    protected $hidden = ['id'];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id',
        'nama_hari_libur',
        'tanggal_libur',
        'status_absen'
    ];

    protected $appends = [];

}
