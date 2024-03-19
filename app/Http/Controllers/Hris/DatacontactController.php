<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\BaseController;
use App\Models\Datacontact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

/**
 * Class AdminDashboardController
 * @package App\Http\Controllers\Admin
 */
class DatacontactController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Data Contact';

    }

    public function index()
    {

        return View::make('hris/datacontact', $this->data);

    }

    public function ajax_datacontact(Request $request)
    {
        $columns = array(
            0 => 'employee_id',
            1 => 'phone',
        );

        $totalData = Datacontact::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $query = Datacontact::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        } else {
            $search = $request->input('search.value');

            $query =  Datacontact::where('employee_id','LIKE',"%{$search}%")
                            ->orWhere('phone', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Datacontact::where('employee_id','LIKE',"%{$search}%")
                            ->orWhere('phone', 'LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $show =  "#";
                $edit =  "#";

                $nestedData['employee_id'] = $q->employee_id;
                $nestedData['phone'] = $q->phone;
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

