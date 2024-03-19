<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\BaseController;
use App\Models\Logs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

/**
 * Class AdminDashboardController
 * @package App\Http\Controllers\Admin
 */
class LogsController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Data Logs';

    }

    public function index()
    {

        return View::make('logs/data', $this->data);

    }

    public function ajax_logs(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'user_id',
            2 => 'url',
            3 => 'access',
            4 => 'method',
            5 => 'body',
            6 => 'time'
        );

        $totalData = Logs::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $query = Logs::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        } else {
            $search = $request->input('search.value');

            $query =  Logs::where('id','LIKE',"%{$search}%")
                            ->orWhere('user_id', 'LIKE',"%{$search}%")
                            ->orWhere('url', 'LIKE',"%{$search}%")
                            ->orWhere('access', 'LIKE',"%{$search}%")
                            ->orWhere('method', 'LIKE',"%{$search}%")
                            ->orWhere('body', 'LIKE',"%{$search}%")
                            ->orWhere('time', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Karyawan::where('id','LIKE',"%{$search}%")
                            ->orWhere('user_id', 'LIKE',"%{$search}%")
                            ->orWhere('url', 'LIKE',"%{$search}%")
                            ->orWhere('access', 'LIKE',"%{$search}%")
                            ->orWhere('method', 'LIKE',"%{$search}%")
                            ->orWhere('body', 'LIKE',"%{$search}%")
                            ->orWhere('time', 'LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $show =  "#";
                $edit =  "#";

                $nestedData['id'] = $q->id;
                $nestedData['user_id'] = $q->user_id;
                $nestedData['url'] = $q->url;
                $nestedData['access'] = $q->access;
                $nestedData['method'] = $q->method;
                $nestedData['body'] = $q->body;
                $nestedData['options'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='glyphicon glyphicon-list'></span></a>
                &emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='edit-link' data-employee_id='{{ $q->id }}' data-original-title='Edit' ><span class='glyphicon glyphicon-edit'></span></a>";
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

    public function screenlock()
    {
        Session::put('lock', '1');
        return View::make('admin/screen_lock', $this->data);
    }


}

