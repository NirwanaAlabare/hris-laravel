<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\DataKoreksiPotongan;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Datatables;
use App\Exports\MasterDataAbsenKehadiranExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Models\EmployeeAtribut;
use App\Models\RefAbsenIjin;
use App\Models\DataKoreksiUpah;
use App\Exports\KoreksiPotonganExport;


/**
 * Class KoreksiPotonganController
 * @package App\Http\Controllers\Hris
 */
class KoreksiPotonganController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Koreksi Potongan';
    }

    public function index()
    {
        $data_priode=DataKoreksiPotongan::groupBy('periode_tanggal_koreksi')->orderBy('tanggal_koreksi', 'desc')->get();
        $x=[];
        foreach ($data_priode as $key => $value) {
            $x[]=['periode'=>$value->periode_tanggal_koreksi];
        }
        $this->priode_potongan=$x;
        return View::make('hris/koreksipotongan', $this->data);
    }

    public function verifikasi_koreksi()
    {
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

        $refabsenijin = $this->ajax_getselectrefabsenijin();

        $selectemployee = $this->ajax_getallemployeeatribut();

        return view('hris/verifikasi_koreksi', [
            'page' => 'dashboard-mut-karyawan', "subPageGroup" => "proses-karyawan", "subPage" => "form-lembur-non-sewing",
            "data_dept" => $data_dept, "user" => $user,
            "selectemployee" => $selectemployee,
            "refabsenijin" => $refabsenijin,
        ], $this->data);
    }

       private function ajax_getselectrefabsenijin()
    {
        $query =  RefAbsenIjin::selectRaw('kode_absen_ijin,
                                           concat(kode_absen_ijin," - "
                                                  ,nama_absen_ijin) kode_nama_absen_ijin')
                                ->whereNotIn('kode_absen_ijin', ['CH', 'CBD', 'PP', 'CB', 'L', 'LN', 'LP'])
                                ->orderby('nama_absen_ijin', 'asc')
                                ->get();

        return $query;

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

    public function ajax_datakoreksipotongan(Request $request)
    {

        if(request()->ajax()) {

        $limit = $request->input('length');
        $start = $request->input('start');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  DataKoreksiPotongan::selectRaw('
                        data_koreksi_potongan.uuid,
                        data_koreksi_potongan.kode_koreksi_potongan,
                        data_koreksi_potongan.tanggal_koreksi,
                        employee_atribut.enroll_id,
                        employee_atribut.nik,
                        employee_atribut.employee_name,
                        department_all.sub_dept_name,
                        department_all.department_name,
                        data_koreksi_potongan.jumlah_rp_potongan,
                        data_koreksi_potongan.periode_tanggal_koreksi,
                        data_koreksi_potongan.jenis_potongan,
                        data_koreksi_potongan.operator,
                        data_koreksi_potongan.keterangan,
                        data_koreksi_potongan.created_at,
                        data_koreksi_potongan.updated_at
                    ')
                    ->leftJoin('employee_atribut','data_koreksi_potongan.enroll_id','=','employee_atribut.enroll_id')
                    ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
                    ->orderBy('data_koreksi_potongan.updated_at','desc')
                    ->offset($start)
                    ->limit($limit)
                    ->get();

            $totalData = DataKoreksiPotongan::count();
            $totalFiltered = $totalData;

        } else {
            $search = $request->input('search.value');

            $query =  DataKoreksiPotongan::selectRaw('
                        data_koreksi_potongan.uuid,
                        data_koreksi_potongan.kode_koreksi_potongan,
                        data_koreksi_potongan.tanggal_koreksi,
                        employee_atribut.enroll_id,
                        employee_atribut.nik,
                        employee_atribut.employee_name,
                        department_all.sub_dept_name,
                        department_all.department_name,
                        data_koreksi_potongan.jumlah_rp_potongan,
                        data_koreksi_potongan.periode_tanggal_koreksi,
                        data_koreksi_potongan.jenis_potongan,
                        data_koreksi_potongan.operator,
                        data_koreksi_potongan.keterangan,
                        data_koreksi_potongan.created_at,
                        data_koreksi_potongan.updated_at
                    ')
                    ->whereRaw('
                        employee_atribut.enroll_id LIKE "%' . $search . '%"
                        OR employee_atribut.nik LIKE "%' . $search . '%"
                        OR employee_atribut.employee_name LIKE "%' . $search . '%"
                        OR department_all.sub_dept_name LIKE "%' . $search . '%"
                        OR data_koreksi_potongan.keterangan LIKE "%' . $search . '%"
                    ')
                    ->leftJoin('employee_atribut','data_koreksi_potongan.enroll_id','=','employee_atribut.enroll_id')
                    ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
                    ->orderBy('data_koreksi_potongan.updated_at','desc')
                    ->offset($start)
                    ->limit($limit)
                    ->get();

            $totalData = DataKoreksiPotongan::whereRaw('
                            employee_atribut.enroll_id LIKE "%' . $search . '%"
                            OR employee_atribut.nik LIKE "%' . $search . '%"
                            OR employee_atribut.employee_name LIKE "%' . $search . '%"
                            OR department_all.sub_dept_name LIKE "%' . $search . '%"
                            OR data_koreksi_potongan.keterangan LIKE "%' . $search . '%"
                        ')
                        ->leftJoin('employee_atribut','data_koreksi_potongan.enroll_id','=','employee_atribut.enroll_id')
                        ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
                        ->count();
            $totalFiltered = $totalData;

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {

                $nestedData['uuid'] = $q->uuid;
                $nestedData['kode_koreksi_potongan'] = $q->kode_koreksi_potongan;
                $nestedData['tanggal_koreksi'] = $q->tanggal_koreksi;
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['nik'] = $q->nik;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['department_name'] = $q->department_name;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;
                $nestedData['jumlah_rp_potongan'] = $q->jumlah_rp_potongan;
                $nestedData['jumlah_rp_potongan_format'] = number_format($q->jumlah_rp_potongan);
                $nestedData['periode_tanggal_koreksi'] = $q->periode_tanggal_koreksi;

                setlocale(LC_ALL, 'id-ID', 'id_ID');
                $explodePeriode = explode(" - ", $q->periode_tanggal_koreksi);
                $periodeStartKoreksi = strtoupper(strftime("%d %b %Y", strtotime(substr($explodePeriode[0], 6, 4) . "-" . substr($explodePeriode[0], 0, 2) . "-" . substr($explodePeriode[0], 3, 2))));
                $periodeEndKoreksi = strtoupper(strftime("%d %b %Y", strtotime(substr($explodePeriode[1], 6, 4) . "-" . substr($explodePeriode[1], 0, 2) . "-" . substr($explodePeriode[1], 3, 2))));
                $periode_tanggal_koreksi_format = $periodeStartKoreksi . " - " . $periodeEndKoreksi;

                $nestedData['periode_tanggal_koreksi_format'] = $periode_tanggal_koreksi_format;

                $nestedData['jenis_potongan'] = $q->jenis_potongan;

                switch ($q->jenis_potongan) {
                    case 1:
                        $nama_potongan = "Potongan BPJS";
                        break;
                    case 2:
                        $nama_potongan = "Potongan Bazar";
                        break;
                    default:
                        $nama_potongan = "Potongan Lainnya";
                        break;
                }
                $nestedData['nama_potongan'] = $nama_potongan;
                $nestedData['operator'] = $q->operator;
                $nestedData['keterangan'] = $q->keterangan;
                $nestedData['created_at'] = substr($q->created_at, 0, 10) . " " . substr($q->created_at, 11, 5);
                $nestedData['updated_at'] = substr($q->updated_at, 0, 10) . " " . substr($q->updated_at, 11, 5);

                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
            );

        echo json_encode($json_data);
        }
    }


    function unverifikasi_koreksi(Request $request)
    {
        $uuid = $request->uuid;
        $sumber = $request->sumber;
        if($sumber == 'PENAMBAH UPAH') {
            $data = DataKoreksiUpah::where('uuid', $uuid)->update(['is_verifikasi_acc' => 0]);
        }else if($sumber == 'POTONGAN') {
            $data = DataKoreksiPotongan::where('uuid', $uuid)->update(['is_verifikasi_acc' => 0]);
        }
        return response()->json($data);
    }

  public function verifikasi_koreksi_data(Request $request)
    {
        $uuidList = $request->uuid_checked;

        if (!is_array($uuidList) || empty($uuidList)) {
            return response()->json(['message' => 'Tidak ada UUID yang dipilih'], 400);
        }

        // Update tabel potongan
        $potonganUpdated = DataKoreksiPotongan::whereIn('uuid', $uuidList)
            ->update(['is_verifikasi_acc' => 1]);

        // Update tabel upah
        $upahUpdated = DataKoreksiUpah::whereIn('uuid', $uuidList)
            ->update(['is_verifikasi_acc' => 1]);

        return response()->json([
            'message' => 'Data berhasil diverifikasi',
            'potongan_updated' => $potonganUpdated,
            'upah_updated' => $upahUpdated
        ]);
    }


    public function list_verifikasi_koreksi (Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([]);
        }
        $limit = $request->input('length');
        $start = $request->input('start');
        $search = $request->input('search.value');


        $data = [];

        $upahQuery = DataKoreksiUpah::selectRaw('
                data_koreksi_upah.uuid,
                data_koreksi_upah.kode_koreksi_upah AS kode_koreksi,
                data_koreksi_upah.tanggal_koreksi,
                data_koreksi_upah.is_verifikasi_acc,
                employee_atribut.enroll_id,
                employee_atribut.nik,
                employee_atribut.employee_name,
                department_all.sub_dept_name,
                department_all.department_name,
                data_koreksi_upah.jumlah_rp_potongan,
                data_koreksi_upah.periode_tanggal_koreksi,
                data_koreksi_upah.jenis_koreksi AS jenis,
                data_koreksi_upah.operator,
                data_koreksi_upah.keterangan,
                data_koreksi_upah.created_at,
                data_koreksi_upah.updated_at,
                "PENAMBAH UPAH" AS sumber
            ')
            ->leftJoin('employee_atribut','data_koreksi_upah.enroll_id','=','employee_atribut.enroll_id')
            ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
            ->where('data_koreksi_upah.jenis_koreksi', '!=', 2);

        // ====================
        // Query 2: Data Koreksi Potongan
        // ====================
        $potonganQuery = DataKoreksiPotongan::selectRaw('
                data_koreksi_potongan.uuid,
                data_koreksi_potongan.kode_koreksi_potongan AS kode_koreksi,
                data_koreksi_potongan.tanggal_koreksi,
                data_koreksi_potongan.is_verifikasi_acc,
                employee_atribut.enroll_id,
                employee_atribut.nik,
                employee_atribut.employee_name,
                department_all.sub_dept_name,
                department_all.department_name,
                data_koreksi_potongan.jumlah_rp_potongan,
                data_koreksi_potongan.periode_tanggal_koreksi,
                data_koreksi_potongan.jenis_potongan AS jenis,
                data_koreksi_potongan.operator,
                data_koreksi_potongan.keterangan,
                data_koreksi_potongan.created_at,
                data_koreksi_potongan.updated_at,
                "POTONGAN" AS sumber
            ')
            ->leftJoin('employee_atribut','data_koreksi_potongan.enroll_id','=','employee_atribut.enroll_id')
            ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id');

        // ====================
        // Filter (jika ada search)
        // ====================

        if (!empty($request->tanggal_range)) {
            $daterange = explode(" s/d ", $request->tanggal_range);
            $tanggal_awal = date('Y-m-d', strtotime($daterange[0]));
            $tanggal_akhir = date('Y-m-d', strtotime($daterange[1]));
            $upahQuery->whereBetween('data_koreksi_upah.tanggal_koreksi', [$tanggal_awal, $tanggal_akhir]);
            $potonganQuery->whereBetween('data_koreksi_potongan.tanggal_koreksi', [$tanggal_awal, $tanggal_akhir]);

        }

        if (!empty($search)) {
            $searchFilter = function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('employee_atribut.enroll_id', 'like', "%$search%")
                    ->orWhere('employee_atribut.nik', 'like', "%$search%")
                    ->orWhere('employee_atribut.employee_name', 'like', "%$search%")
                    ->orWhere('department_all.sub_dept_name', 'like', "%$search%")
                    ->orWhere('department_all.department_name', 'like', "%$search%")
                    ->orWhere('data_koreksi_upah.keterangan', 'like', "%$search%")
                    ->orWhere('data_koreksi_upah.kode_koreksi_upah', 'like', "%$search%");
                });
            };

            $upahQuery->where($searchFilter);
            $potonganQuery->where(function($q) use ($search) {
                $q->where('employee_atribut.enroll_id', 'like', "%$search%")
                ->orWhere('employee_atribut.nik', 'like', "%$search%")
                ->orWhere('employee_atribut.employee_name', 'like', "%$search%")
                ->orWhere('department_all.sub_dept_name', 'like', "%$search%")
                ->orWhere('department_all.department_name', 'like', "%$search%")
                ->orWhere('data_koreksi_potongan.keterangan', 'like', "%$search%")
                ->orWhere('data_koreksi_potongan.kode_koreksi_potongan', 'like', "%$search%");
            });
        }

         if ($request->is_verifikasi_acc == '0') {
            $upahQuery->where('data_koreksi_upah.is_verifikasi_acc', 0);
            $potonganQuery->where('data_koreksi_potongan.is_verifikasi_acc', 0);
        } elseif ($request->is_verifikasi_acc == '1') {
            $upahQuery->where('data_koreksi_upah.is_verifikasi_acc', 1);
            $potonganQuery->where('data_koreksi_potongan.is_verifikasi_acc', 1);
        }

        // ====================
        // Ambil Data
        // ====================
        $upahResults = $upahQuery->get();
        $potonganResults = $potonganQuery->get();
        $merged = $upahResults->concat($potonganResults)->values();

        // Sort by updated_at descending
        $sorted = $merged->sortByDesc('updated_at')->values();

        // Hitung total sebelum pagination
        $totalData = $sorted->count();

        // Ambil sesuai pagination
        $paginated = $sorted->slice($start)->take($limit);

        // Format data akhir
        $data = [];
        foreach ($paginated as $q) {
            $nested = [
                'uuid' => $q->uuid,
                'kode_koreksi' => $q->kode_koreksi,
                'tanggal_koreksi' => $q->tanggal_koreksi,
                'enroll_id' => $q->enroll_id,
                'nik' => $q->nik,
                'employee_name' => $q->employee_name,
                'department_name' => $q->department_name,
                'sub_dept_name' => $q->sub_dept_name,
                'jumlah_rp_potongan' => $q->jumlah_rp_potongan,
                'jumlah_rp_potongan_format' => number_format($q->jumlah_rp_potongan),
                'periode_tanggal_koreksi' => $q->periode_tanggal_koreksi,
                'operator' => $q->operator,
                'keterangan' => $q->keterangan,
                'created_at' => substr($q->created_at, 0, 10) . " " . substr($q->created_at, 11, 5),
                'updated_at' => substr($q->updated_at, 0, 10) . " " . substr($q->updated_at, 11, 5),
                'sumber' => $q->sumber,
            ];

            // Format periode
            $explodePeriode = explode(" - ", $q->periode_tanggal_koreksi);
            if (count($explodePeriode) == 2) {
                $periodeStart = strtoupper(date("d M Y", strtotime(str_replace('/', '-', $explodePeriode[0]))));
                $periodeEnd = strtoupper(date("d M Y", strtotime(str_replace('/', '-', $explodePeriode[1]))));
                $nested['periode_tanggal_koreksi_format'] = "$periodeStart - $periodeEnd";
            }

            // Label jenis
            if ($q->sumber === 'upah') {
                $nested['jenis_koreksi'] = $q->jenis;
            } else {
                $nested['jenis_potongan'] = $q->jenis;
                $nested['nama_potongan'] = match ($q->jenis) {
                    1 => 'Potongan BPJS',
                    2 => 'Potongan Bazar',
                    default => 'Potongan Lainnya'
                };
            }

            $data[] = $nested;
        }
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData,
            'data' => $data,
        ]);
    }


    public function create(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        session(['loggedAdmin' => $loggedAdmin]);
        $email = $loggedAdmin->email;
        $kode_koreksi_potongan = $request->kode_koreksi_potongan;

        $tanggal_koreksi = $request->tanggal_koreksi;
        $enroll_id = $request->enroll_id;
        $nik = $request->nik;
        $employee_name = $request->employee_name;
        $site_nirwana_id = $request->site_nirwana_id;
        $site_nirwana_name = $request->site_nirwana_name;
        $department_id = $request->department_id;
        $department_name = $request->department_name;
        $sub_dept_id = $request->sub_dept_id;
        $sub_dept_name = $request->sub_dept_name;
        $jumlah_rp_potongan = $request->jumlah_rp_potongan;
        $periode_tanggal_koreksi = $request->periode_tanggal_koreksi;
        $jenis_potongan = $request->jenis_potongan;
        $keterangan = $request->keterangan;

        $tanggal_awal = explode(' - ', $periode_tanggal_koreksi)[0];

        // Ubah ke format yang diinginkan
        $format_tanggal = \Carbon\Carbon::createFromFormat('m/d/Y', $tanggal_awal)->format('Y-m-d');

        // sebelumnya
        // $findDT = DataKoreksiPotongan::where('kode_koreksi_potongan','=', $kode_koreksi_potongan)->count();

        // if($findDT > 0) {
        //     $query = false;
        // }
        $findDT = DataKoreksiPotongan::where('enroll_id',$request->enroll_id)->where('periode_tanggal_koreksi',$request->periode_tanggal_koreksi)
        ->where('jenis_potongan',$request->jenis_potongan)->count();
        if($findDT > 0) {
            $query = false;
        } else {
            $query = DataKoreksiPotongan::create([
                'uuid' => Str::uuid(),
                'kode_koreksi_potongan' => $kode_koreksi_potongan,
                'tanggal_koreksi' => $tanggal_koreksi,
                'enroll_id' => $enroll_id,
                'jumlah_rp_potongan' => $jumlah_rp_potongan,
                'periode_tanggal_koreksi' => $periode_tanggal_koreksi,
                'jenis_potongan' => $jenis_potongan,
                'keterangan' => $keterangan,
                'operator' => $email,
                'is_verifikasi_acc' => 0,
            ]);
        }

        return $query;
    }

    public function update(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $email = $loggedAdmin->email;
        session(['loggedAdmin' => $loggedAdmin]);

        $uuid = $request->uuid;
        $kode_koreksi_potongan = $request->kode_koreksi_potongan;
        $tanggal_koreksi = $request->tanggal_koreksi;
        $enroll_id = $request->enroll_id;
        $nik = $request->nik;
        $employee_name = $request->employee_name;
        $site_nirwana_id = $request->site_nirwana_id;
        $site_nirwana_name = $request->site_nirwana_name;
        $department_id = $request->department_id;
        $department_name = $request->department_name;
        $sub_dept_id = $request->sub_dept_id;
        $sub_dept_name = $request->sub_dept_name;
        $jumlah_rp_potongan = $request->jumlah_rp_potongan;
        $periode_tanggal_koreksi = $request->periode_tanggal_koreksi;
        $jenis_potongan = $request->jenis_potongan;
        $keterangan = $request->keterangan;

        $query = DataKoreksiPotongan::where('uuid','=', $uuid)
                    ->update([
                        'kode_koreksi_potongan' => $kode_koreksi_potongan,
                        'tanggal_koreksi' => $tanggal_koreksi,
                        'enroll_id' => $enroll_id,
                        'jumlah_rp_potongan' => $jumlah_rp_potongan,
                        'periode_tanggal_koreksi' => $periode_tanggal_koreksi,
                        'jenis_potongan' => $jenis_potongan,
                        'keterangan' => $keterangan,
                        'operator' => $email
                    ]);

        return $query;
    }

    public function destroy(Request $request)
    {
        $uuid = $request->uuid;
        $loggedAdmin = Auth::guard('admin')->user();
        session(['loggedAdmin' => $loggedAdmin]);

        $findDT = DataKoreksiPotongan::where('uuid','=', $uuid)->count();

        if($findDT > 0) {
            $query = DataKoreksiPotongan::where('uuid','=',$uuid)->delete();
        } else {
            $query = false;
        }

        return $query;
    }

     //=============Andri====================
     public function format_import_koreksipotongan()
     {
         $filepath = public_path('format_import/format_import_koreksi_potongan.xlsx');
         return Response()->download($filepath);
     }

     public function import_koreksipotongan(Request $request)
     {
        try{
            $data=Excel::toArray([],$request->file('file_import'));
            $loggedAdmin = Auth::guard('admin')->user();
            $loggedAdmin->email;
            $data_import=[];
            $head=$data[0][0];


            $jenisPotonganMapping = [
                'POTONGAN BPJS TK' => 1,
                'POTONGAN BPJS KS' => 2,
                'POTONGAN BAZAR' => 3,
                'POTONGAN KAS BON' => 4,
                'POTONGAN LAINNYA' => 5,
                'POTONGAN KARYAWAN' => 6,
                'POTONGAN UPAH' => 7,
                'POTONGAN LEMBUR' => 8,
            ];

            if($head[0]=='enroll_id' && $head[3]=='jumlah_rp_potongan' ){
                foreach ($data[0] as $key2 => $row) {
                    if($key2>0){
                        $error=$key2;
                        $employee=EmployeeAtribut::where('enroll_id',$row[0])->first();
                        $nik=$employee->nik??null;

                        $tgl_awal =$row[4];
                        $tgl_awal = ($tgl_awal - 25569) * 86400;
                        $tgl_awal = 25569 + ($tgl_awal / 86400);
                        $tgl_awal = ($tgl_awal - 25569) * 86400;
                        $tgl_priode_awal=date('m/d/Y', $tgl_awal);

                        $tgl_akhir =$row[5];
                        $tgl_akhir = ($tgl_akhir - 25569) * 86400;
                        $tgl_akhir = 25569 + ($tgl_akhir / 86400);
                        $tgl_akhir = ($tgl_akhir - 25569) * 86400;
                        $tgl_priode_akhir=date('m/d/Y', $tgl_akhir);

                        $tgl_koreksi_awal =$row[8];
                        $tgl_koreksi_awal = ($tgl_koreksi_awal - 25569) * 86400;
                        $tgl_koreksi_awal = 25569 + ($tgl_koreksi_awal / 86400);
                        $tgl_koreksi_awal = ($tgl_koreksi_awal - 25569) * 86400;
                        $tgl_koreksi = date('Y-m-d', $tgl_koreksi_awal);

                        $jenis_potongan_string = strtoupper(trim($row[7]));

                        $data_import[]=[
                            'kode_koreksi_potongan'=>date('Y').date('m').date('i').date('s').$nik,
                            'tanggal_koreksi'=>$tgl_koreksi,
                            'enroll_id'=>$row[0],
                            'nik'=>$employee->nik??null,
                            'employee_name'=>$employee->employee_name??null,
                            'site_nirwana_id'=>$employee->site_nirwana_id??null,
                            'site_nirwana_name'=>$employee->site_nirwana_name??null,
                            'department_id'=>$employee->department_id??null,
                            'department_name'=>$employee->department_name??null,
                            'sub_dept_id'=>$employee->sub_dept_id??null,
                            'sub_dept_name'=>$employee->sub_dept_name??null,
                            'jumlah_rp_potongan'=>$row[3],
                            'periode_tanggal_koreksi'=>$tgl_priode_awal.' - '.$tgl_priode_akhir,
                            'keterangan'=>$row[6],
                            'operator'=> $loggedAdmin->email,
                            'jenis_potongan'=>$jenisPotonganMapping[$jenis_potongan_string] ?? null,
                        ];
                    }
                }
                $collect=collect($data_import)->where('nik','!=',null);

                foreach ($collect as $key => $value) {
                   $findDT = DataKoreksiPotongan::where('enroll_id',$value['enroll_id'])->where('periode_tanggal_koreksi',$value['periode_tanggal_koreksi'])
                        ->where('jenis_potongan',$value['jenis_potongan'])->count();
                    if($findDT){
                        $data_update=[
                            'kode_koreksi_potongan'=>$value['kode_koreksi_potongan'],
                            'tanggal_koreksi'=>$value['tanggal_koreksi'],
                            'enroll_id'=>$value['enroll_id'],
                            'jumlah_rp_potongan'=>$value['jumlah_rp_potongan'],
                            'periode_tanggal_koreksi'=>$value['periode_tanggal_koreksi'],
                            'keterangan'=>$value['keterangan'],
                            'operator'=>$value['operator'],
                            'jenis_potongan'=>$value['jenis_potongan'],

                        ];
                        DataKoreksiPotongan::where('enroll_id',$value['enroll_id'])->where('periode_tanggal_koreksi',$value['periode_tanggal_koreksi'])->where('jenis_potongan',$value['jenis_potongan'])->update($data_update);
                    }
                    else{
                        $data_insert=[
                            'uuid' => Str::uuid(),
                            'kode_koreksi_potongan'=>$value['kode_koreksi_potongan'],
                            'tanggal_koreksi'=>$value['tanggal_koreksi'],
                            'enroll_id'=>$value['enroll_id'],
                            'jumlah_rp_potongan'=>$value['jumlah_rp_potongan'],
                            'periode_tanggal_koreksi'=>$value['periode_tanggal_koreksi'],
                            'keterangan'=>$value['keterangan'],
                            'operator'=>$value['operator'],
                            'jenis_potongan'=>$value['jenis_potongan'],
                            'is_verifikasi_acc' => 0,

                        ];
                        DataKoreksiPotongan::create($data_insert);
                    }
                }
                return back()->with("success",'berhasil disimpan');
            }
            else{
                return back()->with("error",'gagal tersimpan format salah');
            }
        }catch(\Exception $e){
            $error=$error+1;
            return back()->with("error",'gagal tersimpan terdapat kesalah di row '.$error);
        }
     }
    public function export_koreksipotongan(Request $request)
    {
        // $DataKoreksiPotongan=DataKoreksiPotongan::where('periode_tanggal_koreksi',$request->priode_payroll)->get();
        $DataKoreksiPotongan =  DataKoreksiPotongan::selectRaw('
        data_koreksi_potongan.uuid,
        data_koreksi_potongan.kode_koreksi_potongan,
        data_koreksi_potongan.tanggal_koreksi,
        employee_atribut.enroll_id,
        employee_atribut.nik,
        employee_atribut.employee_name,
        department_all.sub_dept_name,
        department_all.department_name,
        data_koreksi_potongan.jumlah_rp_potongan,
        data_koreksi_potongan.periode_tanggal_koreksi,
        data_koreksi_potongan.jenis_potongan,
        data_koreksi_potongan.operator,
        data_koreksi_potongan.keterangan,
        data_koreksi_potongan.created_at,
        data_koreksi_potongan.updated_at
    ')
    ->leftJoin('employee_atribut','data_koreksi_potongan.enroll_id','=','employee_atribut.enroll_id')
    ->leftJoin('department_all','employee_atribut.sub_dept_id','=','department_all.sub_dept_id')
    ->where('periode_tanggal_koreksi',$request->priode_payroll)
    ->where('jenis_potongan',$request->jenis_potongan_export)
    ->orderBy('data_koreksi_potongan.updated_at','asc')
    ->get();
        return Excel::download(new KoreksiPotonganExport($DataKoreksiPotongan, $request->priode_payroll),'KoreksiUpah'.time().'.xlsx');
    }

}
