<?php

namespace App\Http\Controllers\Hris\LicensePermit;
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
use App\Models\PengajuanDokumenLegal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Exports\ExportLineSheet;
use App\Exports\ExportPengajuanBazzar;
use Illuminate\Support\Facades\Storage;
use FilippoToso\PdfWatermarker\Facades\ImageWatermarker;
use FilippoToso\PdfWatermarker\Support\Position;
use Intervention\Image\Facades\Image;
class LicensePermitController extends AdminBaseController
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
        return view('hris/ga/license_permit/dokumen_legal', [
            'page' => 'dashboard-mut-karyawan', "subPageGroup" => "proses-karyawan", "subPage" => "form-lembur-non-sewing",
            "data_dept" => $data_dept, "user" => $user,
            "selectemployee" => $selectemployee
        ], $this->data);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'jenis_dokumen'       => 'required|string',
            'kode_dokumen'        => 'required|string|unique:pengajuan_dokumen_legal,kode_dokumen',
            'nama_dokumen'        => 'required|string',
            'tanggal_berlaku'     => 'required|date',
            'tanggal_kadaluarsa'  => 'required|date|after:tanggal_berlaku',
            'penanggung_jawab'    => 'required|string',
            'penanggung_jawab_email'    => 'required|string',
            'revisi_ke'           => 'required|integer|min:1',
            'keterangan'          => 'nullable|string',
            'dokumen_file.*'      => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:2048', // Maks 2MB per file
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pengajuan = PengajuanDokumenLegal::create([
            'jenis_dokumen'       => $request->jenis_dokumen,
            'kode_dokumen'        => $request->kode_dokumen,
            'nama_dokumen'        => $request->nama_dokumen,
            'tanggal_berlaku'     => $request->tanggal_berlaku,
            'tanggal_kadaluarsa'  => $request->tanggal_kadaluarsa,
            'penanggung_jawab'    => $request->penanggung_jawab,
            'penanggung_jawab_email' => $request->penanggung_jawab_email,
            'revisi_ke'           => $request->revisi_ke,
            'keterangan'          => $request->keterangan,
            'dokumen_url'         => null,
        ]);

        if ($request->hasFile('dokumen_file')) {
                $file = $request->file('dokumen_file');
                $filePath = $file->store('dokumen_legal', 'public');
                $fileUrl = asset('storage/' . $filePath);
                $pengajuan->update([
                    'dokumen_url' => $fileUrl
                ]);
        }

        // Simpan ke database dalam format JSON

        return response()->json([
            'message' => 'Pengajuan dokumen berhasil disimpan!',
            'data'    => $pengajuan
        ], 201);

    }

    public function get_dokumen_legal(Request $request)
{
    if ($request->ajax()) {
        $data = PengajuanDokumenLegal::query();

        if ($request->jenis_dokumen) {
            $data->where('jenis_dokumen', $request->jenis_dokumen);
        }

        if ($request->kode_dokumen) {
            $data->where('kode_dokumen', $request->kode_dokumen);
        }

        return DataTables::of($data)
        ->editColumn('tanggal_berlaku', function ($row) {
            return Carbon::parse($row->tanggal_berlaku)->translatedFormat('d F Y');
        })
        ->editColumn('tanggal_kadaluarsa', function ($row) {
            return Carbon::parse($row->tanggal_kadaluarsa)->translatedFormat('d F Y');
        })
            ->addColumn('action', function ($row) {
                return '<a href="' . $row->dokumen_url . '" class="btn btn-primary btn-sm" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        <button class="btn btn-warning btn-sm download-btn" data-id="' . $row->id . '"><i class="fa fa-download" aria-hidden="true"></i></button>
                        <button class="btn btn-success btn-sm edit-btn" data-id="' . $row->id . '"><i class="fa fa-edit" aria-hidden="true"></i></button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '"><i class="fa fa-trash" aria-hidden="true"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
    public function getDokumenLegal($id)
    {
        $dokumen = PengajuanDokumenLegal::find($id);

        if (!$dokumen) {
            return response()->json(['success' => false, 'message' => 'Dokumen tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $dokumen]);
    }

    public function download_watermark($id)
    {
        $dokumen = PengajuanDokumenLegal::find($id);

        if (!$dokumen) {
            return response()->json(['success' => false, 'message' => 'Dokumen tidak ditemukan'], 404);
        }

        $pdfUrl = $dokumen->dokumen_url;
        $pdfPath = str_replace(asset('storage') . '/', '', $pdfUrl);
        $pdfPath = storage_path('app/public/' . $pdfPath);
        // Path watermark image dari public/image
        $originalWatermarkPath = public_path('assets/image/pngwing.com.png');

        // Pastikan file PDF dan watermark ada
        if (!file_exists($pdfPath) || !file_exists($originalWatermarkPath)) {
            return response()->json(['success' => false, 'message' => 'File PDF atau watermark tidak ditemukan'], 404);
        }


        // **Resize watermark sebelum digunakan**
        $resizedWatermarkPath = storage_path('app/public/resized_watermark.png');

        $watermark = Image::make($originalWatermarkPath)->resize(400, 400)->opacity(50); // Resize ke 100x100 px
        $watermark->save($resizedWatermarkPath);

        // Path output hasil watermark
        $outputPath = storage_path('app/public/watermark/watermarked_' . basename($pdfPath));

        // Tambahkan watermark ke PDF
        ImageWatermarker::input($pdfPath)
            ->watermark($resizedWatermarkPath)
            ->output($outputPath)
            ->position(Position::TOP_RIGHT, -3, 3)
            // ->asBackground()
            ->resolution(300) // 300 dpi
            ->save();

        // Kembalikan file yang sudah diberi watermark sebagai response download
        return response()->file($outputPath);

        // return response()->download($outputPath)->deleteFileAfterSend(true);
    }

    public function delete_dokumen_legal($id){
        $dokumen = PengajuanDokumenLegal::findOrFail($id);

        // Hapus file jika ada
        if ($dokumen->dokumen_url) {
            Storage::disk('public')->delete($dokumen->dokumen_url);
        }

        $dokumen->delete();

        return response()->json(['message' => 'Dokumen berhasil dihapus']);
    }

    public function updateDokumenLegal(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:pengajuan_dokumen_legal,id',
            'jenis_dokumen' => 'required',
            'kode_dokumen' => 'required',
            'nama_dokumen' => 'required',
            'tanggal_berlaku' => 'required|date',
            'tanggal_kadaluarsa' => 'required|date',
            'penanggung_jawab' => 'required',
            'revisi_ke' => 'required|numeric',
            'dokumen_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:2048',
        ]);

        $dokumen = PengajuanDokumenLegal::findOrFail($request->id);

        // Update data selain file
        $dokumen->update($request->except(['dokumen_file', 'dokumen_url']));

        // Jika ada file baru yang diupload
        if ($request->hasFile('dokumen_file')) {
            // Hapus file lama jika ada
            if ($dokumen->dokumen_url) {
                $oldFilePath = str_replace(asset('storage/'), '', $dokumen->dokumen_url);
                Storage::disk('public')->delete($oldFilePath);
            }

            // Simpan file baru
            $file = $request->file('dokumen_file');
            $filePath = $file->store('dokumen_legal', 'public');
            $fileUrl = asset('storage/' . $filePath);

            // Update dokumen dengan file baru
            $dokumen->update(['dokumen_url' => $fileUrl]);
        }

        return response()->json(['success' => true, 'message' => 'Dokumen berhasil diperbarui!', 'data' => $dokumen]);
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
            $status = $request->status;

            if ($status === "waiting_list") {
                $status = "pending";
            } elseif ($status === "approve_list") {
                $status = "approve";
            }
            $data_tmp = PengajuanBazzar::select(DB::raw('SUM(pengajuan_bazzar.jumlah) as jumlah') , DB::raw('COUNT(*) as jml_data'),'pengajuan_bazzar.created_at','pengajuan_bazzar.status', 'employee_atribut.nik', 'employee_atribut.employee_name','employee_atribut.sub_dept_name', 'employee_atribut.sub_dept_id','employee_atribut.department_name', 'employee_atribut.status_staff')->leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'pengajuan_bazzar.enroll_id')->where('status', $status)->groupby('employee_atribut.sub_dept_id')->get();
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
            ->orderBy('voucher_bazzar.enroll_id', 'ASC')
            ->get();
        }else{
            $data = VoucherBazzar::leftJoin('employee_atribut', 'employee_atribut.enroll_id', '=', 'voucher_bazzar.enroll_id')->where('sub_dept_id', request()->sub_dept_id)
            ->orderBy('voucher_bazzar.enroll_id', 'ASC')
            ->get();
        }
        $fileName='Voucher_Bazzar_'.$data[0]->employee->employee_name.' '.date('Y-m-d').' '.rand(10,1000000);
        $pdf = PDF::loadView('hris.mutasi-karyawan.bazzar.export-voucher-pdf',["data" => $data])->setPaper('F4', 'fotrait')->stream($fileName.'.pdf',array('Attachment'=>0));
        return $pdf;
    }

}
