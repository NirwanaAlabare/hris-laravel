<?php

namespace App\Http\Controllers\Hris;

use App\Models\DepartmentAll;
use App\Models\EmployeeAtribut;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Exports\DepartmentAllFilterExport;
use Datatables;


/**
 * Class MdAbsenHadirController
 * @package App\Http\Controllers\Hris
 */
class DepartmentAllController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Master Data';
    }

    public function index()
    {
        $site_nirwana_id=DepartmentAll::orderBy('site_nirwana_name')->groupBy('site_nirwana_id')->get();
        return View::make('hris/departmentall', $this->data,compact('site_nirwana_id'));
    }
    public function get_last_dept_id(){
        return DepartmentAll::where('site_nirwana_id','NAG')->orderBy('department_id','desc')->limit(1)->pluck('department_id');
    }
    public function get_dept_name(){
        return DepartmentAll::where('site_nirwana_id','NAG')->orderBy('department_name')->groupBy('department_name')->get();
    }
    public function save_department_id(){
        $this->_validation(request());
        DepartmentAll::create([
            'site_nirwana_id'=>'NAG',
            'site_nirwana_name'=>'PT. NIRWANA ALABARE GARMENT',
            'department_id'=>request()->department_id,
            'department_name'=>strtoupper(request()->department_name),
            'sub_dept_id'=>request()->department_id.'SUB'.sprintf("%03d",1),
            'sub_dept_name'=>strtoupper(request()->sub_department_name)
        ]);
    }
    public function save_sub_department(){
        $this->_validation2(request());
        DepartmentAll::create([
            'site_nirwana_id'=>'NAG',
            'site_nirwana_name'=>'PT. NIRWANA ALABARE GARMENT',
            'department_id'=>request()->department_name,
            'department_name'=>DepartmentAll::where('department_id',request()->department_name)->pluck('department_name')[0],
            'sub_dept_id'=>request()->department_name.'SUB'.sprintf("%03d",intval(substr(DepartmentAll::where('site_nirwana_id','NAG')->where('department_id',request()->department_name)->orderBy('sub_dept_id','desc')->limit(1)->pluck('sub_dept_id')[0],8,9))+1),
            'sub_dept_name'=>strtoupper(request()->sub_department_name)
        ]);
    }
    private function _validation(){
        $validation=request()->validate([
            'department_id'=>'unique:department_all',
            'department_name'=>'required|unique:department_all',
            'sub_department_name'=>'required'
        ],
        [
            'department_id.unique'=>'sudah ada di database',
            'department_name.required'=>'harus diisi',
            'department_name.unique'=>'sudah ada di database',
            'sub_department_name.required'=>'harus diisi',
        ]);
    }
    private function _validation2(){
        $validation=request()->validate([
            'department_name'=>'required',
            'sub_department_name'=>'required'
        ],
        [
            'department_name.required'=>'harus diisi',
            'sub_department_name.required'=>'harus diisi',
        ]);

    }


    public function getDepartmentName(Request $request)
    {
        $query =  DepartmentAll::where('department_id','=',$request->id)
                                 ->groupby('sub_dept_name')
                                 ->get();
        return $query;

    }

    public function getSelectSubDept(Request $request)
    {
        $department_id = $request->department_id;

        $query =  DepartmentAll::where('department_name','=',$department_id)
                    ->whereIn('site_nirwana_id', ['NAG', 'NAGD','NAK'])
                    ->groupBy('sub_dept_name')
                    ->orderBy('sub_dept_name','asc')
                    ->get();

        return $query;

    }
    public function getSelectSubDeptIn(){
        $site_nirwana_id = request()->site_nirwana_id;
        $department_id = request()->department_id;

        $query =  DepartmentAll::where('site_nirwana_id','=',$site_nirwana_id)->where('department_id',request()->department_id)->get();

        return $query;
    }
    public function getSelectDeptId(Request $request)
    {
        $site_nirwana_id = $request->site_nirwana_id;

        $query =  DepartmentAll::where('site_nirwana_id','=',$site_nirwana_id)
                    ->groupBy('department_name')
                    ->orderBy('department_name','asc')
                    ->get();

        return $query;

    }
    public function getJumlahKaryawan(Request $request)
    {
        $sub_dept_id = $request->sub_dept_id;


        $query =  EmployeeAtribut::where('sub_dept_id','=',$sub_dept_id)->count();

        return $query;

    }
    public function export_excel_department_all(){
        $site_nirwana=request()->site_nirwana;
        $department=request()->department;
        $sub_department=request()->sub_department;
        $fileName = 'DataDepartmentAll.xlsx';
        return Excel::download(new DepartmentAllFilterExport($site_nirwana,$department,$sub_department), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
    }
    public function import_department(){
        $data=Excel::toArray([],request()->file('excel_file'));
        $arrayEmployee=[];
        for($i=4;$i<count($data[0]);$i++){
            $arrayEmployee[$i]=[
                'site_nirwana_id'=>$data[0][$i][1],
                'site_nirwana_name'=>$data[0][$i][2],
                'department_id'=>$data[0][$i][3],
                'department_name'=>$data[0][$i][4],
                'sub_dept_id'=>$data[0][$i][5],
                'sub_dept_name'=>$data[0][$i][6],
                'jumlah_karyawan'=>$data[0][$i][7],
                'status'=>$data[0][$i][8],
            ];
        }
        return $arrayEmployee;
    }
    public function import_department_to_database(){
        $data=Excel::toArray([],request()->file('excel_file'));
        for($i=4;$i<count($data[0]);$i++){
            DepartmentAll::where('site_nirwana_id',$data[0][$i][1])->where('department_id',$data[0][$i][3])->where('sub_dept_id',$data[0][$i][5])->update([
                'status'=>$data[0][$i][8],
            ]);
        }
    }
    public function ajax_departmentall(Request $request)
    {
        if(request()->ajax()) {

            $columns = array(
                0 => 'site_nirwana_id',
                1 => 'site_nirwana_name',
                2 => 'department_id',
                3 => 'department_name',
                4 => 'sub_dept_id',
                5 => 'sub_dept_name',
                6 => 'jumlah',
                7 => 'status',
            );

            $totalData = DepartmentAll::count();
            $totalFiltered = $totalData;

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $site_nirwana=$request->site_nirwana_id;
            $department=$request->department_id;
            $sub_dept_id=$request->sub_dept_id;
            $inSiteNirwana='';
            $inDepartment='';
            $inSubDepartment='';
            if($site_nirwana){
                $inSiteNirwana = ' AND site_nirwana_id = "'.$site_nirwana.'"';
            }
            if($department){
                $inDepartment = ' AND department_id = "'.$department.'"';
            }
            if($sub_dept_id){
                $inSubDepartment = ' AND sub_dept_id = "'.$sub_dept_id.'"';
            }
            if(empty($inSiteNirwana) && empty($inDepartment) && empty($inSubDepartment) && empty($request->input('search.value')))
            {
                $query = DepartmentAll::offset($start)
                             ->limit($limit)
                             ->orderBy($order,$dir)
                             ->get();
            } else {
                $search = $request->input('search.value');

                $query =  DepartmentAll::whereRaw('status is not null'.$inSiteNirwana.''.$inDepartment.''.$inSubDepartment.'')->where(function($query)use($search){
                    $query->where('site_nirwana_id','LIKE',"%{$search}%")
                    ->orWhere('site_nirwana_name', 'LIKE',"%{$search}%")
                    ->orWhere('department_id', 'LIKE',"%{$search}%")
                    ->orWhere('department_name', 'LIKE',"%{$search}%")
                    ->orWhere('sub_dept_id', 'LIKE',"%{$search}%")
                    ->orWhere('sub_dept_name', 'LIKE',"%{$search}%");
                })
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get();

                $totalFiltered = DepartmentAll::whereRaw('status is not null'.$inSiteNirwana.''.$inDepartment.''.$inSubDepartment.'')->where(function($query)use($search){
                    $query->where('site_nirwana_id','LIKE',"%{$search}%")
                    ->orWhere('site_nirwana_name', 'LIKE',"%{$search}%")
                    ->orWhere('department_id', 'LIKE',"%{$search}%")
                    ->orWhere('department_name', 'LIKE',"%{$search}%")
                    ->orWhere('sub_dept_id', 'LIKE',"%{$search}%")
                    ->orWhere('sub_dept_name', 'LIKE',"%{$search}%");
                })->count();
            }

            $data = array();
            if(!empty($query))
            {
                foreach ($query as $q)
                {
                    $jumlah=EmployeeAtribut::where('sub_dept_id',$q->sub_dept_id)->where(function($query){
                        $query->where('status_aktif','AKTIF')
                        ->orWhere('tanggal_resign','>',date('Y-m-d'));
                    })->count();
                    $showData = $q->site_nirwana_id . "/" . $q->department_id . "/" . $q->sub_dept_id;
                    $addeditData = $q->site_nirwana_id . "/" . $q->department_id . "/" . $q->sub_dept_id;
                    $nestedData['site_nirwana_id'] = $q->site_nirwana_id;
                    $nestedData['site_nirwana_name'] = $q->site_nirwana_name;
                    $nestedData['department_id'] = $q->department_id;
                    $nestedData['department_name'] = $q->department_name;
                    $nestedData['sub_dept_id'] = $q->sub_dept_id;
                    $nestedData['sub_dept_name'] = $q->sub_dept_name;
                    $nestedData['jumlah'] = $jumlah;
                    $nestedData['status'] = $q->status;
                    $nestedData['option'] = '
                    <a href="" type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="glyphicon glyphicon-list"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        <li><a  href="javascript:void(0)" data-toggle="tooltip" id="showData-link" data-id="' . $showData . '" data-original-title="Show">Show</a></li>
                        <li><a  href="javascript:void(0)" data-toggle="tooltip" id="addeditData-link" data-id="' . $addeditData . '" data-original-title="Edit">Edit</a></li>
                        <li><a href="#">Delete</li>
                    </ul>';

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

    public function show_data(Request $request)
    {
        $showData = $request->showData;
        $dataAjax = explode("/", $showData);
        $site_nirwana_id = $dataAjax[0];
        $department_id = $dataAjax[1];
        $sub_dept_id = $dataAjax[2];

        $query =  DepartmentAll::where('site_nirwana_id','=',$site_nirwana_id)
        ->where('department_id', '=',$department_id)
        ->where('sub_dept_id', '=',$sub_dept_id)
        ->first();

        return Response()->json($query);

    }

    public function edit_data(Request $request)
    {
        $editData = $request->editData;
        $dataAjax = explode("/", $editData);
        $site_nirwana_id = $dataAjax[0];
        $department_id = $dataAjax[1];
        $sub_dept_id = $dataAjax[2];

        $query =  DepartmentAll::where('site_nirwana_id','=',$site_nirwana_id)
        ->where('department_id', '=',$department_id)
        ->where('sub_dept_id', '=',$sub_dept_id)
        ->first();

        return Response()->json($query);

    }

    public function store(Request $request)
    {
        $site_nirwana_id = $request->site_nirwana_id;
        $site_nirwana_name = $request->site_nirwana_name;
        $department_id = $request->department_id;
        $department_name = $request->department_name;
        $sub_dept_id = $request->sub_dept_id;
        $sub_dept_name = $request->sub_dept_name;
        $status=$request->status;

        $query =  DepartmentAll::where('site_nirwana_id','=',$site_nirwana_id)
                    ->where('department_id', '=',$department_id)
                    ->where('sub_dept_id', '=',$sub_dept_id)
                    ->update([
                        'site_nirwana_id' => $site_nirwana_id,
                        'site_nirwana_name' => $site_nirwana_name,
                        'department_id' => $department_id,
                        'department_name' => $department_name,
                        'sub_dept_id' => $sub_dept_id,
                        'sub_dept_name' => $sub_dept_name,
                        'status'=>$status
                    ]);

        return Response()->json($query);
    }

    /*    Screen lock controller.When screen lock button from menu is cliked this controller is called.
    *     lock variable is set to 1 when screen is locked.SET to 0  if you dont want screen variable
    */

    public function screenlock()
    {
        Session::put('lock', '1');
        return View::make('admin/screen_lock', $this->data);
    }


}
