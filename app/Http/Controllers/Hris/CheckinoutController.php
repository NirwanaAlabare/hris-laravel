<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\BaseController;
use App\Models\Checkinout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

/**
 * Class AdminDashboardController
 * @package App\Http\Controllers\Admin
 */
class CheckinoutController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Data CHeck In/Out';

    }

    public function index()
    {

        return View::make('hris/checkinout', $this->data);

    }

    public function ajax_checkinout(Request $request)
    {
        $columns = array(
            0 => 'enroll_id',
            1 => 'time',
        );

        $totalData = Checkinout::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $query = Checkinout::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        } else {
            $search = $request->input('search.value');

            $query =  Checkinout::where('enroll_id','LIKE',"%{$search}%")
                            ->orWhere('time', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Checkinout::where('enroll_id','LIKE',"%{$search}%")
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

                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['time'] = $q->time;
                $nestedData['options'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='glyphicon glyphicon-list'></span></a>
                &emsp;<a href='javascript:void(0)' data-toggle='tooltip' id='edit-link' data-employee_id='{{ $q->enroll_id }}' data-original-title='Edit' ><span class='glyphicon glyphicon-edit'></span></a>";
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

