<?php

namespace App\Http\Controllers\Hris;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PemeliharaanKendaraan;
use App\Models\VehicleItem;
use App\Models\KategoriItem;
use App\Models\VehicleMaintenance;
use App\Models\VehicleMaintenancePrice;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class PemeliharaanKendaraanController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
    }
    public function index(){
        $vehicles =  DB::connection('laravel_nds')->select( DB::raw("select*from ga_master_kendaraan") );
        $vehicle_item=DB::select("select*from vehicle_item order by created_at desc");
        return View::make('hris/ga/pemeliharaan_kendaraan',compact('vehicles','vehicle_item'), $this->data);
    }
    public function get_data_jenis_pemeliharaan(){
        $data_input=DB::select("select*from jenis_pemeliharaan order by created_at desc");
        return DataTables::of($data_input)->toJson();
    }
    public function store_vehicle_maintenance(){
        $this->_validation(request());
        if(request()->status=='add'){
            PemeliharaanKendaraan::create([
                'jenis_pemeliharaan'=>request()->jenis_pemeliharaan,
                'jadwal_pemeliharaan'=>request()->jadwal_pemeliharaan,
                'km'=>request()->km
            ]);
        }else{
            PemeliharaanKendaraan::where('id',request()->id_pemeliharaan)->update([
                'jenis_pemeliharaan'=>request()->jenis_pemeliharaan,
                'jadwal_pemeliharaan'=>request()->jadwal_pemeliharaan,
                'km'=>request()->km
            ]);
        }
    }
    public function _validation(){
        $validation=request()->validate([
            'jenis_pemeliharaan'=>'required',
            'jadwal_pemeliharaan'=>'required',
            'km'=>'required',
        ]);
    }
    public function _validation2(){
        $validation=request()->validate([
            'nama_barang'=>'required',
            'quantity_pemeliharaan'=>'required',
            'satuan_pemeliharaan'=>'required',
        ]);
    }
    public function get_vehicle_item_data(){
        $data_input=DB::select("select*from vehicle_item order by created_at desc");
        return DataTables::of($data_input)->toJson();
    }
    public function store_vehicle_item_master(){
        $this->_validation2(request());
        if(request()->status=='add'){
            VehicleItem::create([
                'nama_barang'=>request()->nama_barang,
                'quantity_pemeliharaan'=>request()->quantity_pemeliharaan,
                'satuan_pemeliharaan'=>request()->satuan_pemeliharaan
            ]);
        }else{
            VehicleItem::where('id',request()->id)->update([
                'nama_barang'=>request()->nama_barang,
                'quantity_pemeliharaan'=>request()->quantity_pemeliharaan,
                'satuan_pemeliharaan'=>request()->satuan_pemeliharaan
            ]);
        }
    }
    public function delete_vehicle_item(){
        VehicleItem::where('id',request()->id)->delete();
    }
    public function get_vehicle_maintenance_data(){
        $data_input=DB::select("select a.id,a.tanggal_pengajuan tanggal,DATE_FORMAT(a.tanggal_pengajuan, '%d %M %Y') tanggal_pengajuan,a.vehicle_id,concat(b.merk,' ',b.tipe,' (',b.plat_no,')') vehicle_merk,c.vehicle_item_id,c.price,DATE_FORMAT(a.updated_at, '%d %M %Y - %H:%i') last_update from vehicle_maintenance a inner join ga_master_kendaraan b on a.vehicle_id=b.id inner join vehicle_maintenance_price c on a.id=c.vehicle_maintenance_id group by a.id order by a.created_at desc");
        return DataTables::of($data_input)->toJson();
    }
    public function delete_vehicle_maintenance(){
        VehicleMaintenance::where('id',request()->id)->delete();
        VehicleMaintenancePrice::where('vehicle_maintenance_id',request()->id)->delete();
    }
    public function store_vehicle_item_maintenance(){
        $this->_validation3(request());
        if(request()->status=='add'){
            $query=VehicleMaintenance::create([
                'tanggal_pengajuan'=>request()->tanggal_pengajuan,
                'vehicle_id'=>request()->vehicle_id
            ]);
            foreach(request()->vehicle_item as $key=>$value){
                VehicleMaintenancePrice::create([
                    'vehicle_maintenance_id'=>$query->id,
                    'vehicle_order'=>$key,
                    'vehicle_item_id'=>$value,
                    'quantity'=>request()->vehicle_quantity[$key],
                    'price'=>request()->vehicle_item_price[$key],
                ]);
            }
        }else{
            VehicleMaintenance::where('id',request()->id)->update([
                'tanggal_pengajuan'=>request()->tanggal_pengajuan,
                'vehicle_id'=>request()->vehicle_id
            ]);
            VehicleMaintenancePrice::where('vehicle_maintenance_id',request()->id)->delete();
            foreach(request()->vehicle_item as $key=>$value){
                VehicleMaintenancePrice::create([
                    'vehicle_maintenance_id'=>request()->id,
                    'vehicle_order'=>$key,
                    'vehicle_item_id'=>$value,
                    'quantity'=>request()->vehicle_quantity[$key],
                    'price'=>request()->vehicle_item_price[$key],
                ]);
            }
        }
    }
    public function _validation3(){
        $validation=request()->validate([
            'tanggal_pengajuan'=>'required',
            'vehicle_id'=>'required',
        ]);
    }
    public function print_vehicle_maintenance(){
        $id=request()->id;
        $data=DB::select("select a.id,a.tanggal_pengajuan tanggal,DATE_FORMAT(a.tanggal_pengajuan, '%d %M %Y') tanggal_pengajuan,a.vehicle_id,concat(b.merk,' ',b.tipe,' (',b.plat_no,')') vehicle_merk,c.vehicle_item_id,c.price from vehicle_maintenance a inner join ga_master_kendaraan b on a.vehicle_id=b.id inner join vehicle_maintenance_price c on a.id=c.vehicle_maintenance_id where a.id='$id' group by a.id order by a.created_at desc");
        $data2=DB::select("select a.vehicle_maintenance_id,b.nama_barang,a.quantity,a.price from vehicle_maintenance_price a inner join vehicle_item b on a.vehicle_item_id=b.id where vehicle_maintenance_id='$id'");
        $data3=DB::select("select quantity,price, sum(quantity*price) price_unit from vehicle_maintenance_price where vehicle_maintenance_id='$id' group by vehicle_maintenance_id");
        $fileName='Form Penugasan Transportasi '.date('His');
        $pdf = PDF::loadView('hris.laporan.vehicle_maintenance',["data"=>$data,"data2"=>$data2,"data3"=>$data3])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        return $pdf;
    }
    public function get_vehicle_item_maintenance_price(){
        $id=request()->id;
        $data=DB::select("select*from vehicle_maintenance_price where vehicle_maintenance_id ='$id' order by vehicle_order");
        return $data;
    }
    public function vehicle_monitoring(){
        return View::make('hris/ga/vehicle_monitoring', $this->data);
    }
    public function get_vehicle_item_monitoring(){
        $data_input=DB::select("select rekap_1.tanggal,rekap_1.vehicle_id,rekap_1.vehicle_merk,rekap_1.vehicle_item_id,rekap_1.nama_barang,(rekap_1.quantity_pemeliharaan*rekap_1.count_to_next) day_add,DATE_ADD(rekap_1.tanggal, INTERVAL (rekap_1.quantity_pemeliharaan*rekap_1.count_to_next) DAY) next_maintain_plan from (select max(a.tanggal_pengajuan) tanggal,a.vehicle_id,concat(b.merk,' ',b.tipe,' (',b.plat_no,')') vehicle_merk,c.vehicle_item_id,d.nama_barang,d.quantity_pemeliharaan,d.satuan_pemeliharaan,case when d.satuan_pemeliharaan='hari' then 1 when d.satuan_pemeliharaan='minggu' then 7 when d.satuan_pemeliharaan='bulan' then 30 else 365 end count_to_next from vehicle_maintenance a inner join ga_master_kendaraan b on a.vehicle_id=b.id inner join vehicle_maintenance_price c on a.id=c.vehicle_maintenance_id inner join vehicle_item d on c.vehicle_item_id=d.id group by c.vehicle_item_id,a.vehicle_id order by a.created_at desc)rekap_1");
        return DataTables::of($data_input)->toJson();
    }
    public function store_kategori_item(){
        $this->_validation_kategori_item(request());
        if(request()->status=='add'){
            KategoriItem::create([
                'category_name'=>request()->category_name
            ]);
        }else{
            KategoriItem::where('id',request()->id)->update([
                'category_name'=>request()->category_name
            ]);
        }
        return request()->status;
    }
    public function store_item(){
        $this->_validation_item(request());
        if(request()->status=='add'){
            ItemKendaraan::create([
                'category_id'=>request()->category,
                'item_name'=>request()->item_name
            ]);
        }else{
            ItemKendaraan::where('id',request()->id)->update([
                'category_id'=>request()->category,
                'item_name'=>request()->item_name
            ]);
        }
        return request()->status;
    }
    public function get_kategori_item(){
        $kategori_items = DB::select("select*from kategori_items order by category_name");
        return $kategori_items;
    }
    
    public function _validation_kategori_item(){
        $validation=request()->validate([
            'category_name'=>'required',
        ]);
    }
    public function get_vehicle_category_name(){
        $data_input=DB::select("select*from kategori_items order by created_at desc");
        return DataTables::of($data_input)->toJson();
    }
}
