<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\GradingSalary;
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
use App\Models\EmployeeGrading;

/**
 * Class GradingSalaryController
 * @package App\Http\Controllers\Hris
 */
class GradingSalaryController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Grading Salary';
    }

    public function index()
    {
        return View::make('hris/gradingsalary', $this->data);
    }

    public function ajax_data(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'kode_grade',
            1 => 'salary_bulanan',
            2 => 'periode_umk',
            3 => 'updated_at',
            4 => 'insentif'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  GradingSalary::offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = GradingSalary::count();
            $totalFiltered = $totalData;  

        } else {
            $search = $request->input('search.value');

            $query =  GradingSalary::where('kode_grade','LIKE',"%{$search}%")
                ->orWhere('salary_bulanan','LIKE',"%{$search}%")
                ->orWhere('periode_umk','LIKE',"%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

            $totalData = GradingSalary::where('kode_grade','LIKE',"%{$search}%")
                ->orWhere('salary_bulanan','LIKE',"%{$search}%")
                ->orWhere('periode_umk','LIKE',"%{$search}%")
                ->count();
            $totalFiltered = $totalData;                            

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['id'] = $q->id_grade;
                $nestedData['kode_grade'] = $q->kode_grade;
                $nestedData['salary_bulanan'] = $q->salary_bulanan;
                $nestedData['insentif'] = $q->insentif;
                $nestedData['periode_umk'] = $q->periode_umk;
                $nestedData['operator'] = $q->operator;
                $nestedData['created_at'] = substr($q->created_at, 0, 10) . " " . substr($q->created_at, 11, 5);
                $nestedData['updated_at'] = substr($q->updated_at, 0, 10) . " " . substr($q->updated_at, 11, 5);
        
                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
            );

        echo json_encode($json_data);
        }
    }

    public function replace(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $operator = $loggedAdmin->email;

        $id=$request->id;
        $kode_grade = $request->kode_grade;
        $salary_bulanan = $request->salary_bulanan;
        $insentif = $request->insentif;
        $periode_umk = $request->periode_umk;

        $findDT = GradingSalary::where('id_grade', $id)->count();
        $id_grade = GradingSalary::selectRaw('MAX(id_grade) as max')->get();
        $minValue=$id_grade[0]->max;
        $maxValue=$minValue+1;
        if($findDT > 0) {
            $query = GradingSalary::where('id_grade', $id)
            ->update([
                'id_grade'=>$maxValue,
                'kode_grade' => $kode_grade,
                'salary_bulanan' => $salary_bulanan,
                'insentif'=>$insentif,
                'periode_umk' => $periode_umk,
                'operator' => $operator
            ]);
        } else {
            $query = GradingSalary::create([
                'id_grade'=>$maxValue,
                'kode_grade' => $kode_grade,
                'salary_bulanan' => $salary_bulanan,
                'insentif'=>$insentif,
                'periode_umk' => $periode_umk,
                'operator' => $operator
            ]);
        }
        EmployeeGrading::where('kode_grade',$kode_grade)->where('periode_umk','2023-01')->update([
            'salary_bulanan'=>$salary_bulanan,
            'periode_umk'=>$periode_umk
        ]);
    }

    public function destroy(Request $request)
    {
        $kode_grade = $request->kode_grade;

        $findDT = GradingSalary::where('kode_grade','=', $kode_grade)->count();

        if($findDT > 0) {
            $query = GradingSalary::where('kode_grade','=',$kode_grade)->delete();
        } else {
            $query = false;
        }

        return $query;
    }

}
