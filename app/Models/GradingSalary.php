<?php
namespace App\Models;

class GradingSalary extends \Eloquent
{
    protected $table = 'grading_salary';

    protected $fillable = [
        'id_grade',
        'kode_grade',
        'salary_bulanan',
        'insentif',
        'periode_umk',
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
        'id_grade',
        'kode_grade',
        'salary_bulanan',
        'insentif',
        'periode_umk',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['kode_grade'];
    public function employee_atribut(){
        return $this->hasMany(EmployeeAtribut::class, 'kode_grade','kode_grade');
    }


}
