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
use App\Models\DataKoreksiPotongan;
use App\Models\VoucherBazzar;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
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
        return Excel::download(new ExportPengajuanBazzar(request()->id,request()->sub_dept_id,request()->status), 'Laporan_pengajuan_kupon.xlsx');
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
        $user = Auth::guard('admin')->user()->name;
        $email = Auth::guard('admin')->user()->email;
        $id_bazzar = $request->id_bazzar;
        if (!$id_bazzar) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus']);
        }

        // Cek apakah data ada dan apakah sudah di-approve
        $pengajuan = PengajuanBazzar::find($id_bazzar);

        if (!$pengajuan) {
            return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }


        if ($pengajuan->status == 'approve') {
            if ($email != 'fadli' && $email != 'mega@ptnag.com' && $email != 'rudy@ptnag.com'){
                return response()->json(['status' => 'error', 'message' => 'Anda tidak memiliki izin untuk menghapus data yang sudah di-approve']);
            }

            // Hapus DataKoreksiPotongan
            $periode_tanggal_koreksi = '02/26/2025 - 03/25/2025';
            DataKoreksiPotongan::whereIn('enroll_id', [$pengajuan->enroll_id])
                ->where('jenis_potongan', 3)
                ->where('periode_tanggal_koreksi', $periode_tanggal_koreksi)
                ->delete();
        }

        $pengajuan->delete();

        VoucherBazzar::where('id_pengajuan_bazzar', $id_bazzar)->delete();

        return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
    }

    public function hapus_per_bagian(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $sub_dept_id = $request->sub_dept_id;

        if (!$sub_dept_id) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus']);
        }

        // Pastikan ada data yang akan dihapus
        $data_tmp = PengajuanBazzar::leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')
            ->where('employee_atribut.sub_dept_id', $sub_dept_id)
            ->where('pengajuan_bazzar.status', $request->status)
            ->select('pengajuan_bazzar.*')
            ->get();

        if ($data_tmp->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ada data yang ditemukan']);
        }
        if($request->status == 'approve'){
            VoucherBazzar::whereIn('id_pengajuan_bazzar', $data_tmp->pluck('id'))->delete();
            $periode_tanggal_koreksi = '02/26/2025 - 03/25/2025';
            DataKoreksiPotongan::whereIn('enroll_id', $data_tmp->pluck('enroll_id'))->where('jenis_potongan', 3)->where('periode_tanggal_koreksi', $periode_tanggal_koreksi)->delete();
        }
        PengajuanBazzar::whereIn('id', $data_tmp->pluck('id'))->delete();

        return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
    }



    public function get_bazzar(Request $request)
    {

        $user = Auth::guard('admin')->user()->name;
        $user_email = Auth::guard('admin')->user()->email;
        $sub_dept_id = $request->sub_dept_id;
        if ($request->ajax()) {
            if($user_email == 'mega@ptnag.com' || $user_email == 'rudy@patnag.com' || $user_email == 'fadli' || $user_email == 'HR' || $user_email == 'ersa@ptnag.com' || $user_email == 'kiki@ptnag.com' || $user_email == 'hrd'){
                $data_tmp = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('employee_atribut.sub_dept_id', $sub_dept_id)->where('status', $request->status)->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
            }else{
                $data_tmp = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('pengajuan_bazzar.operator', $user)->where('employee_atribut.sub_dept_id', $sub_dept_id)->where('status', $request->status)->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
            }
            return DataTables::of($data_tmp)->toJson();
        }
    }
    public function get_bazzar_detail(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        $user_email = Auth::guard('admin')->user()->email;

        if ($request->ajax()) {
            $status = $request->status;
            $search = $request->search; // Ambil input pencarian

            if ($status === "waiting_list") {
                $status = "pending";
            } elseif ($status === "approve_list") {
                $status = "approve";
            }

            // Query dasar
            $query = PengajuanBazzar::select(
                    DB::raw('SUM(pengajuan_bazzar.jumlah) as jumlah'),
                    DB::raw('COUNT(*) as jml_data'),
                    'pengajuan_bazzar.id',
                    'pengajuan_bazzar.created_at',
                    'pengajuan_bazzar.status',
                    'employee_atribut.nik',
                    'employee_atribut.employee_name',
                    'employee_atribut.sub_dept_name',
                    'employee_atribut.sub_dept_id',
                    'employee_atribut.department_name',
                    'employee_atribut.status_staff'
                )
                ->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')
                ->where('pengajuan_bazzar.status', $status);

            // Jika user bukan admin tertentu, filter berdasarkan operator
            if (!in_array($user_email, ['mega@ptnag.com', 'rudy@patnag.com', 'fadli', 'HR', 'ersa@ptnag.com', 'kiki@ptnag.com', 'hrd'])) {
                $query->where('pengajuan_bazzar.operator', $user);
            }

            // Filter hanya berdasarkan nama karyawan, tanpa mengubah agregasi
            if (!empty($search)) {
                $query->whereExists(function ($subquery) use ($search) {
                    $subquery->select(DB::raw(1))
                             ->from('employee_atribut')
                             ->whereRaw('employee_atribut.enroll_id = pengajuan_bazzar.enroll_id')
                             ->where('employee_atribut.employee_name', 'like', "%{$search}%");
                });
            }

            $data_tmp = $query->groupBy('employee_atribut.sub_dept_id')->get();
            return DataTables::of($data_tmp)->toJson();
        }
    }



    public function approve(Request $request)
    {

        $user= Auth::guard('admin')->user()->name;
        $email = Auth::guard('admin')->user()->email;

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
                $periode_tanggal_koreksi = '02/26/2025 - 03/25/2025';

                $exists = DataKoreksiPotongan::where('enroll_id', $pengajuan->enroll_id)
                ->where('periode_tanggal_koreksi', $periode_tanggal_koreksi)
                ->where('jenis_potongan', 3)
                ->exists();

                $now = Carbon::now();
                $kode_koreksi_potongan = $now->format('YmdHi') . $pengajuan->nik;
                $tanggal_koreksi = $now->format('Y-m-d');
                $jumlah_rp_potongan = $pengajuan->jumlah;
                $jenis_potongan = 3;
                $keterangan = 'Pengajuan Bazzar';
                if(!$exists) {
                    DataKoreksiPotongan::create([
                        'uuid' => Str::uuid(),
                        'kode_koreksi_potongan' => $kode_koreksi_potongan,
                        'tanggal_koreksi' => $tanggal_koreksi,
                        'enroll_id' => $pengajuan->enroll_id,
                        'jumlah_rp_potongan' => $jumlah_rp_potongan,
                        'periode_tanggal_koreksi' => $periode_tanggal_koreksi,
                        'jenis_potongan' => $jenis_potongan,
                        'keterangan' => $keterangan,
                        'operator' => $email
                    ]);
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
        $user = Auth::guard('admin')->user()->name;
        $email = Auth::guard('admin')->user()->email;
        $ids = $request->input('id_pengajuan');
        $jumlah = $request->input('jumlah_edit');

        if (!$ids) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data yang dipilih'
            ]);
        }

        // Cek apakah pengajuan sudah di-approve
        $pengajuan = PengajuanBazzar::find($ids);

        if (!$pengajuan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        if ($pengajuan->status == 'approve') {
            if ($email != 'fadli' && $email != 'mega@ptnag.com' && $email != 'rudy@ptnag.com'){
                return response()->json(['status' => 'error', 'message' => 'Anda tidak memiliki izin untuk mengedit data yang sudah di-approve']);
            }

            $periode_tanggal_koreksi = '02/26/2025 - 03/25/2025';
            $dataKoreksi = DataKoreksiPotongan::where('enroll_id', $pengajuan->enroll_id)
            ->where('periode_tanggal_koreksi', $periode_tanggal_koreksi)
            ->where('jenis_potongan', 3)
            ->first();

            if ($dataKoreksi) {
                DataKoreksiPotongan::where('uuid','=', $dataKoreksi->uuid)
                    ->update([
                      'jumlah_rp_potongan' => $jumlah,
                    'operator' => $email
                    ]);

            }
        }

        // Update data jika belum di-approve
        $pengajuan->update([
            'jumlah' => $jumlah,
            'operator' => $user,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diubah'
        ]);
    }


    public function export_laporan_pengajuan(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        if($request->id){
            $data = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('status', 'pending')->where('id', $request->id)->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
        }else if($request->sub_dept_id){
            $data = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('status', 'pending')->where('employee_atribut.sub_dept_id', $request->sub_dept_id)->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
        }else{
            $data = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('status', 'pending')->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
        }
        $total_jumlah = $data->sum('jumlah');
        $fileName='Pengajuan-Bazzar_'.date('His');
        $pdf = PDF::loadView('hris.mutasi-karyawan.bazzar.export-bazzar-pdf',["data" => $data,"total_jumlah"=>$total_jumlah])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
    }
    public function export_laporan_pengajuan_ids(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        if($request->id){
            $data = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('status', 'approve')->where('id', $request->id)->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
        }else if($request->sub_dept_id){
            $data = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('status', 'approve')->where('employee_atribut.sub_dept_id', $request->sub_dept_id)->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
        }else{
            $data = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('status', 'approve')->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
        }
        $total_jumlah = $data->sum('jumlah');
        $fileName='Pengajuan-Bazzar_'.date('His');
        $pdf = PDF::loadView('hris.mutasi-karyawan.bazzar.export-bazzar-pdf',["data" => $data,"total_jumlah"=>$total_jumlah])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
    }
    public function export_laporan_tanda_terima_bagian(Request $request)
    {
        $user = Auth::guard('admin')->user()->name;
        if($request->id){
            $data = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('status', 'approve')->where('id', $request->id)->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
        }else if($request->sub_dept_id){
            $data = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('status', 'approve')->where('employee_atribut.sub_dept_id', $request->sub_dept_id)->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
        }else{
            $data = PengajuanBazzar::select('pengajuan_bazzar.*', 'employee_atribut.nik', 'employee_atribut.employee_name', 'employee_atribut.department_name', 'employee_atribut.sub_dept_name', 'employee_atribut.status_staff')->where('status', 'approve')->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->get();
        }
        $total_jumlah = $data->sum('jumlah');
        $fileName='Pengajuan-Bazzar_'.date('His');
        $pdf = PDF::loadView('hris.mutasi-karyawan.bazzar.export-pengajuan-tanda-terima',["data" => $data,"total_jumlah"=>$total_jumlah])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
    }
    public function export_voucher(){
        if(request()->id){
            $data = VoucherBazzar::where('id_pengajuan_bazzar', request()->id)
            ->orderBy('voucher_bazzar.enroll_id', 'ASC')
            ->get();
        }else{
            $data = VoucherBazzar::leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'voucher_bazzar.enroll_id')->where('sub_dept_id', request()->sub_dept_id)
            ->orderBy('voucher_bazzar.enroll_id', 'ASC')
            ->get();
        }
        $fileName='Voucher_Bazzar_ '.date('Y-m-d').' '.rand(10,1000000);
        $pdf = PDF::loadView('hris.mutasi-karyawan.bazzar.export-voucher-pdf',["data" => $data])->setPaper('F4', 'fotrait')->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
    }

}
