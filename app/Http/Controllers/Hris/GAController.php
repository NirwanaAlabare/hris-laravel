<?php

namespace App\Http\Controllers\Hris;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use App\Models\EmployeeAtribut;
use App\Models\PermintaanTransportasi;
use App\Models\TujuanTransportasi;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Auth;
use Carbon\Carbon;
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
        return View::make('hris/ga/form_pengajuan_transportasi', $this->data,compact('provincies','cities','districts','subdistricts'));
    }
    public function data_pengajuan_transportasi(){
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        $id_user = $loggedAdmin->enroll_id;
        $id_department=EmployeeAtribut::where('enroll_id',$id_user)->first()->department_id;
        $permintaan_transportasi=PermintaanTransportasi::whereHas('employee_atribut',function($query)use($id_department){
            $query->where('department_id',$id_department);
        })->orderBy('tanggal_pemberangkatan','desc')->orderBy('jam_pemberangkatan','desc')->get();
        $drivers=EmployeeAtribut::where('sub_dept_id','DEP08SUB002')->get();
        $vehicles =  DB::connection('laravel_nds')->select(
            DB::raw("select*from ga_master_kendaraan") );
        return View::make('hris/ga/data_pengajuan_transportasi', $this->data,compact('permintaan_transportasi','email','id_user','drivers','vehicles'));
    }
    public function get_data_pengajuan_transportasi(){
        $user=request()->user;
        $department_id=EmployeeAtribut::where('enroll_id',$user)->first()->department_id;
        if($user!=5321){
            $data_input=DB::select("select '$user' user,a.id,a.enroll_id,b.employee_name,b.department_name,a.detail_alamat,concat(c.subdis_name,' - ',d.dis_name,' - ',e.city_name,' - ',f.prov_name) desa,a.detail_alamat_tujuan,concat(g.subdis_name,' - ',h.dis_name,' - ',i.city_name,' - ',j.prov_name) desa_tujuan,concat(DATE_FORMAT(a.tanggal_pemberangkatan, '%d %M %Y'),' - ',substring(a.jam_pemberangkatan,1,5)) tanggal_pemberangkatan,a.jam_pemberangkatan,REPLACE(a.tujuan_pemberangkatan, '_', ' ') tujuan_pemberangkatan,a.status from (select*from permintaan_transportasi where enroll_id in (select enroll_id from employee_atribut where department_id='$department_id')) a inner join employee_atribut b on a.enroll_id=b.enroll_id inner join subdistricts c on a.id_desa=c.subdis_id inner join districts d on c.dis_id=d.dis_id inner join cities e on d.city_id=e.city_id inner join provinces f on e.prov_id=f.prov_id inner join subdistricts g on a.id_desa_tujuan=g.subdis_id inner join districts h on g.dis_id=h.dis_id inner join cities i on h.city_id=i.city_id inner join provinces j on i.prov_id=j.prov_id order by a.tanggal_pemberangkatan,a.jam_pemberangkatan desc");
        }else{
            $data_input=DB::select("select '$user' user,a.id,a.enroll_id,b.employee_name,b.department_name,a.detail_alamat,concat(c.subdis_name,' - ',d.dis_name,' - ',e.city_name,' - ',f.prov_name) desa,a.detail_alamat_tujuan,concat(g.subdis_name,' - ',h.dis_name,' - ',i.city_name,' - ',j.prov_name) desa_tujuan,concat(DATE_FORMAT(a.tanggal_pemberangkatan, '%d %M %Y'),' - ',substring(a.jam_pemberangkatan,1,5)) tanggal_pemberangkatan,a.jam_pemberangkatan,REPLACE(a.tujuan_pemberangkatan, '_', ' ') tujuan_pemberangkatan,a.status from permintaan_transportasi a inner join employee_atribut b on a.enroll_id=b.enroll_id inner join subdistricts c on a.id_desa=c.subdis_id inner join districts d on c.dis_id=d.dis_id inner join cities e on d.city_id=e.city_id inner join provinces f on e.prov_id=f.prov_id inner join subdistricts g on a.id_desa_tujuan=g.subdis_id inner join districts h on g.dis_id=h.dis_id inner join cities i on h.city_id=i.city_id inner join provinces j on i.prov_id=j.prov_id order by a.tanggal_pemberangkatan,a.jam_pemberangkatan desc");
        }
        return DataTables::of($data_input)->toJson();
    }
    public function approve_car_request(){
        $this->_validation5(request());
        PermintaanTransportasi::where('id',request()->id_request)->update([
            'id_driver'=>request()->driver,
            'nomor_kendaraan'=>request()->vehicle_id,
            'status'=>1
        ]);
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
        PermintaanTransportasi::where('id',request()->id_request)->update([
            'status'=>0,
            'alasan_status'=>request()->alasan_reject
        ]);
    }
    public function print_penugasan_transportasi(){
        $id=request()->id;
        $data=DB::select("select a.id,a.enroll_id,b.nik,b.employee_name,b.department_name,a.detail_alamat,concat(c.subdis_name,', ',d.dis_name,', ',e.city_name,', ',f.prov_name) desa,a.detail_alamat_tujuan,concat(g.subdis_name,', ',h.dis_name,', ',i.city_name,', ',j.prov_name) desa_tujuan,a.tanggal_pemberangkatan,a.tanggal_kedatangan,substring(a.jam_kedatangan,1,5) jam_kedatangan,a.tujuan_pemberangkatan,substring(a.jam_pemberangkatan,1,5) jam_keberangkatan,a.jarak_tempuh,k.employee_name driver,k.nik nik_driver,a.nomor_kendaraan from (select*from permintaan_transportasi where id='$id') a inner join employee_atribut b on a.enroll_id=b.enroll_id inner join subdistricts c on a.id_desa=c.subdis_id inner join districts d on c.dis_id=d.dis_id inner join cities e on d.city_id=e.city_id inner join provinces f on e.prov_id=f.prov_id inner join subdistricts g on a.id_desa_tujuan=g.subdis_id inner join districts h on g.dis_id=h.dis_id inner join cities i on h.city_id=i.city_id inner join provinces j on i.prov_id=j.prov_id inner join employee_atribut k on a.id_driver=k.enroll_id order by a.tanggal_pemberangkatan,a.jam_pemberangkatan desc");
        $kendaraan=$data[0]->nomor_kendaraan;
        $nomor_kendaraan=DB::connection('laravel_nds')->select( DB::raw("select*from ga_master_kendaraan where id ='$kendaraan'") );
        $fileName='Form Penugasan Transportasi '.date('His');
        $pdf = PDF::loadView('hris.laporan.penugasan_transportasi',["data"=>$data,"nomor_kendaraan"=>$nomor_kendaraan])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
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
        $query=TujuanTransportasi::where('permintaan_transportasi_id',request()->id)->orderBy('created_at')->with('subdistrict.district.city.prov')->get();
        return $query;
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
        ]);
    }
    public function ajax_getallemployeeatribut()
    {
        $query =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')->groupby('enroll_id')->orderby('employee_name', 'asc')->get();
        return $query;

    }
    public function get_province(){
        $province = DB::select("select*from provinces order by prov_id");
        return $province;
    }
    public function get_cities(){
        $provinsi = request()->provinsi;
        $cities = DB::select("select*from cities where prov_id = '$provinsi'");
        return $cities;
    }
    public function get_districts(){
        $cities = request()->cities;
        $districts = DB::select("select*from districts where city_id = '$cities'");
        return $districts;
    }
    public function get_subdistricts(){
        $districts = request()->districts;
        $subdistricts = DB::select("select*from subdistricts where dis_id = '$districts'");
        return $subdistricts;
    }
    public function post_car_request(){
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->id;
        if((request()->cb_antar_tamu==1 || request()->cb_jemput_tamu==1) && request()->cb_antar_barang!=1 && request()->cb_jemput_barang!=1){
            $this->_validation2(request());
        }else if((request()->cb_antar_barang==1 || request()->cb_jemput_barang==1) && request()->cb_antar_tamu!=1 && request()->cb_jemput_tamu!=1){
            $this->_validation3(request());
        }else if((request()->cb_antar_barang==1 || request()->cb_jemput_barang==1) && (request()->cb_antar_tamu==1 || request()->cb_jemput_tamu==1)){
            $this->_validation4(request());
        }else{
            $this->_validation(request());
        }
        $enroll_id_array[]=explode(",",request()->employee);
        $enroll_id=implode('', $enroll_id_array[0]);
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
        $instansi=request()->instansi;
        $nama_instansi=request()->nama_instansi;
        $keterangan_barang=request()->keterangan_barang;
        $tujuan=request()->tujuan;
        $date_now=Carbon::now();
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
            'nomor_tamu'=>$nomor_tamu,
            'instansi_tamu'=>$instansi_tamu,
            'jenis_barang'=>$jenis_barang,
            'quantity'=>$quantity,
            'nama_instansi'=>$instansi,
            'nama_penerima'=>$nama_instansi,
            'keterangan_barang'=>$keterangan_barang,
            'created_by'=>$email
        ]);
        foreach(request()->tujuan_array as $key=>$value){
            DB::table('tujuan_transportasi')->insert([
                'permintaan_transportasi_id'=>$query->id,
                'tujuan_id'=>$value,
                'subdistrict'=>request()->subdistrict_array[$key],
                'detail_alamat'=>request()->detail_alamat_array[$key],
                'tanggal_kedatangan'=>request()->tanggal_kedatangan_array[$key],
                'jam_kedatangan'=>request()->jam_kedatangan_array[$key],
                'created_at'=>$date_now,
                'updated_at'=>$date_now
            ]);
        }
    }
    public function lihat_detail(){
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        $provincies=DB::select('select * from provinces order by prov_id');
        $cities=DB::select("select * from cities order by city_id");
        $districts=DB::select("select * from districts order by dis_id");
        $subdistricts=DB::select("select * from subdistricts order by subdis_id");
        $id=request()->id;
        $drivers=EmployeeAtribut::where('sub_dept_id','DEP08SUB002')->get();
        $pengajuan_transportasi=DB::select("select a.id,a.created_at,a.jarak_tempuh,a.jenis_barang,a.quantity,a.nama_instansi,a.nama_penerima,a.keterangan_barang,a.nama_tamu,a.instansi_tamu,a.nomor_hp_tamu,a.id_driver,a.nomor_kendaraan,a.status,b.employee_name,b.nik,b.department_name,b.sub_dept_name,c.subdis_name nama_desa,d.dis_name nama_kecamatan,e.city_name nama_kota,f.prov_name nama_provinsi,a.detail_alamat,a.tanggal_pemberangkatan,a.jam_pemberangkatan,a.tujuan_pemberangkatan,h.subdis_name nama_desa_tujuan,i.dis_name nama_kecamatan_tujuan,j.city_name nama_kota_tujuan,k.prov_name nama_provinsi_tujuan,g.detail_alamat detail_alamat_tujuan,g.tanggal_kedatangan,g.jam_kedatangan from (select*from permintaan_transportasi where id='$id') a inner join employee_atribut b on a.enroll_id=b.enroll_id inner join subdistricts c on a.id_desa=c.subdis_id inner join districts d on c.dis_id=d.dis_id inner join cities e on d.city_id=e.city_id inner join provinces f on e.prov_id=f.prov_id inner join (select*from tujuan_transportasi where permintaan_transportasi_id='$id' order by tujuan_id limit 1) g on a.id=g.permintaan_transportasi_id inner join subdistricts h on g.subdistrict=h.subdis_id inner join districts i on h.dis_id=i.dis_id inner join cities j on i.city_id=j.city_id inner join provinces k on j.prov_id=k.prov_id");
        $vehicles =  DB::connection('laravel_nds')->select( DB::raw("select*from ga_master_kendaraan") );
        return View::make('hris/ga/data_detail_pengajuan_transportasi', $this->data,compact('pengajuan_transportasi','provincies','cities','districts','subdistricts','drivers','vehicles'));
    }
    public function edit_detail(){
        $this->selectemployee = $this->ajax_getallemployeeatribut();
        $provincies=DB::select('select * from provinces order by prov_id');
        $id=request()->id;
        $pengajuan_transportasi=DB::select("select a.id,b.employee_name,b.nik,b.department_name,b.sub_dept_name,a.id_desa,d.dis_id,e.city_id,f.prov_id,a.detail_alamat,a.tanggal_pemberangkatan,a.jam_pemberangkatan,a.tujuan_pemberangkatan,a.jarak_tempuh,a.nama_tamu,a.nomor_hp_tamu,a.instansi_tamu,a.jenis_barang,a.quantity,a.nama_instansi,a.nama_penerima,a.keterangan_barang,g.detail_alamat detail_alamat_tujuan,g.subdistrict sub_dis_tujuan,g.tanggal_kedatangan,g.jam_kedatangan,i.dis_id dis_tujuan,j.city_id city_tujuan,k.prov_id prov_tujuan,a.created_at,a.updated_at from (select*from permintaan_transportasi where id='$id') a inner join employee_atribut b on a.enroll_id=b.enroll_id inner join subdistricts c on a.id_desa=c.subdis_id inner join districts d on c.dis_id=d.dis_id inner join cities e on d.city_id=e.city_id inner join provinces f on e.prov_id=f.prov_id inner join (select*from tujuan_transportasi where permintaan_transportasi_id=8 order by tujuan_id limit 1) g on a.id=g.permintaan_transportasi_id inner join subdistricts h on g.subdistrict=h.subdis_id inner join districts i on h.dis_id=i.dis_id inner join cities j on i.city_id=j.city_id inner join provinces k on j.prov_id=k.prov_id");
        $prov_id=$pengajuan_transportasi[0]->prov_id;
        $prov_id_2=$pengajuan_transportasi[0]->prov_tujuan;
        $city_id=$pengajuan_transportasi[0]->city_id;
        $city_id_2=$pengajuan_transportasi[0]->city_tujuan;
        $district=$pengajuan_transportasi[0]->dis_id;
        $district_2=$pengajuan_transportasi[0]->dis_tujuan;
        $cities=DB::select("select * from cities where prov_id='$prov_id'");
        $cities_2=DB::select("select * from cities where prov_id='$prov_id_2'");
        $districts=DB::select("select * from districts where city_id='$city_id'");
        $districts_2=DB::select("select * from districts where city_id='$city_id_2'");
        $subdistricts=DB::select("select * from subdistricts where dis_id='$district'");
        $subdistricts_2=DB::select("select * from subdistricts where dis_id='$district_2'");
        return View::make('hris/ga/edit_detail_pengajuan_transportasi', $this->data,compact('pengajuan_transportasi','provincies','cities','cities_2','districts','districts_2','subdistricts','subdistricts_2'));
    }
    public function show_another_route(){
        $id=request()->id;
        $route=DB::select("select a.tujuan_id,b.subdis_name,c.dis_name,d.city_name,e.prov_name,a.detail_alamat,a.tanggal_kedatangan,substring(a.jam_kedatangan,1,5) jam_kedatangan from (select* from tujuan_transportasi where permintaan_transportasi_id='$id' order by tujuan_id) a inner join subdistricts b on a.subdistrict=b.subdis_id inner join districts c on b.dis_id=c.dis_id inner join cities d on c.city_id=d.city_id inner join provinces e on d.prov_id=e.prov_id");
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
            'nama_instansi'=>'required'
        ],
        [
            'employee.required'=>'harus dipilih',
        ]);
    }
}
