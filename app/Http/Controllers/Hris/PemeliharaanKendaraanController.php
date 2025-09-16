<?php

namespace App\Http\Controllers\Hris;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use App\Models\PemeliharaanKendaraan;
use App\Models\VehicleItem;
use App\Models\EmployeeAtribut;
use App\Models\KategoriItem;
use App\Models\VehicleMaintenance;
use App\Models\GaPengajuanPerbaikanKendaraan;
use App\Models\GaPengajuanPerbaikanImage;
use App\Models\GaPengajuanPerbaikanKendaraanDetail;
use App\Models\VehicleMaintenancePrice;
use App\Models\GaPemeriksaanKendaraan;
use App\Models\GaPemeriksaanKendaraanDet;
use App\Exports\PengajuanPerbaikanKendaraanExport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
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
    public function pengajuan_perbaikan_kendaraan(){
        $vehicles =  DB::connection('laravel_nds')->select( DB::raw("select*from ga_master_kendaraan") );
        $vehicle_item=DB::select("select*from vehicle_item order by created_at desc");
        $selectemployee = $this->ajax_getallemployeeatribut(['DEP08SUB002']);
        $komponent_pemerikasaan_kendaraan = DB::select("SELECT * FROM komponen_pemeriksaan_kendaraan_input");
        $user = auth()->user();
        return View::make('hris/ga/pengajuan_perbaikan_kendaraan',compact('vehicles','vehicle_item','user','selectemployee','komponent_pemerikasaan_kendaraan'), $this->data);
    }

    public function pemeriksaan_kendaraan(){
        $selectemployee = $this->ajax_getallemployeeatribut(['DEP08SUB002']);
        $vehicles =  DB::connection('laravel_nds')->select( DB::raw("select*from ga_master_kendaraan") );
        $komponent_pemerikasaan_kendaraan = DB::table('komponen_pemeriksaan_kendaraan')
            ->orderBy('sort', 'asc')
            ->get();
        $komponent_pemerikasaan_kendaraan_from_pemeriksaan = DB::select("SELECT * FROM ga_pemeriksaan_kendaraan_det  gpkd JOIN komponen_pemeriksaan_kendaraan_input kpki ON kpki.id = gpkd.komponen_id WHERE gpkd.`status` ='tidak_baik' GROUP BY komponen_id;");
        foreach ($komponent_pemerikasaan_kendaraan as $k) {
            $k->inputs = DB::table('komponen_pemeriksaan_kendaraan_input')
                ->where('id_komponen_pemeriksaan_kendaraan', $k->id)
                ->get();
        }
        $user = auth()->user();
        return View::make('hris/ga/pemeriksaan_kendaraan',compact('vehicles','komponent_pemerikasaan_kendaraan','user','selectemployee','komponent_pemerikasaan_kendaraan_from_pemeriksaan'), $this->data);
    }


    public function edit_pengajuan_perbaikan_kendaraan($id)
    {
         $data = GaPengajuanPerbaikanKendaraan::with(['details', 'images'])->findOrFail($id);
        return response()->json($data);
    }

    public function update_pengajuan_perbaikan_kendaraan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_pengajuan_perbaikan' => 'required|date',
            'diajukanOlehID'                   => 'required|integer',
            'vehicle_id'                  => 'required|integer',
            'jenis_pemeliharaan'          => 'required|array',
            'jenis_pemeliharaan.*'        => 'nullable|integer',
            'odometer'                    => 'required|array',
            'odometer.*'                  => 'nullable|numeric',
            'penyedia_jasa'               => 'required|array',
            'penyedia_jasa.*'             => 'nullable|string',
            'keterangan'                  => 'nullable|array',
            'keterangan.*'                => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            // update parent
            $pengajuan = GaPengajuanPerbaikanKendaraan::findOrFail($id);
            $pengajuan->update([
                'kendaraan_id'     => $request->vehicle_id,
                'enroll_id'        => $request->diajukanOlehID,
                'tanggal_pengajuan'=> $request->tanggal_pengajuan_perbaikan,
            ]);

            // hapus detail lama
            GaPengajuanPerbaikanKendaraanDetail::where('pengajuan_id', $id)->delete();

            // simpan detail baru
            foreach ($request->jenis_pemeliharaan as $i => $komponenId) {
                if ($komponenId) {
                    GaPengajuanPerbaikanKendaraanDetail::create([
                        'pengajuan_id'                            => $pengajuan->id,
                        'komponen_pemeriksaan_kendaraan_input_id' => $komponenId,
                        'odometer'                                => $request->odometer[$i] ?? null,
                        'penyedia_jasa'                           => $request->penyedia_jasa[$i] ?? null,
                        'keterangan'                              => $request->keterangan[$i] ?? null,
                    ]);
                }
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Pengajuan berhasil diupdate',
                'data'    => $pengajuan->load('details')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal update data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

   public function realisasi_pengajuan_perbaikan_kendaraan(Request $request, $id)
{
    $request->validate([
        'deletedImages' => 'array',
        'deletedImages.*' => 'integer'
    ]);
    try {
        $pengajuan = GaPengajuanPerbaikanKendaraan::findOrFail($id);
        $pengajuan->update([
            'tanggal_realisasi' => $request->tanggal_realisasi ? Carbon::createFromFormat('d-m-Y', $request->tanggal_realisasi)->format('Y-m-d') : null,
        ]);
        if ($request->has('odometer')) {
            foreach ($request->odometer as $index => $odo) {
                if (!empty($request->id_detail_realisasi[$index])) {
                    $pengajuan->details()
                        ->where('id', $request->id_detail_realisasi[$index])
                        ->update([
                            'odometer_realisasi' => $odo,
                        ]);
                }
            }
        }
        // pakai transaction biar aman
        \DB::transaction(function () use ($request, $pengajuan) {
            // hapus gambar lama yang dipilih
            if ($request->filled('deletedImages')) {
                $deletedIds = $request->deletedImages;
                $imagesToDelete = GaPengajuanPerbaikanImage::whereIn('id', $deletedIds)->get();

                foreach ($imagesToDelete as $img) {
                    if (\Storage::disk('public')->exists($img->path)) {
                        \Storage::disk('public')->delete($img->path);
                    }
                    $img->delete();
                }
            }

            // upload gambar baru
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploads/perbaikan_kendaraan', $filename, 'public');

                    GaPengajuanPerbaikanImage::create([
                        'pengajuan_id' => $pengajuan->id,
                        'path' => $path
                    ]);
                }
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Realisasi berhasil diperbarui',
            'data'    => $pengajuan->load('images')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal update data',
            'error'   => $e->getMessage()
        ], 500);
    }
}




    public function approve_pengajuan($id)
    {
        $pengajuan = GaPengajuanPerbaikanKendaraan::findOrFail($id);
        $loggedAdmin = Auth::guard('admin')->user();
        $enroll_id = $loggedAdmin->enroll_id;
        $pengajuan->update([
            'status_pengajuan' => 'approved',
            'approved_at' => now(),
            'approved_by' => $enroll_id ?? null,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function create_pengajuan_perbaikan_kendaraan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_pengajuan_perbaikan' => 'required|date',
            'diajukanOlehID' => 'required|integer',
            'vehicle_id' => 'required|integer',

            'jenis_pemeliharaan' => 'required|array',
            'jenis_pemeliharaan.*' => 'required|integer',

            'odometer' => 'nullable|array',
            'odometer.*' => 'nullable|numeric',

            'penyedia_jasa' => 'required|array',
            'penyedia_jasa.*' => 'required|string',

            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        // dd($request->odometer);
        try {
            DB::beginTransaction();

            // simpan induk
            $pengajuan = GaPengajuanPerbaikanKendaraan::create([
                'id_pemerliharaan' => $request->id_pemerliharaan ? $request->id_pemerliharaan : null,
                'kendaraan_id'     => $request->vehicle_id,
                'enroll_id'        => $request->diajukanOlehID,
                'tanggal_pengajuan'=> $request->tanggal_pengajuan_perbaikan,
                'status_pengajuan' => 'pending',
            ]);

            // simpan detail
            foreach ($request->jenis_pemeliharaan as $i => $komponen) {
                GaPengajuanPerbaikanKendaraanDetail::create([
                    'pengajuan_id'                             => $pengajuan->id,
                    'komponen_pemeriksaan_kendaraan_input_id'  => $komponen,
                    'odometer'                                 => $request->odometer[$i] ?? null,
                    'penyedia_jasa'                            => $request->penyedia_jasa[$i],
                    'keterangan'                               => $request->keterangan[$i] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan perbaikan berhasil disimpan',
                'data' => $pengajuan->load('details')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $pengajuan = GaPengajuanPerbaikanKendaraan::findOrFail($id);

        // Storage::disk('public')->delete($data->foto_path);

        GaPengajuanPerbaikanKendaraanDetail::where('pengajuan_id', $pengajuan->id)->delete();

        // Kalau ada gambar terkait bisa ikut dihapus juga
        if ($pengajuan->images && is_array($pengajuan->images)) {
            foreach ($pengajuan->images as $img) {
                Storage::disk('public')->delete($img['path']);
            }
        }
        // Hapus induk
        $pengajuan->delete();

        return response()->json(['success' => true]);
    }


    public function ajax_get_pengajuan_perbaikan_kendaraan_list(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('ga_pengajuan_perbaikan_kendaraan as pk')
                ->leftJoin('ga_master_kendaraan as k', 'pk.kendaraan_id', '=', 'k.id')
                ->leftJoin('employee_atribut as e', 'pk.enroll_id', '=', 'e.enroll_id')
                ->select(
                    'pk.id',
                    'pk.tanggal_pengajuan',
                    'pk.tanggal_realisasi',
                    'k.tipe as merk',
                    'k.plat_no',
                    'e.employee_name',
                    'e.nik as nip',
                    'pk.status_pengajuan',
                    DB::raw('(SELECT COUNT(*) FROM ga_pengajuan_perbaikan_images
                          WHERE pengajuan_id = pk.id) as total_images')
                )
                ->orderBy('pk.created_at', 'desc');

            if ($request->has('status_pengajuan') && $request->status_pengajuan != '') {
                $data->where('pk.status_pengajuan', $request->status_pengajuan);
            }

            // 🔹 Filter tanggal_range (format: "dd-mm-yyyy - dd-mm-yyyy")
            if ($request->has('tanggal_range') && !empty($request->tanggal_range)) {
                $dates = explode(" s/d ", $request->tanggal_range);
                 // pastikan ada dua tanggal yang valid
                if (count($dates) == 2) {
                    try {
                        $data->whereBetween('pk.tanggal_pengajuan', [$dates[0], $dates[1]]);
                    } catch (\Exception $e) {
                        // kalau parsing gagal, abaikan filter tanggal
                    }
                }
            }

             return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('tanggal_pengajuan', function ($row) {
                return \Carbon\Carbon::parse($row->tanggal_pengajuan)
                    ->translatedFormat('d F Y');
            })
            ->addColumn('merk_kendaraan', fn($row) => $row->merk ?? '-')
            ->addColumn('nomor_polisi', fn($row) => $row->plat_no ?? '-')
            ->addColumn('driver', fn($row) => $row->employee_name ?? '-')
            ->addColumn('nip', fn($row) => $row->nip ?? '-')
            ->addColumn('status_pengajuan', function ($row) {
                switch ($row->status_pengajuan) {
                    case 'pending':
                        return '<span class="badge bg-warning">Pending</span>';
                    case 'approved':
                        return '<span class="badge bg-success">Approved</span>';
                    case 'rejected':
                        return '<span class="badge bg-danger">Rejected</span>';
                    default:
                        return '<span class="badge bg-secondary">Unknown</span>';
                }
            })
            ->rawColumns(['status_pengajuan'])
            ->make(true);
        }
    }

    public function store_pemeriksaan_kendaraan(Request $request){
        DB::beginTransaction();
        try {
            $tanggalPemeriksaan = null;
            if ($request->filled('tanggal_pemeriksaan')) {
                $tanggalPemeriksaan = Carbon::createFromFormat('d-m-Y', $request->tanggal_pemeriksaan)
                                        ->format('Y-m-d');
            }

            // 1. Insert ke header
            $header = GaPemeriksaanKendaraan::create([
                'kendaraan_id'        => $request->kendaraan_id,
                'enroll_id'           => $request->enroll_id,
                'oddometer'           => $request->oddometer,
                'tanggal_pemeriksaan' => $tanggalPemeriksaan,
                'created_by'          => auth()->id() ?? null,
            ]);

            // 2. Insert ke detail
            if ($request->has('pemeriksaan')) {
                foreach ($request->pemeriksaan as $komponenId => $status) {
                    $catatan = $request->catatan[$komponenId] ?? null;

                    $fotoPaths = [];
                    if ($request->hasFile("foto.$komponenId")) {
                        foreach ($request->file("foto.$komponenId") as $file) {
                            // $path = $file->store('pemeriksaan_kendaraan', 'public');
                            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                            $path = $file->storeAs('pemeriksaan_kendaraan', $filename, 'public');

                            $fotoPaths[] = [
                                'path' => $path,
                                'original' => $file->getClientOriginalName(),
                            ];
                        }
                    }

                    GaPemeriksaanKendaraanDet::create([
                        'pemeriksaan_kendaraan_id' => $header->id,
                        'komponen_id' => $komponenId,
                        'status'      => $status,
                        'catatan'     => $catatan,
                        'foto_path'   => $fotoPaths ? $fotoPaths[0]['path'] : null,
                        'original_name'   => $fotoPaths ? $fotoPaths[0]['original'] : null,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Data berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'error'   => $e->getMessage()
            ], 500);
        }

    }

    public function export_pengajuan_perbaikan_kendaraan(Request $request)
    {
         $data = GaPengajuanPerbaikanKendaraan::with(['details.detail_input_list', 'images'])
            ->leftJoin('ga_master_kendaraan as k', 'ga_pengajuan_perbaikan_kendaraan.kendaraan_id', '=', 'k.id')
            ->leftJoin('employee_atribut as e', 'ga_pengajuan_perbaikan_kendaraan.enroll_id', '=', 'e.enroll_id')
            ->select(
                'ga_pengajuan_perbaikan_kendaraan.id',
                'ga_pengajuan_perbaikan_kendaraan.tanggal_pengajuan',
                'ga_pengajuan_perbaikan_kendaraan.tanggal_realisasi',
                'k.tipe as merk',
                'k.plat_no',
                'e.employee_name',
                'e.nik as nip',
                'ga_pengajuan_perbaikan_kendaraan.status_pengajuan',
                DB::raw('(SELECT COUNT(*) FROM ga_pengajuan_perbaikan_images
                        WHERE pengajuan_id = ga_pengajuan_perbaikan_kendaraan.id) as total_images')
            )
            ->orderBy('ga_pengajuan_perbaikan_kendaraan.created_at', 'desc');


            if ($request->has('status_pengajuan') && $request->status_pengajuan != '') {
                $data->where('ga_pengajuan_perbaikan_kendaraan.status_pengajuan', $request->status_pengajuan);
            }
            // 🔹 Filter tanggal_range (format: "dd-mm-yyyy - dd-mm-yyyy")
            if ($request->has('tanggal_range') && !empty($request->tanggal_range)) {
                $dates = explode(" s/d ", $request->tanggal_range);
                if (count($dates) == 2) {
                    try {
                        $data->whereBetween('ga_pengajuan_perbaikan_kendaraan.tanggal_pengajuan', [$dates[0], $dates[1]]);
                    } catch (\Exception $e) {
                    }
                }
            }
        $data = $data->get();
        // dd($data->toArray());
        return Excel::download(new PengajuanPerbaikanKendaraanExport($data), 'pengajuan_perbaikan_kendaraan.xlsx');
    }

    public function ajax_get_pemeriksaan_kendaraan_list(Request $request)
    {
        if ($request->ajax()) {
            $tanggal = $request->tanggal;

            if ($tanggal) {
                try {
                    // coba format d-m-Y (misal 02-09-2025)
                    $parsed = Carbon::createFromFormat('d-m-Y', $tanggal);
                } catch (\Exception $e) {
                    try {
                        // fallback format Y-m-d (misal 2025-09-02)
                        $parsed = Carbon::createFromFormat('Y-m-d', $tanggal);
                    } catch (\Exception $e) {
                        // fallback terakhir → pakai hari ini
                        $parsed = Carbon::today();
                    }
                }
            } else {
                $parsed = Carbon::today();
            }
            $tanggal = $parsed->format('Y-m-d');
            $data = DB::table('ga_pemeriksaan_kendaraan as pk')
                ->leftjoin('ga_pemeriksaan_kendaraan_det as pkd', 'pk.id', '=', 'pkd.pemeriksaan_kendaraan_id')
                ->leftJoin('ga_master_kendaraan as k', 'pk.kendaraan_id', '=', 'k.id')
                ->leftJoin('ga_pengajuan_perbaikan_kendaraan as gppk', 'pk.id', '=', 'gppk.id_pemerliharaan')
                ->leftJoin('employee_atribut as e', 'pk.enroll_id', '=', 'e.enroll_id')
                ->select(
                    'pk.id',
                    'pk.oddometer',
                    'pk.tanggal_pemeriksaan',
                    'k.tipe',
                    'e.employee_name',
                    'gppk.id as pengajuan_id',
                    DB::raw("SUM(CASE WHEN pkd.status = 'tidak_baik' THEN 1 ELSE 0 END) as jumlah_tidak_baik")
                )
                ->whereDate('pk.tanggal_pemeriksaan', $tanggal)
                ->groupBy('pk.id', 'pk.tanggal_pemeriksaan', 'k.tipe', 'e.employee_name');
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('tanggal_pemeriksaan', function ($row) {
                    return \Carbon\Carbon::parse($row->tanggal_pemeriksaan)->format('d-m-Y');
                })
                ->addColumn('kendaraan', function ($row) {
                    return $row->tipe ?? '-';
                })
                ->addColumn('diajukan_oleh', function ($row) {
                    return $row->employee_name ?? '-';
                })
                ->addColumn('oddometer', function ($row) {
                    return $row->oddometer ?? '-';
                })
                ->addColumn('jumlah_tidak_baik', function ($row) {
                    return $row->jumlah_tidak_baik;
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '
                        <button class="btn btn-sm btn-primary btn-detail mr-2" data-id="'.$row->id.'">Detail</button>
                        <button class="btn btn-sm btn-warning btn-edit mr-2" data-id="'.$row->id.'">Edit</button>
                    ';

                    if ($row->jumlah_tidak_baik != 0 && !$row->pengajuan_id) {
                        $btn .= '<button class="btn btn-sm btn-danger btn-ajukan-perbaikan" data-id="'.$row->id.'">Ajukan Perbaikan</button>';
                    }

                    if ($row->pengajuan_id) {
                        $btn .= '<button class="btn btn-sm btn-info btn-detail-pengajuan" data-id="'.$row->pengajuan_id.'"><i class="fa fa-file-pdf-o"></i> Perbaikan Diajukan</button>';
                    }

                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function ajax_edit_pemeriksaan_kendaraan($id)
    {
        // ambil data utama
        $data = GaPemeriksaanKendaraan::with(['detail.detail_input_list','kendaraan','employee'])->findOrFail($id);

        // kembalikan response JSON agar bisa isi form di modal
        return response()->json($data);
    }

    public function ajax_update_pemeriksaan_kendaraan(Request $request, $id)
    {
        try {
            // Format tanggal
            $tanggalPemeriksaan = null;
            if ($request->filled('tanggal_pemeriksaan')) {
                $tanggalPemeriksaan = Carbon::createFromFormat('Y-m-d', $request->tanggal_pemeriksaan)
                                        ->format('Y-m-d');
            }

            // 1. Update header
            $header = GaPemeriksaanKendaraan::findOrFail($id);
            $header->update([
                'kendaraan_id'        => $request->kendaraan_id,
                'enroll_id'           => $request->enroll_id,
                'oddometer'           => $request->oddometer,
                'tanggal_pemeriksaan' => $tanggalPemeriksaan,
                'updated_by'          => auth()->id() ?? null,
            ]);

            // 2. Hapus detail lama
            GaPemeriksaanKendaraanDet::where('pemeriksaan_kendaraan_id', $header->id)->delete();

            // 3. Insert detail baru
       if ($request->has('pemeriksaan')) {
            foreach ($request->pemeriksaan as $komponenId => $status) {
                $catatan = $request->catatan[$komponenId] ?? null; // ambil sesuai ID
                $fotoPaths = [];
                \Log::info('Loop data', [
                    'komponenId' => $komponenId,
                    'status'     => $status,
                    'catatan'    => $catatan,
                ]);
                if ($request->hasFile("foto.$komponenId")) {
                    foreach ($request->file("foto.$komponenId") as $file) {
                        $filename = uniqid().'.'.$file->getClientOriginalExtension();
                        $path = $file->storeAs('pemeriksaan_kendaraan', $filename, 'public');
                        $fotoPaths[] = [
                            'path'     => $path,
                            'original' => $file->getClientOriginalName(),
                        ];
                    }
                }

                GaPemeriksaanKendaraanDet::updateOrCreate(
                    [
                        'pemeriksaan_kendaraan_id' => $header->id,
                        'komponen_id' => $komponenId, // selalu sesuai key
                    ],
                    [
                        'status'        => $status,
                        'catatan'       => $catatan,
                        'foto_path'     => $fotoPaths ? $fotoPaths[0]['path'] : null,
                        'original_name' => $fotoPaths ? $fotoPaths[0]['original'] : null,
                    ]
                );
            }
        }



            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data pemeriksaan berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal update data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function print_pengajuan_perbaikan_kendaraan(){
        $id=request()->id;
        $data = GaPengajuanPerbaikanKendaraan::with('details.detail_input_list','employee','pemeliharaan')
        ->where('id', $id)
        ->firstOrFail();
        $kendaraan = DB::connection('laravel_nds')
        ->table('ga_master_kendaraan')
        ->where('id', $data->kendaraan_id)
        ->first();
        $fileName='Form Pengajuan Pemeliharaan Kendaraan '.date('His');
        $pdf = PDF::loadView('hris.laporan.print_pemeliharaan_kendaraan',["data"=>$data,"kendaraan"=>$kendaraan])->setPaper('A4', 'fotrait')->stream($fileName.'.pdf');
        return $pdf;
    }

    public function ajax_get_pemeriksaan_kendaraan_detail(Request $request)
    {
        if ($request->ajax()) {
        $data = DB::table('ga_pemeriksaan_kendaraan as pk')
            ->join('ga_pemeriksaan_kendaraan_det as pkd', 'pk.id', '=', 'pkd.pemeriksaan_kendaraan_id')
            ->join('komponen_pemeriksaan_kendaraan_input as kpk', 'pkd.komponen_id', '=', 'kpk.id')
            ->leftJoin('ga_master_kendaraan as k', 'pk.kendaraan_id', '=', 'k.id')
            ->leftJoin('employee_atribut as e', 'pk.enroll_id', '=', 'e.enroll_id')
            ->select(
                'pk.id',
                'pk.tanggal_pemeriksaan',
                'k.tipe',
                'e.employee_name',
                'kpk.nama_item_pemeriksaan_detail as nama_komponen',
                'pkd.status',
                'pkd.catatan',
                'pkd.foto_path'
            )
            ->where('pk.id', $request->id)
            ->get();
        // dd($data->toArray());
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('tanggal_pemeriksaan', function ($row) {
                return \Carbon\Carbon::parse($row->tanggal_pemeriksaan)->format('d-m-Y');
            })
            ->addColumn('kendaraan', function ($row) {
                return $row->tipe ?? '-'; // FIX
            })
            ->addColumn('diajukan_oleh', function ($row) {
                return $row->employee_name ?? '-';
            })
            ->addColumn('komponen', function ($row) {
                return $row->nama_komponen ?? '-';
            })
            ->addColumn('status', function ($row) {
                return $row->employee_name ? ($row->status == 'baik'
                    ? '<span class="badge bg-success">Baik</span>'
                    : '<span class="badge bg-danger">Tidak Baik</span>') :
                    '-';
            })
            ->addColumn('catatan', function ($row) {
                return $row->catatan ?? '-';
            })
            ->addColumn('foto_path', function ($row) {
                return $row->foto_path
                    ? '<a href="'.asset('storage/'.$row->foto_path).'" target="_blank">Lihat Foto</a>'
                    : '-';
            })
            ->rawColumns(['status','foto_path'])
            ->make(true);
        }
    }

    public function ajax_getallemployeeatribut($bagian)
    {
        if(empty($bagian)){
            $query = EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, department_name,department_id,sub_dept_name, sub_dept_id, status_aktif,
                                           concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                    ->where('status_aktif', 'AKTIF')
                                    ->groupby('enroll_id')
                                    ->orderby('employee_name', 'asc')
                                    ->get();
        }else{
            $query = EmployeeAtribut::selectRaw('enroll_id, nik, employee_name, department_name,department_id,sub_dept_name, sub_dept_id, status_aktif,
                                           concat(enroll_id, " - ", nik, " - ", employee_name) select_employee')
                                    ->where('status_aktif', 'AKTIF')
                                    ->whereIn('sub_dept_id', $bagian)
                                    ->groupby('enroll_id')
                                    ->orderby('employee_name', 'asc')
                                    ->get();
        }
        return $query;
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
