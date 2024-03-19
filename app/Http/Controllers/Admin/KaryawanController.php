<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Reply;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Employee\CreateRequest;
use App\Http\Requests\Admin\Employee\UpdateRequest;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Datatables;

class KaryawanController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }
    public function ajax_karyawan(Request $request)
    {
        if(request()->ajax()) {

        $columns = array(
            0 => 'enroll_id',
            1 => 'employee_id',
            2 => 'name',
            3 => 'gender',
            4 => 'place_of_birth',
            5 => 'date_birth'
        );

        $totalData = Karyawan::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $query = Karyawan::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        } else {
            $search = $request->input('search.value');

            $query =  Karyawan::where('enroll_id','LIKE',"%{$search}%")
                            ->orWhere('employee_id', 'LIKE',"%{$search}%")
                            ->orWhere('name', 'LIKE',"%{$search}%")
                            ->orWhere('gender', 'LIKE',"%{$search}%")
                            ->orWhere('place_of_birth', 'LIKE',"%{$search}%")
                            ->orWhere('date_birth', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Karyawan::where('enroll_id','LIKE',"%{$search}%")
                            ->orWhere('employee_id', 'LIKE',"%{$search}%")
                            ->orWhere('name', 'LIKE',"%{$search}%")
                            ->orWhere('gender', 'LIKE',"%{$search}%")
                            ->orWhere('place_of_birth', 'LIKE',"%{$search}%")
                            ->orWhere('date_birth', 'LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $show =  route('admin.karyawan.show',$q->employee_id);
                $edit =  route('admin.karyawan.edit',$q->employee_id);

                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['employee_id'] = $q->employee_id;
                $nestedData['name'] = $q->name;
                $nestedData['gender'] = $q->gender;
                $nestedData['place_of_birth'] = $q->place_of_birth;
                $nestedData['date_birth'] = $q->date_birth;
                $nestedData['options'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='glyphicon glyphicon-list'></span></a>
                &emsp;<a href='javascript:void(0)' data-toggle='tooltip'  data-employee_id='{{ $q->employee_id }}'' data-original-title='Edit' class='edit'><span class='glyphicon glyphicon-list'></span></a>";
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employee_id = $request->employee_id;
        $karyawan = Karyawan::updateOrCreate(
                    [
                     'employee_id' => $employee_id
                    ],
                    [
                    'enroll_id' =>  $request->enroll_id,
                    'employee_id' => $request->employee_id,
                    'name' => $request->name,
                    'gender' => $request->gender,
                    'place_of_birth' => $request->place_of_birth,
                    'date_birth' => $request->date_birth
                    ]);
        return Response()->json($karyawan);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $employee_id = $request->employee_id;
        $employee_id = str_replace("{{ ","","$employee_id");
        $employee_id = str_replace(" }}","","$employee_id");
        //$karyawan  = Karyawan::where($where)->first();
        //$karyawan = "{ 'employee_id': '131600996', 'enroll_id': '996' }";
        //info(Response()->json($karyawan));
        $query = Karyawan::where('employee_id','=',$employee_id)->first();
        //info("Total Data: " . $query);
        return Response()->json($query);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
    }

}
