<?php
namespace App\Models;

class CheckInOut extends \Eloquent
{
    protected $table = 'checkinout';

    // Don't forget to fill this array
    protected $fillable = [
        'uuid',
        'tanggal_absen',
        'enroll_id',
        'type',
        'absen_in',
        'absen_out',
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
        'uuid',
        'tanggal_absen',
        'enroll_id',
        'type',
        'absen_in',
        'absen_out',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['uuid', 'tanggal_absen', 'enroll_id'];

}
