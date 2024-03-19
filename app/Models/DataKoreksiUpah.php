<?php
namespace App\Models;

class DataKoreksiUpah extends \Eloquent
{
    protected $table = 'data_koreksi_upah';

    // Don't forget to fill this array
    protected $fillable = [
        'uuid',
        'kode_koreksi_upah',
        'tanggal_koreksi',
        'enroll_id',
        'nik',
        'employee_name',
        'site_nirwana_id',
        'site_nirwana_name',
        'department_id',
        'department_name',
        'sub_dept_id',
        'sub_dept_name',
        'jumlah_rp_potongan',
        'periode_tanggal_koreksi',
        'keterangan',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at',
        'jenis_koreksi'
    ];
    protected $guarded = ['kode_koreksi_upah'];

    protected $hidden = [''];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'uuid',
        'kode_koreksi_upah',
        'tanggal_koreksi',
        'enroll_id',
        'nik',
        'employee_name',
        'site_nirwana_id',
        'site_nirwana_name',
        'department_id',
        'department_name',
        'sub_dept_id',
        'sub_dept_name',
        'jumlah_rp_potongan',
        'periode_tanggal_koreksi',
        'keterangan',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at',
        'jenis_koreksi'
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['kode_koreksi_upah'];


    public function atribut(){
        return $this->belongsTo('App\Models\EmployeeAtribut', 'enroll_id','enroll_id');
    }
}
