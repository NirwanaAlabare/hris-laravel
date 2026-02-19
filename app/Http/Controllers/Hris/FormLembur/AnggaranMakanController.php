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
    // public function index(Request $request){
    //     $tgl_awal = $request->tgl_awal;
    //     if(empty($tgl_awal)){
    //         $tgl_awal=date('Y-m-d');
    //     }
    //     $user=Auth::guard('admin')->user()->name;
    //     if ($request->ajax()) {
    //         if(Auth::guard('admin')->user()->name=='HR' || Auth::guard('admin')->user()->name=='IT' || Auth::guard('admin')->user()->name=='GA' || Auth::guard('admin')->user()->email =='mega@ptnag.com' || Auth::guard('admin')->user()->email =='rudy@ptnag.com' || Auth::guard('admin')->user()->email =='dev_hris' || Auth::guard('admin')->user()->email =='ersa@ptnag.com' || Auth::guard('admin')->user()->email =='indri@nag.nirwanaindonesia.com'  || Auth::guard('admin')->user()->email =='tita'){
    //             $data_input=DB::select("select a.id,a.keterangan,a.tanggal,DATE_FORMAT(tanggal, '%d %M %Y') tanggal_fix,d.department_name,a.staff,a.non_staff,a.created_by from estimasi_anggaran_makan a inner join (select*from department_all where site_nirwana_id='NAG' and status='AKTIF' group by department_id)d on a.dept=d.department_id where tanggal = '$tgl_awal' order by a.updated_at desc");
    //             return DataTables::of($data_input)->toJson();
    //         }else{
    //             $data_input=DB::select("select a.id,a.keterangan,a.tanggal,DATE_FORMAT(tanggal, '%d %M %Y') tanggal_fix,d.department_name,a.staff,a.non_staff,a.created_by from estimasi_anggaran_makan a inner join (select*from department_all where site_nirwana_id='NAG' and status='AKTIF' group by department_id)d on a.dept=d.department_id where tanggal = '$tgl_awal' and created_by='$user' order by a.updated_at desc");
    //             return DataTables::of($data_input)->toJson();
    //         }
    //     }
    //     $dept=DB::select('select department_id,department_name from department_all where site_nirwana_id="NAG" group by department_id');
    //     return view('hris/mutasi-karyawan/anggaran_makan/index', [
    //         'page' => 'dashboard-mut-karyawan',
    //         "subPageGroup" => "anggaran-makan",
    //         "subPage" => "estimasi-anggaran-makan",
    //         "user"=>$user,
    //         "dept"=>$dept,
    //     ], $this->data);
    // }
        public function index(Request $request){
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        if(empty($tgl_awal)){
            $tgl_awal=date('Y-m-d');
            $tgl_akhir=date('Y-m-d');
            // dd($tgl_awal,$tgl_akhir);
        }

        $user=Auth::guard('admin')->user()->name;


        if ($request->ajax()) {
            if(Auth::guard('admin')->user()->name=='HR' || Auth::guard('admin')->user()->name=='IT' || Auth::guard('admin')->user()->name=='GA' || Auth::guard('admin')->user()->email =='mega@ptnag.com' || Auth::guard('admin')->user()->email =='rudy@ptnag.com' || Auth::guard('admin')->user()->email =='dev_hris' || Auth::guard('admin')->user()->email =='ersa@ptnag.com' || Auth::guard('admin')->user()->email =='indri@nag.nirwanaindonesia.com'  || Auth::guard('admin')->user()->email =='tita'|| Auth::guard('admin')->user()->email =='willy@ptnag.com'|| Auth::guard('admin')->user()->email =='steven'){
                $data_input=DB::select("SELECT
                        a.id,
                        a.keterangan,
                        a.tanggal,
                        DATE_FORMAT(tanggal, '%d %M %Y') tanggal_fix,
                        COALESCE(
                            (SELECT department_name FROM department_all
                            WHERE department_id = a.dept
                            LIMIT 1),
                            'Unknown'
                        ) as department_name,
                        COALESCE(
                            (SELECT sub_dept_name FROM department_all
                            WHERE department_id = a.dept
                            AND sub_dept_id = a.sub_dept
                            LIMIT 1),
                            'No Sub Dept'
                        ) as sub_dept_name,
                        a.staff,
                        a.non_staff,
                        a.created_by
                    FROM estimasi_anggaran_makan a
                    WHERE tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'
                    ORDER BY a.tanggal,sub_dept_name ASC");

                // $data_input=DB::select("SELECT a.dept, d.department_name, DATE_FORMAT(a.tanggal, '%d %M %Y') AS tanggal_fix, a.keterangan, GROUP_CONCAT(DISTINCT a.created_by ORDER BY a.created_by SEPARATOR ', ') AS created_by, SUM(a.staff) AS staff, SUM(a.non_staff) AS non_staff from estimasi_anggaran_makan a INNER JOIN (SELECT department_id, department_name FROM department_all WHERE site_nirwana_id = 'NAG' AND status = 'AKTIF' GROUP BY department_id, department_name) d ON a.dept = d.department_id where tanggal  BETWEEN '$tgl_awal' AND '$tgl_akhir' GROUP BY a.dept, d.department_name, a.tanggal, a.keterangan order by a.updated_at desc");
                // dd($data_input);
                return DataTables::of($data_input)->toJson();
            }else{
                $data_input=DB::select("SELECT
                        a.id,
                        a.keterangan,
                        a.tanggal,
                        DATE_FORMAT(tanggal, '%d %M %Y') tanggal_fix,
                        COALESCE(
                            (SELECT department_name FROM department_all
                            WHERE department_id = a.dept
                            LIMIT 1),
                            'Unknown'
                        ) as department_name,
                        COALESCE(
                            (SELECT sub_dept_name FROM department_all
                            WHERE department_id = a.dept
                            AND sub_dept_id = a.sub_dept
                            LIMIT 1),
                            'No Sub Dept'
                        ) as sub_dept_name,
                        a.staff,
                        a.non_staff,
                        a.created_by
                    FROM estimasi_anggaran_makan a
                    WHERE tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir' AND a.created_by='$user'
                    ORDER BY a.tanggal,sub_dept_name ASC");
                // $data_input=DB::select("SELECT a.dept, d.department_name, DATE_FORMAT(a.tanggal, '%d %M %Y') AS tanggal_fix, a.keterangan, GROUP_CONCAT(DISTINCT a.created_by ORDER BY a.created_by SEPARATOR ', ') AS created_by, SUM(a.staff) AS staff, SUM(a.non_staff) AS non_staff from estimasi_anggaran_makan a INNER JOIN (SELECT department_id, department_name FROM department_all WHERE site_nirwana_id = 'NAG' AND status = 'AKTIF' GROUP BY department_id, department_name) d ON a.dept = d.department_id where tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir' GROUP BY a.dept, d.department_name, a.tanggal, a.keterangan  order by a.updated_at desc");
                return DataTables::of($data_input)->toJson();
            }
        }
        // $dept=DB::select('select department_id,department_name from department_all where site_nirwana_id="NAG" group by department_id');
        $dept=DB::select('select department_id,department_name,sub_dept_id,sub_dept_name from department_all where site_nirwana_id="NAG" and status="AKTIF" ORDER BY department_name ASC,sub_dept_name ASC ');
        return view('hris/mutasi-karyawan/anggaran_makan/index', [
            'page' => 'dashboard-mut-karyawan',
            "subPageGroup" => "anggaran-makan",
            "subPage" => "estimasi-anggaran-makan",
            "user"=>$user,
            "dept"=>$dept,
        ], $this->data);
    }
    // public function store(){
    //     $this->_validation(request());
    //     $tanggal=request()->tanggal;
    //     $keterangan=request()->keterangan;
    //     $dept=request()->bagian;
    //     $staff=request()->staff;
    //     $non_staff=request()->non_staff;
    //     $timestamp=Carbon::now();
    //     $created_by=Auth::guard('admin')->user()->name;
    //     DB::insert("insert into estimasi_anggaran_makan (tanggal,keterangan,dept,staff,non_staff,created_by,created_at,updated_at) values('$tanggal','$keterangan','$dept','$staff','$non_staff','$created_by','$timestamp','$timestamp')");
    // }
       public function store()
        {
            $this->_validation(request());

            $tanggal = request()->tanggal;
            $keterangan = request()->keterangan;
            [$department_id, $sub_dept_id] = explode('|', request()->bagian);
            $staff = request()->staff;
            $non_staff = request()->non_staff;
            $timestamp = Carbon::now();
            $created_by = Auth::guard('admin')->user()->name;

            $existingData = DB::table('estimasi_anggaran_makan')
                ->where('tanggal', $tanggal)
                ->where('dept', $department_id)
                ->where('sub_dept', $sub_dept_id)
                ->where('keterangan', $keterangan)
                ->first();

            if ($existingData) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data untuk tanggal dan bagian ini sudah ada.'
                ], 422);
            }

            DB::table('estimasi_anggaran_makan')->insert([
                'tanggal' => $tanggal,
                'keterangan' => $keterangan,
                'dept' => $department_id,
                'sub_dept' => $sub_dept_id,
                'staff' => $staff,
                'non_staff' => $non_staff,
                'created_by' => $created_by,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ]);
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
        // dd($id_estimasi);
        $dataestimasi=DB::select("SELECT
                a.id,
                a.keterangan,
                a.tanggal,
                DATE_FORMAT(a.tanggal, '%d %M %Y') AS tanggal_fix,

                a.dept AS department_id,
                a.sub_dept AS sub_dept_id,

                COALESCE(
                    (SELECT department_name FROM department_all
                    WHERE department_id = a.dept
                    LIMIT 1),
                    'Unknown'
                ) AS department_name,

                COALESCE(
                    (SELECT sub_dept_name FROM department_all
                    WHERE department_id = a.dept
                    AND sub_dept_id = a.sub_dept
                    LIMIT 1),
                    'No Sub Bagian'
                ) AS sub_dept_name,

                a.staff,
                a.non_staff,
                a.created_by
            FROM estimasi_anggaran_makan a
            WHERE a.id = '$id_estimasi'
            ");
        return $dataestimasi;
    }
    // public function update(){
    //     $id_estimasi=request()->id;
    //     $tanggal=request()->tanggal;
    //     $keterangan=request()->keterangan;
    //     $dept=request()->bagian;
    //     $staff=request()->staff;

    //     $non_staff=request()->non_staff;
    //     $timestamp=Carbon::now();
    //     DB::update("update estimasi_anggaran_makan set tanggal='$tanggal', keterangan='$keterangan',dept='$dept',staff='$staff',non_staff='$non_staff' where id='$id_estimasi'");
    // }

    public function update()
{
    $id_estimasi = request()->id;
    $timestamp=Carbon::now();
    $created_by=Auth::guard('admin')->user()->name;
// dd($timestamp, $created_by);
    // ambil data lama
    $old = DB::table('estimasi_anggaran_makan')
        ->where('id', $id_estimasi)
        ->first();

    $dept = $old->dept;        // default: data lama
    $sub_dept = $old->sub_dept;

    $bagian = request()->bagian; // dept|sub_dept

    if (!empty($bagian)) {
        $explode = explode('|', $bagian);

        // ✅ dept WAJIB disimpan
        if (!empty($explode[0])) {
            $dept = $explode[0];
        }

        // ✅ sub_dept boleh kosong
        if (isset($explode[1]) && $explode[1] !== '') {
            $sub_dept = $explode[1];
        }
    }

    // dd($dept, $sub_dept);

    DB::update(
        "UPDATE estimasi_anggaran_makan
         SET
            tanggal = ?,
            keterangan = ?,
            dept = ?,
            sub_dept = ?,
            staff = ?,
            non_staff = ?,
            updated_at = ?
         WHERE id = ?",
        [
            request()->tanggal,
            request()->keterangan,
            $dept,
            $sub_dept,
            request()->staff,
            request()->non_staff,
            $timestamp,
            $id_estimasi
        ]
    );

    return response()->json(['success' => true]);
}

    public function delete(){
        $id_estimasi=request()->id;
        DB::delete("delete from estimasi_anggaran_makan where id = '$id_estimasi'");
    }
    public function export_excel_konsumsi_estimasi(Request $request){
        // dd($request->from);
        // $from = \Carbon\Carbon::createFromFormat('d-m-Y', $request->from)->format('Y-m-d');
        $from = $request->from ?? date('Y-m-d');
        $to   = $request->to ?? date('Y-m-d');
        return Excel::download(new BiayaMakanKaryawanEstimasi($from, $to), 'Laporan_Penerimaan FG_Stok.xlsx');
    }
    public function export_excel_overtime_recap(Request $request){
        $from = \Carbon\Carbon::createFromFormat('d-m-Y', $request->from)->format('Y-m-d');
        return Excel::download(new OvertimeRecap($from), 'Laporan_Penerimaan FG_Stok.xlsx');
    }
    public function export_excel_overtime_recap2(Request $request){
        $from=date('Y-m-d');
        $fileName = $from." Laporan Rekap lembur ".rand().".xlsx";
        return Excel::download(new OvertimeRecap($from), $fileName);
    }
    public function export_pdf_konsumsi(Request $request){
                $from = $request->from ?? date('Y-m-d');
        $to   = $request->to ?? date('Y-m-d');
        // dd($from,$to);
        // $tanggal = \Carbon\Carbon::createFromFormat('d-m-Y', request()->tanggal)->format('Y-m-d');
        // $tanggal_carbon=Carbon::parse($tanggal)->translatedFormat('l d F Y');
        $data = DB::select("SELECT 'LEMBUR' shift,
            a.tanggal,
                DATE_FORMAT(a.tanggal, '%d %M %Y') AS tanggal_fix,
                COALESCE(
                    (SELECT d.department_name
                    FROM department_all d
                    WHERE d.department_id = a.dept
                    LIMIT 1),
                    'Unknown'
                ) AS department,
                COALESCE(
                    (SELECT d.sub_dept_name
                    FROM department_all d
                    WHERE d.department_id = a.dept
                    AND d.sub_dept_id = a.sub_dept
                    LIMIT 1),
                    'No Sub Dept'
                ) AS sub_dept_name,
            COALESCE(a.non_staff,0) AS non_staff,
            IF(COALESCE(a.non_staff,0) > 0, 8000, 0) AS harga,
            COALESCE(a.non_staff,0) * 8000 AS jumlah,
            COALESCE(a.staff,0) AS staff,
            IF(COALESCE(a.staff,0) > 0, 10000, 0) AS harga2,
            COALESCE(a.staff,0) * 10000 AS jumlah2,
            (COALESCE(a.staff,0) + COALESCE(a.non_staff,0)) AS jumlah_karyawan,
            (COALESCE(a.staff,0) * 10000)
            + (COALESCE(a.non_staff,0) * 8000) AS total
            FROM estimasi_anggaran_makan a
            WHERE a.tanggal BETWEEN '$from' AND '$to'
            and a.keterangan ='LEMBUR'
            ORDER BY department, sub_dept_name");
            // dd( $data);
        $data2 = DB::select("SELECT 'SHIFT MALAM' shift,
            a.tanggal,
                DATE_FORMAT(a.tanggal, '%d %M %Y') AS tanggal_fix,

                -- Department (aman walau dept NULL)
                COALESCE(
                    (SELECT d.department_name
                    FROM department_all d
                    WHERE d.department_id = a.dept
                    LIMIT 1),
                    'Unknown'
                ) AS department,

                -- Sub Department (aman walau sub_dept NULL)
                COALESCE(
                    (SELECT d.sub_dept_name
                    FROM department_all d
                    WHERE d.department_id = a.dept
                    AND d.sub_dept_id = a.sub_dept
                    LIMIT 1),
                    'No Sub Dept'
                ) AS sub_dept_name,
            COALESCE(a.non_staff,0) AS non_staff,
            IF(COALESCE(a.non_staff,0) > 0, 8000, 0) AS harga,
            COALESCE(a.non_staff,0) * 8000 AS jumlah,

            COALESCE(a.staff,0) AS staff,
            IF(COALESCE(a.staff,0) > 0, 10000, 0) AS harga2,
            COALESCE(a.staff,0) * 10000 AS jumlah2,

            (COALESCE(a.staff,0) + COALESCE(a.non_staff,0)) AS jumlah_karyawan,
            (COALESCE(a.staff,0) * 10000)
            + (COALESCE(a.non_staff,0) * 8000) AS total

            FROM estimasi_anggaran_makan a
            WHERE a.tanggal BETWEEN '$from' AND '$to'
            and a.keterangan ='SHIFT MALAM'
                ORDER BY department, sub_dept_name");
        $data6 = DB::select("SELECT 'TAKJIL' shift,
            a.tanggal,
                DATE_FORMAT(a.tanggal, '%d %M %Y') AS tanggal_fix,
                COALESCE(
                    (SELECT d.department_name
                    FROM department_all d
                    WHERE d.department_id = a.dept
                    LIMIT 1),
                    'Unknown'
                ) AS department,
                COALESCE(
                    (SELECT d.sub_dept_name
                    FROM department_all d
                    WHERE d.department_id = a.dept
                    AND d.sub_dept_id = a.sub_dept
                    LIMIT 1),
                    'No Sub Dept'
                ) AS sub_dept_name,
            COALESCE(a.non_staff,0) AS non_staff,
            IF(COALESCE(a.non_staff,0) > 0, 5000, 0) AS harga,
            COALESCE(a.non_staff,0) * 5000 AS jumlah,
            COALESCE(a.staff,0) AS staff,
            IF(COALESCE(a.staff,0) > 0, 5000, 0) AS harga2,
            COALESCE(a.staff,0) * 5000 AS jumlah2,
            (COALESCE(a.staff,0) + COALESCE(a.non_staff,0)) AS jumlah_karyawan,
            (COALESCE(a.staff,0) * 5000)
            + (COALESCE(a.non_staff,0) * 5000) AS total
            FROM estimasi_anggaran_makan a
            WHERE a.tanggal BETWEEN '$from' AND '$to'
            and a.keterangan ='TAKJIL'
            ORDER BY department, sub_dept_name");
        $data3 = DB::select("SELECT
                'LEMBUR TOTAL' AS shift,
                '' AS department,


                SUM(COALESCE(a.non_staff, 0)) AS non_staff,
            COUNT(DISTINCT CASE
                WHEN COALESCE(a.non_staff,0) > 0
                THEN COALESCE(NULLIF(a.sub_dept, ''), a.dept)
            END) * 8000 AS harga,
                SUM(COALESCE(a.non_staff, 0)) * 8000 AS jumlah,

                SUM(COALESCE(a.staff, 0)) AS staff,
                COUNT(DISTINCT CASE
                WHEN COALESCE(a.staff,0) > 0
                THEN COALESCE(NULLIF(a.sub_dept, ''), a.dept)
            END) * 10000 AS harga2,
                SUM(COALESCE(a.staff, 0)) * 10000 AS jumlah2,

                SUM(COALESCE(a.staff, 0) + COALESCE(a.non_staff, 0)) AS jumlah_karyawan,

                (SUM(COALESCE(a.non_staff, 0)) * 8000)
                + (SUM(COALESCE(a.staff, 0)) * 10000) AS total
            FROM estimasi_anggaran_makan a
            WHERE a.tanggal BETWEEN '$from' AND '$to'
            AND a.keterangan = 'LEMBUR'
            AND EXISTS (
                SELECT 1
                FROM department_all b
                WHERE b.department_id = a.dept
                AND b.site_nirwana_id IN ('NAG','NAK','NAGD')
        )");
        $data4 = DB::select(" SELECT
                'SHIFT MALAM TOTAL' AS shift,
                '' AS department,

                SUM(COALESCE(a.non_staff, 0)) AS non_staff,
                COUNT(DISTINCT CASE
                WHEN COALESCE(a.non_staff,0) > 0
                THEN COALESCE(NULLIF(a.sub_dept, ''), a.dept)
            END) * 8000 AS harga,
                SUM(COALESCE(a.non_staff, 0)) * 8000 AS jumlah,

                SUM(COALESCE(a.staff, 0)) AS staff,
            COUNT(DISTINCT CASE
                WHEN COALESCE(a.staff,0) > 0
                THEN COALESCE(NULLIF(a.sub_dept, ''), a.dept)
            END) * 10000 AS harga2,
                SUM(COALESCE(a.staff, 0)) * 10000 AS jumlah2,

                SUM(COALESCE(a.staff, 0) + COALESCE(a.non_staff, 0)) AS jumlah_karyawan,

                (SUM(COALESCE(a.non_staff, 0)) * 8000)
                + (SUM(COALESCE(a.staff, 0)) * 10000) AS total

            FROM estimasi_anggaran_makan a
            WHERE a.tanggal BETWEEN '$from' AND '$to'
            AND a.keterangan = 'SHIFT MALAM'
            AND EXISTS (
                SELECT 1
                FROM department_all b
                WHERE b.department_id = a.dept
                AND b.site_nirwana_id IN ('NAG','NAK','NAGD')
                )");
        $data7 = DB::select(" SELECT
                'TAKJIL TOTAL' AS shift,
                '' AS department,

                SUM(COALESCE(a.non_staff, 0)) AS non_staff,
                COUNT(DISTINCT CASE
                WHEN COALESCE(a.non_staff,0) > 0
                THEN COALESCE(NULLIF(a.sub_dept, ''), a.dept)
            END) * 5000 AS harga,
                SUM(COALESCE(a.non_staff, 0)) * 5000 AS jumlah,

                SUM(COALESCE(a.staff, 0)) AS staff,
            COUNT(DISTINCT CASE
                WHEN COALESCE(a.staff,0) > 0
                THEN COALESCE(NULLIF(a.sub_dept, ''), a.dept)
            END) * 5000 AS harga2,
                SUM(COALESCE(a.staff, 0)) * 5000 AS jumlah2,

                SUM(COALESCE(a.staff, 0) + COALESCE(a.non_staff, 0)) AS jumlah_karyawan,

                (SUM(COALESCE(a.non_staff, 0)) * 5000)
                + (SUM(COALESCE(a.staff, 0)) * 5000) AS total

            FROM estimasi_anggaran_makan a
            WHERE a.tanggal BETWEEN '$from' AND '$to'
            AND a.keterangan = 'TAKJIL'
            AND EXISTS (
                SELECT 1
                FROM department_all b
                WHERE b.department_id = a.dept
                AND b.site_nirwana_id IN ('NAG','NAK','NAGD')
                )");

                $data5 = DB::select("SELECT
                'GRAND TOTAL' AS shift,
                '' AS department,

                SUM(COALESCE(a.non_staff,0)) AS non_staff,

                /* ===== HARGA NON STAFF ===== */
                SUM(
                    CASE
                        WHEN COALESCE(a.non_staff,0) > 0 THEN
                            CASE
                                WHEN a.keterangan = 'TAKJIL' THEN 5000
                                ELSE 8000
                            END
                        ELSE 0
                    END
                ) AS harga,

                /* ===== JUMLAH NON STAFF ===== */
                SUM(
                    CASE
                        WHEN a.keterangan = 'TAKJIL' THEN 5000
                        ELSE 8000
                    END * COALESCE(a.non_staff,0)
                ) AS jumlah,

                SUM(COALESCE(a.staff,0)) AS staff,

                /* ===== HARGA STAFF ===== */
                SUM(
                    CASE
                        WHEN COALESCE(a.staff,0) > 0 THEN
                            CASE
                                WHEN a.keterangan = 'TAKJIL' THEN 5000
                                ELSE 10000
                            END
                        ELSE 0
                    END
                ) AS harga2,

                /* ===== JUMLAH STAFF ===== */
                SUM(
                    CASE
                        WHEN a.keterangan = 'TAKJIL' THEN 5000
                        ELSE 10000
                    END * COALESCE(a.staff,0)
                ) AS jumlah2,

                SUM(COALESCE(a.staff,0) + COALESCE(a.non_staff,0)) AS jumlah_karyawan,

                /* ===== TOTAL ===== */
                SUM(
                    CASE
                        WHEN a.keterangan = 'TAKJIL'
                            THEN (COALESCE(a.staff,0) + COALESCE(a.non_staff,0)) * 5000
                        ELSE (COALESCE(a.non_staff,0) * 8000)
                        + (COALESCE(a.staff,0) * 10000)
                    END
                ) AS total

            FROM estimasi_anggaran_makan a
            WHERE a.tanggal BETWEEN '$from' AND '$to'
            AND EXISTS (
                SELECT 1
                FROM department_all b
                WHERE b.department_id = a.dept
                AND b.site_nirwana_id IN ('NAG','NAK','NAGD')
            )
        ");

            $fileName='Budgeting Makan '.$from.' '.rand();
        $pdf = PDF::loadView('hris/mutasi-karyawan/anggaran_makan/approval_anggaran_makan',["data" => $data,"data2"=>$data2,"data3"=>$data3,"data4"=>$data4,"data6"=>$data6,"data7"=>$data7,"data4"=>$data4,"data5"=>$data5,"tanggal"=>$from ,"tanggal2"=>$to])->setPaper('A4', 'potrait')->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
    }
    public function getEstimasiMakan(Request $request)
    {
        $subDeptId = $request->sub_dept_id;
        $tgl = date('Y-m-d', strtotime($request->tanggal));

        $data = DB::selectOne("
            SELECT
                SUM(CASE WHEN status_staff = 'NON STAFF' THEN 1 ELSE 0 END) AS non_staff,
                SUM(CASE WHEN status_staff = 'STAFF' THEN 1 ELSE 0 END) AS staff
            FROM (
                SELECT e.status_staff, e.sub_dept_id
                FROM mut_karyawan_input_form_lembur_det d
                JOIN mut_karyawan_input_form_lembur l ON d.no_form = l.no_form
                JOIN employee_atribut e ON d.enroll_id = e.enroll_id
                WHERE DATE(l.tgl_lembur) = ?
                AND d.konsumsi = 1

                UNION ALL

                SELECT e.status_staff, e.sub_dept_id
                FROM mut_karyawan_input_non_sewing_form_lembur_det d
                JOIN mut_karyawan_input_non_sewing_form_lembur l ON d.no_form = l.no_form
                JOIN employee_atribut e ON d.enroll_id = e.enroll_id
                WHERE DATE(l.tgl_lembur) = ?
                AND d.konsumsi = 1
            ) x
            WHERE x.sub_dept_id = ?
        ", [$tgl, $tgl, $subDeptId]);

        return response()->json([
            'non_staff' => $data->non_staff ?? 0,
            'staff'     => $data->staff ?? 0
        ]);
    }
}


