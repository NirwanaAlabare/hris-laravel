<?php

namespace App\Http\Controllers\Hris\Entertaint;
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
use App\Models\EntertainPengajuanTamu;
use App\Models\EntertainPengajuanPendamping;
use App\Models\EntertainPengajuanKeterangan;
use App\Models\PengajuanDokumenLegal;
use App\Models\EntertainRealisasiPengajuan;
use App\Models\DepartmentAll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Yajra\DataTables\Facades\DataTables;
use DateTime;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Exports\ExportLineSheet;
use App\Exports\ExportPengajuanKas;
use App\Exports\ExportPengajuanBazzar;
use Illuminate\Support\Facades\Storage;
use FilippoToso\PdfWatermarker\Facades\ImageWatermarker;
use FilippoToso\PdfWatermarker\Support\Position;
use Intervention\Image\Facades\Image;
class EntertaintController extends AdminBaseController
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
        return view('hris/ga/entertaint/entertaint_tamu', [
            'page' => 'dashboard-mut-karyawan', "subPageGroup" => "proses-karyawan", "subPage" => "form-lembur-non-sewing",
            "data_dept" => $data_dept, "user" => $user,
            "selectemployee" => $selectemployee
        ], $this->data);
    }
    public function store(Request $request)
    {
        $request->validate([
            'enroll_id' => 'required|string|max:10',
            'department_id' => 'required|string|max:50',
            'sub_dept_id' => 'required|string|max:50',
            'tanggal_kedatangan_tamu' => 'required|date',
            'tamu_instansi' => 'required|string|max:100',
            'nama_tamu' => 'required|string|max:100',
            'jabatan_tamu' => 'required|string|max:50',
            'qty_tamu' => 'required|integer',
            'keperluan' => 'required|string|max:100',
            'pendamping_tamu' => 'array',
            'keterangan_list' => 'array',
        ]);

        $pengajuan = EntertainPengajuanTamu::create($request->only([
            'enroll_id', 'department_id', 'sub_dept_id', 'tanggal_kedatangan_tamu',
            'tamu_instansi', 'nama_tamu', 'jabatan_tamu', 'qty_tamu', 'keperluan'
        ]));

        if ($request->has('pendamping_tamu')) {
            foreach ($request->pendamping_tamu as $pendamping) {
                EntertainPengajuanPendamping::create([
                    'pengajuan_id' => $pengajuan->id,
                    'enroll_id' => $pendamping,
                ]);
            }
        }

        if ($request->has('keterangan_list')) {
            $totalJumlah = 0;
            foreach ($request->keterangan_list as $item) {

                $jumlah = (int) preg_replace('/[^0-9]/', '', $item['jumlah']);
                $totalJumlah += $jumlah;

                EntertainPengajuanKeterangan::create([
                    'pengajuan_id' => $pengajuan->id,
                    'keterangan' => $item['keterangan'],
                    'jumlah' => $jumlah,
                ]);
                $pengajuan->update([
                    'jumlah_pengajuan' => $totalJumlah,
                ]);
            }
        }

        return response()->json(['message' => 'Pengajuan berhasil disimpan'], 201);
    }
    public function realisasi(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required|string|max:10',
            'keterangan_list' => 'array',
        ]);

        $totalRealisasi = 0;

        if ($request->has('keterangan_list')) {
            foreach ($request->keterangan_list as $item) {
                $jumlah = preg_replace('/[^0-9]/', '', $item['jumlah']);
                $totalRealisasi += (int) $jumlah;

                EntertainRealisasiPengajuan::create([
                    'pengajuan_id' => $request->pengajuan_id,
                    'keterangan' => $item['keterangan'],
                    'jumlah' => $jumlah,
                ]);
            }
        }

        $pengajuan = EntertainPengajuanTamu::where('id', $request->pengajuan_id)->first();
        $pengajuan->update([
            'is_realisasi' => 1,
            'realisasi_by' => Auth::guard('admin')->user()->email,
            'jumlah_realisasi' => $totalRealisasi,
            'jumlah_sisa' => $pengajuan->jumlah_pengajuan - $totalRealisasi,
        ]);

        return response()->json(['message' => 'Realisasi berhasil disimpan'], 201);
    }
    public function getData()
    {
        $pengajuan = EntertainPengajuanTamu::with(['pendamping', 'keterangan','employee'])->get();

        return datatables()->of($pengajuan)
            ->addColumn('pendamping', function ($row) {
                return $row->pendamping->pluck('enroll_id')->implode(', ');
            })
            ->addColumn('enroll_id', function ($row) {
                $employee =  EmployeeAtribut::where('enroll_id', $row->enroll_id)->first();
                return  ''.$employee->enroll_id.' - '.$employee->employee_name;
            })
            ->addColumn('department_name', function ($row) {
                $department =  DepartmentAll::where('department_id', $row->department_id)->where('sub_dept_id', $row->sub_dept_id)->first();
                return  $department->department_name;
            })
            ->addColumn('bagian_name', function ($row) {
                $bagian =  DepartmentAll::where('department_id', $row->department_id)->where('sub_dept_id', $row->sub_dept_id)->first();
                return  $bagian->sub_dept_name;
            })
            ->addColumn('jumlah', function ($row) {
                return $row->jumlah_pengajuan;
            })
            ->addColumn('jumlah_realisasi', function ($row) {
                return $row->jumlah_realisasi;
            })
            ->addColumn('jumlah_sisa', function ($row) {
                return $row->jumlah_sisa;
            })
            ->addColumn('actions', function ($row) {
                return  '<div class="btn-group d-flex justify-content-center">
                            <button type="button" class="btn btn-primary rounded-circle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button class="dropdown-item" type="button" onclick="open_modal_realisasi_pengajuan('.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').')">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                    <span class="ml-2">Realisasi</span>
                                </button>
                                <button class="dropdown-item" type="button" onclick="export_pengajuan_permintaan_kas('.$row->id.')">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    <span class="ml-2">Export Pengajuan</span>
                                </button>
                                <button class="dropdown-item" type="button" onclick="export_realisasi_permintaan_kas('.$row->id.')">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    <span class="ml-2">Export Realisasi</span>
                                </button>
                            </div>
                        </div>';
            })
            ->rawColumns(['keterangan', 'actions'])
            ->make(true);
    }

    public function update(Request $request, $id)
    {
        $pengajuan = EntertainPengajuanTamu::findOrFail($id);
        $pengajuan->update($request->only([
            'enroll_id', 'department', 'bagian', 'tanggal_kedatangan_tamu',
            'tamu_instansi', 'nama_tamu', 'jabatan_tamu', 'qty_tamu', 'keperluan'
        ]));

        EntertainPengajuanPendamping::where('pengajuan_id', $id)->delete();
        if ($request->has('pendamping_tamu')) {
            foreach ($request->pendamping_tamu as $pendamping) {
                EntertainPengajuanPendamping::create([
                    'pengajuan_id' => $pengajuan->id,
                    'enroll_id' => $pendamping,
                ]);
            }
        }

        EntertainPengajuanKeterangan::where('pengajuan_id', $id)->delete();
        if ($request->has('keterangan_list')) {
            foreach ($request->keterangan_list as $item) {
                EntertainPengajuanKeterangan::create([
                    'pengajuan_id' => $pengajuan->id,
                    'keterangan' => $item['keterangan'],
                    'jumlah' => preg_replace('/[^0-9]/', '', $item['jumlah']),
                ]);
            }
        }

        return response()->json(['message' => 'Pengajuan berhasil diperbarui']);
    }

    public function ajax_getallemployeeatribut()
    {
        $query =  EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, department_name,department_id,sub_dept_name, sub_dept_id, status_aktif,
                                           concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                    ->groupby('enroll_id')
                                    ->orderby('employee_name', 'asc')
                                    ->get();
        return $query;
    }


    public function export_pengajuan_permintaan_kas(Request $request)
        {
            $pengajuan = EntertainPengajuanTamu::with(['pendamping', 'keterangan'])->where('id', $request->entertain_id)->first();
            $department = DepartmentAll::where('department_id', $pengajuan->department_id)->where('sub_dept_id', $pengajuan->sub_dept_id)->first();
            $employee =  EmployeeAtribut::where('enroll_id', $pengajuan->enroll_id)->first();
            $employee_manager =  EmployeeAtribut::where('department_id', $pengajuan->department_id)->where('sub_dept_id', $pengajuan->sub_dept_id)->where('status_jabatan', 'MANAGER')->first();
            $total_jumlah = $pengajuan->keterangan->sum('jumlah');
            return view('hris/ga/entertaint/export_pengajuan_permintaan_kas_pdf', compact('pengajuan', 'department', 'employee', 'total_jumlah','employee','employee_manager'));
        }
    public function export_realisasi_permintaan_kas(Request $request)
        {
            $pengajuan = EntertainPengajuanTamu::with(['pendamping', 'keterangan'])->where('id', $request->entertain_id)->first();
            $department = DepartmentAll::where('department_id', $pengajuan->department_id)->where('sub_dept_id', $pengajuan->sub_dept_id)->first();
            $employee =  EmployeeAtribut::where('enroll_id', $pengajuan->enroll_id)->first();
            $employee_manager =  EmployeeAtribut::where('department_id', $pengajuan->department_id)->where('sub_dept_id', $pengajuan->sub_dept_id)->where('status_jabatan', 'MANAGER')->first();
            $total_jumlah = $pengajuan->keterangan->sum('jumlah');
            return view('hris/ga/entertaint/export_realisasi_permintaan_kas', compact('pengajuan', 'department', 'employee', 'total_jumlah','employee','employee_manager'));
        }

}
