<?php

namespace App\Http\Controllers\Hris\FormLembur;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\View;
use DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\BiayaMakanKaryawanEstimasi;
use App\Exports\OvertimeRecap;
use Illuminate\Support\Str;

class AnggaranMakanController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
    }
    public function index(Request $request){
        $tgl_awal = $request->tgl_awal;
        if(empty($tgl_awal)){
            $tgl_awal=date('Y-m-d');
        }
        $user=Auth::guard('admin')->user()->name;
        if ($request->ajax()) {
            if(Auth::guard('admin')->user()->name=='HR' || Auth::guard('admin')->user()->name=='IT' || Auth::guard('admin')->user()->name=='GA' || Auth::guard('admin')->user()->email =='mega@ptnag.com' || Auth::guard('admin')->user()->email =='rudy@ptnag.com' || Auth::guard('admin')->user()->email =='fadli' || Auth::guard('admin')->user()->email =='ersa@ptnag.com' || Auth::guard('admin')->user()->email =='indri@nag.nirwanaindonesia.com'){
                $data_input=DB::select("select a.id,a.keterangan,a.tanggal,DATE_FORMAT(tanggal, '%d %M %Y') tanggal_fix,d.department_name,a.staff,a.non_staff,a.created_by from estimasi_anggaran_makan a inner join (select*from department_all where site_nirwana_id='NAG' and status='AKTIF' group by department_id)d on a.dept=d.department_id where tanggal = '$tgl_awal' order by a.updated_at desc");
                return DataTables::of($data_input)->toJson();
            }else{
                $data_input=DB::select("select a.id,a.keterangan,a.tanggal,DATE_FORMAT(tanggal, '%d %M %Y') tanggal_fix,d.department_name,a.staff,a.non_staff,a.created_by from estimasi_anggaran_makan a inner join (select*from department_all where site_nirwana_id='NAG' and status='AKTIF' group by department_id)d on a.dept=d.department_id where tanggal = '$tgl_awal' and created_by='$user' order by a.updated_at desc");
                return DataTables::of($data_input)->toJson();
            }
        }
        $dept=DB::select('select department_id,department_name from department_all where site_nirwana_id="NAG" group by department_id');
        return view('hris/mutasi-karyawan/anggaran_makan/index', [
            'page' => 'dashboard-mut-karyawan',
            "subPageGroup" => "anggaran-makan",
            "subPage" => "estimasi-anggaran-makan",
            "user"=>$user,
            "dept"=>$dept,
        ], $this->data);
    }
    public function store(){
        $this->_validation(request());
        $tanggal=request()->tanggal;
        $keterangan=request()->keterangan;
        $dept=request()->bagian;
        $staff=request()->staff;
        $non_staff=request()->non_staff;
        $timestamp=Carbon::now();
        $created_by=Auth::guard('admin')->user()->name;
        DB::insert("insert into estimasi_anggaran_makan (tanggal,keterangan,dept,staff,non_staff,created_by,created_at,updated_at) values('$tanggal','$keterangan','$dept','$staff','$non_staff','$created_by','$timestamp','$timestamp')");
    }
    private function _validation(){
        $validation=request()->validate([
            'tanggal'=>'required',
            'keterangan'=>'required',
            'bagian'=>'required',
        ],
        [
            'tanggal.required'=>'harus diisi',
            'keterangan.required'=>'harus diisi',
            'bagian.required'=>'harus diisi',
        ]);
    }
    public function edit(){
        $id_estimasi=request()->id;
        $dataestimasi=DB::select("select a.tanggal,a.id,a.keterangan,a.dept,a.staff,a.non_staff,a.created_by, b.sub_dept_name from estimasi_anggaran_makan a inner join department_all b on a.dept=b.department_id where id = '$id_estimasi' group by department_id");
        return $dataestimasi;
    }
    public function update(){
        $id_estimasi=request()->id;
        $tanggal=request()->tanggal;
        $keterangan=request()->keterangan;
        $dept=request()->bagian;
        $staff=request()->staff;

        $non_staff=request()->non_staff;
        $timestamp=Carbon::now();
        DB::update("update estimasi_anggaran_makan set tanggal='$tanggal', keterangan='$keterangan',dept='$dept',staff='$staff',non_staff='$non_staff' where id='$id_estimasi'");
    }
    public function delete(){
        $id_estimasi=request()->id;
        DB::delete("delete from estimasi_anggaran_makan where id = '$id_estimasi'");
    }
    public function export_excel_konsumsi_estimasi(Request $request){
        return Excel::download(new BiayaMakanKaryawanEstimasi($request->from), 'Laporan_Penerimaan FG_Stok.xlsx');
    }
    public function export_excel_overtime_recap(Request $request){
        return Excel::download(new OvertimeRecap($request->from), 'Laporan_Penerimaan FG_Stok.xlsx');
    }
    public function export_excel_overtime_recap2(Request $request){
        $from=date('Y-m-d');
        $fileName = $from." Laporan Rekap lembur ".rand().".xlsx";
        return Excel::download(new OvertimeRecap($from), $fileName);
    }
    public function export_pdf_konsumsi(){
        $tanggal=request()->tanggal;
        $tanggal_carbon=Carbon::parse($tanggal)->translatedFormat('l d F Y');
        $data = DB::select("select '' shift,b.department_name department,non_staff,if(non_staff!=0,8000,0) harga,8000*non_staff jumlah,staff,if(staff!=0,10000,0) harga2,10000*staff jumlah2,staff+non_staff jumlah_karyawan,(non_staff*8000)+(staff*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id='NAG' and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$tanggal' and keterangan='LEMBUR' order by keterangan,dept");
        $data2 = DB::select("select '' shift,b.department_name department,non_staff,if(non_staff!=0,8000,0) harga,8000*non_staff jumlah,staff,if(staff!=0,10000,0) harga2,10000*staff jumlah2,staff+non_staff jumlah_karyawan,(non_staff*8000)+(staff*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id='NAG' and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$tanggal' and keterangan='SHIFT MALAM' order by keterangan,dept");
        $data3 = DB::select("select 'LEMBUR TOTAL' shift,'' department,sum(non_staff) as non_staff,sum(if(non_staff!=0,1,0))*8000 harga,8000*sum(non_staff) jumlah,sum(staff) staff,sum(if(staff!=0,1,0))*10000 harga2,10000*sum(staff) jumlah2,sum(staff)+sum(non_staff) jumlah_karyawan,(sum(non_staff)*8000)+(sum(staff)*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id='NAG' and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$tanggal' and keterangan='LEMBUR' group by keterangan order by keterangan,dept");
        $data4 = DB::select("select 'SHIFT MALAM TOTAL' shift,'' department,sum(non_staff) as non_staff,sum(if(non_staff!=0,1,0))*8000 harga,8000*sum(non_staff) jumlah,sum(staff) staff,sum(if(staff!=0,1,0))*10000 harga2,10000*sum(staff) jumlah2,sum(staff)+sum(non_staff) jumlah_karyawan,(sum(non_staff)*8000)+(sum(staff)*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id='NAG' and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$tanggal' and keterangan='SHIFT MALAM' group by keterangan order by keterangan,dept");
        $data5 = DB::select("select 'GRAND TOTAL' shift,'' department,sum(non_staff) as non_staff,sum(if(non_staff!=0,1,0))*8000 harga,8000*sum(non_staff) jumlah,sum(staff) staff,sum(if(non_staff!=0,1,0))*10000 harga2,10000*sum(staff) jumlah2,sum(staff)+sum(non_staff) jumlah_karyawan,(sum(non_staff)*8000)+(sum(staff)*10000) total from estimasi_anggaran_makan inner join (select*from department_all where site_nirwana_id='NAG' and status='AKTIF' group by department_id) b on estimasi_anggaran_makan.dept=b.department_id where tanggal='$tanggal' group by shift order by keterangan,dept");
        $fileName='Budgeting Makan '.$tanggal_carbon.' '.rand();
        $pdf = PDF::loadView('hris/mutasi-karyawan/anggaran_makan/approval_anggaran_makan',["data" => $data,"data2"=>$data2,"data3"=>$data3,"data4"=>$data4,"data5"=>$data5,"tanggal"=>$tanggal])->setPaper('A4', 'potrait')->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
    }
}


