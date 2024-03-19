<?php
namespace App\Models;

class EmployeeGrading extends \Eloquent
{
    protected $table = 'employee_grading';

    protected $fillable = [
        'employee_id',
        'enroll_id',
        'periode_umk',
        'nik',
        'employee_name',
        'status_staff',
        'kode_grade',
        'salary_bulanan',
        'operator',
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
        'employee_id',
        'enroll_id',
        'periode_umk',
        'nik',
        'employee_name',
        'status_staff',
        'kode_grade',
        'salary_bulanan',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['enroll_id','periode_umk'];


}
