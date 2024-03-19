<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\BgProcess;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Datatables;
use App\Exports\MasterDataAbsenKehadiranExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class BgProcessController
 * @package App\Http\Controllers\Hris
 */
class BgProcessController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Background Process';
    }

    public function index()
    {
        return View::make('hris/bgprocess', $this->data);
    }

    public function ajax_bgprocess(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;

        if(request()->ajax()) {
    
        $columns = array(
            0 => 'uuid',
            1 => 'created_at',
            2 => 'nama_process',
            3 => 'process_start',
            4 => 'process_end',
            5 => 'process_status',
            6 => 'operator'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            if ($loggedAdmin->role_user == "superadmin") {

                $query =  BgProcess::offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

                $totalData = BgProcess::count();
            } else {
                $query =  BgProcess::where('operator','=',$email)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

                $totalData = BgProcess::where('operator','=',$email)->count();

            }
            $totalFiltered = $totalData;  
        } else {
            $search = $request->input('search.value');
    
            if ($loggedAdmin->role_user == "superadmin") {

                $query =  BgProcess::where('uuid','LIKE',"%{$search}%")
                                ->orWhere('nama_process','LIKE',"%{$search}%")
                                ->orWhere('process_start','LIKE',"%{$search}%")
                                ->orWhere('process_end','LIKE',"%{$search}%")
                                ->orWhere('process_status','LIKE',"%{$search}%")
                                ->orWhere('operator','LIKE',"%{$search}%")
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

                $totalData = BgProcess::where('uuid','LIKE',"%{$search}%")
                                ->orWhere('nama_process','LIKE',"%{$search}%")
                                ->orWhere('process_start','LIKE',"%{$search}%")
                                ->orWhere('process_end','LIKE',"%{$search}%")
                                ->orWhere('process_status','LIKE',"%{$search}%")
                                ->orWhere('operator','LIKE',"%{$search}%")
                                ->count();
            } else {
                $query =  BgProcess::where('uuid','LIKE',"%{$search}%")
                                ->orWhere('nama_process','LIKE',"%{$search}%")
                                ->orWhere('process_start','LIKE',"%{$search}%")
                                ->orWhere('process_end','LIKE',"%{$search}%")
                                ->orWhere('process_status','LIKE',"%{$search}%")
                                ->orWhere('operator','=',$email)
                                ->offset($start)
                                ->limit($limit)
                                ->orderBy($order,$dir)
                                ->get();

                $totalData = BgProcess::where('uuid','LIKE',"%{$search}%")
                                ->orWhere('nama_process','LIKE',"%{$search}%")
                                ->orWhere('process_start','LIKE',"%{$search}%")
                                ->orWhere('process_end','LIKE',"%{$search}%")
                                ->orWhere('process_status','LIKE',"%{$search}%")
                                ->orWhere('operator','=',$email)
                                ->count();

            }
                $totalFiltered = $totalData;                            

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
        
                $nestedData['uuid'] = $q->uuid;
                $nestedData['nama_process'] = $q->nama_process;
                $nestedData['process_start'] = $q->process_start;
                $nestedData['process_end'] = $q->process_end;
                $nestedData['process_status'] = $q->process_status;
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

}
