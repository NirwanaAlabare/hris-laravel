<?php

namespace App\Http\Controllers\Hris\Bazzar;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use App\Models\EmployeeAtributHistory;
use App\Models\MutKaryawan;
use Carbon\Carbon;
use Dompdf\Options;
use Dompdf\FontMetrics;
use App\Models\EmployeeAtribut;
use App\Models\PengajuanBazzar;
use App\Models\VoucherBazzar;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Exports\ExportLineSheet;
use App\Exports\ExportPengajuanBazzar;

class BazzarController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Dashboard';
    }

    public function index(){
        $tglskrg = date('Y-m-d');
        $user = Auth::guard('admin')->user()->email;
        $data_dept = DB::select("select
        d.sub_dept_id isi,
        concat(department_name,' - ', sub_dept_name) tampil
        from department_all d
        left join
        (select sub_dept_id,count(employee_id) tot from employee_atribut where status_aktif = 'aktif' group by sub_dept_id) e on d.sub_dept_id = e.sub_dept_id
        where site_nirwana_id = 'NAG'
        and sub_dept_name not like 'line%'
        and e.tot != '0'
        group by d.sub_dept_id
        order by department_name asc");

        $selectemployee = $this->ajax_getallemployeeatribut();
        return view('hris/mutasi-karyawan/bazzar/pengajuan_bazzar', [
            'page' => 'dashboard-mut-karyawan', "subPageGroup" => "proses-karyawan", "subPage" => "form-lembur-non-sewing",
            "data_dept" => $data_dept, "user" => $user,
            "selectemployee" => $selectemployee
        ], $this->data);
    }

    public function export_excel()
    {
        return Excel::download(new ExportPengajuanBazzar(request()->id), 'Laporan_pengajuan_kupon.xlsx');
    }

    public function ajax_getallemployeeatribut()
    {
        $query =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, jenis_kelamin, employee_status, department_name, sub_dept_name, status_aktif, status_staff,
                                           concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')->where('status_aktif', 'aktif')
                                    ->groupby('enroll_id')
                                    ->orderby('employee_name', 'asc')
                                    ->get();
        return $query;

    }


    public function store(Request $request)
    {

        $user               = Auth::guard('admin')->user()->name;
        $enroll_id         = $request->enroll_id;
        $jumlah            = $request->jumlah;
        if(isset($enroll_id) || isset($jumlah)){
            PengajuanBazzar::create([
                'enroll_id' => $enroll_id,
                'jumlah' => $jumlah,
                'status' => 'pending',
                'operator' => $user,
            ]);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil disimpan']);
        }else{
            return response()->json(['status' => 'error', 'message' => 'Data gagal disimpan']);
        }

    }

    public function hapus(Request $request)
    {

        $user               = Auth::guard('admin')->user()->name;
        $id_bazzar         = $request->id_bazzar;
        if(isset($id_bazzar)){
            PengajuanBazzar::destroy($id_bazzar);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihappus']);
        }else{
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihappus']);
        }

    }
    public function get_bazzar(Request $request)
    {

        $user = Auth::guard('admin')->user()->name;
        $sub_dept_id = $request->sub_dept_id;
        if ($request->ajax()) {
            $data_tmp = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('employee_atribut.sub_dept_id', $sub_dept_id)->where('status', $request->status)->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
            return DataTables::of($data_tmp)->toJson();
        }

    }
    public function get_bazzar_detail(Request $request)
    {

        $user = Auth::guard('admin')->user()->name;
        if ($request->ajax()) {
            $data_tmp = PengajuanBazzar::select(DB::raw('SUM(pengajuan_bazzar.jumlah) as jumlah') , DB::raw('COUNT(*) as jml_data'),'pengajuan_bazzar.created_at', 'employee_atribut.nik', 'employee_atribut.employee_name','employee_atribut.sub_dept_name', 'employee_atribut.sub_dept_id','employee_atribut.department_name', 'employee_atribut.status_staff')->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->groupby('employee_atribut.sub_dept_id')->get();
            return DataTables::of($data_tmp)->toJson();
        }

    }

    public function approve(Request $request)
    {

        $user= Auth::guard('admin')->user()->name;
        $ids = $request->input('ids');

        if (!empty($ids)) {
            $pengajuanList = PengajuanBazzar::select('employee_atribut.*','pengajuan_bazzar.id','pengajuan_bazzar.jumlah')->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->whereIn('id', $ids)->get(['id', 'jumlah']);
            $currentYear = date('Y');
            $voucherCount = DB::table('voucher_bazzar')
                ->where('nomor_voucher', 'like', $currentYear . '.%')
                ->count();
            // Simpan voucher

            $vouchers = [];
            $nextVoucherNumber = $voucherCount + 1;
            foreach ($pengajuanList as $pengajuan) {
                $jumlahVoucher = floor($pengajuan->jumlah / 50000);

                for ($i = 0; $i < $jumlahVoucher; $i++) {
                    $formattedVoucher = sprintf(
                        '%s.%05d.%s %s',
                        $currentYear,
                        $nextVoucherNumber,
                        $pengajuan->enroll_id,
                        $pengajuan->employee_name
                    );

                    $vouchers[] = [
                        'nomor_voucher' => $formattedVoucher,
                        'id_pengajuan_bazzar' => $pengajuan->id,
                        'enroll_id' => $pengajuan->enroll_id,
                        'nominal' => ($jumlahVoucher * 50000) / $jumlahVoucher,
                    ];

                    $nextVoucherNumber++;
                }
            }

            // Insert ke database
            if (!empty($vouchers)) {
                DB::table('voucher_bazzar')->insert($vouchers);
            }

            PengajuanBazzar::whereIn('id', $ids)->update([
                'status' => 'approve',
                'operator' => $user,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diubah'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data yang dipilih'
            ]);
        }
    }

    public function reject(Request $request)
    {

        $user= Auth::guard('admin')->user()->name;
        $ids = $request->input('ids');

        if (!empty($ids)) {
            PengajuanBazzar::whereIn('id', $ids)->update([
                'status' => 'reject',
                'operator' => $user,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diubah'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data yang dipilih'
            ]);
        }

    }
    public function edit_pengajuan(Request $request)
    {

        $user= Auth::guard('admin')->user()->name;
        $ids = $request->input('id_pengajuan');
        $jumlah = $request->input('jumlah_edit');
        if ($ids) {
            PengajuanBazzar::where('id', $ids)->update([
                'jumlah' => $jumlah,
                'operator' => $user,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diubah'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data yang dipilih'
            ]);
        }
    }

    public function export_laporan_pengajuan(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        if($request->id){
            $data = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('status', 'approve')->where('id', $request->id)->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
        }else{
            $data = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('status', 'approve')->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
        }
        $total_jumlah = $data->sum('jumlah');
        $fileName='Pengajuan-Bazzar_'.date('His');
        $pdf = PDF::loadView('hris.mutasi-karyawan.bazzar.export-bazzar-pdf',["data" => $data,"total_jumlah"=>$total_jumlah])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
    }
    public function export_voucher(){
        if(request()->id){
            $data = VoucherBazzar::where('id_pengajuan_bazzar', request()->id)
            ->orderBy('nomor_voucher', 'ASC')
            ->get();
        }else{
            $data = VoucherBazzar::leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'voucher_bazzar.enroll_id')->where('sub_dept_id', request()->sub_dept_id)
            ->orderBy('nomor_voucher', 'ASC')
            ->get();
        }
        $fileName='Voucher_Bazzar_'.$data[0]->employee->employee_name.' '.date('Y-m-d').' '.rand(10,1000000);
        $pdf = PDF::loadView('hris.mutasi-karyawan.bazzar.export-voucher-pdf',["data" => $data])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
    }

}
