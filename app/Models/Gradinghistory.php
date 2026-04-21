<?php
namespace App\Models;

class Gradinghistory extends \Eloquent
{
    protected $table = 'grading_history';

    protected $fillable = [
        'id_grade',
        'kode_grade_lama',
        'kode_grade_baru',
        'enroll_id',
        'periode_payroll',
        'periode_umk',
        'operator',
        'tanggal_pengajuan',
        'verifikasi',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $guarded = [''];

    protected $hidden = [''];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
       'id_grade',
        'kode_grade_lama',
        'kode_grade_baru',
        'enroll_id',
        'periode_payroll',
        'periode_umk',
        'operator',
        'tanggal_pengajuan',
        'verifikasi',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['enroll_id'];
     public function absen(){
        return $this->hasMany(MasterDataAbsenKehadiran::class, 'enroll_id','enroll_id');
    }
     public function grading_salary(){
        return $this->hasOne(GradingSalary::class, 'kode_grade','kode_grade_baru');
    }


}
