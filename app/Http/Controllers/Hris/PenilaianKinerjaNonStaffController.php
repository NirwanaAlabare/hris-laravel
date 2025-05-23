<?php

namespace App\Http\Controllers\Hris;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Dompdf\Options;
use Dompdf\FontMetrics;
use App\Models\EmployeeAtribut;
use App\Models\DepartmentAll;
use App\Models\VoucherBazzar;
use App\Imports\KontrakKerjaImport;
use App\Imports\KontrakKerjaImportToDatabase;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\MasterDataAbsenKehadiran;
use App\Exports\ExcelPenilaianKinerjaNonstaff;
use App\Models\DasarPotBPJS;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;

class PenilaianKinerjaNonStaffController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
    }

    public function index(){
        $user = Auth::guard('admin')->user();
        $email_allowed = ['mega@ptnag.com', 'rudy@ptnag.com', 'fadli'];
        $selectEmployee =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')->groupby('enroll_id')->orderby('employee_name', 'asc')->get();
        $selectNoKTP = EmployeeAtribut::selectRaw('nomor_ktp')->groupby('nomor_ktp')->orderby('nomor_ktp', 'asc')->get();
        $department =DepartmentAll::select('department_name')->distinct()->orderBy('department_id')->groupBy('department_id')->get();
        $is_allowed = in_array($user->email, $email_allowed);
        return View::make('hris/hrd/penilaian_kinerja_nonstaff',compact('selectEmployee','selectNoKTP','department','is_allowed'), $this->data);
    }


    public function get_employee_contract_nonstaff(){
        $loggedAdmin = Auth::guard('admin')->user();
        $loggedEmail = $loggedAdmin->email;
        $email = ['mega@ptnag.com', 'rudy@ptnag.com', 'fadli'];
        $department_id = EmployeeAtribut::where('enroll_id', $loggedAdmin->enroll_id)->value('department_id');
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
        if(request()->notification_id){
            $notification_id = request()->notification_id;
            $notification_enroll_id = DB::table('notifications')
                ->where('id', $notification_id)
                ->value('enroll_ids');

            // Decode JSON string ke array
            $enroll_ids_array = json_decode($notification_enroll_id, true);

            // Pastikan hasil decode adalah array
            if (is_array($enroll_ids_array)) {
                // Ubah array menjadi string "5684,8085,8086,..."
                $enroll_id_string = implode(',', $enroll_ids_array);

                // Masukkan ke dalam klausa SQL
                $inEnrollId = 'AND z.enroll_id IN (' . $enroll_id_string . ')';
            } else {
                // Tangani jika format data tidak valid
                $inEnrollId = '';
            }
        }
        if(request()->ibu_kandung){
            $ibu_kandung_string=request()->ibu_kandung;
            $inIbuKandung='AND z.ibu_kandung LIKE "%'.$ibu_kandung_string.'%"';
        }
        if(request()->no_ktp){
            $no_ktp_string=request()->no_ktp;
            $inNoKTP='AND z.nomor_ktp LIKE "'.$no_ktp_string.'%"';
        }
        $inDepartment = '';
        if (!in_array($loggedEmail, $email)) {
            $inDepartment = 'AND z.department_id =  "'.$department_id.'"';
        }
        $inStatusKontrak='';
        if($status_kontrak=='Active'){
            $inStatusKontrak='AND y.contract_end >= curdate()';
        }else if($status_kontrak=='Nonactive'){
            $inStatusKontrak='AND y.contract_end < curdate()';
        }else if($status_kontrak=='One Day'){
            $inStatusKontrak='AND y.contract_end = curdate()';
        }else if($status_kontrak=='Thirty Day'){
            // $thirty_day_more = date('Y-m-d',strtotime('+30 days',strtotime(date("Y-m-d")))) . PHP_EOL;
            // $inStatusKontrak='AND y.contract_end = "'.$thirty_day_more.'"';
            $today = date('Y-m-d');
            $thirty_days_later = date('Y-m-d', strtotime('+30 days'));
            $inStatusKontrak = 'AND y.contract_end BETWEEN "'.$today.'" AND "'.$thirty_days_later.'"';
        }else if($status_kontrak=='Sixty Day'){
                $thirty_day_more = date('Y-m-d',strtotime('+60 days',strtotime(date("Y-m-d")))) . PHP_EOL;
                $inStatusKontrak='AND c.max_contract_end = "'.$thirty_day_more.'"';
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
        $data_input = DB::select("select z.enroll_id,z.nik,z.employee_name,z.department_name, z.department_id,z.sub_dept_name,z.status_aktif,z.tanggal_resign,z.ibu_kandung,z.nomor_ktp,z.status_staff,y.id,y.contract,y.contract_end from (select a.enroll_id,a.id,e.contract,e.contract_end from (select enroll_id,max(contract) contract,max(contract_end) contract_end from employee_contract group by enroll_id)e inner join (select id,enroll_id,contract,contract_end from employee_contract)a on e.enroll_id=a.enroll_id and e.contract_end=a.contract_end)y right join (select enroll_id,nik,employee_name,tanggal_resign,tempat_lahir,status_staff,nomor_tlpn,agama,status_kawin,nomor_kk,pendidikan_terakhir,jurusan_pendidikan,alamat_rumah,department_name,department_id,sub_dept_name,status_aktif,ibu_kandung,nomor_ktp from employee_atribut)z on y.enroll_id=z.enroll_id where z.enroll_id is not null ".$inSearchVariable." ".$inIbuKandung." ".$inNoKTP." ".$inStatusKontrak." ".$inStatusAktif." ".$inEnrollId." ".$inDepartment." AND z.status_staff = 'NON STAFF' GROUP BY enroll_id  order by enroll_id");

        return DataTables::of($data_input)->toJson();
    }


    public function download_excel_penilaian_kinerja_nonstaff(){
        $loggedAdmin = Auth::guard('admin')->user();
        $loggedEmail = $loggedAdmin->email;
        $email = ['mega@ptnag.com', 'rudy@ptnag.com', 'fadli'];
        $department_id = EmployeeAtribut::where('enroll_id', $loggedAdmin->enroll_id)->value('department_id');

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
        $inStatusAktif='AND a.status_aktif = "AKTIF"';
        if(request()->status_kontrak){
            $status_kontrak=request()->status_kontrak;
            if($status_kontrak=='Active'){
                $inStatusKontrak='AND c.max_contract_end >= curdate()';
            }else if($status_kontrak=='Nonactive'){
                $inStatusKontrak='AND c.max_contract_end < curdate()';
            }else if($status_kontrak=='One Day'){
                $inStatusKontrak='AND c.max_contract_end = curdate()';
            }else if($status_kontrak=='Thirty Day'){
                // $thirty_day_more = date('Y-m-d',strtotime('+30 days',strtotime(date("Y-m-d")))) . PHP_EOL;
                // $inStatusKontrak='AND c.max_contract_end = "'.$thirty_day_more.'"';

                $today = date('Y-m-d');
                $thirty_days_later = date('Y-m-d', strtotime('+30 days'));
                $inStatusKontrak = 'AND c.max_contract_end BETWEEN "'.$today.'" AND "'.$thirty_days_later.'"';
            }else if($status_kontrak=='Sixty Day'){
                $thirty_day_more = date('Y-m-d',strtotime('+60 days',strtotime(date("Y-m-d")))) . PHP_EOL;
                $inStatusKontrak='AND c.max_contract_end = "'.$thirty_day_more.'"';
            }else if($status_kontrak=='Not yet extended'){
                $inStatusKontrak='AND c.max_contract_end < curdate() AND a.status_aktif ="AKTIF"';
            }else if($status_kontrak=='Unfilled'){
                $inStatusKontrak='AND b.contract_end is null';
            }
        }

        $inDepartment = '';
        if (!in_array($loggedEmail, $email)) {
            $inDepartment = 'AND a.department_id =  "'.$department_id.'"';
        }

        $inDepartment_name='';
        if(request("department_name")){
            $department=request("department_name");
            $inDepartment_name = ' AND a.department_name = "'.$department.'"';
        }


        $query = DB::select("
                SELECT
                    a.status_staff,
                    a.enroll_id,
                    a.nik,
                    a.employee_name,
                    a.department_id,
                    a.status_jabatan,
                    a.sub_dept_name,
                    a.department_name,
                    a.status_kontrak_tetap,
                    a.status_aktif,
                    a.join_date,
                    a.tanggal_resign,
                    a.nomor_ktp,
                    b.contract,
                    b.contract_end,
                    c.max_contract_end,
                    d.contract AS contract_last,
                    d.contract_end AS contract_end_last,
                    mda.total_mangkir,
                    mda.total_izin,
                    mda.total_sakit
                FROM employee_atribut a
                LEFT JOIN employee_contract b
                    ON a.enroll_id = b.enroll_id
                LEFT JOIN (
                    SELECT enroll_id, MAX(contract_end) AS max_contract_end
                    FROM employee_contract
                    GROUP BY enroll_id
                ) c ON a.enroll_id = c.enroll_id
                LEFT JOIN (
                    SELECT enroll_id, contract, contract_end
                    FROM employee_contract ec
                    WHERE (ec.enroll_id, ec.contract_end) IN (
                        SELECT enroll_id, MAX(contract_end)
                        FROM employee_contract
                        GROUP BY enroll_id
                    )
                ) d ON a.enroll_id = d.enroll_id
              LEFT JOIN (
                    SELECT
                        enroll_id,
                        tanggal_berjalan,
                        SUM(CASE WHEN status_absen = 'M' THEN 1 ELSE 0 END) AS total_mangkir,
                        SUM(CASE WHEN status_absen = 'I' THEN 1 ELSE 0 END) AS total_izin,
                        SUM(CASE WHEN status_absen = 'S' THEN 1 ELSE 0 END) AS total_sakit
                    FROM master_data_absen_kehadiran
                    WHERE status_absen IN ('M','I','S')
                    GROUP BY enroll_id
                ) mda ON a.enroll_id = mda.enroll_id
                AND mda.tanggal_berjalan BETWEEN d.contract AND d.contract_end
                WHERE a.enroll_id IS NOT NULL
                    $inSearchVariable
                    $inEnrollId
                    $inNoKTP
                    $inIbuKandung
                    $inStatusAktif
                    $inStatusKontrak
                    $inDepartment
                    $inDepartment_name
                     AND a.status_staff = 'NON STAFF'
                ORDER BY a.enroll_id, b.contract_end
            ");
        return Excel::download(new ExcelPenilaianKinerjaNonstaff($query), 'Laporan_Penerimaan FG_Stok.xlsx');
    }

}
