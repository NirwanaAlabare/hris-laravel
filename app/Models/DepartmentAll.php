<?php
namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DepartmentAll extends \Eloquent
{
    protected $table = 'department_all';

    // Don't forget to fill this array
    protected $fillable = [
        'site_nirwana_id',
        'site_nirwana_id_new',
        'site_nirwana_name',
        'site_nirwana_name_new',
        'department_id',
        'department_id_new',
        'department_name',
        'department_name_new',
        'sub_dept_id',
        'sub_dept_id_new',
        'sub_dept_name',
        'sub_dept_name_new',
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
        'site_nirwana_id',
        'site_nirwana_id_new',
        'site_nirwana_name',
        'site_nirwana_name_new',
        'department_id',
        'department_id_new',
        'department_name',
        'department_name_new',
        'sub_dept_id',
        'sub_dept_id_new',
        'sub_dept_name',
        'sub_dept_name_new',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['site_nirwana_id','department_id','sub_dept_id', 'site_nirwana_id_new','department_id_new','sub_dept_id_new'];

    public function getAllDepartment()
    {
        $query =  DB::table('department_all')
                    ->groupBy('department_id')
                    ->orderBy('department_name','asc')
                    ->get();
        return $query;

    }
    
}
