<?php

namespace App\Http\Controllers\Hris;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Dompdf\Options;
use Dompdf\FontMetrics;
use App\Models\EmployeeAtribut;
use App\Imports\KontrakKerjaImport;
use App\Imports\KontrakKerjaImportToDatabase;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\MasterDataAbsenKehadiran;
use App\Exports\exportExcelKontrak;
use App\Models\DasarPotBPJS;
use Maatwebsite\Excel\Facades\Excel;

class HRDController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
    }
    public function index(){
        $selectEmployee =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')->groupby('enroll_id')->orderby('employee_name', 'asc')->get();
        $selectNoKTP = EmployeeAtribut::selectRaw('nomor_ktp')->groupby('nomor_ktp')->orderby('nomor_ktp', 'asc')->get();
        return View::make('hris/hrd',compact('selectEmployee','selectNoKTP'), $this->data);
    }
    public function export_pdf_print_sk(){
        $tipe_surat=request()->tipe_surat;
        foreach($tipe_surat as $key=>$value){
            $sudah_diprint=EmployeeAtribut::where('enroll_id',$key)->first()->sudah_diprint;
            if($sudah_diprint==1){
                continue;
            }else{
                $data[]=[
                    'enroll_id'=>$key,
                    'nik'=>EmployeeAtribut::where('enroll_id',$key)->first()->nik,
                    'employee_name'=>EmployeeAtribut::where('enroll_id',$key)->first()->employee_name,
                    'join_date'=>EmployeeAtribut::where('enroll_id',$key)->first()->join_date,
                    'status_aktif'=>EmployeeAtribut::where('enroll_id',$key)->first()->status_aktif,
                    'status_jabatan'=>EmployeeAtribut::where('enroll_id',$key)->first()->status_jabatan,
                    'tanggal_resign'=>EmployeeAtribut::where('enroll_id',$key)->first()->tanggal_resign,
                    'department_name'=>EmployeeAtribut::where('enroll_id',$key)->first()->department_name,
                    'sub_dept_name'=>EmployeeAtribut::where('enroll_id',$key)->first()->sub_dept_name,
                    'site_nirwana_name'=>EmployeeAtribut::where('enroll_id',$key)->first()->site_nirwana_name,
                    'alamat_rumah'=>EmployeeAtribut::where('enroll_id',$key)->first()->alamat_rumah,
                    'no_surat'=>EmployeeAtribut::where('enroll_id',$key)->first()->nomor_surat,
                    'sebab_resign'=>EmployeeAtribut::where('enroll_id',$key)->first()->sebab_resign,
                    'tipe_surat'=>$value,
                    'no_surat'=>EmployeeAtribut::where('enroll_id',$key)->first()->no_surat,
                ];
            }
        }
        if(isset($data)){
            $no_forms=request()->no_form;
            $fileName='all sk kerja.'.date('His').'_'.rand();
            $pdf = PDF::loadView('hris.laporan.all_sk_kerja_karyawan',["data" => $data,"no_form"=>$no_forms])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
            return $pdf;
        }else{
            $pdf = PDF::loadView('hris.laporan.blank_page')->setPaper('A4', 'fotrait')->stream('no data receipt'.'.pdf');
            return $pdf;
        }
    }
    public function export_pdf_sk_kerja(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $reason=request()->reason;
        $data=DB::select("select*from employee_atribut where enroll_id='$enroll_id'");
        // if($data[0]->tanggal_resign==null){
            $fileName=request()->enroll_id.'_'.date('His');
            $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan',["data" => $data,"no_form"=>$no_form,"reason"=>$reason])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
            return $pdf;
        // }else{
        //     $tanggal_masuk = $data[0]->join_date;
        //     $tanggal_resign = $data[0]->tanggal_resign;
        //     $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_resign))->y;
        //     if($selisih_tahun<1){
        //         $fileName=request()->enroll_id.'_'.date('His');
        //         $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan',["data" => $data,"no_form"=>$no_form])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        //         return $pdf;
        //     }else{
        //         $fileName=request()->enroll_id.'_'.date('His');
        //         $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan_lebih_1_tahun',["data" => $data,"no_form"=>$no_form])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        //         return $pdf;
        //     }
        // }
    }
    public function export_pdf_paklaring(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $reason=request()->reason;
        $data=DB::select("select*from employee_atribut where enroll_id='$enroll_id'");
        if($data[0]->tanggal_resign==null){
            $fileName=request()->enroll_id.'_'.date('His');
            $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan_lebih_1_tahun',["data" => $data,"no_form"=>$no_form,"reason"=>$reason])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
            return $pdf;
        }else{
            $tanggal_masuk = $data[0]->join_date;
            $tanggal_resign = $data[0]->tanggal_resign;
            $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_resign))->y;
            if($selisih_tahun<1){
                $fileName=request()->enroll_id.'_'.date('His');
                $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan_lebih_1_tahun',["data" => $data,"no_form"=>$no_form,"reason"=>$reason])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
                return $pdf;
            }else{
                $fileName=request()->enroll_id.'_'.date('His');
                $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan_lebih_1_tahun',["data" => $data,"no_form"=>$no_form,"reason"=>$reason])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
                return $pdf;
            }
        }
    }
    public function export_pdf_sk_bni(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $item=request()->item;
        $reason=request()->reason;
        $created_date=request()->created_date;
        $data=DB::select("select*from employee_atribut where enroll_id='$enroll_id'");
        // if($data[0]->tanggal_resign==null){
        //     $fileName=request()->enroll_id.'_'.date('His');
        //     $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan',["data" => $data,"no_form"=>$no_form,"reason"=>$reason])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        //     return $pdf;
        // }else{
        //     $tanggal_masuk = $data[0]->join_date;
        //     $tanggal_resign = $data[0]->tanggal_resign;
        //     $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_resign))->y;
        //     if($selisih_tahun<1){
                $fileName='Surat Ketarangan BNI '.$data[0]->employee_name.'_'.request()->enroll_id.'_'.date('His');
                $pdf = PDF::loadView('hris.laporan.sk_bni',["data" => $data,"no_form"=>$no_form,"item"=>$item,"reason"=>$reason,"created_date"=>$created_date])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
                return $pdf;
            // }else{
            //     $fileName=request()->enroll_id.'_'.date('His');
            //     $pdf = PDF::loadView('hris.laporan.sk_kerja_karyawan_lebih_1_tahun',["data" => $data,"no_form"=>$no_form,"reason"=>$reason])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
            //     return $pdf;
            // }
        // }
    }
    public function kontrak_kerja(){
        $selectEmployee =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')->groupby('enroll_id')->orderby('employee_name', 'asc')->get();
        $selectNoKTP = EmployeeAtribut::selectRaw('nomor_ktp')->groupby('nomor_ktp')->orderby('nomor_ktp', 'asc')->get();
        return View::make('hris/hrd/kontrak_kerja',compact('selectEmployee','selectNoKTP'), $this->data);
    }
    public function get_employee_contract(){
        $inSearchVariable='';
        $inEnrollId='';
        $inIbuKandung='';
        $inNoKTP='';
        $status_kontrak=request()->status_kontrak;
        $compare='';
        if (request("search_variable")) {
            $search_variable=request()->search_variable;
            $inSearchVariable = 'AND (z.enroll_id = "'.$search_variable.'" or z.nik LIKE "'.$search_variable.'%" or z.employee_name LIKE "%'.$search_variable.'%" or z.tempat_lahir LIKE "%'.$search_variable.'%" or z.nomor_tlpn LIKE "'.$search_variable.'%" or z.agama LIKE "'.$search_variable.'%" or z.status_kawin LIKE "'.$search_variable.'%" or z.nomor_kk LIKE "'.$search_variable.'%" or z.pendidikan_terakhir LIKE "'.$search_variable.'%" or z.jurusan_pendidikan LIKE "'.$search_variable.'%" or z.alamat_rumah LIKE "%'.$search_variable.'%" or z.department_name LIKE "%'.$search_variable.'%" or z.sub_dept_name LIKE "%'.$search_variable.'%" or z.status_aktif LIKE "'.$search_variable.'%" or z.ibu_kandung LIKE "%'.$search_variable.'%" or z.nomor_ktp LIKE "'.$search_variable.'%")';
        }
        if(request()->enroll_id){
            $enroll_id = request()->enroll_id;
            $enroll_id_string = implode(',', $enroll_id);
            $inEnrollId='AND z.enroll_id in ('.$enroll_id_string.')';
        }
        if(request()->ibu_kandung){
            $ibu_kandung_string=request()->ibu_kandung;
            $inIbuKandung='AND z.ibu_kandung LIKE "%'.$ibu_kandung_string.'%"';
        }
        if(request()->no_ktp){
            $no_ktp_string=request()->no_ktp;
            $inNoKTP='AND z.nomor_ktp LIKE "'.$no_ktp_string.'%"';
        }
        $inStatusKontrak='';
        if($status_kontrak=='Active'){
            $inStatusKontrak='AND y.contract_end >= curdate()';
        }else if($status_kontrak=='Nonactive'){
            $inStatusKontrak='AND y.contract_end < curdate()';
        }else if($status_kontrak=='One Day'){
            $inStatusKontrak='AND y.contract_end = curdate()';
        }else if($status_kontrak=='Thirty Day'){
            $thirty_day_more = date('Y-m-d',strtotime('+30 days',strtotime(date("Y-m-d")))) . PHP_EOL;
            $inStatusKontrak='AND y.contract_end = "'.$thirty_day_more.'"';
        }else if($status_kontrak=='Not yet extended'){
            $inStatusKontrak='AND y.contract_end < curdate() AND z.status_aktif ="AKTIF"';
        }else if($status_kontrak=='Unfilled'){
            $inStatusKontrak='AND y.contract_end is null';
        }
        $inStatusAktif='';
        if(request()->status_aktif){
            $status_aktif=request()->status_aktif;
            $inStatusAktif='AND z.status_aktif = "'.$status_aktif.'"';
        }
        $data_input = DB::select("select z.enroll_id,z.nik,z.employee_name,z.department_name,z.sub_dept_name,z.status_aktif,z.tanggal_resign,z.ibu_kandung,z.nomor_ktp,y.id,y.contract,y.contract_end from (select a.enroll_id,a.id,e.contract,e.contract_end from (select enroll_id,max(contract) contract,max(contract_end) contract_end from employee_contract group by enroll_id)e inner join (select id,enroll_id,contract,contract_end from employee_contract)a on e.enroll_id=a.enroll_id and e.contract_end=a.contract_end)y right join (select enroll_id,nik,employee_name,tanggal_resign,tempat_lahir,nomor_tlpn,agama,status_kawin,nomor_kk,pendidikan_terakhir,jurusan_pendidikan,alamat_rumah,department_name,sub_dept_name,status_aktif,ibu_kandung,nomor_ktp from employee_atribut)z on y.enroll_id=z.enroll_id where z.enroll_id is not null ".$inSearchVariable." ".$inIbuKandung." ".$inNoKTP." ".$inStatusKontrak." ".$inStatusAktif." ".$inEnrollId." order by enroll_id");
        return DataTables::of($data_input)->toJson();
    }
    public function get_employee_contract2(){
        $enroll_id=request()->id;
        $contracts=DB::select("select*from employee_contract where enroll_id='$enroll_id' order by contract_end");
        return $contracts;
    }
    public function update_employee_contract(){
        $enroll_id=request()->id;
        $id=request()->last_id;
        $last_date=request()->last_date;
        DB::update("update employee_contract set contract_end='$last_date' where id = '$id'");
        return $enroll_id;
    }
    public function new_employee_contract(){
        $timestamp = Carbon::now();
        $enroll_id=request()->id;
        $contract=request()->contract;
        $end_contract=request()->end_contract;
        DB::insert("insert into employee_contract (id, enroll_id, contract, contract_end, created_at, updated_at) VALUES ('','$enroll_id','$contract','$end_contract','$timestamp','$timestamp')");
        return $enroll_id;
    }
    public function delete_employee_contract(){
        $id=request()->id;
        DB::delete("delete from employee_contract where id = '$id'");
        return request()->enroll_id;
    }
    public function import_kontrak_kerja(){
        $import = new KontrakKerjaImport;
        Excel::import($import, request()->file('excel_file'));
        return $import->getRowCount();
    }
    public function import_kontrak_kerja_to_database(){
        // khawatir terjadi penumpukan
        ini_set("max_execution_time", 0);
        ini_set("max_input_time", 0);
        Excel::import(new KontrakKerjaImportToDatabase, request()->file('excel_file'));
    }
    public function export_excel_kontrak(){
        $inSearchVariable='';
        $inNoKTP='';
        $inEnrollId='';
        $inIbuKandung='';
        $inStatusAktif='';
        $inStatusKontrak='';
        if (request("search_variable")) {
            $search_variable=request()->search_variable;
            $inSearchVariable = 'AND (a.enroll_id = "'.$search_variable.'" or a.nik LIKE "'.$search_variable.'%" or a.employee_name LIKE "%'.$search_variable.'%" or a.tempat_lahir LIKE "%'.$search_variable.'%" or a.nomor_tlpn LIKE "'.$search_variable.'%" or a.agama LIKE "'.$search_variable.'%" or a.status_kawin LIKE "'.$search_variable.'%" or a.nomor_kk LIKE "'.$search_variable.'%" or a.pendidikan_terakhir LIKE "'.$search_variable.'%" or a.jurusan_pendidikan LIKE "'.$search_variable.'%" or a.alamat_rumah LIKE "%'.$search_variable.'%" or a.department_name LIKE "%'.$search_variable.'%" or a.sub_dept_name LIKE "%'.$search_variable.'%" or a.status_aktif LIKE "'.$search_variable.'%" or a.ibu_kandung LIKE "%'.$search_variable.'%" or a.nomor_ktp LIKE "'.$search_variable.'%")';
        }
        if(request()->no_ktp){
            $no_ktp_string=request()->no_ktp;
            $inNoKTP='AND a.nomor_ktp LIKE "'.$no_ktp_string.'%"';
        }
        if(request()->enroll_id){
            $enroll_id=request()->enroll_id;
            $enroll_id_string=implode(',', $enroll_id);
            $inEnrollId='AND a.enroll_id in ('.$enroll_id_string.')';
        }
        if(request()->ibu_kandung){
            $ibu_kandung_string=request()->ibu_kandung;
            $inIbuKandung='AND a.ibu_kandung LIKE "%'.$ibu_kandung_string.'%"';
        }
        if(request()->status_aktif){
            $status_aktif=request()->status_aktif;
            $inStatusAktif='AND a.status_aktif = "'.$status_aktif.'"';
        }
        if(request()->status_kontrak){
            $status_kontrak=request()->status_kontrak;
            if($status_kontrak=='Active'){
                $inStatusKontrak='AND c.max_contract_end >= curdate()';
            }else if($status_kontrak=='Nonactive'){
                $inStatusKontrak='AND c.max_contract_end < curdate()';
            }else if($status_kontrak=='One Day'){
                $inStatusKontrak='AND c.max_contract_end = curdate()';
            }else if($status_kontrak=='Thirty Day'){
                $thirty_day_more = date('Y-m-d',strtotime('+30 days',strtotime(date("Y-m-d")))) . PHP_EOL;
                $inStatusKontrak='AND c.max_contract_end = "'.$thirty_day_more.'"';
            }else if($status_kontrak=='Not yet extended'){
                $inStatusKontrak='AND c.max_contract_end < curdate() AND a.status_aktif ="AKTIF"';
            }else if($status_kontrak=='Unfilled'){
                $inStatusKontrak='AND b.contract_end is null';
            }
        }
        $query= DB::select("select a.status_staff,a.enroll_id,a.nik,a.employee_name,a.status_jabatan,a.sub_dept_name,a.department_name,a.status_kontrak_tetap,a.status_aktif,a.join_date,a.tanggal_resign,a.nomor_ktp,b.contract,b.contract_end,c.max_contract_end from employee_atribut a left join employee_contract b on a.enroll_id=b.enroll_id left join (select enroll_id,max(contract_end) max_contract_end from employee_contract group by enroll_id)c on a.enroll_id=c.enroll_id where a.enroll_id is not null ".$inSearchVariable." ".$inEnrollId." ".$inNoKTP." ".$inIbuKandung." ".$inStatusAktif." ".$inStatusKontrak." order by enroll_id,contract_end");
        return Excel::download(new exportExcelKontrak($query), 'Laporan_Penerimaan FG_Stok.xlsx');
    }
    public function print_pdf_kontrak(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $data=DB::select("select a.status_staff,a.enroll_id,a.nik,a.employee_name,a.status_jabatan,a.sub_dept_name,a.department_name,a.status_kontrak_tetap,a.status_aktif,a.join_date,a.tanggal_resign,a.nomor_ktp,a.tempat_lahir,a.alamat_rumah,a.tanggal_lahir,a.no_surat,b.contract,b.contract_end,c.max_contract,c.max_contract_end from employee_atribut a left join employee_contract b on a.enroll_id=b.enroll_id left join (select enroll_id,max(contract) max_contract,max(contract_end) max_contract_end from employee_contract)c on a.enroll_id=c.enroll_id where a.enroll_id=".$enroll_id."");
        $umk=DasarPotBPJS::orderBy('created_at','desc')->limit(1)->first()->dasar_pot_bpjs_rupiah;
        $fileName='Kontrak Kerja '.$data[0]->employee_name.'('.request()->enroll_id.') '.$data[0]->max_contract_end.' '.date('His');
        $pdf = PDF::loadView('hris.laporan.kontrak_kerja_karyawan',["no_form"=>$no_form,"data" => $data,"umk"=>$umk])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        return $pdf;
    }
    public function print_pdf_kontrak_2(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $contract=request()->contract;
        $contract_end=request()->contract_end;
        $data=DB::select("select a.status_staff,a.enroll_id,a.nik,a.employee_name,a.status_jabatan,a.sub_dept_name,a.department_name,a.status_kontrak_tetap,a.status_aktif,a.join_date,a.tanggal_resign,a.nomor_ktp,a.tempat_lahir,a.alamat_rumah,a.tanggal_lahir,a.no_surat,b.contract,b.contract_end,c.max_contract,c.max_contract_end from employee_atribut a left join employee_contract b on a.enroll_id=b.enroll_id left join (select enroll_id,max(contract) max_contract,max(contract_end) max_contract_end from employee_contract group by enroll_id)c on a.enroll_id=c.enroll_id where a.enroll_id=".$enroll_id." group by a.enroll_id");
        $umk=DasarPotBPJS::orderBy('created_at','desc')->limit(1)->first()->dasar_pot_bpjs_rupiah;
        $fileName='Kontrak Kerja '.$data[0]->employee_name.'('.request()->enroll_id.') '.$contract_end.' '.date('His');
        $pdf = PDF::loadView('hris.laporan.kontrak_kerja_karyawan_2',["no_form"=>$no_form,"contract2"=>$contract,"contract_end2"=>$contract_end,"data" => $data,"umk"=>$umk])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        return $pdf;
    }
    public function print_all_pdf_kontrak(){
        $enroll_id=request()->enroll_id;
        $no_form=request()->no_form;
        $data=DB::select("select a.status_staff,a.enroll_id,a.nik,a.employee_name,a.status_jabatan,a.sub_dept_name,a.department_name,a.status_kontrak_tetap,a.status_aktif,a.join_date,a.tanggal_resign,a.nomor_ktp,a.tempat_lahir,a.alamat_rumah,a.tanggal_lahir,a.no_surat,b.contract,b.contract_end,c.max_contract,c.max_contract_end from employee_atribut a left join employee_contract b on a.enroll_id=b.enroll_id left join (select enroll_id,max(contract) max_contract,max(contract_end) max_contract_end from employee_contract)c on a.enroll_id=c.enroll_id where a.enroll_id in (".$enroll_id.")");
        $umk=DasarPotBPJS::orderBy('created_at','desc')->limit(1)->first()->dasar_pot_bpjs_rupiah;
        $fileName='Kontrak Kerja '.$data[0]->employee_name.'('.request()->enroll_id.') '.$data[0]->max_contract_end.' '.date('His');
        $pdf = PDF::loadView('hris.laporan.kontrak_kerja_karyawan',["no_form"=>$no_form,"data" => $data,"umk"=>$umk])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        return $pdf;
    }
    public function ajax_getemployeeidbyfilter(){
        $inSearchVariable='';
        $inNoKTP='';
        $inEnrollId='';
        $inIbuKandung='';
        $inStatusAktif='';
        $inStatusKontrak='';
        if (request("search_variable")) {
            $search_variable=request()->search_variable;
            $inSearchVariable = 'AND (a.enroll_id = "'.$search_variable.'" or a.nik LIKE "'.$search_variable.'%" or a.employee_name LIKE "%'.$search_variable.'%" or a.tempat_lahir LIKE "%'.$search_variable.'%" or a.nomor_tlpn LIKE "'.$search_variable.'%" or a.agama LIKE "'.$search_variable.'%" or a.status_kawin LIKE "'.$search_variable.'%" or a.nomor_kk LIKE "'.$search_variable.'%" or a.pendidikan_terakhir LIKE "'.$search_variable.'%" or a.jurusan_pendidikan LIKE "'.$search_variable.'%" or a.alamat_rumah LIKE "%'.$search_variable.'%" or a.department_name LIKE "%'.$search_variable.'%" or a.sub_dept_name LIKE "%'.$search_variable.'%" or a.status_aktif LIKE "'.$search_variable.'%" or a.ibu_kandung LIKE "%'.$search_variable.'%" or a.nomor_ktp LIKE "'.$search_variable.'%")';
        }
        if(request()->no_ktp){
            $no_ktp_string=request()->no_ktp;
            $inNoKTP='AND a.nomor_ktp LIKE "'.$no_ktp_string.'%"';
        }
        if(request()->enroll_id){
            $enroll_id=request()->enroll_id;
            $enroll_id_string=implode(',', $enroll_id);
            $inEnrollId='AND a.enroll_id in ('.$enroll_id_string.')';
        }
        if(request()->ibu_kandung){
            $ibu_kandung_string=request()->ibu_kandung;
            $inIbuKandung='AND a.ibu_kandung LIKE "%'.$ibu_kandung_string.'%"';
        }
        if(request()->status_aktif){
            $status_aktif=request()->status_aktif;
            $inStatusAktif='AND a.status_aktif = "'.$status_aktif.'"';
        }
        if(request()->status_kontrak){
            $status_kontrak=request()->status_kontrak;
            if($status_kontrak=='Active'){
                $inStatusKontrak='AND c.max_contract_end >= curdate()';
            }else if($status_kontrak=='Nonactive'){
                $inStatusKontrak='AND c.max_contract_end < curdate()';
            }else if($status_kontrak=='One Day'){
                $inStatusKontrak='AND c.max_contract_end = curdate()';
            }else if($status_kontrak=='Thirty Day'){
                $thirty_day_more = date('Y-m-d',strtotime('+30 days',strtotime(date("Y-m-d")))) . PHP_EOL;
                $inStatusKontrak='AND c.max_contract_end = "'.$thirty_day_more.'"';
            }else if($status_kontrak=='Not yet extended'){
                $inStatusKontrak='AND c.max_contract_end < curdate() AND a.status_aktif ="AKTIF"';
            }else if($status_kontrak=='Unfilled'){
                $inStatusKontrak='AND b.contract_end is null';
            }
        }
        $query= DB::select("select a.enroll_id from employee_atribut a left join employee_contract b on a.enroll_id=b.enroll_id left join (select enroll_id,max(contract_end) max_contract_end from employee_contract group by enroll_id)c on a.enroll_id=c.enroll_id where a.enroll_id is not null ".$inSearchVariable." ".$inEnrollId." ".$inNoKTP." ".$inIbuKandung." ".$inStatusAktif." ".$inStatusKontrak." group by a.enroll_id");
        $enroll_id_array=array_column($query,'enroll_id');
        return $enroll_id_array;
    }
}
