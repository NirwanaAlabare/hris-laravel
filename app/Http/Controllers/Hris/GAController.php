<?php

namespace App\Http\Controllers\Hris;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use App\Models\EmployeeAtribut;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\PermintaanTransportasi;
use App\Models\TujuanTransportasi;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PermintaanTransportasiExport;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class GAController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
    }
    public function form_pengajuan_transportasi(){
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        $provincies=DB::select('select * from provinces order by prov_id');
        $cities=DB::select("select * from cities order by city_id");
        $districts=DB::select("select * from districts order by dis_id");
        $subdistricts=DB::select("select * from subdistricts order by subdis_id");
        $loggedAdmin = Auth::guard('admin')->user();
        $id_user = $loggedAdmin->enroll_id;
        $pengajuan_transportasi=PermintaanTransportasi::where('status',0)->count();
        $tujuan_short=DB::select("select a.provinsi,a.city,a.district,a.subdistrict,e.prov_id,d.city_id,c.dis_id,b.subdis_id,a.instansi,a.detail_alamat from (select*from tujuan_transportasi where permintaan_transportasi_id in (select id from permintaan_transportasi where created_by = '$id_user'))a inner join subdistricts b on a.subdistrict=b.subdis_name inner join districts c on a.district=c.dis_name and b.dis_id=c.dis_id inner join cities d on c.city_id=d.city_id inner join provinces e on e.prov_id=d.prov_id group by a.detail_alamat,b.subdis_id");
        return View::make('hris/ga/form_pengajuan_transportasi_2', $this->data,compact('id_user','provincies','cities','districts','subdistricts','tujuan_short','pengajuan_transportasi'));
    }
    public function form_pengajuan_transportasi_2_admin(){
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        $provincies=DB::select('select * from provinces order by prov_id');
        $cities=DB::select("select * from cities order by city_id");
        $districts=DB::select("select * from districts order by dis_id");
        $subdistricts=DB::select("select * from subdistricts order by subdis_id");
        $loggedAdmin = Auth::guard('admin')->user();
        $id_user = $loggedAdmin->enroll_id;
        $pengajuan_transportasi=PermintaanTransportasi::where('status',0)->count();
        $tujuan_short=DB::select("select a.provinsi,a.city,a.district,a.subdistrict,e.prov_id,d.city_id,c.dis_id,b.subdis_id,a.instansi,a.detail_alamat from (select*from tujuan_transportasi where permintaan_transportasi_id in (select id from permintaan_transportasi where created_by = '$id_user'))a inner join subdistricts b on a.subdistrict=b.subdis_name inner join districts c on a.district=c.dis_name and b.dis_id=c.dis_id inner join cities d on c.city_id=d.city_id inner join provinces e on e.prov_id=d.prov_id group by a.detail_alamat,b.subdis_id");
        return View::make('hris/ga/form_pengajuan_transportasi_2_admin', $this->data,compact('id_user','provincies','cities','districts','subdistricts','tujuan_short','pengajuan_transportasi'));
    }
    public function get_all_destination_history(){
        $id=request()->id;
        $tujuan_short=DB::select("select a.instansi,a.detail_alamat,a.subdistrict,c.dis_id,d.city_id,e.prov_id,b.subdis_name,c.dis_name,d.city_name,e.prov_name,concat(a.instansi,' (',a.detail_alamat,') - ',b.subdis_name,' - ',c.dis_name,' - ',d.city_name,' - ',e.prov_name) detail_alamat_tujuan from (select*from tujuan_transportasi where permintaan_transportasi_id in (select id from permintaan_transportasi where created_by = $id) order by created_at)a inner join subdistricts b on a.subdistrict=b.subdis_id inner join districts c on b.dis_id=c.dis_id inner join cities d on c.city_id=d.city_id inner join provinces e on d.prov_id=e.prov_id group by instansi,detail_alamat,subdistrict");
        return $tujuan_short;
    }
    public function get_all_history_alamat(){
        $id_user=request()->user;
        $tujuan_short=DB::select("select a.provinsi,a.city,a.district,a.subdistrict,e.prov_id,d.city_id,c.dis_id,b.subdis_id,a.instansi,a.detail_alamat from (select*from tujuan_transportasi where permintaan_transportasi_id in (select id from permintaan_transportasi where created_by = '$id_user'))a inner join subdistricts b on a.subdistrict=b.subdis_name inner join districts c on a.district=c.dis_name and b.dis_id=c.dis_id inner join cities d on c.city_id=d.city_id inner join provinces e on e.prov_id=d.prov_id group by a.detail_alamat,b.subdis_id");
        return $tujuan_short;
    }
    public function data_pengajuan_transportasi(){
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        $id_user = $loggedAdmin->enroll_id;
        $drivers=EmployeeAtribut::where('sub_dept_id','DEP08SUB002')->where(function($query){
            $query->where('status_aktif','AKTIF')
            ->orWhere('tanggal_resign','>',date('Y-m-d'));
        })->get();
        $vehicles =  DB::connection('laravel_nds')->select(
            DB::raw("select*from ga_master_kendaraan") );
        $pengajuan_transportasi=PermintaanTransportasi::where('status',0)->count();
        return View::make('hris/ga/data_pengajuan_transportasi', $this->data,compact('email','id_user','drivers','vehicles','pengajuan_transportasi'));
    }
    public function data_pengajuan_transportasi_admin(){
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        $id_user = $loggedAdmin->enroll_id;
        $drivers=EmployeeAtribut::where('sub_dept_id','DEP08SUB002')->where(function($query){
            $query->where('status_aktif','AKTIF')
            ->orWhere('tanggal_resign','>',date('Y-m-d'));
        })->get();
        $vehicles =  DB::connection('laravel_nds')->select(
            DB::raw("select*from ga_master_kendaraan") );
        $pengajuan_transportasi=PermintaanTransportasi::where('status',0)->count();
        return View::make('hris/ga/data_pengajuan_transportasi_admin', $this->data,compact('email','id_user','drivers','vehicles','pengajuan_transportasi'));
    }
    public function get_data_pengajuan_transportasi(Request $request){
        $user=request()->user;
        $status=request()->status;
        $inStatus='';
        if($status!=''){
            $inStatus='and a.status= '.$status.' ';
        }
        $daterange = $request->daterange;
        $tanggal_array=explode(' s/d ',$daterange);
        $tanggal_awal=Carbon::parse($tanggal_array[0])->format('Y-m-d');
        $tanggal_akhir=Carbon::parse($tanggal_array[1])->format('Y-m-d');
        info($tanggal_awal.' - '.$tanggal_akhir);
        if($user!=0){
            $department_id=EmployeeAtribut::where('enroll_id',$user)->first()->department_id;
        }
        if($user!=6083 && $user!=5321 && $user!=20 && $user!=4241 && $user!=0 && $user!=8590 && $user!=17){
            $data_input=DB::select("select '$user' user,a.id,a.enroll_id,b.employee_name,b.department_name,a.detail_alamat detail_address,a.instansi detail_alamat,concat(c.subdis_name,' - ',d.dis_name,' - ',e.city_name,' - ',f.prov_name) desa,g.instansi detail_alamat_tujuan,concat(h.subdis_name,' - ',i.dis_name,' - ',j.city_name,' - ',k.prov_name) desa_tujuan,g.tujuan_pemberangkatan,concat(DATE_FORMAT(a.tanggal_pemberangkatan, '%d %M %Y'),' - ',substring(a.jam_pemberangkatan,1,5)) tanggal_pemberangkatan,a.jam_pemberangkatan,a.status,a.alasan_status,a.created_by from (select*from permintaan_transportasi where enroll_id in (select enroll_id from employee_atribut where department_id='$department_id') or created_by in (select enroll_id from employee_atribut where department_id='$department_id')) a inner join employee_atribut b on a.enroll_id=b.enroll_id inner join subdistricts c on a.id_desa=c.subdis_id inner join districts d on c.dis_id=d.dis_id inner join cities e on d.city_id=e.city_id inner join provinces f on e.prov_id=f.prov_id inner join (select*from tujuan_transportasi where tujuan_id=0) g on a.id=g.permintaan_transportasi_id inner join subdistricts h on g.subdistrict=h.subdis_id inner join districts i on h.dis_id=i.dis_id inner join cities j on i.city_id=j.city_id inner join provinces k on j.prov_id=k.prov_id where DATE_FORMAT(a.created_at, '%Y-%m-%d')>='$tanggal_awal' and DATE_FORMAT(a.created_at, '%Y-%m-%d')<='$tanggal_akhir' ".$inStatus." order by a.created_at desc");
        }else{
            $data_input=DB::select("select '$user' user,a.id,a.enroll_id,b.employee_name,b.department_name,a.detail_alamat detail_address,a.instansi detail_alamat,concat(c.subdis_name,' - ',d.dis_name,' - ',e.city_name,' - ',f.prov_name) desa,g.instansi detail_alamat_tujuan,concat(h.subdis_name,' - ',i.dis_name,' - ',j.city_name,' - ',k.prov_name) desa_tujuan,g.tujuan_pemberangkatan,concat(DATE_FORMAT(a.tanggal_pemberangkatan, '%d %M %Y'),' - ',substring(a.jam_pemberangkatan,1,5)) tanggal_pemberangkatan,a.jam_pemberangkatan,a.status,a.alasan_status,a.created_by from permintaan_transportasi a inner join employee_atribut b on a.enroll_id=b.enroll_id inner join subdistricts c on a.id_desa=c.subdis_id inner join districts d on c.dis_id=d.dis_id inner join cities e on d.city_id=e.city_id inner join provinces f on e.prov_id=f.prov_id inner join (select*from tujuan_transportasi where tujuan_id=0) g on a.id=g.permintaan_transportasi_id inner join subdistricts h on g.subdistrict=h.subdis_id inner join districts i on h.dis_id=i.dis_id inner join cities j on i.city_id=j.city_id inner join provinces k on j.prov_id=k.prov_id where DATE_FORMAT(a.created_at, '%Y-%m-%d')>='$tanggal_awal' and DATE_FORMAT(a.created_at, '%Y-%m-%d')<='$tanggal_akhir' ".$inStatus." order by a.created_at desc");
        }
        return DataTables::of($data_input)->toJson();
    }
    public function get_pengajuan_status(){
        $status=PermintaanTransportasi::where('id',request()->id)->first()->status;
        return $status;
    }
    public function delete_pengajuan_transportasi(){
        PermintaanTransportasi::where('id',request()->id)->delete();
        TujuanTransportasi::where('permintaan_transportasi_id',request()->id)->delete();
    }
    public function export_excel_transportasi(){
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');
        $tanggal_mesin_absensi = request()->daterange;
        $tanggal_array=explode(' s/d ',$tanggal_mesin_absensi);
        $tanggal_awal=Carbon::parse($tanggal_array[0])->format('Y-m-d');
        $tanggal_akhir=Carbon::parse($tanggal_array[1])->format('Y-m-d');
        $tanggal_awal_string=Carbon::parse($tanggal_array[0])->translatedFormat('d F Y');
        $tanggal_akhir_string=Carbon::parse($tanggal_array[1])->translatedFormat('d F Y');
        $status=request()->status;
        $inTanggal='';
        $inStatus='';
        if($status!=''){
            $inStatus='where status= '.$status.' ';
        }
        $pengajuan_transportasi=DB::select("select a.permintaan_transportasi_id,a.tujuan_id,b.created_at,b.enroll_id,c.employee_name,c.department_name department,c.sub_dept_name bagian,concat(b.detail_alamat,' (',d.subdis_name,' - ',e.dis_name,' - ',f.city_name,' - ',g.prov_name,')') destinasi_awal,b.tanggal_pemberangkatan,substring(b.jam_pemberangkatan,1,5) jam_pemberangkatan,concat(a.instansi,' (',a.detail_alamat,' - ',j.subdis_name,' - ',k.dis_name,' - ',l.city_name,' - ',m.prov_name,')') destinasi_akhir,a.tanggal_kedatangan,substring(a.jam_kedatangan,1,5) jam_kedatangan,a.tujuan_pemberangkatan,a.nama_tamu,a.nomor_hp_tamu,a.jenis_barang,a.quantity,a.satuan,a.nama_penerima,a.keterangan_barang,a.karyawan_dinas_luar,b.created_by,i.employee_name nama_pembuat,b.status,a.driver,h.nik id_driver,h.employee_name nama_driver,a.vehicle,concat(n.plat_no,' - ',n.merk,' - ',n.tipe) vehicle_name,b.alasan_status from (select*from tujuan_transportasi)a inner join (select*from permintaan_transportasi where DATE_FORMAT(created_at, '%Y-%m-%d')>='$tanggal_awal' and DATE_FORMAT(created_at, '%Y-%m-%d')<='$tanggal_akhir'".$inStatus.")b on a.permintaan_transportasi_id=b.id inner join employee_atribut c on b.enroll_id=c.enroll_id inner join subdistricts d on b.id_desa=d.subdis_id inner join districts e on d.dis_id=e.dis_id inner join cities f on e.city_id=f.city_id inner join provinces g on f.prov_id=g.prov_id left join employee_atribut h on a.driver=h.enroll_id inner join employee_atribut i on b.created_by=i.enroll_id inner join subdistricts j on a.subdistrict=j.subdis_id inner join districts k on j.dis_id=k.dis_id inner join cities l on k.city_id=l.city_id inner join provinces m on l.prov_id=m.prov_id inner join ga_master_kendaraan n on a.vehicle=n.id order by b.created_at desc,a.permintaan_transportasi_id,a.tujuan_id");
        foreach($pengajuan_transportasi as $value){
            $karyawan_dinas=$value->karyawan_dinas_luar;
            $nama_karyawan_dinas='';
            if($karyawan_dinas!=null){
                $karyawan_dinas=explode(",",$karyawan_dinas);
                foreach($karyawan_dinas as $kardin){
                    $nama_karyawan_dinas.=(EmployeeAtribut::where('enroll_id',$kardin)->first()->employee_name).',';
                }
            }

            $pengajuan[]=[
                'permintaan_transportasi_id'=>$value->permintaan_transportasi_id,
                'tujuan_id'=>$value->tujuan_id,
                'created_at'=>substr($value->created_at,0,10),
                'enroll_id'=>$value->enroll_id,
                'employee_name'=>$value->employee_name,
                'department'=>$value->department,
                'bagian'=>$value->bagian,
                'destinasi_awal'=>$value->destinasi_awal,
                'tanggal_pemberangkatan'=>$value->tanggal_pemberangkatan,
                'jam_pemberangkatan'=>$value->jam_pemberangkatan,
                'destinasi_akhir'=>$value->destinasi_akhir,
                'tanggal_kedatangan'=>$value->tanggal_kedatangan,
                'jam_kedatangan'=>$value->jam_kedatangan,
                'tujuan_pemberangkatan'=>$value->tujuan_pemberangkatan,
                'nama_tamu'=>$value->nama_tamu,
                'nomor_hp_tamu'=>$value->nomor_hp_tamu,
                'jenis_barang'=>$value->jenis_barang,
                'quantity'=>$value->quantity,
                'satuan'=>$value->satuan,
                'nama_penerima'=>$value->nama_penerima,
                'keterangan_barang'=>$value->keterangan_barang,
                'karyawan_dinas'=>$nama_karyawan_dinas,
                'created_by'=>$value->created_by,
                'nama_pembuat'=>$value->nama_pembuat,
                'status'=>$value->status,
                'id_driver'=>$value->id_driver,
                'nama_driver'=>$value->nama_driver,
                'vehicle_name'=>$value->vehicle_name,
                'alternative'=>$value->alasan_status
            ];
        }
        $fileName = 'Daily Labor Cost';
        $response = Excel::download(new PermintaanTransportasiExport($pengajuan,$tanggal_awal_string,$tanggal_akhir_string), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        ob_end_clean();
        return $response;
    }
    public function approve_car_request(){
        PermintaanTransportasi::where('id',request()->id_request)->update([
            'status'=>1
        ]);
        foreach(request()->array_driver as $key=>$value){
            TujuanTransportasi::where('permintaan_transportasi_id',request()->id_request)->where('tujuan_id',$key)->update([
                'driver'=>$value,
                'vehicle'=>request()->array_vehicle[$key],
                'status'=>request()->array_status[$key],
                'alternative'=>request()->array_alternative[$key]
            ]);
        }
    }
    public function approve_this_car_request(){
        $status= request()->status;
        if($status==0){
            $this->_validationWithoutStatus(request());
        }else if($status==2){
            $this->_validationWithoutAlternate(request());
        }else if($status==1 || $status==3 || $status==4){
            $this->_validationWithoutDriver(request());
        }
        PermintaanTransportasi::where('id',request()->id_request)->update([
            'id_driver'=>request()->driver,
            'nomor_kendaraan'=>request()->vehicle,
            'alasan_status'=>request()->alternative,
            'status'=>$status,
        ]);
    }
    public function _validationWithoutStatus(){
        $validation=request()->validate([
            'status'=>'required|not_in:0',
        ]);
    }
    public function _validationWithoutAlternate(){
        $validation=request()->validate([
            'alternative'=>'required',
        ]);
    }
    public function _validationWithoutDriver(){
        $validation=request()->validate([
            'driver'=>'required',
            'vehicle'=>'required',
        ]);
    }
    public function change_status_car_request(){
        $status=request()->status;
        PermintaanTransportasi::where('id',request()->id_request)->update([
            'status'=>$status
        ]);
        return $status;
    }
    public function check_car_request(){
        $this->_validation5(request());
    }
    public function update_car_request(){
        $this->_validation5(request());
        PermintaanTransportasi::where('id',request()->id)->update([
            'id_driver'=>request()->driver,
            'nomor_kendaraan'=>request()->vehicle_id
        ]);
    }
    public function reject_car_request(){
        $this->_validation_reject(request());
        PermintaanTransportasi::where('id',request()->id_request)->update([
            'status'=>2,
            'alasan_status'=>request()->alasan_reject
        ]);
    }
    public function _validation_reject(){
        $validation=request()->validate([
            'alasan_reject'=>'required',
        ]);
    }
    public function print_penugasan_transportasi(){
        $id=request()->id;
        $data=DB::select("select a.created_at,a.tanggal_pemberangkatan,b.employee_name,b.nik,b.department_name from (select*from permintaan_transportasi where id=$id)a inner join employee_atribut b on a.enroll_id=b.enroll_id");
        $data2=DB::select("select b.jam_pemberangkatan,b.instansi detail_alamat_asal,concat(b.detail_alamat,' - ',c.subdis_name,' - ',d.dis_name,' - ',e.city_name,' - ',f.prov_name) desa,a.jarak_tempuh,a.instansi detail_alamat_tujuan,concat(a.detail_alamat,' - ',g.subdis_name,' - ',h.dis_name,' - ',i.city_name,' - ',j.prov_name) desa_tujuan,a.tanggal_kedatangan,a.jam_kedatangan,a.tujuan_pemberangkatan,a.jenis_barang,a.quantity,a.satuan,a.nama_penerima,a.keterangan_barang,a.nama_tamu,a.nomor_hp_tamu,a.karyawan_dinas_luar from (select*from tujuan_transportasi where permintaan_transportasi_id=$id order by tujuan_id)a inner join permintaan_transportasi b on a.permintaan_transportasi_id=b.id inner join subdistricts c on b.id_desa=c.subdis_id inner join districts d on c.dis_id=d.dis_id inner join cities e on d.city_id=e.city_id inner join provinces f on e.prov_id=f.prov_id inner join subdistricts g on a.subdistrict=g.subdis_id inner join districts h on g.dis_id=h.dis_id inner join cities i on h.city_id=i.city_id inner join provinces j on i.prov_id=j.prov_id");
        $data3=[];
        foreach($data2 as $key=>$value){
            $nama_karyawan_dinas=[];
            $nama_karyawan_dinas_luar='';
            if($value->karyawan_dinas_luar!=null){
                $karyawan_dinas=explode(",",$value->karyawan_dinas_luar);
                foreach($karyawan_dinas as $val){
                    $nama_karyawan_dinas[]=EmployeeAtribut::where('enroll_id',$val)->first()->employee_name;
                }
                $nama_karyawan_dinas_luar=(implode(", ",$nama_karyawan_dinas));
            }
            $data3[]=[
                'jam_pemberangkatan'=>$value->jam_pemberangkatan,
                'detail_alamat_asal'=>$value->detail_alamat_asal,
                'desa'=>$value->desa,
                'jarak_tempuh'=>$value->jarak_tempuh,
                'detail_alamat_tujuan'=>$value->detail_alamat_tujuan,
                'desa_tujuan'=>$value->desa_tujuan,
                'tanggal_kedatangan'=>$value->tanggal_kedatangan,
                'jam_kedatangan'=>$value->jam_kedatangan,
                'tujuan_pemberangkatan'=>$value->tujuan_pemberangkatan,
                'jenis_barang'=>$value->jenis_barang,
                'quantity'=>$value->quantity,
                'satuan'=>$value->satuan,
                'nama_penerima'=>$value->nama_penerima,
                'keterangan_barang'=>$value->keterangan_barang,
                'nama_tamu'=>$value->nama_tamu,
                'nomor_hp_tamu'=>$value->nomor_hp_tamu,
                'karyawan_dinas_luar'=>$nama_karyawan_dinas_luar
            ];
        }
        $fileName='Form Penugasan Transportasi '.date('His');
        $pdf = PDF::loadView('hris.laporan.penugasan_transportasi',["data"=>$data,"data2"=>$data3])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        return $pdf;
    }
    public function add_another_route(){
        $this->_validation6(request());
        $provinsi=request()->provinsi;
        $city=request()->city;
        $district=request()->districts;
        $subdistrict=request()->subdistricts;
        $tanggal_kedatatangan=request()->tanggal_kedatatangan;
        $jam_kedatangan=request()->jam_kedatangan;
        $province=DB::select("select prov_name from provinces where prov_id = '$provinsi'")[0]->prov_name;
        $city=DB::select("select city_name from cities where city_id = '$city'")[0]->city_name;
        $district=DB::select("select dis_name from districts where dis_id = '$district'")[0]->dis_name;
        $subdistrict=DB::select("select subdis_name from subdistricts where subdis_id = '$subdistrict'")[0]->subdis_name;
        $x[]=[
            'province'=>$province,
            'city'=>$city,
            'district'=>$district,
            'subdistrict'=>$subdistrict,
            'tanggal_kedatatangan'=>$tanggal_kedatatangan,
            'jam_kedatangan'=>$jam_kedatangan
        ];
        return $x;
    }
    public function add_another_route_2(){
        $this->_validation6(request());
        $query=TujuanTransportasi::where('permintaan_transportasi_id',request()->id)->get();
        return $query;
    }
    public function add_another_route_3(){
        $this->_validation6(request());
    }
    public function _validation5(){
        $validation=request()->validate([
            'driver'=>'required',
            'vehicle_id'=>'required',
        ]);
    }
    public function _validation6(){
        $validation=request()->validate([
            'provinsi'=>'required',
            'city'=>'required',
            'districts'=>'required',
            'subdistricts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
        ],
        [
            'provinsi.required'=>'harus dipilih',
            'city.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'subdistricts.required'=>'harus dipilih',
        ]);
    }
    // public function ajax_getallemployeeatribut()
    // {
    //     $loggedAdmin = Auth::guard('admin')->user();
    //     $email = $loggedAdmin->email;
    //     $id_user = $loggedAdmin->enroll_id;
    //     $department_id = EmployeeAtribut::where('enroll_id', $id_user)->first()->department_id;
    //     $query =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')->where('status_aktif', 'AKTIF')->where('department_id', $department_id)->groupby('enroll_id')->orderby('employee_name', 'asc')->get();
    //     return $query;

    // }

    public function ajax_getallemployeeatribut()
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $id_user = $loggedAdmin->enroll_id;

        $site_nirwana_id = EmployeeAtribut::where('enroll_id', $id_user)->first()->site_nirwana_id;
        $department_id = EmployeeAtribut::where('enroll_id', $id_user)->first()->department_id;
       if ($site_nirwana_id == 'NAG') {
            if (in_array($department_id, ['DEP13', 'DEP07', 'DEP23'])) {
                $allowedDepartments = ['DEP13', 'DEP07', 'DEP23'];
            } elseif (in_array($department_id, ['DEP03', 'DEP04', 'DEP01', 'DEP14'])) {
                $allowedDepartments = ['DEP03', 'DEP04', 'DEP01', 'DEP14'];
            } elseif (in_array($department_id, ['DEP17', 'DEP18'])) {
                $allowedDepartments = ['DEP17', 'DEP18'];
            } elseif (in_array($department_id, ['DEP22', 'DEP19'])) {
                $allowedDepartments = ['DEP22', 'DEP19'];
            } elseif ($department_id == 'DEP08') {
                $allowedDepartments = ['DEP08', 'DEP02'];
            } else {
                $allowedDepartments = [$department_id];
            }

            $query = EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, concat(enroll_id, " - ", nik, " - ", employee_name) as select_employee')
                ->where('status_aktif', 'AKTIF')
                ->where('site_nirwana_id', 'NAG')
                ->whereIn('department_id', $allowedDepartments)
                ->groupBy('enroll_id', 'nik', 'employee_name')
                ->orderBy('employee_name', 'asc')
                ->get();
        } elseif ($site_nirwana_id == 'NAK') {
            $query = EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, concat(enroll_id, " - ", nik, " - ", employee_name) as select_employee')
                ->where('status_aktif', 'AKTIF')
                ->where('site_nirwana_id', 'NAK')
                ->groupBy('enroll_id', 'nik', 'employee_name')
                ->orderBy('employee_name', 'asc')
                ->get();
        }

        return $query;
    }

    public function get_province(){
        $province = DB::select("select*from provinces order by prov_id");
        return $province;
    }
    public function get_all_zone_name(){
        $subdis_id=request()->subdis_id;
        $zona = Subdistrict::where('subdis_id',$subdis_id)->with('district.city.prov')->first();
        $tujuan_trans[]=[
            'prov_name'=>$zona->district->city->prov->prov_name,
            'city_name'=>$zona->district->city->city_name,
            'dis_name'=>$zona->district->dis_name,
            'subdis_name'=>$zona->subdis_name,
            'instansi'=>request()->instansi,
            'detail_alamat'=>request()->detail_alamat,
            'tanggal_kedatangan'=>request()->tanggal_kedatangan,
            'jam_kedatangan'=>request()->jam_kedatangan,
            'keterangan'=>request()->keterangan
        ];
        return $tujuan_trans;
    }
    public function get_cities(){
        $provinsi = request()->provinsi;
        $cities = DB::select("select*from cities where prov_id = '$provinsi' order by city_name");
        return $cities;
    }
    public function get_cities_name(){
        $provinsi = request()->provinsi;
        $prov_id=Province::where('prov_name',$provinsi)->first()->prov_id;
        $cities = DB::select("select*from cities where prov_id = '$prov_id' order by city_name");
        return $cities;
    }
    public function get_districts(){
        $cities = request()->cities;
        $districts = DB::select("select*from districts where city_id = '$cities' order by dis_name");
        return $districts;
    }
    public function get_districts_name(){
        $prov_name=request()->prov;
        $cities = request()->cities;
        $cities_id=City::where('city_name',$cities)->whereHas('prov',function($query)use($prov_name){
            $query->where('prov_name',$prov_name);
        })->first()->city_id;
        $districts = DB::select("select*from districts where city_id = '$cities_id' order by dis_name");
        return $districts;
    }
    public function get_subdistricts(){
        $districts = request()->districts;
        $subdistricts = DB::select("select*from subdistricts where dis_id = '$districts' order by subdis_name");
        return $subdistricts;
    }
    public function get_subdistricts_name(){
        $prov=request()->prov;
        $city=request()->city;
        $districts = request()->districts;
        $districts_id=District::where('dis_name',$districts)->whereHas('city',function($query)use($city,$prov){
            $query->where('city_name',$city);
        })->whereHas('city.prov',function($query)use($prov){
            $query->where('prov_name',$prov);
        })->first()->dis_id;
        $subdistricts = DB::select("select*from subdistricts where dis_id = '$districts_id' order by subdis_name");
        return $subdistricts;
    }
    public function update_car_request_user(){
        $loggedAdmin=Auth::guard('admin')->user();
        $created_by=$loggedAdmin->enroll_id;
        $id=request()->id;
        $status=PermintaanTransportasi::where('id',$id)->first()->status;
        if($status!=0){
            return 'approved';
        }else{
            $query=PermintaanTransportasi::where('id',$id)->update([
                'enroll_id'=>request()->employee,
                'id_desa'=>request()->sub_districts,
                'instansi'=>request()->instansi,
                'detail_alamat'=>request()->detail_alamat,
                'tanggal_pemberangkatan'=>request()->tanggal_pemberangkatan,
                'jam_pemberangkatan'=>request()->jam_pemberangkatan,
                'status'=>0,
                'created_by'=>$created_by
            ]);
            TujuanTransportasi::where('permintaan_transportasi_id',$id)->delete();
            foreach(request()->tujuan_pemberangkatan_array as $key=>$value){
                $date = str_replace('/', '-', request()->tanggal_pemberangkatan_array[$key]);
                TujuanTransportasi::create([
                    'permintaan_transportasi_id'=>$id,
                    'tujuan_id'=>$key,
                    'subdistrict'=>request()->desa_array[$key],
                    'instansi'=>request()->instansi_array[$key],
                    'detail_alamat'=>request()->detail_alamat_array[$key],
                    'tanggal_kedatangan'=>date('Y-m-d', strtotime($date)),
                    'jam_kedatangan'=>request()->jam_pemberangkatan_array[$key],
                    'jarak_tempuh'=>request()->jarak_tempuh_array[$key],
                    'tujuan_pemberangkatan'=>request()->tujuan_pemberangkatan_array[$key],
                    'jenis_barang'=>request()->jenis_barang_array[$key],
                    'quantity'=>request()->quantity_array[$key],
                    'satuan'=>request()->satuan_array[$key],
                    'nama_penerima'=>request()->nama_penerima_array[$key],
                    'keterangan_barang'=>request()->keterangan_barang_array[$key],
                    'nama_tamu'=>request()->nama_tamu_array[$key],
                    'nomor_hp_tamu'=>request()->nomor_hp_tamu_array[$key],
                    'karyawan_dinas_luar'=>request()->karyawan_dinas_luar_array[$key],
                ]);
            }
        }
    }
    public function update_car_request_administrator(){
        $loggedAdmin=Auth::guard('admin')->user();
        $created_by=$loggedAdmin->enroll_id;
        $id=request()->id;
        $status=PermintaanTransportasi::where('id',$id)->count();
        if($status==0){
            return 'hapus';
        }else{
            $query=PermintaanTransportasi::where('id',$id)->update([
                'status'=>request()->status,
                'alasan_status'=>request()->alternative
            ]);
            foreach(request()->array_driver as $key=>$value){
                TujuanTransportasi::where('tujuan_id',$key)->update([
                    'driver'=>$value,
                    'vehicle'=>request()->array_vehicle[$key],
                    'status'=>request()->array_status[$key],
                    'alternative'=>request()->array_alternative[$key]
                ]);
            }
        }
    }
    public function summary_driver_task(){
        $loggedAdmin = Auth::guard('admin')->user();
        $id_user = $loggedAdmin->enroll_id;
        $pengajuan_transportasi=PermintaanTransportasi::where('status',0)->count();
        $drivers=EmployeeAtribut::where('sub_dept_id','DEP08SUB002')->where(function($query){
            $query->where('status_aktif','AKTIF')
            ->orWhere('tanggal_resign','>',date('Y-m-d'));
        })->get();
        return View::make('hris/ga/summary_driver_task', $this->data,compact('id_user','pengajuan_transportasi','drivers'));
    }
    public function get_data_summary_driver(){
        $inTanggal='';
        if(request()->tanggal!=''){
            $tanggal=request()->tanggal;
            $inTanggal="where tanggal_pemberangkatan = '".$tanggal."'";
        }
        $inDriver='';
        if(request()->driver!=''){
            $driver=request()->driver;
            $inDriver=" and driver = $driver";
        }

        $data_input=DB::select("SELECT b.enroll_id,g.nik,g.employee_name,g.department_name,b.instansi,b.detail_alamat,concat(c.subdis_name,' - ',d.dis_name,' - ',e.city_name,' - ',f.prov_name) detail_alamat_asal,b.tanggal_pemberangkatan,b.jam_pemberangkatan,a.instansi instansi_tujuan,a.detail_alamat detail_alamat2,concat(h.subdis_name,' - ',i.dis_name,' - ',j.city_name,' - ',k.prov_name) detail_alamat_tujuan,a.tanggal_kedatangan,a.jam_kedatangan,a.jarak_tempuh,a.tujuan_pemberangkatan,a.jenis_barang,a.quantity,a.satuan,a.nama_penerima,a.keterangan_barang,a.nama_tamu,a.nomor_hp_tamu,a.karyawan_dinas_luar,a.status,a.driver,l.employee_name nama_driver,l.nik nik_driver,a.vehicle,concat(m.merk,' ',m.tipe,' (',m.plat_no,')') vehicle_merk FROM (select*from tujuan_transportasi where permintaan_transportasi_id in (select id from permintaan_transportasi $inTanggal) and driver is not null $inDriver)a inner join permintaan_transportasi b on a.permintaan_transportasi_id=b.id inner join subdistricts c on b.id_desa=c.subdis_id inner join districts d on c.dis_id=d.dis_id inner join cities e on d.city_id=e.city_id inner join provinces f on e.prov_id=f.prov_id inner join employee_atribut g on b.enroll_id=g.enroll_id inner join subdistricts h on a.subdistrict=h.subdis_id inner join districts i on h.dis_id=i.dis_id inner join cities j on i.city_id=j.city_id inner join provinces k on j.prov_id=k.prov_id left join employee_atribut l on a.driver=l.enroll_id left join ga_master_kendaraan m on a.vehicle=m.id ORDER BY b.tanggal_pemberangkatan desc,a.tujuan_id");
        $data_input2=[];
        foreach($data_input as $key=>$value){
            $nama_karyawan_dinas=[];
            $nama_karyawan_dinas_luar='';
            if($value->karyawan_dinas_luar!=null){
                $karyawan_dinas=explode(",",$value->karyawan_dinas_luar);
                foreach($karyawan_dinas as $val){
                    $nama_karyawan_dinas[]=EmployeeAtribut::where('enroll_id',$val)->first()->employee_name;
                }
                $nama_karyawan_dinas_luar=(implode(", ",$nama_karyawan_dinas));
            }
            $data_input2[]=[
                'enroll_id'=>$value->enroll_id,
                'employee_name'=>$value->employee_name,
                'department_name'=>$value->department_name,
                'nama_driver'=>$value->nama_driver,
                'instansi'=>$value->instansi,
                'detail_alamat'=>$value->detail_alamat,
                'detail_alamat_asal'=>$value->detail_alamat_asal,
                'tanggal_pemberangkatan'=>$value->tanggal_pemberangkatan,
                'jam_pemberangkatan'=>$value->jam_pemberangkatan,
                'instansi_tujuan'=>$value->instansi_tujuan,
                'detail_alamat2'=>$value->detail_alamat2,
                'detail_alamat_tujuan'=>$value->detail_alamat_tujuan,
                'jarak_tempuh'=>$value->jarak_tempuh,
                'tanggal_kedatangan'=>$value->tanggal_kedatangan,
                'jam_kedatangan'=>$value->jam_kedatangan,
                'tujuan_pemberangkatan'=>$value->tujuan_pemberangkatan,
                'jenis_barang'=>$value->jenis_barang,
                'quantity'=>$value->quantity,
                'satuan'=>$value->satuan,
                'nama_penerima'=>$value->nama_penerima,
                'keterangan_barang'=>$value->keterangan_barang,
                'nama_tamu'=>$value->nama_tamu,
                'nomor_hp_tamu'=>$value->nomor_hp_tamu,
                'karyawan_dinas_luar'=>$nama_karyawan_dinas_luar,
                'status'=>$value->status
            ];
        }
        return DataTables::of($data_input2)->toJson();
    }
    public function print_pdf_summary_driver(){
        $inTanggal='';
        $tanggal='';
        if(request()->tanggal!=''){
            $tanggal=request()->tanggal;
            $inTanggal="where tanggal_pemberangkatan = '$tanggal'";
        }
        $inDriver='';
        $driver_name='';
        if(request()->driver!=''){
            $driver=request()->driver;
            $inDriver=" and driver = $driver";
            $driver_name=EmployeeAtribut::where('enroll_id',$driver)->first()->employee_name;
        }
        $data_input=DB::select("SELECT b.enroll_id,g.nik,g.employee_name,g.department_name,b.instansi,b.detail_alamat,concat(c.subdis_name,' - ',d.dis_name,' - ',e.city_name,' - ',f.prov_name) detail_alamat_asal,b.tanggal_pemberangkatan,b.jam_pemberangkatan,a.instansi instansi_tujuan,a.detail_alamat detail_alamat2,concat(h.subdis_name,' - ',i.dis_name,' - ',j.city_name,' - ',k.prov_name) detail_alamat_tujuan,a.tanggal_kedatangan,a.jam_kedatangan,a.jarak_tempuh,a.tujuan_pemberangkatan,a.jenis_barang,a.quantity,a.satuan,a.nama_penerima,a.keterangan_barang,a.nama_tamu,a.nomor_hp_tamu,a.karyawan_dinas_luar,a.status,a.driver,l.employee_name nama_driver,l.nik nik_driver,a.vehicle,concat(m.merk,' ',m.tipe,' (',m.plat_no,')') vehicle_merk FROM (select*from tujuan_transportasi where permintaan_transportasi_id in (select id from permintaan_transportasi $inTanggal) and driver is not null $inDriver)a inner join permintaan_transportasi b on a.permintaan_transportasi_id=b.id inner join subdistricts c on b.id_desa=c.subdis_id inner join districts d on c.dis_id=d.dis_id inner join cities e on d.city_id=e.city_id inner join provinces f on e.prov_id=f.prov_id inner join employee_atribut g on b.enroll_id=g.enroll_id inner join subdistricts h on a.subdistrict=h.subdis_id inner join districts i on h.dis_id=i.dis_id inner join cities j on i.city_id=j.city_id inner join provinces k on j.prov_id=k.prov_id left join employee_atribut l on a.driver=l.enroll_id left join ga_master_kendaraan m on a.vehicle=m.id ORDER BY b.tanggal_pemberangkatan desc,a.tujuan_id");
        $data_input2=[];
        foreach($data_input as $key=>$value){
            $nama_karyawan_dinas=[];
            $nama_karyawan_dinas_luar='';
            if($value->karyawan_dinas_luar!=null){
                $karyawan_dinas=explode(",",$value->karyawan_dinas_luar);
                foreach($karyawan_dinas as $val){
                    $nama_karyawan_dinas[]=EmployeeAtribut::where('enroll_id',$val)->first()->employee_name;
                }
                $nama_karyawan_dinas_luar=(implode(", ",$nama_karyawan_dinas));
            }
            $data_input2[]=[
                'enroll_id'=>$value->enroll_id,
                'employee_name'=>$value->employee_name,
                'department_name'=>$value->department_name,
                'nama_driver'=>$value->nama_driver,
                'instansi'=>$value->instansi,
                'detail_alamat'=>$value->detail_alamat,
                'detail_alamat_asal'=>$value->detail_alamat_asal,
                'tanggal_pemberangkatan'=>$value->tanggal_pemberangkatan,
                'jam_pemberangkatan'=>$value->jam_pemberangkatan,
                'instansi_tujuan'=>$value->instansi_tujuan,
                'detail_alamat2'=>$value->detail_alamat2,
                'detail_alamat_tujuan'=>$value->detail_alamat_tujuan,
                'jarak_tempuh'=>$value->jarak_tempuh,
                'tanggal_kedatangan'=>$value->tanggal_kedatangan,
                'jam_kedatangan'=>$value->jam_kedatangan,
                'tujuan_pemberangkatan'=>$value->tujuan_pemberangkatan,
                'jenis_barang'=>$value->jenis_barang,
                'quantity'=>$value->quantity,
                'satuan'=>$value->satuan,
                'nama_penerima'=>$value->nama_penerima,
                'keterangan_barang'=>$value->keterangan_barang,
                'nama_tamu'=>$value->nama_tamu,
                'nomor_hp_tamu'=>$value->nomor_hp_tamu,
                'karyawan_dinas_luar'=>$nama_karyawan_dinas_luar,
                'status'=>$value->status
            ];
        }
        $fileName='summary_driver_task '.date('His');
        $pdf = PDF::loadView('hris.laporan.summary_driver_task',["data"=>$data_input2,"driver_name"=>$driver_name,"tanggal"=>$tanggal])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        return $pdf;
    }
    public function store_car_request(){
        $loggedAdmin=Auth::guard('admin')->user();
        $created_by=$loggedAdmin->enroll_id;
        $query=PermintaanTransportasi::create([
            'enroll_id'=>request()->employee,
            'id_desa'=>request()->sub_districts,
            'instansi'=>request()->instansi,
            'detail_alamat'=>request()->detail_alamat,
            'tanggal_pemberangkatan'=>request()->tanggal_pemberangkatan,
            'jam_pemberangkatan'=>request()->jam_pemberangkatan,
            'status'=>0,
            'created_by'=>$created_by
        ]);
        foreach(request()->tujuan_pemberangkatan_array as $key=>$value){
            $date = str_replace('/', '-', request()->tanggal_pemberangkatan_array[$key]);
            TujuanTransportasi::create([
                'permintaan_transportasi_id'=>$query->id,
                'tujuan_id'=>$key,
                'subdistrict'=>request()->desa_array[$key],
                'instansi'=>request()->instansi_array[$key],
                'detail_alamat'=>request()->detail_alamat_array[$key],
                'tanggal_kedatangan'=>date('Y-m-d', strtotime($date)),
                'jam_kedatangan'=>request()->jam_pemberangkatan_array[$key],
                'jarak_tempuh'=>request()->jarak_tempuh_array[$key],
                'tujuan_pemberangkatan'=>request()->tujuan_pemberangkatan_array[$key],
                'jenis_barang'=>request()->jenis_barang_array[$key],
                'quantity'=>request()->quantity_array[$key],
                'satuan'=>request()->satuan_array[$key],
                'nama_penerima'=>request()->nama_penerima_array[$key],
                'keterangan_barang'=>request()->keterangan_barang_array[$key],
                'nama_tamu'=>request()->nama_tamu_array[$key],
                'nomor_hp_tamu'=>request()->nomor_hp_tamu_array[$key],
                'karyawan_dinas_luar'=>request()->karyawan_dinas_luar_array[$key],
                'status'=>0
            ]);
        }
    }
    public function post_car_request(){
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->enroll_id;
        if((request()->cb_antar_tamu==1 || request()->cb_jemput_tamu==1) && request()->cb_antar_barang!=1 && request()->cb_jemput_barang!=1 && request()->cb_antar_dinas!=1 && request()->cb_jemput_dinas!=1){
            $this->_validation2(request());
        }else if((request()->cb_antar_barang==1 || request()->cb_jemput_barang==1) && request()->cb_antar_tamu!=1 && request()->cb_jemput_tamu!=1 && request()->cb_antar_dinas!=1 && request()->cb_jemput_dinas!=1){
            $this->_validation3(request());
        }else if((request()->cb_antar_dinas==1 || request()->cb_jemput_dinas==1) && request()->cb_antar_tamu!=1 && request()->cb_jemput_tamu!=1 && request()->cb_antar_barang!=1 && request()->cb_antar_barang!=1){
            $this->_validation11(request());
        }else if((request()->cb_antar_tamu==1 || request()->cb_jemput_tamu==1) && (request()->cb_antar_barang==1 || request()->cb_jemput_barang==1) && request()->cb_antar_dinas!=1 && request()->cb_jemput_dinas!=1){
            $this->_validation12(request());
        }else if((request()->cb_antar_tamu==1 || request()->cb_jemput_tamu==1) && request()->cb_antar_barang!=1 && request()->cb_jemput_barang!=1 && (request()->cb_antar_dinas==1 || request()->cb_jemput_dinas==1)){
            $this->_validation13(request());
        }else if(request()->cb_antar_tamu!=1 && request()->cb_jemput_tamu!=1 && (request()->cb_antar_barang==1 || request()->cb_jemput_barang==1) && (request()->cb_antar_dinas==1 || request()->cb_jemput_dinas==1)){
            $this->_validation14(request());
        }else if((request()->cb_antar_barang==1 || request()->cb_jemput_barang==1) && (request()->cb_antar_tamu==1 || request()->cb_jemput_tamu==1) && (request()->cb_antar_dinas==1 || request()->cb_antar_dinas==1)){
            $this->_validation4(request());
        }else{
            $this->_validation(request());
        }
        $enroll_id_array[]=explode(",",request()->employee);
        $enroll_id= $enroll_id_array[0][0];
        $id_desa=request()->sub_districts;
        $detail_alamat=request()->detail_alamat;
        $id_desa_tujuan=request()->sub_districts_2;
        $detail_alamat_tujuan=request()->detail_alamat_2;
        $tanggal_pemberangkatan=request()->tanggal_pemberangkatan;
        $jam_pemberangkatan=request()->jam_pemberangkatan;
        $tanggal_kedatangan=request()->tanggal_kedatangan;
        $jam_kedatangan=request()->jam_kedatangan;
        $tujuan_pemberangkatan=request()->tujuan;
        $jarak_tempuh=request()->jarak_tempuh;
        $nama_tamu=request()->nama_tamu;
        $nomor_tamu=request()->nomor_tamu;
        $instansi_tamu=request()->instansi_tamu;
        $jenis_barang=request()->jenis_barang;
        $quantity=request()->quantity;
        $satuan=request()->satuan;
        $instansi=request()->instansi;
        $nama_instansi=request()->nama_instansi;
        $keterangan_barang=request()->keterangan_barang;
        $tujuan=request()->tujuan;
        $date_now=Carbon::now();
        $enroll_id_dinas=null;
        if(request()->employee_dinas!='' || request()->employee_dinas!=null){
            $enroll_id_dinas=implode(',', request()->employee_dinas);
        }
        $query=PermintaanTransportasi::create([
            'enroll_id'=>$enroll_id,
            'id_desa'=>$id_desa,
            'detail_alamat'=>$detail_alamat,
            'id_desa_tujuan'=>$id_desa_tujuan,
            'detail_alamat_tujuan'=>$detail_alamat_tujuan,
            'tanggal_pemberangkatan'=>$tanggal_pemberangkatan,
            'jam_pemberangkatan'=>$jam_pemberangkatan,
            'tanggal_kedatangan'=>$tanggal_kedatangan,
            'jam_kedatangan'=>$jam_kedatangan,
            'tujuan_pemberangkatan'=>$tujuan_pemberangkatan,
            'jarak_tempuh'=>$jarak_tempuh,
            'nama_tamu'=>$nama_tamu,
            'nomor_hp_tamu'=>$nomor_tamu,
            'instansi_tamu'=>$instansi_tamu,
            'jenis_barang'=>$jenis_barang,
            'quantity'=>$quantity,
            'satuan'=>$satuan,
            'nama_instansi'=>$instansi,
            'nama_penerima'=>$nama_instansi,
            'keterangan_barang'=>$keterangan_barang,
            'karyawan_dinas'=>$enroll_id_dinas,
            'status'=>0,
            'created_by'=>$email
        ]);
        foreach(request()->tujuan_array as $key=>$value){
            $province=Province::where('prov_id',request()->provinsi_array[$key])->first()->prov_name;
            $city=City::where('city_id',request()->city_array[$key])->first()->city_name;
            $district=District::where('dis_id',request()->district_array[$key])->first()->dis_name;
            $subdistrict=Subdistrict::where('subdis_id',request()->subdistrict_array[$key])->first()->subdis_name;
            if(request()->instansi_array[$key]==null){
                $instansi=' ';
            }else{
                $instansi=request()->instansi_array[$key];
            }
            if(request()->keterangan_array[$key]==null){
                $keterangan=' ';
            }else{
                $keterangan=request()->keterangan_array[$key];
            }
            DB::table('tujuan_transportasi')->insert([
                'permintaan_transportasi_id'=>$query->id,
                'tujuan_id'=>$value,
                'district'=>$district,
                'city'=>$city,
                'provinsi'=>$province,
                'subdistrict'=>$subdistrict,
                'instansi'=>$instansi,
                'detail_alamat'=>request()->detail_alamat_array[$key],
                'tanggal_kedatangan'=>request()->tanggal_kedatangan_array[$key],
                'jam_kedatangan'=>request()->jam_kedatangan_array[$key],
                'keterangan'=>$keterangan,
                'created_at'=>$date_now,
                'updated_at'=>$date_now
            ]);
        }
    }
    public function lihat_detail(){
        $loggedAdmin = Auth::guard('admin')->user();
        $id_user = $loggedAdmin->enroll_id;
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        $provincies=DB::select('select * from provinces order by prov_id');
        $id=request()->id;
        $drivers=EmployeeAtribut::where('sub_dept_id','DEP08SUB002')->where(function($query){
            $query->where('status_aktif','AKTIF')
            ->orWhere('tanggal_resign','>',date('Y-m-d'));
        })->get();
        $vehicles =  DB::connection('laravel_nds')->select(
            DB::raw("select*from ga_master_kendaraan") );
        $pengajuan_transportasi=DB::select("select a.created_by,a.enroll_id,a.id,b.employee_name,b.nik,b.department_name,b.sub_dept_name,a.id_desa,c.subdis_name nama_desa,d.dis_id,d.dis_name nama_kecamatan,e.city_id,e.city_name nama_kota,f.prov_id,f.prov_name nama_provinsi,a.instansi,a.detail_alamat,a.tanggal_pemberangkatan,a.jam_pemberangkatan,a.status,a.created_at,a.updated_at from (select*from permintaan_transportasi where id='$id') a inner join employee_atribut b on a.enroll_id=b.enroll_id inner join subdistricts c on a.id_desa=c.subdis_id inner join districts d on c.dis_id=d.dis_id inner join cities e on d.city_id=e.city_id inner join provinces f on e.prov_id=f.prov_id");
        return View::make('hris/ga/data_detail_pengajuan_transportasi_2', $this->data,compact('pengajuan_transportasi','provincies','id_user','drivers','vehicles'));
    }
    public function lihat_detail_admin(){
        $loggedAdmin = Auth::guard('admin')->user();
        $id_user = $loggedAdmin->enroll_id;
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        $provincies=DB::select('select * from provinces order by prov_id');
        $id=request()->id;
        $drivers=EmployeeAtribut::where('sub_dept_id','DEP08SUB002')->where(function($query){
            $query->where('status_aktif','AKTIF')
            ->orWhere('tanggal_resign','>',date('Y-m-d'));
        })->get();
        $vehicles =  DB::connection('laravel_nds')->select(
            DB::raw("select*from ga_master_kendaraan") );
        $pengajuan_transportasi=DB::select("select a.created_by,a.enroll_id,a.id,b.employee_name,b.nik,b.department_name,b.sub_dept_name,a.id_desa,c.subdis_name nama_desa,d.dis_id,d.dis_name nama_kecamatan,e.city_id,e.city_name nama_kota,f.prov_id,f.prov_name nama_provinsi,a.instansi,a.detail_alamat,a.tanggal_pemberangkatan,a.jam_pemberangkatan,a.status,a.created_at,a.updated_at from (select*from permintaan_transportasi where id='$id') a inner join employee_atribut b on a.enroll_id=b.enroll_id inner join subdistricts c on a.id_desa=c.subdis_id inner join districts d on c.dis_id=d.dis_id inner join cities e on d.city_id=e.city_id inner join provinces f on e.prov_id=f.prov_id");
        return View::make('hris/ga/data_detail_pengajuan_transportasi_2_admin', $this->data,compact('pengajuan_transportasi','provincies','id_user','drivers','vehicles'));
    }
    public function get_employee_dinas(){
        $employee_array = explode(',', request()->id);
        foreach($employee_array as $key=>$value){
            $array_employee[$key]=EmployeeAtribut::where('enroll_id',$value)->first()->employee_name;
        }
        $string_employee_name=implode(',',$array_employee);
        return $string_employee_name;
    }
    public function edit_detail(){
        $loggedAdmin = Auth::guard('admin')->user();
        $id_user = $loggedAdmin->enroll_id;
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        $provincies=DB::select('select * from provinces order by prov_id');
        $id=request()->id;
        $pengajuan_transportasi=DB::select("select a.created_by,a.enroll_id,a.id,b.employee_name,b.nik,b.department_name,b.sub_dept_name,a.id_desa,d.dis_id,e.city_id,f.prov_id,a.instansi,a.detail_alamat,a.tanggal_pemberangkatan,a.jam_pemberangkatan,a.created_at,a.updated_at from (select*from permintaan_transportasi where id='$id') a inner join employee_atribut b on a.enroll_id=b.enroll_id inner join subdistricts c on a.id_desa=c.subdis_id inner join districts d on c.dis_id=d.dis_id inner join cities e on d.city_id=e.city_id inner join provinces f on e.prov_id=f.prov_id");
        return View::make('hris/ga/edit_detail_pengajuan_transportasi_2', $this->data,compact('pengajuan_transportasi','provincies','id_user'));
    }
    public function get_data_detail(){
        $id=request()->id;
        $pengajuan_transportasi=DB::select("select a.created_by,a.enroll_id,a.id,b.employee_name,b.nik,b.department_name,b.sub_dept_name,a.id_desa,d.dis_id,e.city_id,f.prov_id,a.instansi,a.detail_alamat,a.tanggal_pemberangkatan,a.jam_pemberangkatan,a.status,a.created_at,a.updated_at from (select*from permintaan_transportasi where id='$id') a inner join employee_atribut b on a.enroll_id=b.enroll_id inner join subdistricts c on a.id_desa=c.subdis_id inner join districts d on c.dis_id=d.dis_id inner join cities e on d.city_id=e.city_id inner join provinces f on e.prov_id=f.prov_id");
        return $pengajuan_transportasi;
    }
    public function get_tujuan_detail(){
        $id=request()->id;
        $tujuan_transportasi=DB::select("SELECT a.*,e.prov_id,e.prov_name,d.city_id,d.city_name,c.dis_id,c.dis_name,b.subdis_name,f.employee_name driver_name,concat('(',g.plat_no,') ',g.merk,' ',g.tipe) vehicle_name FROM (select*from tujuan_transportasi where permintaan_transportasi_id=$id order by tujuan_id)a inner join subdistricts b on a.subdistrict=b.subdis_id inner join districts c on b.dis_id=c.dis_id inner join cities d on c.city_id=d.city_id inner join provinces e on d.prov_id=e.prov_id left join employee_atribut f on a.driver=f.enroll_id left join ga_master_kendaraan g on a.vehicle=g.id");
        return $tujuan_transportasi;
    }
    public function get_route_from_user(){
        $user=request()->user;
        return DB::select("select*from tujuan_transportasi where permintaan_transportasi_id in (select id from permintaan_transportasi where created_by = '$user') group by provinsi,city,district,subdistrict,detail_alamat");
    }
    public function get_route_from_user_choice(){
        $query=TujuanTransportasi::where('permintaan_transportasi_id',request()->permintaan_transportasi_id)->where('tujuan_id',request()->tujuan_id)->get();
        return $query;
    }
    public function show_another_route(){
        $id=request()->id;
        $route=DB::select("select a.tujuan_id,a.subdistrict subdis_name,a.district dis_name,a.city city_name,a.provinsi prov_name,a.instansi,a.detail_alamat,a.tanggal_kedatangan,substring(a.jam_kedatangan,1,5) jam_kedatangan,a.keterangan from (select* from tujuan_transportasi where permintaan_transportasi_id='$id' order by tujuan_id) a");
        return $route;
    }
    private function _validation(){
        $validation=request()->validate([
            'employee'=>'required',
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
        ],
        [
            'employee.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih ya',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
        ]);
    }
    private function _validation2(){
        $validation=request()->validate([
            'employee'=>'required',
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'nama_tamu'=>'required',
            'nomor_tamu'=>'required',
            'instansi_tamu'=>'required'
        ],
        [
            'employee.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
        ]);
    }
    private function _validation3(){
        $validation=request()->validate([
            'employee'=>'required',
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'jenis_barang'=>'required',
            'quantity'=>'required',
            'instansi'=>'required',
            'nama_instansi'=>'required'
        ],
        [
            'employee.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
        ]);
    }
    private function _validation11(){
        $validation=request()->validate([
            'employee'=>'required',
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'employee_dinas'=>'required',
        ],
        [
            'employee.required'=>'harus dipilih',
            'employee_dinas.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
        ]);
    }
    private function _validation4(){
        $validation=request()->validate([
            'employee'=>'required',
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'nama_tamu'=>'required',
            'nomor_tamu'=>'required',
            'instansi_tamu'=>'required',
            'jenis_barang'=>'required',
            'quantity'=>'required',
            'instansi'=>'required',
            'nama_instansi'=>'required',
            'employee_dinas'=>'required',
        ],
        [
            'employee.required'=>'harus dipilih',
            'employee_dinas.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
        ]);
    }
    private function _validation12(){
        $validation=request()->validate([
            'employee'=>'required',
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'nama_tamu'=>'required',
            'nomor_tamu'=>'required',
            'instansi_tamu'=>'required',
            'jenis_barang'=>'required',
            'quantity'=>'required',
            'instansi'=>'required',
            'nama_instansi'=>'required',
        ],
        [
            'employee.required'=>'harus dipilih',
            'employee_dinas.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
        ]);
    }
    private function _validation13(){
        $validation=request()->validate([
            'employee'=>'required',
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'nama_tamu'=>'required',
            'nomor_tamu'=>'required',
            'instansi_tamu'=>'required',
            'employee_dinas'=>'required',
        ],
        [
            'employee.required'=>'harus dipilih',
            'employee_dinas.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
        ]);
    }
    private function _validation14(){
        $validation=request()->validate([
            'employee'=>'required',
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'jenis_barang'=>'required',
            'quantity'=>'required',
            'instansi'=>'required',
            'nama_instansi'=>'required',
            'employee_dinas'=>'required',
        ],
        [
            'employee.required'=>'harus dipilih',
            'employee_dinas.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
        ]);
    }

    private function _validation7(){
        $validation=request()->validate([
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
        ],
        [
            'employee.required'=>'harus dipilih',
        ]);
    }
    private function _validation8(){
        $validation=request()->validate([
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'nama_tamu'=>'required',
            'nomor_tamu'=>'required',
            'instansi_tamu'=>'required'
        ],
        [
            'employee.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
        ]);
    }
    private function _validation9(){
        $validation=request()->validate([
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'jenis_barang'=>'required',
            'quantity'=>'required',
            'satuan'=>'required',
            'instansi'=>'required',
            'nama_instansi'=>'required'
        ],
        [
            'employee.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
            'detail_alamat_2'=>'harus dipilih',
        ]);
    }
    private function _validation15(){
        $validation=request()->validate([
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'employee_dinas'=>'required',
        ],
        [
            'employee_dinas.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
            'detail_alamat_2'=>'harus dipilih',
        ]);
    }
    private function _validation10(){
        $validation=request()->validate([
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'nama_tamu'=>'required',
            'nomor_tamu'=>'required',
            'instansi_tamu'=>'required',
            'jenis_barang'=>'required',
            'quantity'=>'required',
            'satuan'=>'required',
            'instansi'=>'required',
            'nama_instansi'=>'required'
        ],
        [
            'employee.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
            'detail_alamat_2'=>'harus dipilih',
        ]);
    }
    private function _validation16(){
        $validation=request()->validate([
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'nama_tamu'=>'required',
            'nomor_tamu'=>'required',
            'instansi_tamu'=>'required',
            'employee_dinas'=>'required'
        ],
        [
            'employee.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
            'detail_alamat_2'=>'harus dipilih',
        ]);
    }
    private function _validation17(){
        $validation=request()->validate([
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'jenis_barang'=>'required',
            'quantity'=>'required',
            'satuan'=>'required',
            'instansi'=>'required',
            'nama_instansi'=>'required',
            'employee_dinas'=>'required'
        ],
        [
            'employee_dinas.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
            'detail_alamat_2'=>'harus dipilih',
        ]);
    }
    private function _validation18(){
        $validation=request()->validate([
            'provinsi'=>'required',
            'cities'=>'required',
            'districts'=>'required',
            'sub_districts'=>'required',
            'detail_alamat'=>'required',
            'tanggal_pemberangkatan'=>'required',
            'jam_pemberangkatan'=>'required',
            'tanggal_kedatangan'=>'required',
            'jam_kedatangan'=>'required',
            'provinsi_2'=>'required',
            'cities_2'=>'required',
            'districts_2'=>'required',
            'sub_districts_2'=>'required',
            'detail_alamat_2'=>'required',
            'nama_tamu'=>'required',
            'nomor_tamu'=>'required',
            'instansi_tamu'=>'required',
            'jenis_barang'=>'required',
            'quantity'=>'required',
            'satuan'=>'required',
            'instansi'=>'required',
            'nama_instansi'=>'required',
            'employee_dinas'=>'required'
        ],
        [
            'employee_dinas.required'=>'harus dipilih',
            'provinsi.required'=>'harus dipilih',
            'cities.required'=>'harus dipilih',
            'districts.required'=>'harus dipilih',
            'sub_districts.required'=>'harus dipilih',
            'provinsi_2'=>'harus dipilih',
            'cities_2'=>'harus dipilih',
            'districts_2'=>'harus dipilih',
            'sub_districts_2'=>'harus dipilih',
            'detail_alamat_2'=>'harus dipilih',
        ]);
    }
}
