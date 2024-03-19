<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\EmployeeAtribut;
use App\Models\GradingSalary;
use App\Models\EmployeeGrading;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Datatables;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class EmployeeGradingController
 * @package App\Http\Controllers\Hris
 */
class EmployeeGradingController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Grading Karyawan';
    }

    public function index()
    {
        $this->periode_umr = $this->ajax_getperiodeumr();
        return View::make('hris/employeegrading', $this->data);
    }

    public function ajax_getperiodeumr()
    {
        $query =  EmployeeGrading::selectRaw('periode_umk')
                                    ->groupby('periode_umk')
                                    ->orderby('periode_umk', 'desc')
                                    ->get();
        return $query;

    }

    public function ajax_data(Request $request)
    {
        $periode_umk = $request->periode_umk;

        $status_staff = $request->status_staff;
        if($status_staff) {
            $whereStatusStaff = ' AND employee_atribut.status_staff = "' . $status_staff . '" ';
        } else {
            $whereStatusStaff = '';
        }

        $searchData = $request->searchData;
        if($searchData) {
            $whereSearchData = ' AND (upper( employee_atribut.enroll_id ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_atribut.employee_name ) LIKE upper( "%' . $searchData . '%" ) OR upper( department_all.sub_dept_name ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_atribut.status_aktif ) LIKE upper( "%' . $searchData . '%" ) OR upper( employee_grading.kode_grade ) LIKE upper( "%' . $searchData . '%" ) ) ';
        } else {
            $whereSearchData = '';
        }

        $query =  EmployeeGrading::selectRaw('
            employee_atribut.employee_id, employee_atribut.enroll_id, employee_atribut.nik,
            employee_atribut.employee_name, employee_atribut.status_staff, employee_grading.kode_grade,
            employee_atribut.status_aktif,
            department_all.site_nirwana_id, department_all.site_nirwana_name,
            department_all.department_id, department_all.department_name,
            department_all.sub_dept_id, department_all.sub_dept_name,
            FORMAT(employee_grading.salary_bulanan, 2,"id_ID") salary_bulanan_rupiah,
            employee_grading.salary_bulanan,
            employee_grading.periode_umk,
            employee_grading.operator, employee_grading.updated_at
            ')
            ->whereRaw('
                employee_grading.periode_umk = "' . $periode_umk . '"
                ' . $whereStatusStaff . '
                ' . $whereSearchData . '
            ')
            ->join('employee_atribut','employee_atribut.enroll_id','=','employee_grading.enroll_id')
            ->join('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
            ->groupBy('enroll_id')
            ->groupBy('periode_umk')
            ->orderBy('employee_grading.employee_name','asc')
            ->get();

        return Response()->json($query);

    }

    public function update_grading(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $operator = $loggedAdmin->email;

        $periode_umk = $request->periode_umk;
        $count_grading=GradingSalary::where('periode_umk',$periode_umk)->count();
        $query='Tidak ada';
        // NOT employee_atribut.tanggal_resign < DATE_ADD( LAST_DAY( DATE_SUB( NOW(), INTERVAL 2 MONTH )), INTERVAL 26 DAY ))

        if($count_grading){
            EmployeeGrading::whereRaw('periode_umk = "' . $periode_umk . '"')->delete();

            $queryEmpGrading =  EmployeeAtribut::selectRaw('
                                employee_atribut.employee_id, employee_atribut.enroll_id, employee_atribut.nik,
                                employee_atribut.employee_name, employee_atribut.status_staff,
                                employee_atribut.status_aktif, employee_atribut.join_date,
                                employee_atribut.tanggal_resign, employee_atribut.kode_grade,
                                grading_salary.periode_umk, grading_salary.salary_bulanan
                            ')
                            ->whereRaw('
                                employee_atribut.enroll_id is not null
                                AND (employee_atribut.tanggal_resign is null OR employee_atribut.tanggal_resign = "0000-00-00" OR
                                    NOT employee_atribut.tanggal_resign < "2023-03-26")
                                AND grading_salary.periode_umk = "' . $periode_umk . '"')
                            ->join('grading_salary','grading_salary.kode_grade','=','employee_atribut.kode_grade')
                            ->orderBy('employee_atribut.employee_name','asc')
                            ->get();

            foreach ($queryEmpGrading as $key => $value) {

                $countEmp = EmployeeGrading::whereRaw('enroll_id = "' . $value['enroll_id'] . '" AND periode_umk = "' . $value['periode_umk'] . '"')->count();

                if ($countEmp > 0) {
                    $query = EmployeeGrading::whereRaw('enroll_id = "' . $value['enroll_id'] . '" AND periode_umk = "' . $value['periode_umk'] . '"')
                    ->update([
                        'employee_id' => $value['employee_id'],
                        'enroll_id' => $value['enroll_id'],
                        'periode_umk' => $value['periode_umk'],
                        'nik' => $value['nik'],
                        'employee_name' => $value['employee_name'],
                        'status_staff' => $value['status_staff'],
                        'kode_grade' => $value['kode_grade'],
                        'salary_bulanan' => $value['salary_bulanan'],
                        'operator' => $operator
                    ]);
                } else {
                    $query = EmployeeGrading::create([
                        'employee_id' => $value['employee_id'],
                        'enroll_id' => $value['enroll_id'],
                        'periode_umk' => $value['periode_umk'],
                        'nik' => $value['nik'],
                        'employee_name' => $value['employee_name'],
                        'status_staff' => $value['status_staff'],
                        'kode_grade' => $value['kode_grade'],
                        'salary_bulanan' => $value['salary_bulanan'],
                        'operator' => $operator
                    ]);
                }

            }

        }
        return Response()->json($query);
    }

}
