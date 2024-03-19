<?php
namespace App\Models;
use Alfa6661\AutoNumber\AutoNumberTrait;

class LogDataGagalAbsen extends \Eloquent
{
    use AutoNumberTrait;

    protected $table = 'log_data_gagal_absen';

    // Don't forget to fill this array
    protected $fillable = [
        'uuid',
        'uuid_master',
        'nomor_form_gagal_absen',
        'tanggal_absen',
        'kode_hari',
        'nama_hari',
        'enroll_id',
        'employee_id',
        'nik',
        'employee_name',
        'kode_shift',
        'department_id',
        'department_name',
        'sub_dept_id',
        'sub_dept_name',
        'jadwal_masuk_kerja',
        'jadwal_pulang_kerja',
        'status_absen',
        'status_absen_old',
        'absen_in',
        'absen_out',
        'absen_in_old',
        'absen_out_old',
        'absen_alasan',
        'absen_alasan_old',
        'is_approved',
        'operator',
        'created_at'
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
        'uuid_master',
        'nomor_form_gagal_absen',
        'tanggal_absen',
        'kode_hari',
        'nama_hari',
        'enroll_id',
        'employee_id',
        'nik',
        'employee_name',
        'kode_shift',
        'department_id',
        'department_name',
        'sub_dept_id',
        'sub_dept_name',
        'jadwal_masuk_kerja',
        'jadwal_pulang_kerja',
        'status_absen',
        'status_absen_old',
        'absen_in',
        'absen_out',
        'absen_in_old',
        'absen_out_old',
        'absen_alasan',
        'absen_alasan_old',
        'is_approved',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['uuid'];

    /**
     * Return the autonumber configuration array for this model.
     *
     * @return array
     */
    public function getAutoNumberOptions()
    {
        return [
            'nomor_form_gagal_absen' => [
                'format' => 'FKA/2208/?', // Format kode yang akan digunakan.
                'length' => 4 // Jumlah digit yang akan digunakan sebagai nomor urut
            ]
        ];
    }
}
