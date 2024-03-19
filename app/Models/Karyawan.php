<?php
namespace App\Models;

class Karyawan extends \Eloquent
{
    protected $table = 'master_data_absen_kehadiran';

    // Don't forget to fill this array
    protected $fillable = [];
    protected $guarded = ['employee_id'];

    protected $hidden = [];
    protected $appends = [];

    /**
     * @return mixed
     */
    public static function getKaryawan()
    {
        ///
    }
}
