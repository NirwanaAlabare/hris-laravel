<?php

namespace App\Http\Controllers\Hris;

use App\Http\Controllers\AdminBaseController;
use App\Models\MasterDataAbsenKehadiran;
use App\Models\EmployeeAtribut;
use App\Models\DepartmentAll;
use App\Models\Admin;
use App\Models\WorkTimeTable;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Str;
use App\Exports\EmployeeAtrExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\EmployeeAtributHistory;
use App\Services\employee\Kehadiran;
use App\Exports\FormatImportBPJSExport;
use App\Imports\EmployeeImport;
use App\Exports\newEmployeeExport;
use Spipu\Html2Pdf\Html2Pdf;
use App\Models\DataKehadiranInOutEdited;
use App\Models\LogDataGagalAbsen;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\Storage;
/**
 * Class MdAbsenHadirController
 * @package App\Http\Controllers\Hris
 */
class EmployeeAtrController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->dashboardActive = 'active';
        $this->pageTitle = 'Master Data Karyawan';
    }

    public function index()
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $this->loggedAdmin = $loggedAdmin;
        $this->divisi = $this->ajax_getselectdivisi();
        $department=DepartmentAll::select('department_name')->distinct()->orderBy('department_id')->groupBy('department_id')->get();
        $jabatan=EmployeeAtribut::orderBy('created_at')->groupBy('status_jabatan')->pluck('status_jabatan');

        return View::make('hris/employeeatr', $this->data,compact('department','jabatan'));
    }
    public function export_pdf_id_card(){
        $print_by=request()->print_by;
        $employee=EmployeeAtribut::where('enroll_id',request()->enroll_id)->where(function ($query){
            $query->where('status_aktif','AKTIF')
            ->orWhere(function($queryes){
                $queryes->where('status_aktif','TIDAK AKTIF');
            });
        })->get();
        $pdf = PDF::loadView('hris.Laporan.id_card',["employee" => $employee,"print_by"=>$print_by])->stream('Id card karyawan'.'.pdf',array('Attachment'=>0));
        return $pdf;
    }
    public function export_pdf_id_card_department(){
        $inDepartment='';
        $print_by=request()->print_by;
        if(request()->department){
            $inDepartment=' AND department_name = "'.request()->department.'"';
        }
        $inSubDepartment='';
        if(request()->sub_department){
            $inSubDepartment=' AND sub_dept_id = "'.request()->sub_department.'"';
        }
        $employee=EmployeeAtribut::whereRaw('status_aktif!=""'.$inDepartment.''.$inSubDepartment.'')->where(function ($query){
            $query->where('status_aktif','AKTIF')
            ->orWhere(function($queryes){
                $queryes->where('status_aktif','TIDAK AKTIF');
            });
        })->get();
        $pdf = PDF::loadView('hris.Laporan.id_card_department',["employee" => $employee,"print_by"=>$print_by])->setPaper('letter', 'landscape')->stream('Id card karyawan department'.'.pdf',array('Attachment'=>0));
        return $pdf;
    }

    public function export_pdf_id_card_employee(){
        $print_by=request()->print_by;
        $employees=explode(",",request()->employee);
        $employee=EmployeeAtribut::whereIn('enroll_id',$employees)->get();
        $pdf = PDF::loadView('hris.Laporan.id_card_department', ["employee" => $employee,  "print_by"=>$print_by])
        ->setPaper('letter', 'landscape')
        ->setOptions([
            'margin-top' => 0,
            'margin-bottom' => 0,
            'margin-left' => 0,
            'margin-right' => 0,
        ])
        ->stream('Id card karyawan department.pdf', ['Attachment' => 0]);

    return $pdf;

    }

    public function select_employee(){
        $inDepartment='';
        if(request()->department){
            $inDepartment=' AND department_name = "'.request()->department.'"';
        }
        $inSubDepartment='';
        if(request()->sub_department){
            $inSubDepartment=' AND sub_dept_id = "'.request()->sub_department.'"';
        }
        $employee=EmployeeAtribut::whereRaw('status_aktif!=""'.$inDepartment.''.$inSubDepartment.'')->where(function ($query){
            $query->where('status_aktif','AKTIF')
            ->orWhere(function($queryes){
                $queryes->where('status_aktif','TIDAK AKTIF');
            });
        })->get();
        return $employee;
    }
    public function get_employee(){
        $employee=EmployeeAtribut::where('enroll_id',request()->id)->get();
        return $employee;
    }
    public function store_photo(){
        $image = request()->file('photo');
        $employee=EmployeeAtribut::where('enroll_id',request()->enroll_id)->get();
        $enroll_id='';
        $employee_name='';
        foreach($employee as $value){
            $nik=$value->nik;
            $employee_name=$value->employee_name;
        }
        // $new_name = $nik.'_'.$employee_name.'_profile_photo_'.date('y-m-d').'_'.rand(). '.' . $image->getClientOriginalExtension();
        $new_name = $nik.'.' . $image->getClientOriginalExtension();
        $image->move(public_path('storage/app/public/images'), $new_name);
        EmployeeAtribut::where('enroll_id',request()->enroll_id)->update([
            'lokasi_foto'=>$new_name
        ]);
        return request()->enroll_id;
    }

  public function change_photo_profile(Request $request)
{
    $request->validate([
        'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    $admin = Auth::guard('admin')->user();

    $image = $request->file('photo');

    $filename = $admin->nik . '_' . Str::random(5) . '.' . $image->getClientOriginalExtension();

    // Simpan file ke storage/app/public/images/profile
    $path = $image->storeAs('public/images/profile', $filename); // path internal, bukan URL

    // Simpan nama file ke database (opsional)
    $admin->path_foto = $filename;
    $admin->save();

    // Buat full URL yang bisa diakses di browser
    $publicUrl = asset(Storage::url($path)); // menghasilkan /storage/images/profile/namafile.jpg

    return response()->json([
        'message' => 'Upload berhasil',
        'photo_url' => $publicUrl
    ]);
}

    private function ajax_getselectdivisi()
    {
        $query =  DepartmentAll::selectRaw('site_nirwana_id, site_nirwana_name')
                                 ->groupby('site_nirwana_name')
                                 ->orderby('site_nirwana_name', 'asc')
                                 ->get();

        return $query;

    }

    public function ajax_getselectdept(Request $request)
    {
        $site_nirwana_id = $request->site_nirwana_id;

        $query =  DepartmentAll::selectRaw('department_id, department_name')
                                 ->whereRaw('site_nirwana_id = "' . $site_nirwana_id . '"')
                                 ->where('status','AKTIF')
                                 ->groupby('department_name')
                                 ->orderby('department_name', 'asc')
                                 ->get();

        return $query;

    }

    public function ajax_getselectsubdept(Request $request)
    {
        $site_nirwana_id = $request->site_nirwana_id;
        $department_id = $request->department_id;

        $query =  DepartmentAll::selectRaw('sub_dept_id, sub_dept_name')
                                 ->whereRaw('site_nirwana_id = "' . $site_nirwana_id . '" and department_id = "' . $department_id . '"')
                                 ->groupby('sub_dept_name')
                                 ->orderby('sub_dept_name', 'asc')
                                 ->get();

        return $query;

    }

    public function ajax_periksaenroll_id(Request $request)
    {
        $enroll_id = $request->enroll_id;

        $query =  EmployeeAtribut::where('enroll_id', '=', $enroll_id)
                                 ->count();

        if($query > 0) {
            return false;
        } else {
            return true;
        }

    }

    public function ajax_periksanik(Request $request)
    {
        $enroll_id=request()->enroll_id;
        $nik=strtoupper($request->nik);
        $site_nirwana_id = preg_replace('/[^A-Z]/', '', substr($nik,0,3));
        $enroll_id_nik=substr($nik,-4);
        $site_nirwana_array = DepartmentAll::groupBy('site_nirwana_id')->pluck('site_nirwana_id')->toArray();
        if (!in_array($site_nirwana_id, $site_nirwana_array) && $enroll_id!=$enroll_id_nik) {
            return false;
        }else if (in_array($site_nirwana_id, $site_nirwana_array) && $enroll_id!=$enroll_id_nik) {
            return false;
        }else if (!in_array($site_nirwana_id, $site_nirwana_array) && $enroll_id==$enroll_id_nik) {
            if($site_nirwana_id='SGT'){
                return true;
            }else{
                return false;
            }
        }else{
            $query =  EmployeeAtribut::where('nik', '=', $nik)->count();
            if($query > 0) {
                return false;
            } else {
                return true;
            }
        }

    }


    public function ajax_getemployeeatr(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => '',
            1 => 'nik',
            2 => 'enroll_id',
            3 => 'employee_name'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;
        $department=$request->department_id;
        $sub_dept_id=$request->sub_dept_id;
        $inDepartment='';
        $inSubDepartment='';
        if($department){
            $inDepartment = ' AND department_name = "'.$department.'"';
        }
        if($sub_dept_id){
            $inSubDepartment = ' AND sub_dept_id = "'.$sub_dept_id.'"';
        }
        if(empty($request->input('search.value')))
        {
            $query =  EmployeeAtribut::whereRaw('status_aktif is not null'.$inDepartment.''.$inSubDepartment.'')
            ->leftJoin(\DB::raw('
            (select y.enroll_id id_kontrak,y.id id_employee_kontrak,y.contract,y.contract_end from (
                select a.enroll_id,a.id,e.contract,e.contract_end from (select enroll_id,max(contract) contract,max(contract_end) contract_end from employee_contract group by enroll_id)e
                inner join (select id,enroll_id,contract,contract_end from employee_contract)a on e.enroll_id=a.enroll_id and e.contract_end=a.contract_end)y
                ) AS employee_cont'),
            'employee_atribut.enroll_id', '=', 'employee_cont.id_kontrak')->groupBy('employee_atribut.enroll_id')->offset($start)->limit($limit)->orderBy($order,$dir)->get();
            $totalData = EmployeeAtribut::whereRaw('status_aktif is not null'.$inDepartment.''.$inSubDepartment.'')->count();
            $totalFiltered = $totalData;

        } else {
            $search = $request->input('search.value');

            $query =  EmployeeAtribut::whereRaw('status_aktif is not null'.$inDepartment.''.$inSubDepartment.'')->where(function($query)use($search){
                $query->where('employee_id','LIKE',$search)
                ->orWhere('nik',$search)
                ->orWhere('enroll_id',$search)
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('site_nirwana_name','LIKE',"%{$search}%")
                ->orWhere('department_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->orWhere('work_status','LIKE',"%{$search}%")
                ->orWhere('employee_status','LIKE',"%{$search}%")
                ->orWhere('posisi_name','LIKE',"%{$search}%");
            })->leftJoin(\DB::raw('
            (select y.enroll_id id_kontrak,y.id id_employee_kontrak,y.contract,y.contract_end from (
                select a.enroll_id,a.id,e.contract,e.contract_end from (select enroll_id,max(contract) contract,max(contract_end) contract_end from employee_contract group by enroll_id)e
                inner join (select id,enroll_id,contract,contract_end from employee_contract)a on e.enroll_id=a.enroll_id and e.contract_end=a.contract_end)y
                ) AS employee_cont'),
            'employee_atribut.enroll_id', '=', 'employee_cont.id_kontrak')
            ->groupBy('employee_atribut.enroll_id')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalData = EmployeeAtribut::whereRaw('status_aktif is not null'.$inDepartment.''.$inSubDepartment.'')->where(function($query)use($search){
                $query->where('employee_id',$search)
                ->orWhere('nik',$search)
                ->orWhere('enroll_id',$search)
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('site_nirwana_name','LIKE',"%{$search}%")
                ->orWhere('department_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->orWhere('work_status','LIKE',"%{$search}%")
                ->orWhere('employee_status','LIKE',"%{$search}%")
                ->orWhere('posisi_name','LIKE',"%{$search}%");
            })->count();

            $totalFiltered = $totalData;

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['employee_id'] = $q->employee_id;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['jenis_kelamin'] = $q->jenis_kelamin;
                $nestedData['tempat_lahir'] = $q->tempat_lahir;
                $nestedData['tanggal_lahir'] = $q->tanggal_lahir;
                $nestedData['golongan_darah'] = $q->golongan_darah;
                $nestedData['email'] = $q->email;
                $nestedData['nomor_tlpn'] = $q->nomor_tlpn;
                $nestedData['agama'] = $q->agama;
                $nestedData['status_kawin'] = $q->status_kawin;
                $nestedData['npwp'] = $q->npwp;
                $nestedData['nomor_ktp'] = $q->nomor_ktp;
                $nestedData['nomor_kk'] = $q->nomor_kk;
                $nestedData['ptkp'] = $q->ptkp;
                $nestedData['pendidikan_terakhir'] = $q->pendidikan_terakhir;
                $nestedData['jurusan_pendidikan'] = $q->jurusan_pendidikan;
                $nestedData['nama_bank'] = $q->nama_bank;
                $nestedData['nomor_rekening_bank'] = $q->nomor_rekening_bank;
                $nestedData['ibu_kandung'] = $q->ibu_kandung;
                $nestedData['propinsi'] = $q->propinsi;
                $nestedData['kota_kab'] = $q->kota_kab;
                $nestedData['kecamatan'] = $q->kecamatan;
                $nestedData['kelurahan_desa'] = $q->kelurahan_desa;
                $nestedData['alamat_rumah'] = $q->alamat_rumah;
                $nestedData['alamat_sementara'] = $q->alamat_sementara;
                $nestedData['site_nirwana_id'] = $q->site_nirwana_id;
                $nestedData['site_nirwana_name'] = $q->site_nirwana_name;
                $nestedData['department_id'] = $q->department_id;
                $nestedData['department_name'] = $q->department_name;
                $nestedData['sub_dept_id'] = $q->sub_dept_id;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;
                $nestedData['sewing_nonsewing'] = $q->sewing_nonsewing;
                $nestedData['direct_indirect'] = $q->direct_indirect;
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['join_date'] = $q->join_date;

                $new_employee = 0;
                $bulanKemarin = date('Y-m-d', strtotime('first day of last month'));
                $bulanSkrng = date('Y-m-d', strtotime('last day of this month'));
                if (($q->join_date >= $bulanKemarin) && ($q->join_date <= $bulanSkrng)) {
                    $new_employee = 1;
                }

                $nestedData['new_employee'] = $new_employee;

                $nestedData['nik'] = $q->nik;
                $nestedData['status_aktif'] = $q->status_aktif;
                $nestedData['status_jabatan'] = $q->status_jabatan;
                $nestedData['status_kontrak_tetap'] = $q->status_kontrak_tetap;
                $nestedData['status_staff'] = $q->status_staff;

                $nestedData['tanggal_resign'] = $q->tanggal_resign;
                $nestedData['sebab_resign'] = $q->sebab_resign;
                $deactive = 0;
                if (($q->tanggal_resign <= now()) && ($q->tanggal_resign !== null ) ) {
                    $deactive = 1;
                }

                $nestedData['deactive'] = $deactive;

                $nestedData['tunjangan'] = $q->tunjangan;
                $nestedData['kode_grade'] = $q->kode_grade;
                $nestedData['referensi'] = $q->referensi;
                $nestedData['employee_name_atasan'] = $q->employee_name_atasan;
                $nestedData['status_aktif_bpjs_tk'] = $q->status_aktif_bpjs_tk;
                $nestedData['tanggal_bpjs_ketenagakerjaan'] = $q->tanggal_bpjs_ketenagakerjaan;
                $nestedData['nomor_bpjs_ketenagakerjaan'] = $q->nomor_bpjs_ketenagakerjaan;
                $nestedData['status_aktif_bpjs_ks'] = $q->status_aktif_bpjs_ks;
                $nestedData['tanggal_bpjs_kesehatan'] = $q->tanggal_bpjs_kesehatan;
                $nestedData['nomor_bpjs_kesehatan'] = $q->nomor_bpjs_kesehatan;
                $nestedData['premi'] = $q->premi;
                $nestedData['pengalaman_bekerja'] = $q->pengalaman_bekerja;
                $nestedData['lokasi_file_cv'] = $q->lokasi_file_cv;
                $nestedData['nama_kerabat'] = $q->nama_kerabat;
                $nestedData['nomor_tlpn_kerabat'] = $q->nomor_tlpn_kerabat;
                $nestedData['hubungan_kerabat'] = $q->hubungan_kerabat;
                $nestedData['alamat_kerabat'] = $q->alamat_kerabat;
                $nestedData['tanggal_vaccine1'] = $q->tanggal_vaccine1;
                $nestedData['nama_vaksin1'] = $q->nama_vaksin1;
                $nestedData['tanggal_vaccine2'] = $q->tanggal_vaccine2;
                $nestedData['nama_vaksin2'] = $q->nama_vaksin2;
                $nestedData['tanggal_vaccine3'] = $q->tanggal_vaccine3;
                $nestedData['nama_vaksin3'] = $q->nama_vaksin3;
                $nestedData['golongan_sim'] = $q->golongan_sim;
                $nestedData['nomor_sim'] = $q->nomor_sim;
                $nestedData['tanggal_expire_sim'] = $q->tanggal_expire_sim;
                $nestedData['catatan'] = $q->catatan;
                $nestedData['no_surat'] = $q->no_surat;
                $nestedData['lokasi_foto'] = $q->lokasi_foto;
                $nestedData['operator'] = $q->operator;
                $nestedData['tanggal_mulai_kontrak'] = $q->tanggal_mulai_kontrak;
                $nestedData['tanggal_akhir_kontrak'] = $q->tanggal_akhir_kontrak;
                $nestedData['catatan_kontrak'] = $q->catatan_kontrak;
                $nestedData['kontrak_awal']=$q->contract;
                $nestedData['kontrak_akhir']=$q->contract_end;
                $nestedData['created_at'] = substr($q->created_at, 0, 10) . " " . substr($q->created_at, 11, 5);
                $nestedData['updated_at'] = substr($q->updated_at, 0, 10) . " " . substr($q->updated_at, 11, 5);
                $nestedData['alamat_jalan'] = $q->alamat_jalan;
                $nestedData['rt'] = $q->rt;
                $nestedData['rw'] = $q->rw;
                $nestedData['kode_pos'] = $q->kode_pos;

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
    public function already_print(){
        $enroll_id=request()->id;
        EmployeeAtribut::where('enroll_id',$enroll_id)->update([
            'sudah_diprint'=>1
        ]);
    }
    public function not_yet_printed(){
        $enroll_id=request()->id;
        EmployeeAtribut::where('enroll_id',$enroll_id)->update([
            'sudah_diprint'=>null
        ]);
    }
    public function ajax_getemployeeatr2(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'enroll_id',
            1 => 'enroll_id',
            2 => 'nik',
            3 => 'employee_name',
            4 => 'department_name',
            5 => 'sub_dept_name',
            6 => 'status_aktif'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;
        $searchData = $request->searchData;
        $searchNoKTP = $request->selectNoKTP;
        $searchIbuKandung = $request->searchIbuKandung;
        $inSearchData='';
        if($searchData){
            $enroll_id = implode(", ", $searchData);
            $allEnroll_id= '('.$enroll_id.')';
            $inSearchData = ' AND employee_atribut.enroll_id IN '.$allEnroll_id.'';
        }
        $inSearchNoKTP='';
        if($searchNoKTP){
            $inSearchNoKTP = ' AND employee_atribut.nomor_ktp LIKE "'.$searchNoKTP.'%"';
        }
        $inSearchIbuKandung='';
        if($searchIbuKandung){
            $inSearchIbuKandung = ' AND employee_atribut.ibu_kandung LIKE "'.$searchIbuKandung.'%"';
        }
        if(empty($request->input('search.value')))
        {
            $query =  EmployeeAtribut::whereRaw('status_aktif is not null'.$inSearchData.''.$inSearchNoKTP.''.$inSearchIbuKandung.'')
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalData = EmployeeAtribut::whereRaw('status_aktif is not null'.$inSearchData.''.$inSearchNoKTP.''.$inSearchIbuKandung.'')->count();
            $totalFiltered = $totalData;

        } else {
            $search = $request->input('search.value');

            $query =  EmployeeAtribut::whereRaw('status_aktif is not null'.$inSearchData.''.$inSearchNoKTP.''.$inSearchIbuKandung.'')->where(function($query)use($search){
                $query->where('employee_id',$search)
                ->orWhere('nik',$search)
                ->orWhere('enroll_id',$search)
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('site_nirwana_name','LIKE',"%{$search}%")
                ->orWhere('department_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->orWhere('work_status','LIKE',"%{$search}%")
                ->orWhere('employee_status','LIKE',"%{$search}%")
                ->orWhere('posisi_name','LIKE',"%{$search}%");
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalData = EmployeeAtribut::whereRaw('status_aktif is not null'.$inSearchData.''.$inSearchNoKTP.''.$inSearchIbuKandung.'')->where(function($query)use($search){
                $query->where('employee_id','LIKE',"%{$search}%")
                ->orWhere('nik',$search)
                ->orWhere('enroll_id',$search)
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('site_nirwana_name','LIKE',"%{$search}%")
                ->orWhere('department_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->orWhere('work_status','LIKE',"%{$search}%")
                ->orWhere('employee_status','LIKE',"%{$search}%")
                ->orWhere('posisi_name','LIKE',"%{$search}%");
            })->count();

            $totalFiltered = $totalData;

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                if($q->tanggal_resign!=null){
                    $tanggal_resign=Carbon::parse($q->tanggal_resign)->format('d-M-Y');
                }else{
                    $tanggal_resign='';
                }
                $nestedData['employee_id'] = $q->employee_id;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['jenis_kelamin'] = $q->jenis_kelamin;
                $nestedData['tempat_lahir'] = $q->tempat_lahir;
                $nestedData['tanggal_lahir'] = $q->tanggal_lahir;
                $nestedData['golongan_darah'] = $q->golongan_darah;
                $nestedData['email'] = $q->email;
                $nestedData['nomor_tlpn'] = $q->nomor_tlpn;
                $nestedData['agama'] = $q->agama;
                $nestedData['status_kawin'] = $q->status_kawin;
                $nestedData['npwp'] = $q->npwp;
                $nestedData['nomor_ktp'] = $q->nomor_ktp;
                $nestedData['nomor_kk'] = $q->nomor_kk;
                $nestedData['ptkp'] = $q->ptkp;
                $nestedData['pendidikan_terakhir'] = $q->pendidikan_terakhir;
                $nestedData['jurusan_pendidikan'] = $q->jurusan_pendidikan;
                $nestedData['nama_bank'] = $q->nama_bank;
                $nestedData['nomor_rekening_bank'] = $q->nomor_rekening_bank;
                $nestedData['ibu_kandung'] = $q->ibu_kandung;
                $nestedData['propinsi'] = $q->propinsi;
                $nestedData['kota_kab'] = $q->kota_kab;
                $nestedData['kecamatan'] = $q->kecamatan;
                $nestedData['kelurahan_desa'] = $q->kelurahan_desa;
                $nestedData['alamat_rumah'] = $q->alamat_rumah;
                $nestedData['alamat_sementara'] = $q->alamat_sementara;
                $nestedData['site_nirwana_id'] = $q->site_nirwana_id;
                $nestedData['site_nirwana_name'] = $q->site_nirwana_name;
                $nestedData['department_id'] = $q->department_id;
                $nestedData['department_name'] = $q->department_name;
                $nestedData['sub_dept_id'] = $q->sub_dept_id;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;
                $nestedData['sewing_nonsewing'] = $q->sewing_nonsewing;
                $nestedData['direct_indirect'] = $q->direct_indirect;
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['join_date'] = $q->join_date;

                $new_employee = 0;
                $bulanKemarin = date('Y-m-d', strtotime('first day of last month'));
                $bulanSkrng = date('Y-m-d', strtotime('last day of this month'));
                if (($q->join_date >= $bulanKemarin) && ($q->join_date <= $bulanSkrng)) {
                    $new_employee = 1;
                }

                $nestedData['new_employee'] = $new_employee;

                $nestedData['nik'] = $q->nik;
                $nestedData['status_aktif'] = $q->status_aktif;
                $nestedData['status_jabatan'] = $q->status_jabatan;
                $nestedData['status_kontrak_tetap'] = $q->status_kontrak_tetap;
                $nestedData['status_staff'] = $q->status_staff;

                $nestedData['tanggal_resign'] = $tanggal_resign;
                $deactive = 0;
                if (($q->tanggal_resign <= now()) && ($q->tanggal_resign !== null ) ) {
                    $deactive = 1;
                }

                $nestedData['deactive'] = $deactive;

                $nestedData['tunjangan'] = $q->tunjangan;
                $nestedData['kode_grade'] = $q->kode_grade;
                $nestedData['referensi'] = $q->referensi;
                $nestedData['employee_name_atasan'] = $q->employee_name_atasan;
                $nestedData['status_aktif_bpjs_tk'] = $q->status_aktif_bpjs_tk;
                $nestedData['tanggal_bpjs_ketenagakerjaan'] = $q->tanggal_bpjs_ketenagakerjaan;
                $nestedData['nomor_bpjs_ketenagakerjaan'] = $q->nomor_bpjs_ketenagakerjaan;
                $nestedData['status_aktif_bpjs_ks'] = $q->status_aktif_bpjs_ks;
                $nestedData['tanggal_bpjs_kesehatan'] = $q->tanggal_bpjs_kesehatan;
                $nestedData['nomor_bpjs_kesehatan'] = $q->nomor_bpjs_kesehatan;
                $nestedData['premi'] = $q->premi;
                $nestedData['pengalaman_bekerja'] = $q->pengalaman_bekerja;
                $nestedData['lokasi_file_cv'] = $q->lokasi_file_cv;
                $nestedData['nama_kerabat'] = $q->nama_kerabat;
                $nestedData['nomor_tlpn_kerabat'] = $q->nomor_tlpn_kerabat;
                $nestedData['hubungan_kerabat'] = $q->hubungan_kerabat;
                $nestedData['alamat_kerabat'] = $q->alamat_kerabat;
                $nestedData['tanggal_vaccine1'] = $q->tanggal_vaccine1;
                $nestedData['nama_vaksin1'] = $q->nama_vaksin1;
                $nestedData['tanggal_vaccine2'] = $q->tanggal_vaccine2;
                $nestedData['nama_vaksin2'] = $q->nama_vaksin2;
                $nestedData['tanggal_vaccine3'] = $q->tanggal_vaccine3;
                $nestedData['nama_vaksin3'] = $q->nama_vaksin3;
                $nestedData['golongan_sim'] = $q->golongan_sim;
                $nestedData['nomor_sim'] = $q->nomor_sim;
                $nestedData['tanggal_expire_sim'] = $q->tanggal_expire_sim;
                $nestedData['catatan'] = $q->catatan;
                $nestedData['lokasi_foto'] = $q->lokasi_foto;
                $nestedData['operator'] = $q->operator;
                $nestedData['tanggal_mulai_kontrak'] = $q->tanggal_mulai_kontrak;
                $nestedData['tanggal_akhir_kontrak'] = $q->tanggal_akhir_kontrak;
                $nestedData['catatan_kontrak'] = $q->catatan_kontrak;
                $nestedData['sudah_diprint'] = $q->sudah_diprint;
                $nestedData['created_at'] = substr($q->created_at, 0, 10) . " " . substr($q->created_at, 11, 5);
                $nestedData['updated_at'] = substr($q->updated_at, 0, 10) . " " . substr($q->updated_at, 11, 5);
                $nestedData['alamat_jalan'] = $q->alamat_jalan;
                $nestedData['rt'] = $q->rt;
                $nestedData['rw'] = $q->rw;
                $nestedData['kode_pos'] = $q->kode_pos;

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
    public function ajax_getemployeeatr3(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'enroll_id',
            1 => 'nik',
            2 => 'employee_name',
            3 => 'tanggal_resign',
            4 => 'masa_kerja',
            5 => 'sebab_resign',
            6 => 'tipe_surat',
            7 => '',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value'))){
            $query =  EmployeeAtribut::whereIn('enroll_id',$request->checked_employees)->limit($limit)->get();

            $totalData = EmployeeAtribut::whereIn('enroll_id',$request->checked_employees)->count();
            $totalFiltered = $totalData;
        }else{
            $search = $request->input('search.value');
            $query =  EmployeeAtribut::whereIn('enroll_id',$request->checked_employees)->where(function($query)use($search){
                $query->where('employee_id','LIKE',$search)
                ->orWhere('nik',$search)
                ->orWhere('enroll_id',$search)
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('site_nirwana_name','LIKE',"%{$search}%")
                ->orWhere('department_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->orWhere('work_status','LIKE',"%{$search}%")
                ->orWhere('employee_status','LIKE',"%{$search}%")
                ->orWhere('posisi_name','LIKE',"%{$search}%");
            })
            ->limit($limit)
            ->get();

            $totalData = EmployeeAtribut::whereIn('enroll_id',$request->checked_employees)->where(function($query)use($search){
                $query->where('employee_id',$search)
                ->orWhere('nik',$search)
                ->orWhere('enroll_id',$search)
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('site_nirwana_name','LIKE',"%{$search}%")
                ->orWhere('department_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->orWhere('work_status','LIKE',"%{$search}%")
                ->orWhere('employee_status','LIKE',"%{$search}%")
                ->orWhere('posisi_name','LIKE',"%{$search}%");
            })->count();

            $totalFiltered = $totalData;
        }
        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                if($q->tanggal_resign!=null){
                    $tanggal_masuk = $q->join_date;
                    $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($q->tanggal_resign))->y;
                    $tipe_surat = 'Paklaring';
                    if($selisih_tahun<1){
                        $tipe_surat = 'SK Kerja';
                        $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create(date('Y-m-d')))->m.' (bulan)';
                    }
                    $tanggal_resign=Carbon::parse($q->tanggal_resign)->format('d-M-Y');
                }else{
                    $tanggal_masuk = $q->join_date;
                    $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create(date('Y-m-d')))->y;
                    $tipe_surat = 'Paklaring';
                    if($selisih_tahun<1){
                        $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create(date('Y-m-d')))->m.' (bulan)';
                        $tipe_surat = 'SK Kerja';
                    }
                    $tanggal_resign='-';
                }
                if(str_contains(strtolower($q->sebab_resign), 'kabur')){
                    $tipe_surat='SK Kerja';
                }
                $nestedData['employee_id'] = $q->employee_id;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['jenis_kelamin'] = $q->jenis_kelamin;
                $nestedData['tempat_lahir'] = $q->tempat_lahir;
                $nestedData['tanggal_lahir'] = $q->tanggal_lahir;
                $nestedData['golongan_darah'] = $q->golongan_darah;
                $nestedData['email'] = $q->email;
                $nestedData['nomor_tlpn'] = $q->nomor_tlpn;
                $nestedData['agama'] = $q->agama;
                $nestedData['status_kawin'] = $q->status_kawin;
                $nestedData['npwp'] = $q->npwp;
                $nestedData['nomor_ktp'] = $q->nomor_ktp;
                $nestedData['nomor_kk'] = $q->nomor_kk;
                $nestedData['ptkp'] = $q->ptkp;
                $nestedData['pendidikan_terakhir'] = $q->pendidikan_terakhir;
                $nestedData['jurusan_pendidikan'] = $q->jurusan_pendidikan;
                $nestedData['nama_bank'] = $q->nama_bank;
                $nestedData['nomor_rekening_bank'] = $q->nomor_rekening_bank;
                $nestedData['ibu_kandung'] = $q->ibu_kandung;
                $nestedData['propinsi'] = $q->propinsi;
                $nestedData['kota_kab'] = $q->kota_kab;
                $nestedData['kecamatan'] = $q->kecamatan;
                $nestedData['kelurahan_desa'] = $q->kelurahan_desa;
                $nestedData['alamat_rumah'] = $q->alamat_rumah;
                $nestedData['alamat_sementara'] = $q->alamat_sementara;
                $nestedData['site_nirwana_id'] = $q->site_nirwana_id;
                $nestedData['site_nirwana_name'] = $q->site_nirwana_name;
                $nestedData['department_id'] = $q->department_id;
                $nestedData['department_name'] = $q->department_name;
                $nestedData['sub_dept_id'] = $q->sub_dept_id;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;
                $nestedData['sewing_nonsewing'] = $q->sewing_nonsewing;
                $nestedData['direct_indirect'] = $q->direct_indirect;
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['join_date'] = $q->join_date;
                $nestedData['tipe_surat'] = $tipe_surat;

                $new_employee = 0;
                $bulanKemarin = date('Y-m-d', strtotime('first day of last month'));
                $bulanSkrng = date('Y-m-d', strtotime('last day of this month'));
                if (($q->join_date >= $bulanKemarin) && ($q->join_date <= $bulanSkrng)) {
                    $new_employee = 1;
                }

                $nestedData['new_employee'] = $new_employee;

                $nestedData['nik'] = $q->nik;
                $nestedData['status_aktif'] = $q->status_aktif;
                $nestedData['status_jabatan'] = $q->status_jabatan;
                $nestedData['status_kontrak_tetap'] = $q->status_kontrak_tetap;
                $nestedData['status_staff'] = $q->status_staff;

                $nestedData['tanggal_resign'] = $tanggal_resign;
                $nestedData['masa_kerja'] = $selisih_tahun;
                $nestedData['sebab_resign'] = $q->sebab_resign;
                $deactive = 0;
                if (($q->tanggal_resign <= now()) && ($q->tanggal_resign !== null ) ) {
                    $deactive = 1;
                }

                $nestedData['deactive'] = $deactive;

                $nestedData['tunjangan'] = $q->tunjangan;
                $nestedData['kode_grade'] = $q->kode_grade;
                $nestedData['referensi'] = $q->referensi;
                $nestedData['employee_name_atasan'] = $q->employee_name_atasan;
                $nestedData['status_aktif_bpjs_tk'] = $q->status_aktif_bpjs_tk;
                $nestedData['tanggal_bpjs_ketenagakerjaan'] = $q->tanggal_bpjs_ketenagakerjaan;
                $nestedData['nomor_bpjs_ketenagakerjaan'] = $q->nomor_bpjs_ketenagakerjaan;
                $nestedData['status_aktif_bpjs_ks'] = $q->status_aktif_bpjs_ks;
                $nestedData['tanggal_bpjs_kesehatan'] = $q->tanggal_bpjs_kesehatan;
                $nestedData['nomor_bpjs_kesehatan'] = $q->nomor_bpjs_kesehatan;
                $nestedData['premi'] = $q->premi;
                $nestedData['pengalaman_bekerja'] = $q->pengalaman_bekerja;
                $nestedData['lokasi_file_cv'] = $q->lokasi_file_cv;
                $nestedData['nama_kerabat'] = $q->nama_kerabat;
                $nestedData['nomor_tlpn_kerabat'] = $q->nomor_tlpn_kerabat;
                $nestedData['hubungan_kerabat'] = $q->hubungan_kerabat;
                $nestedData['alamat_kerabat'] = $q->alamat_kerabat;
                $nestedData['tanggal_vaccine1'] = $q->tanggal_vaccine1;
                $nestedData['nama_vaksin1'] = $q->nama_vaksin1;
                $nestedData['tanggal_vaccine2'] = $q->tanggal_vaccine2;
                $nestedData['nama_vaksin2'] = $q->nama_vaksin2;
                $nestedData['tanggal_vaccine3'] = $q->tanggal_vaccine3;
                $nestedData['nama_vaksin3'] = $q->nama_vaksin3;
                $nestedData['golongan_sim'] = $q->golongan_sim;
                $nestedData['nomor_sim'] = $q->nomor_sim;
                $nestedData['tanggal_expire_sim'] = $q->tanggal_expire_sim;
                $nestedData['catatan'] = $q->catatan;
                $nestedData['lokasi_foto'] = $q->lokasi_foto;
                $nestedData['operator'] = $q->operator;
                $nestedData['tanggal_mulai_kontrak'] = $q->tanggal_mulai_kontrak;
                $nestedData['tanggal_akhir_kontrak'] = $q->tanggal_akhir_kontrak;
                $nestedData['catatan_kontrak'] = $q->catatan_kontrak;
                $nestedData['sudah_diprint'] = $q->sudah_diprint;
                $nestedData['created_at'] = substr($q->created_at, 0, 10) . " " . substr($q->created_at, 11, 5);
                $nestedData['updated_at'] = substr($q->updated_at, 0, 10) . " " . substr($q->updated_at, 11, 5);
                $nestedData['alamat_jalan'] = $q->alamat_jalan;
                $nestedData['rt'] = $q->rt;
                $nestedData['rw'] = $q->rw;
                $nestedData['kode_pos'] = $q->kode_pos;

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

    public function ajax_getemployeeatr4(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'enroll_id',
            1 => 'nik',
            2 => 'employee_name',
            3 => 'department_name',
            4 => 'sub_dept_name',
            5 => '',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;
        $searchData = $request->searchData;
        $searchNoKTP = $request->selectNoKTP;
        $searchIbuKandung = $request->searchIbuKandung;
        $inSearchData='';
        if($searchData){
            $enroll_id = implode(", ", $searchData);
            $allEnroll_id= '('.$enroll_id.')';
            $inSearchData = ' AND employee_atribut.enroll_id IN '.$allEnroll_id.'';
        }
        $inSearchNoKTP='';
        if($searchNoKTP){
            $inSearchNoKTP = ' AND employee_atribut.nomor_ktp LIKE "'.$searchNoKTP.'%"';
        }
        $inSearchIbuKandung='';
        if($searchIbuKandung){
            $inSearchIbuKandung = ' AND employee_atribut.ibu_kandung LIKE "'.$searchIbuKandung.'%"';
        }
        if(empty($request->input('search.value')))
        {
            $query =  EmployeeAtribut::selectRaw('
                            employee_atribut.enroll_id,
                            employee_atribut.nik,
                            employee_atribut.employee_name,
                            employee_atribut.department_name,
                            employee_atribut.sub_dept_name,
                            max(employee_contract.contract) contract,
                            max(employee_contract.contract_end) contract_end,
                            employee_contract.created_at,
                            employee_contract.updated_at
                        ')
                        ->leftJoin('employee_contract','employee_atribut.enroll_id','=','employee_contract.enroll_id')
                        ->offset($start)
                        ->limit($limit)
                        ->orderBy($order,$dir)
                        ->groupBy('employee_atribut.enroll_id')
                        ->get();
            $totalData = EmployeeAtribut::count();
            $totalFiltered = $totalData;

        } else {
            $search = $request->input('search.value');

            $query =  EmployeeAtribut::whereRaw('status_aktif is not null'.$inSearchData.''.$inSearchNoKTP.''.$inSearchIbuKandung.'')->where(function($query)use($search){
                $query->where('employee_id',$search)
                ->orWhere('nik',$search)
                ->orWhere('enroll_id',$search)
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('site_nirwana_name','LIKE',"%{$search}%")
                ->orWhere('department_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->orWhere('work_status','LIKE',"%{$search}%")
                ->orWhere('employee_status','LIKE',"%{$search}%")
                ->orWhere('posisi_name','LIKE',"%{$search}%");
            })
            ->offset($start)
            ->limit($limit)
            ->orderBy($order,$dir)
            ->get();

            $totalData = EmployeeAtribut::whereRaw('status_aktif is not null'.$inSearchData.''.$inSearchNoKTP.''.$inSearchIbuKandung.'')->where(function($query)use($search){
                $query->where('employee_id','LIKE',"%{$search}%")
                ->orWhere('nik',$search)
                ->orWhere('enroll_id',$search)
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('site_nirwana_name','LIKE',"%{$search}%")
                ->orWhere('department_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->orWhere('work_status','LIKE',"%{$search}%")
                ->orWhere('employee_status','LIKE',"%{$search}%")
                ->orWhere('posisi_name','LIKE',"%{$search}%");
            })->count();

            $totalFiltered = $totalData;

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['nik'] = $q->nik;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['department_name'] = $q->department_name;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;
                $nestedData['kontrak_kerja'] = $q->contract;
                $nestedData['kontrak_kerja_akhir'] = $q->contract_end;

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
    public function ajax_getemployeeid(Request $request)
    {
        // $department=$request->department_id;
        // $sub_dept_id=$request->sub_dept_id;
        // $inDepartment='';
        // $inSubDepartment='';
        // if($department){
        //     $inDepartment = ' AND department_name = "'.$department.'"';
        // }
        // if($sub_dept_id){
        //     $inSubDepartment = ' AND sub_dept_id = "'.$sub_dept_id.'"';
        // }

        if(empty($request->input('search.value')))
        {
            $employeeIds =  EmployeeAtribut::select("enroll_id")->pluck("enroll_id")->toArray();
        } else {
            $search = $request->input('search.value');

            $employeeIds =  EmployeeAtribut::select("enroll_id")->pluck("enroll_id")->toArray();
        }

        return $employeeIds;
    }
    public function ajax_getemployeeids(Request $request)
    {
        $department=$request->department_id;
        $sub_dept_id=$request->sub_dept_id;
        $inDepartment='';
        $inSubDepartment='';
        if($department){
            $inDepartment = ' AND department_name = "'.$department.'"';
        }
        if($sub_dept_id){
            $inSubDepartment = ' AND sub_dept_id = "'.$sub_dept_id.'"';
        }

        if(empty($request->input('search.value')))
        {
            $employeeIds =  EmployeeAtribut::select("enroll_id")->whereRaw('(status_aktif != "TIDAK AKTIF" or tanggal_resign> CURDATE())'.$inDepartment.''.$inSubDepartment.'')->pluck("enroll_id")->toArray();
        } else {
            $search = $request->input('search.value');

            $employeeIds =  EmployeeAtribut::select("enroll_id")->whereRaw('(status_aktif != "TIDAK AKTIF" or tanggal_resign> CURDATE())'.$inDepartment.''.$inSubDepartment.'')->where(function($query)use($search){
                $query->where('employee_id','LIKE',"%{$search}%")
                ->orWhere('nik','LIKE',"%{$search}%")
                ->orWhere('enroll_id','LIKE',"%{$search}%")
                ->orWhere('employee_name','LIKE',"%{$search}%")
                ->orWhere('site_nirwana_name','LIKE',"%{$search}%")
                ->orWhere('department_name','LIKE',"%{$search}%")
                ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                ->orWhere('work_status','LIKE',"%{$search}%")
                ->orWhere('employee_status','LIKE',"%{$search}%")
                ->orWhere('posisi_name','LIKE',"%{$search}%");
            })
            ->pluck("enroll_id")->toArray();
        }

        return $employeeIds;
    }
    public function set_already_print_sk(){
        $employees=request()->employees;
        foreach($employees as $value){
            EmployeeAtribut::where('enroll_id',$value)->where('sudah_diprint',null)->update([
                'sudah_diprint'=>1
            ]);
        }
    }
    public function set_back_print_sk(){
        $employees=request()->employees;
        foreach($employees as $value){
            EmployeeAtribut::where('enroll_id',$value)->where('sudah_diprint',1)->update([
                'sudah_diprint'=>null
            ]);
        }
    }
    public function ajax_getcheckedemployee(Request $request){
        return EmployeeAtribut::whereIn('enroll_id',$request->employees)->get();
    }
    public function ajax_getempatr(Request $request)
    {

        if(request()->ajax()) {

        $columns = array(
            0 => 'enroll_id',
            1 => 'nik',
            2 => 'employee_name',
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $totalData = 0;
        $totalFiltered = 0;

        if(empty($request->input('search.value')))
        {
            $query =  EmployeeAtribut::offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalData = EmployeeAtribut::count();
            $totalFiltered = $totalData;

        } else {
            $search = $request->input('search.value');

            $query =  EmployeeAtribut::where('nik','LIKE',"%{$search}%")
                            ->orWhere('enroll_id','LIKE',"%{$search}%")
                            ->orWhere('employee_name','LIKE',"%{$search}%")
                            ->orWhere('site_nirwana_name','LIKE',"%{$search}%")
                            ->orWhere('department_name','LIKE',"%{$search}%")
                            ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                            ->orWhere('status_staff','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalData = EmployeeAtribut::where('nik','LIKE',"%{$search}%")
                            ->orWhere('enroll_id','LIKE',"%{$search}%")
                            ->orWhere('employee_name','LIKE',"%{$search}%")
                            ->orWhere('site_nirwana_name','LIKE',"%{$search}%")
                            ->orWhere('department_name','LIKE',"%{$search}%")
                            ->orWhere('sub_dept_name','LIKE',"%{$search}%")
                            ->orWhere('status_staff','LIKE',"%{$search}%")
                            ->count();
            $totalFiltered = $totalData;

        }

        $data = array();
        if(!empty($query))
        {
            foreach ($query as $q)
            {
                $nestedData['employee_id'] = $q->employee_id;
                $nestedData['nik'] = $q->nik;
                $nestedData['employee_name'] = $q->employee_name;
                $nestedData['enroll_id'] = $q->enroll_id;
                $nestedData['site_nirwana_name'] = $q->site_nirwana_name;
                $nestedData['department_name'] = $q->department_name;
                $nestedData['sub_dept_name'] = $q->sub_dept_name;
                $nestedData['work_status'] = $q->work_status;
                $nestedData['employee_status'] = $q->employee_status;
                $nestedData['posisi_name'] = $q->posisi_name;
                $nestedData['join_date'] = $q->join_date;
                $new_employee = 0;
                $bulanKemarin = date('Y-m-d', strtotime('first day of last month'));
                $bulanSkrng = date('Y-m-d', strtotime('last day of this month'));
                if (($q->join_date >= $bulanKemarin) && ($q->join_date <= $bulanSkrng)) {
                    $new_employee = 1;
                }
                $nestedData['new_employee'] = $new_employee;
                $nestedData['tanggal_resign'] = $q->tanggal_resign;
                $deactive = 0;
                if (($q->tanggal_resign <= now()) && ($q->tanggal_resign !== null ) ) {
                    $deactive = 1;
                }
                $nestedData['deactive'] = $deactive;
                $nestedData['jadwal_waktu_kerja1'] = $q->jadwal_waktu_kerja1;
                $nestedData['jadwal_waktu_kerja2'] = $q->jadwal_waktu_kerja2;
                $nestedData['jadwal_waktu_kerja3'] = $q->jadwal_waktu_kerja3;
                $nestedData['site_nirwana_id'] = $q->site_nirwana_id;
                $nestedData['department_id'] = $q->department_id;
                $nestedData['sub_dept_id'] = $q->sub_dept_id;
                $nestedData['sewing_nonsewing'] = $q->sewing_nonsewing;
                $nestedData['direct_indirect'] = $q->direct_indirect;
                $nestedData['kode_grade'] = $q->kode_grade;
                $nestedData['jenis_kelamin'] = $q->jenis_kelamin;
                $nestedData['tempat_lahir'] = $q->tempat_lahir;
                $nestedData['tanggal_lahir'] = $q->tanggal_lahir;
                $nestedData['alamat_rumah'] = $q->alamat_rumah;
                $nestedData['saudara_yang_bisa_dihubungi'] = $q->saudara_yang_bisa_dihubungi;
                $nestedData['hamlet'] = $q->hamlet;
                $nestedData['kota_kab'] = $q->kota_kab;
                $nestedData['kelurahan'] = $q->kelurahan;
                $nestedData['wilayah'] = $q->wilayah;
                $nestedData['propinsi'] = $q->propinsi;
                $nestedData['kode_pos'] = $q->kode_pos;
                $nestedData['agama'] = $q->agama;
                $nestedData['nomor_ktp'] = $q->nomor_ktp;
                $nestedData['golongan_darah'] = $q->golongan_darah;
                $nestedData['status_kawin'] = $q->status_kawin;
                $nestedData['pendidikan_terakhir'] = $q->pendidikan_terakhir;
                $nestedData['jurusan_pendidikan'] = $q->jurusan_pendidikan;
                $nestedData['nomor_bpjs_ketenagakerjaan'] = $q->nomor_bpjs_ketenagakerjaan;
                $nestedData['tanggal_bpjs_ketenagakerjaan'] = $q->tanggal_bpjs_ketenagakerjaan;
                $nestedData['nomor_bpjs_kesehatan'] = $q->nomor_bpjs_kesehatan;
                $nestedData['premi'] = $q->premi;
                $nestedData['tanggal_bpjs_kesehatan'] = $q->tanggal_bpjs_kesehatan;
                $nestedData['allowance'] = $q->allowance;
                $nestedData['nama_bank'] = $q->nama_bank;
                $nestedData['nomor_rekening_bank'] = $q->nomor_rekening_bank;
                $nestedData['catatan'] = $q->catatan;
                $nestedData['email'] = $q->email;
                $nestedData['ptkp'] = $q->ptkp;
                $nestedData['tanggal_vaccine1'] = $q->tanggal_vaccine1;
                $nestedData['tanggal_vaccine2'] = $q->tanggal_vaccine2;
                $nestedData['tanggal_vaccine3'] = $q->tanggal_vaccine3;
                $nestedData['created_at'] = $q->created_at;
                $nestedData['updated_at'] = $q->updated_at;
                $nestedData['nomor_tlpn'] = $q->nomor_tlpn;
                $nestedData['npwp'] = $q->npwp;
                $nestedData['golongan_sim'] = $q->golongan_sim;
                $nestedData['nomor_sim'] = $q->nomor_sim;
                $nestedData['tanggal_expire_sim'] = $q->tanggal_expire_sim;
                $nestedData['nama_vaksin1'] = $q->nama_vaksin1;
                $nestedData['nama_vaksin2'] = $q->nama_vaksin2;
                $nestedData['nama_vaksin3'] = $q->nama_vaksin3;
                $nestedData['employee_name_atasan'] = $q->employee_name_atasan;
                $nestedData['nomor_kk'] = $q->nomor_kk;
                $nestedData['lokasi_foto'] = $q->lokasi_foto;
                $nestedData['lokasi_file_cv'] = $q->lokasi_file_cv;
                $nestedData['pola_kerja'] = $q->pola_kerja;
                $nestedData['alamat_sementara'] = $q->alamat_sementara;
                $nestedData['kode_pos'] = $q->kode_pos;
                $nestedData['kode_pos_sementara'] = $q->kode_pos_sementara;
                $nestedData['nama_kerabat'] = $q->nama_kerabat;
                $nestedData['nomor_tlpn_kerabat'] = $q->nomor_tlpn_kerabat;
                $nestedData['alamat_kerabat'] = $q->alamat_kerabat;
                $nestedData['hubungan_kerabat'] = $q->hubungan_kerabat;
                $nestedData['pengalaman_bekerja'] = $q->pengalaman_bekerja;
                $nestedData['alamat_jalan'] = $q->alamat_jalan;
                $nestedData['rt'] = $q->rt;
                $nestedData['rw'] = $q->rw;
                $nestedData['kode_pos'] = $q->kode_pos;

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

    public function replace(Request $request)
    {
        $loggedAdmin = Auth::guard('admin')->user();
        $operator = $loggedAdmin->email;

        $employee_id = time();
        $employee_name = strtoupper($request->employee_name);
        $jenis_kelamin = strtoupper($request->jenis_kelamin);
        $tempat_lahir = strtoupper($request->tempat_lahir);
        $tanggal_lahir = $request->tanggal_lahir;
        $golongan_darah = strtoupper($request->golongan_darah);
        $email = $request->email;
        $nomor_tlpn = $request->nomor_tlpn;
        $agama = strtoupper($request->agama);
        $status_kawin = strtoupper($request->status_kawin);
        $npwp = $request->npwp;
        $nomor_ktp = $request->nomor_ktp;
        $nomor_kk = $request->nomor_kk;
        $ptkp = $request->ptkp;
        $pendidikan_terakhir = strtoupper($request->pendidikan_terakhir);
        $jurusan_pendidikan = strtoupper($request->jurusan_pendidikan);
        $nama_bank = strtoupper($request->nama_bank);
        $nomor_rekening_bank = $request->nomor_rekening_bank;
        $ibu_kandung = strtoupper($request->ibu_kandung);
        $propinsi = strtoupper($request->propinsi);
        $kota_kab = strtoupper($request->kota_kab);
        $kecamatan = strtoupper($request->kecamatan);
        $kelurahan_desa = strtoupper($request->kelurahan_desa);
        $alamat_rumah = strtoupper($request->alamat_rumah);
        $alamat_sementara = strtoupper($request->alamat_sementara);
        $site_nirwana_id = $request->site_nirwana_id;
        $department_id = $request->department_id;
        $sub_dept_id = $request->sub_dept_id;
        $sewing_nonsewing = $request->sewing_nonsewing;
        $direct_indirect = $request->direct_indirect;
        $enroll_id = $request->enroll_id;
        $join_date = $request->join_date;
        $nik = strtoupper($request->nik);
        $status_aktif = strtoupper($request->status_aktif);
        $status_jabatan = strtoupper($request->status_jabatan);
        $status_kontrak_tetap = strtoupper($request->status_kontrak_tetap);
        $status_staff = strtoupper($request->status_staff);
        if($request->tanggal_resign == "") { $tanggal_resign = null; } else { $tanggal_resign = $request->tanggal_resign; }
        $sebab_resign = $request->sebab_resign;
        $tunjangan = strtoupper($request->tunjangan);
        $kode_grade = strtoupper($request->kode_grade);
        $referensi = strtoupper($request->referensi);
        $employee_name_atasan = strtoupper($request->employee_name_atasan);
        $status_aktif_bpjs_tk = strtoupper($request->status_aktif_bpjs_tk);
        $tanggal_bpjs_ketenagakerjaan = $request->tanggal_bpjs_ketenagakerjaan;
        $nomor_bpjs_ketenagakerjaan = $request->nomor_bpjs_ketenagakerjaan;
        $status_aktif_bpjs_ks = strtoupper($request->status_aktif_bpjs_ks);
        $tanggal_bpjs_kesehatan = $request->tanggal_bpjs_kesehatan;
        $nomor_bpjs_kesehatan = $request->nomor_bpjs_kesehatan;
        $premi = $request->premi;
        $pengalaman_bekerja = strtoupper($request->pengalaman_bekerja);
        $lokasi_file_cv = $request->lokasi_file_cv;
        $nama_kerabat = strtoupper($request->nama_kerabat);
        $nomor_tlpn_kerabat = $request->nomor_tlpn_kerabat;
        $hubungan_kerabat = strtoupper($request->hubungan_kerabat);
        $alamat_kerabat = strtoupper($request->alamat_kerabat);
        $tanggal_vaccine1 = $request->tanggal_vaccine1;
        $nama_vaksin1 = strtoupper($request->nama_vaksin1);
        $tanggal_vaccine2 = $request->tanggal_vaccine2;
        $nama_vaksin2 = strtoupper($request->nama_vaksin2);
        $tanggal_vaccine3 = $request->tanggal_vaccine3;
        $nama_vaksin3 = strtoupper($request->nama_vaksin3);
        $golongan_sim = strtoupper($request->golongan_sim);
        $nomor_sim = $request->nomor_sim;
        $tanggal_expire_sim = $request->tanggal_expire_sim;
        $catatan = strtoupper($request->catatan);
        $no_surat = $request->no_surat;
        $lokasi_foto = $request->lokasi_foto;
        $tanggal_mulai_kontrak = $request->tanggal_mulai_kontrak;
        $tanggal_akhir_kontrak = $request->tanggal_akhir_kontrak;
        $catatan_kontrak = strtoupper($request->catatan_kontrak);

        $alamat_jalan = $request->alamat_jalan;
        $rt = $request->rt;
        $rw = $request->rw;
        $kode_pos = $request->kode_pos;

        $timestamp = Carbon::now();
        $site_nirwana_name =  DepartmentAll::select('site_nirwana_name')
                                    ->where('site_nirwana_id', '=', $site_nirwana_id)
                                    ->groupby('site_nirwana_id')
                                    ->first();

        $department_name =  DepartmentAll::select('department_name')
                                    ->where('site_nirwana_id', '=', $site_nirwana_id)
                                    ->where('department_id', '=', $department_id)
                                    ->groupby('department_id')
                                    ->first();

        $sub_dept_name =  DepartmentAll::select('sub_dept_name')
                                    ->where('site_nirwana_id', '=', $site_nirwana_id)
                                    ->where('department_id', '=', $department_id)
                                    ->where('sub_dept_id', '=', $sub_dept_id)
                                    ->groupby('sub_dept_id')
                                    ->first();


        $query = EmployeeAtribut::whereRaw('enroll_id = "' . $enroll_id . '"')->count();
        MasterDataAbsenKehadiran::where('enroll_id',$enroll_id)->update([
            'nik'=>$request->nik
        ]);
        //update master
        // $kontrak_awal=DB::select("select max(contract) contract from employee_contract where enroll_id='$enroll_id'")[0]->contract;
        // $kontrak_akhir=DB::select("select max(contract_end) contract_end from employee_contract where enroll_id='$enroll_id'")[0]->contract_end;
        // if($kontrak_awal){
        //     $tanggal_mulai_kontrak=$kontrak_awal;
        //     $tanggal_akhir_kontrak=$kontrak_akhir;
        // }else{
        //     if($tanggal_mulai_kontrak!='' && $tanggal_akhir_kontrak!=''){
        //         DB::insert("insert into employee_contract (id, enroll_id, contract, contract_end, created_at, updated_at) VALUES ('','$enroll_id','$tanggal_mulai_kontrak','$tanggal_akhir_kontrak','$timestamp','$timestamp')");
        //     }
        // }
        $employee_contract_before = EmployeeAtribut::whereRaw('enroll_id = "' . $enroll_id . '"')->first();
        // dd($employee_contract_before->tanggal_mulai_kontrak, $employee_contract_before->tanggal_akhir_kontrak);
        if($employee_contract_before){
            if ($tanggal_mulai_kontrak != '' && $tanggal_akhir_kontrak != '') {
                // Cek apakah data kontrak ini sudah ada
                $existing = DB::table('employee_contract')
                    ->where('enroll_id', $enroll_id)
                    ->whereDate('contract', $employee_contract_before->tanggal_mulai_kontrak)
                    ->whereDate('contract_end', $employee_contract_before->tanggal_akhir_kontrak)
                    ->first();

                if ($existing) {
                    // Update jika sudah ada
                    DB::table('employee_contract')
                        ->where('id', $existing->id)
                        ->update([
                            'contract' => $tanggal_mulai_kontrak,
                            'contract_end' => $tanggal_akhir_kontrak,
                            'updated_at' => $timestamp
                        ]);
                }
            }
        }

        if($query > 0) {
            $query = EmployeeAtribut::whereRaw('enroll_id = "' . $enroll_id . '"')
            ->update([
                'employee_name' => $employee_name,
                'jenis_kelamin' => $jenis_kelamin,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'golongan_darah' => $golongan_darah,
                'email' => $email,
                'nomor_tlpn' => $nomor_tlpn,
                'agama' => $agama,
                'status_kawin' => $status_kawin,
                'npwp' => $npwp,
                'nomor_ktp' => $nomor_ktp,
                'nomor_kk' => $nomor_kk,
                'ptkp' => $ptkp,
                'pendidikan_terakhir' => $pendidikan_terakhir,
                'jurusan_pendidikan' => $jurusan_pendidikan,
                'nama_bank' => $nama_bank,
                'nomor_rekening_bank' => $nomor_rekening_bank,
                'ibu_kandung' => $ibu_kandung,
                'propinsi' => $propinsi,
                'kota_kab' => $kota_kab,
                'kecamatan' => $kecamatan,
                'kelurahan_desa' => $kelurahan_desa,
                'alamat_rumah' => $alamat_rumah,
                'alamat_sementara' => $alamat_sementara,
                'site_nirwana_id' => $site_nirwana_id,
                'site_nirwana_name' => $site_nirwana_name->site_nirwana_name,
                'department_id' => $department_id,
                'department_name' => $department_name->department_name,
                'sub_dept_id' => $sub_dept_id,
                'sub_dept_name' => $sub_dept_name->sub_dept_name,
                'sewing_nonsewing'=>$sewing_nonsewing,
                'direct_indirect'=>$direct_indirect,
                'enroll_id' => $enroll_id,
                'join_date' => $join_date,
                'nik' => $nik,
                'status_aktif' => $status_aktif,
                'status_jabatan' => $status_jabatan,
                'status_kontrak_tetap' => $status_kontrak_tetap,
                'status_staff' => $status_staff,
                'tanggal_resign' => $tanggal_resign,
                'sebab_resign' => $sebab_resign,
                'tunjangan' => $tunjangan,
                'kode_grade' => $kode_grade,
                'referensi' => $referensi,
                'employee_name_atasan' => $employee_name_atasan,
                'status_aktif_bpjs_tk' => $status_aktif_bpjs_tk,
                'tanggal_bpjs_ketenagakerjaan' => $tanggal_bpjs_ketenagakerjaan,
                'nomor_bpjs_ketenagakerjaan' => $nomor_bpjs_ketenagakerjaan,
                'status_aktif_bpjs_ks' => $status_aktif_bpjs_ks,
                'tanggal_bpjs_kesehatan' => $tanggal_bpjs_kesehatan,
                'premi' => $premi,
                'nomor_bpjs_kesehatan' => $nomor_bpjs_kesehatan,
                'pengalaman_bekerja' => $pengalaman_bekerja,
                'lokasi_file_cv' => $lokasi_file_cv,
                'nama_kerabat' => $nama_kerabat,
                'nomor_tlpn_kerabat' => $nomor_tlpn_kerabat,
                'hubungan_kerabat' => $hubungan_kerabat,
                'alamat_kerabat' => $alamat_kerabat,
                'tanggal_vaccine1' => $tanggal_vaccine1,
                'nama_vaksin1' => $nama_vaksin1,
                'tanggal_vaccine2' => $tanggal_vaccine2,
                'nama_vaksin2' => $nama_vaksin2,
                'tanggal_vaccine3' => $tanggal_vaccine3,
                'nama_vaksin3' => $nama_vaksin3,
                'golongan_sim' => $golongan_sim,
                'nomor_sim' => $nomor_sim,
                'tanggal_expire_sim' => $tanggal_expire_sim,
                'catatan' => $catatan,
                'no_surat'=>$no_surat,
                'lokasi_foto' => $lokasi_foto,
                'operator' => $operator,
                'tanggal_mulai_kontrak' => $tanggal_mulai_kontrak,
                'tanggal_akhir_kontrak' => $tanggal_akhir_kontrak,
                'catatan_kontrak' => $catatan_kontrak,
                'alamat_jalan' => $alamat_jalan,
                'rt' => $rt,
                'rw' => $rw,
                'kode_pos' => $kode_pos
            ]);


            info('Karyawan dengan nama ' . $employee_name . ' dari departemen '. $sub_dept_name->sub_dept_name .' telah di update oleh '.$operator.' dengan tanggal resign '.$tanggal_resign);
            // update absen
            if($query) {
                // jika tanggal resign kosong
                if($request->tanggal_resign == "") {
                    $query1 =  MasterDataAbsenKehadiran::selectRaw('
                        tanggal_berjalan,
                        employee_atribut.enroll_id,
                        null AS tanggal_resign,
                        null AS status_aktif,
                        employee_atribut.nik,
                        IF(absen_masuk_kerja is not null AND absen_pulang_kerja is null, "TL", "M") status_absen
                    ')
                    ->whereRaw('
                        employee_atribut.enroll_id = "' . $enroll_id . '"
                        AND employee_atribut.status_aktif = "TIDAK AKTIF"
                        AND (absen_masuk_kerja is null OR absen_pulang_kerja is null)
                        AND kode_hari not in (5,6)
                        AND holiday_name is null
                        AND status_absen = "R"
                    ')
                    ->leftJoin('employee_atribut','master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
                    ->get();
                    if(!empty($query1))
                    {
                        foreach ($query1 as $q1)
                        {
                            $query = MasterDataAbsenKehadiran::whereRaw('
                                tanggal_berjalan = "' . $q1->tanggal_berjalan . '"
                                AND enroll_id = "' . $enroll_id . '"
                            ')
                            ->update([
                                'status_absen' => $q1->status_absen,
                                'operator' => $q1->operator
                            ]);
                            info('Karyawan dengan nama ' . $employee_name . ' dari departemen '. $sub_dept_name .' berubah status aktif nya menjadi '.$q1->status_aktif);
                        }
                    }
                    // tanggal resign tidak kosong
                } else {

                    $query1 =  MasterDataAbsenKehadiran::selectRaw('
                        tanggal_berjalan,
                        employee_atribut.enroll_id,
                        null AS tanggal_resign,
                        employee_atribut.nik,
                        null AS status_aktif,
                        IF(absen_masuk_kerja is not null AND absen_pulang_kerja is null, "TL", "M") status_absen
                    ')
                    ->whereRaw('
                        employee_atribut.enroll_id = "' . $enroll_id . '"
                        AND employee_atribut.status_aktif = "TIDAK AKTIF"
                        AND (absen_masuk_kerja is null OR absen_pulang_kerja is null)
                        AND kode_hari not in (5,6)
                        AND holiday_name is null
                        AND status_absen = "R"
                    ')
                    ->leftJoin('employee_atribut','master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
                    ->get();
                    if(!empty($query1))
                    {
                        foreach ($query1 as $q1)
                        {
                            $query = MasterDataAbsenKehadiran::whereRaw('
                                tanggal_berjalan = "' . $q1->tanggal_berjalan . '"
                                AND enroll_id = "' . $enroll_id . '"
                            ')
                            ->update([
                                'status_absen' => $q1->status_absen,
                                'operator' => $q1->operator
                            ]);
                            info('Karyawan dengan nama ' . $employee_name . ' dari departemen '. $sub_dept_name .' berubah tanggal resign nya menjadi '.$q1->tanggal_resign);
                        }
                    }
                    $query2 =  MasterDataAbsenKehadiran::selectRaw('
                        master_data_absen_kehadiran.tanggal_berjalan,
                        employee_atribut.enroll_id,
                        employee_atribut.tanggal_resign,
                        employee_atribut.status_aktif,
                        employee_atribut.nik,
                        "R" status_absen
                    ')
                    ->whereRaw('
                        employee_atribut.enroll_id = "' . $enroll_id . '"
                        AND employee_atribut.tanggal_resign <= master_data_absen_kehadiran.tanggal_berjalan
                        AND master_data_absen_kehadiran.kode_hari not in (5,6)
                        AND master_data_absen_kehadiran.holiday_name is null
                    ')
                    ->leftJoin('employee_atribut','master_data_absen_kehadiran.enroll_id','=','employee_atribut.enroll_id')
                    ->get();
                    if(!empty($query2))
                    {
                        foreach ($query2 as $q2)
                        {
                            $query = MasterDataAbsenKehadiran::whereRaw('
                                tanggal_berjalan = "' . $q2->tanggal_berjalan . '"
                                AND enroll_id = "' . $enroll_id . '"
                            ')
                            ->update([
                                'status_absen' => $q2->status_absen,
                                'operator' => $q2->operator
                            ]);
                        }
                    }
                    $tanggal_awal='';
                    $tanggal_sekarang=date('Y-m-d');
                    $bulan_sekarang=date('Y-m-'.'25');
                    $bulan_sebelum=date('Y-m-d',strtotime( "-1 month", strtotime( $bulan_sekarang ) ));
                    $bulan_setelah=date('Y-m-d',strtotime( "+1 month", strtotime( $bulan_sekarang ) ));

                    if($tanggal_sekarang>$bulan_sebelum && $tanggal_sekarang<=$bulan_sekarang){
                        $tanggal_awal=$bulan_sebelum;
                    }else if($tanggal_sekarang>$bulan_sekarang && $tanggal_sekarang<=$bulan_setelah){
                        $tanggal_awal=$bulan_sekarang;
                    }
                    MasterDataAbsenKehadiran::where('enroll_id',$enroll_id)->where('tanggal_berjalan','<',$tanggal_resign)->where('tanggal_berjalan','>=',$tanggal_awal)->where('status_absen','R')->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja','!=',null)->update([
                        'status_absen'=>''
                    ]);
                    MasterDataAbsenKehadiran::where('enroll_id',$enroll_id)->where('tanggal_berjalan','<',$tanggal_resign)->where('tanggal_berjalan','>=',$tanggal_awal)->where('status_absen','R')->where('absen_masuk_kerja','!=',null)->where('absen_pulang_kerja',null)->update([
                        'status_absen'=>'TL'
                    ]);
                    MasterDataAbsenKehadiran::where('enroll_id',$enroll_id)->where('tanggal_berjalan','<',$tanggal_resign)->where('tanggal_berjalan','>=',$tanggal_awal)->where('status_absen','R')->where('absen_masuk_kerja',null)->where('absen_pulang_kerja','!=',null)->update([
                        'status_absen'=>'TL'
                    ]);
                    MasterDataAbsenKehadiran::where('enroll_id',$enroll_id)->where('tanggal_berjalan','<',$tanggal_resign)->where('tanggal_berjalan','>=',$tanggal_awal)->where('status_absen','R')->where('absen_masuk_kerja',null)->where('absen_pulang_kerja',null)->update([
                        'status_absen'=>'M'
                    ]);
                }
            }
        } else {
            $query = EmployeeAtribut::create([

                'employee_id' => $employee_id,
                'employee_name' => $employee_name,
                'jenis_kelamin' => $jenis_kelamin,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'golongan_darah' => $golongan_darah,
                'email' => $email,
                'nomor_tlpn' => $nomor_tlpn,
                'agama' => $agama,
                'status_kawin' => $status_kawin,
                'npwp' => $npwp,
                'nomor_ktp' => $nomor_ktp,
                'nomor_kk' => $nomor_kk,
                'ptkp' => $ptkp,
                'pendidikan_terakhir' => $pendidikan_terakhir,
                'jurusan_pendidikan' => $jurusan_pendidikan,
                'nama_bank' => $nama_bank,
                'nomor_rekening_bank' => $nomor_rekening_bank,
                'ibu_kandung' => $ibu_kandung,
                'propinsi' => $propinsi,
                'kota_kab' => $kota_kab,
                'kecamatan' => $kecamatan,
                'kelurahan_desa' => $kelurahan_desa,
                'alamat_rumah' => $alamat_rumah,
                'alamat_sementara' => $alamat_sementara,
                'site_nirwana_id' => $site_nirwana_id,
                'site_nirwana_name' => $site_nirwana_name->site_nirwana_name,
                'department_id' => $department_id,
                'department_name' => $department_name->department_name,
                'sub_dept_id' => $sub_dept_id,
                'sub_dept_name' => $sub_dept_name->sub_dept_name,
                'sewing_nonsewing'=>$sewing_nonsewing,
                'direct_indirect'=>$direct_indirect,
                'enroll_id' => $enroll_id,
                'join_date' => $join_date,
                'nik' => $nik,
                'status_aktif' => $status_aktif,
                'status_jabatan' => $status_jabatan,
                'status_kontrak_tetap' => $status_kontrak_tetap,
                'status_staff' => $status_staff,
                'tanggal_resign' => $tanggal_resign,
                'sebab_resign' => $sebab_resign,
                'tunjangan' => $tunjangan,
                'kode_grade' => $kode_grade,
                'referensi' => $referensi,
                'employee_name_atasan' => $employee_name_atasan,
                'status_aktif_bpjs_tk' => $status_aktif_bpjs_tk,
                'tanggal_bpjs_ketenagakerjaan' => $tanggal_bpjs_ketenagakerjaan,
                'nomor_bpjs_ketenagakerjaan' => $nomor_bpjs_ketenagakerjaan,
                'status_aktif_bpjs_ks' => $status_aktif_bpjs_ks,
                'tanggal_bpjs_kesehatan' => $tanggal_bpjs_kesehatan,
                'nomor_bpjs_kesehatan' => $nomor_bpjs_kesehatan,
                'premi' => $premi,
                'pengalaman_bekerja' => $pengalaman_bekerja,
                'lokasi_file_cv' => $lokasi_file_cv,
                'nama_kerabat' => $nama_kerabat,
                'nomor_tlpn_kerabat' => $nomor_tlpn_kerabat,
                'hubungan_kerabat' => $hubungan_kerabat,
                'alamat_kerabat' => $alamat_kerabat,
                'tanggal_vaccine1' => $tanggal_vaccine1,
                'nama_vaksin1' => $nama_vaksin1,
                'tanggal_vaccine2' => $tanggal_vaccine2,
                'nama_vaksin2' => $nama_vaksin2,
                'tanggal_vaccine3' => $tanggal_vaccine3,
                'nama_vaksin3' => $nama_vaksin3,
                'golongan_sim' => $golongan_sim,
                'nomor_sim' => $nomor_sim,
                'tanggal_expire_sim' => $tanggal_expire_sim,
                'catatan' => $catatan,
                'no_surat'=>$no_surat,
                'lokasi_foto' => $lokasi_foto,
                'operator' => $operator,
                'tanggal_mulai_kontrak' => $tanggal_mulai_kontrak,
                'tanggal_akhir_kontrak' => $tanggal_akhir_kontrak,
                'catatan_kontrak' => $catatan_kontrak,
                'alamat_jalan' => $alamat_jalan,
                'rt' => $rt,
                'rw' => $rw,
                'kode_pos' => $kode_pos
            ]);

            // Insert jika belum ada
            DB::table('employee_contract')->insert([
                'enroll_id' => $enroll_id,
                'contract' => $tanggal_mulai_kontrak,
                'contract_end' => $tanggal_akhir_kontrak,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);

            info('Karyawan dengan nama ' . $employee_name . ' dari departemen '. $sub_dept_name->sub_dept_name .' telah di tambah oleh '.$operator);

        $a=(new Kehadiran)->new_employee($enroll_id);


        }

           //menambah atau merubah tabel employee atribut history
           $tanggal_hari_ini=date('Y-m-d');
           $bulan_hari_ini=substr($tanggal_hari_ini,0,8).'26';
           $bulan_sebelum_hari_ini=date('Y-m-d',strtotime( "-1 month", strtotime( $bulan_hari_ini ) ));
           $bulan_setelah_hari_ini=date('Y-m-d',strtotime( "+1 month", strtotime( $bulan_hari_ini ) ));
           if($tanggal_hari_ini>=$bulan_sebelum_hari_ini && $tanggal_hari_ini<$bulan_hari_ini){
               $tanggal_awal_hari_ini=$bulan_sebelum_hari_ini;
           }else if($tanggal_hari_ini>=$bulan_hari_ini && $tanggal_hari_ini<$bulan_setelah_hari_ini){
               $tanggal_awal_hari_ini=$bulan_hari_ini;
           }else{
               $tanggal_awal_hari_ini='';
           }
           $tanggal_akhir_hari_ini=date('Y-m-25',strtotime("+1 month",strtotime($tanggal_awal_hari_ini)));
           $periode_payroll_hari_ini=$tanggal_awal_hari_ini.' s/d '.$tanggal_akhir_hari_ini;
           $history_dirubah=EmployeeAtributHistory::where('enroll_id',$enroll_id)->where('periode_payroll',$periode_payroll_hari_ini)->count();
           if($history_dirubah<1){
               EmployeeAtributHistory::create([
                   'enroll_id'=>$enroll_id,
                   'tanggal_dirubah'=>$tanggal_hari_ini,
                   'periode_payroll'=>$periode_payroll_hari_ini,
                   'employee_id' => $employee_id,
                   'employee_name' => $employee_name,
                   'jenis_kelamin' => $jenis_kelamin,
                   'tempat_lahir' => $tempat_lahir,
                   'tanggal_lahir' => $tanggal_lahir,
                   'golongan_darah' => $golongan_darah,
                   'email' => $email,
                   'nomor_tlpn' => $nomor_tlpn,
                   'agama' => $agama,
                   'status_kawin' => $status_kawin,
                   'npwp' => $npwp,
                   'nomor_ktp' => $nomor_ktp,
                   'nomor_kk' => $nomor_kk,
                   'ptkp' => $ptkp,
                   'pendidikan_terakhir' => $pendidikan_terakhir,
                   'jurusan_pendidikan' => $jurusan_pendidikan,
                   'nama_bank' => $nama_bank,
                   'nomor_rekening_bank' => $nomor_rekening_bank,
                   'ibu_kandung' => $ibu_kandung,
                   'propinsi' => $propinsi,
                   'kota_kab' => $kota_kab,
                   'kecamatan' => $kecamatan,
                   'kelurahan_desa' => $kelurahan_desa,
                   'alamat_rumah' => $alamat_rumah,
                   'alamat_sementara' => $alamat_sementara,
                   'site_nirwana_id' => $site_nirwana_id,
                   'site_nirwana_name' => $site_nirwana_name->site_nirwana_name,
                   'department_id' => $department_id,
                   'department_name' => $department_name->department_name,
                   'sub_dept_id' => $sub_dept_id,
                   'sub_dept_name' => $sub_dept_name->sub_dept_name,
                   'sewing_nonsewing'=>$sewing_nonsewing,
                   'direct_indirect'=>$direct_indirect,
                   'enroll_id' => $enroll_id,
                   'join_date' => $join_date,
                   'nik' => $nik,
                   'status_aktif' => $status_aktif,
                   'status_jabatan' => $status_jabatan,
                   'status_kontrak_tetap' => $status_kontrak_tetap,
                   'status_staff' => $status_staff,
                   'tanggal_resign' => $tanggal_resign,
                   'sebab_resign' => $sebab_resign,
                   'tunjangan' => $tunjangan,
                   'kode_grade' => $kode_grade,
                   'referensi' => $referensi,
                   'employee_name_atasan' => $employee_name_atasan,
                   'status_aktif_bpjs_tk' => $status_aktif_bpjs_tk,
                   'tanggal_bpjs_ketenagakerjaan' => $tanggal_bpjs_ketenagakerjaan,
                   'nomor_bpjs_ketenagakerjaan' => $nomor_bpjs_ketenagakerjaan,
                   'status_aktif_bpjs_ks' => $status_aktif_bpjs_ks,
                   'tanggal_bpjs_kesehatan' => $tanggal_bpjs_kesehatan,
                   'nomor_bpjs_kesehatan' => $nomor_bpjs_kesehatan,
                   'premi' => $premi,
                   'pengalaman_bekerja' => $pengalaman_bekerja,
                   'lokasi_file_cv' => $lokasi_file_cv,
                   'nama_kerabat' => $nama_kerabat,
                   'nomor_tlpn_kerabat' => $nomor_tlpn_kerabat,
                   'hubungan_kerabat' => $hubungan_kerabat,
                   'alamat_kerabat' => $alamat_kerabat,
                   'tanggal_vaccine1' => $tanggal_vaccine1,
                   'nama_vaksin1' => $nama_vaksin1,
                   'tanggal_vaccine2' => $tanggal_vaccine2,
                   'nama_vaksin2' => $nama_vaksin2,
                   'tanggal_vaccine3' => $tanggal_vaccine3,
                   'nama_vaksin3' => $nama_vaksin3,
                   'golongan_sim' => $golongan_sim,
                   'nomor_sim' => $nomor_sim,
                   'tanggal_expire_sim' => $tanggal_expire_sim,
                   'catatan' => $catatan,
                   'no_surat'=>$no_surat,
                   'lokasi_foto' => $lokasi_foto,
                   'operator' => $operator,
                   'tanggal_mulai_kontrak' => $tanggal_mulai_kontrak,
                   'tanggal_akhir_kontrak' => $tanggal_akhir_kontrak,
                   'catatan_kontrak' => $catatan_kontrak
               ]);
           }else{
               EmployeeAtributHistory::where('enroll_id',$enroll_id)->where('periode_payroll',$periode_payroll_hari_ini)->update([
                   'enroll_id'=>$enroll_id,
                   'tanggal_dirubah'=>$tanggal_hari_ini,
                   'periode_payroll'=>$periode_payroll_hari_ini,
                   'employee_id' => $employee_id,
                   'employee_name' => $employee_name,
                   'jenis_kelamin' => $jenis_kelamin,
                   'tempat_lahir' => $tempat_lahir,
                   'tanggal_lahir' => $tanggal_lahir,
                   'golongan_darah' => $golongan_darah,
                   'email' => $email,
                   'nomor_tlpn' => $nomor_tlpn,
                   'agama' => $agama,
                   'status_kawin' => $status_kawin,
                   'npwp' => $npwp,
                   'nomor_ktp' => $nomor_ktp,
                   'nomor_kk' => $nomor_kk,
                   'ptkp' => $ptkp,
                   'pendidikan_terakhir' => $pendidikan_terakhir,
                   'jurusan_pendidikan' => $jurusan_pendidikan,
                   'nama_bank' => $nama_bank,
                   'nomor_rekening_bank' => $nomor_rekening_bank,
                   'ibu_kandung' => $ibu_kandung,
                   'propinsi' => $propinsi,
                   'kota_kab' => $kota_kab,
                   'kecamatan' => $kecamatan,
                   'kelurahan_desa' => $kelurahan_desa,
                   'alamat_rumah' => $alamat_rumah,
                   'alamat_sementara' => $alamat_sementara,
                   'site_nirwana_id' => $site_nirwana_id,
                   'site_nirwana_name' => $site_nirwana_name->site_nirwana_name,
                   'department_id' => $department_id,
                   'department_name' => $department_name->department_name,
                   'sub_dept_id' => $sub_dept_id,
                   'sub_dept_name' => $sub_dept_name->sub_dept_name,
                   'sewing_nonsewing'=>$sewing_nonsewing,
                   'direct_indirect'=>$direct_indirect,
                   'enroll_id' => $enroll_id,
                   'join_date' => $join_date,
                   'nik' => $nik,
                   'status_aktif' => $status_aktif,
                   'status_jabatan' => $status_jabatan,
                   'status_kontrak_tetap' => $status_kontrak_tetap,
                   'status_staff' => $status_staff,
                   'tanggal_resign' => $tanggal_resign,
                   'sebab_resign' => $sebab_resign,
                   'tunjangan' => $tunjangan,
                   'kode_grade' => $kode_grade,
                   'referensi' => $referensi,
                   'employee_name_atasan' => $employee_name_atasan,
                   'status_aktif_bpjs_tk' => $status_aktif_bpjs_tk,
                   'tanggal_bpjs_ketenagakerjaan' => $tanggal_bpjs_ketenagakerjaan,
                   'nomor_bpjs_ketenagakerjaan' => $nomor_bpjs_ketenagakerjaan,
                   'status_aktif_bpjs_ks' => $status_aktif_bpjs_ks,
                   'tanggal_bpjs_kesehatan' => $tanggal_bpjs_kesehatan,
                   'nomor_bpjs_kesehatan' => $nomor_bpjs_kesehatan,
                   'premi' => $premi,
                   'pengalaman_bekerja' => $pengalaman_bekerja,
                   'lokasi_file_cv' => $lokasi_file_cv,
                   'nama_kerabat' => $nama_kerabat,
                   'nomor_tlpn_kerabat' => $nomor_tlpn_kerabat,
                   'hubungan_kerabat' => $hubungan_kerabat,
                   'alamat_kerabat' => $alamat_kerabat,
                   'tanggal_vaccine1' => $tanggal_vaccine1,
                   'nama_vaksin1' => $nama_vaksin1,
                   'tanggal_vaccine2' => $tanggal_vaccine2,
                   'nama_vaksin2' => $nama_vaksin2,
                   'tanggal_vaccine3' => $tanggal_vaccine3,
                   'nama_vaksin3' => $nama_vaksin3,
                   'golongan_sim' => $golongan_sim,
                   'nomor_sim' => $nomor_sim,
                   'tanggal_expire_sim' => $tanggal_expire_sim,
                   'catatan' => $catatan,
                   'no_surat'=>$no_surat,
                   'lokasi_foto' => $lokasi_foto,
                   'operator' => $operator,
                   'tanggal_mulai_kontrak' => $tanggal_mulai_kontrak,
                   'tanggal_akhir_kontrak' => $tanggal_akhir_kontrak,
                   'catatan_kontrak' => $catatan_kontrak
               ]);
           }

        return $query;

    }
    public function creat_master_absen_26()
    {
        $today=date('Y-m-d');
        $employee=DB::select("select enroll_id from master_data_absen_kehadiran where tanggal_berjalan='2024-12-25' and status_absen='TL'");
        $arrEmp=[];
        foreach($employee as $emp){
            $arrEmp[]=$emp->enroll_id;
        }
        $string_absen_emp=implode(',', $arrEmp);
        dd($string_absen_emp);
        // foreach ($employee as $key => $value) {
        //     $staffnonstaff=$value->status_staff;
        //     $tanggal_akhir='2024-10-30';
        //     $tgl_berjalan=$today;
        //     while (strtotime( $tgl_berjalan) <= strtotime($tanggal_akhir)) {
        //         $hari=date('D',strtotime(  $tgl_berjalan));
        //         if($hari=='Sun'){
        //             $kode_hari='6';
        //             $nama_hari='Minggu';
        //             $status_absen=null;
        //             $mulai_jam_kerja=null;
        //             $akhir_jam_kerja=null;
        //         }else if($hari=='Mon'){
        //             $mulai_jam_kerja=$value->mulai_jam_kerja;
        //             $akhir_jam_kerja=$value->akhir_jam_kerja;
        //             $kode_hari='0';
        //             $nama_hari='Senin';
        //             $status_absen='M';
        //         }else if($hari=='Tue'){
        //             $mulai_jam_kerja=$value->mulai_jam_kerja;
        //             $akhir_jam_kerja=$value->akhir_jam_kerja;
        //             $kode_hari='1';
        //             $nama_hari='Selasa';
        //             $status_absen='M';
        //         }else if($hari=='Wed'){
        //             $mulai_jam_kerja=$value->mulai_jam_kerja;
        //             $akhir_jam_kerja=$value->akhir_jam_kerja;
        //             $kode_hari='2';
        //             $nama_hari='Rabu';
        //             $status_absen='M';
        //         }else if($hari=='Thu'){
        //             $mulai_jam_kerja=$value->mulai_jam_kerja;
        //             $akhir_jam_kerja=$value->akhir_jam_kerja;
        //             $kode_hari='3';
        //             $nama_hari='Kamis';
        //             $status_absen='M';
        //         }else if($hari=='Fri'){
        //             $mulai_jam_kerja=$value->mulai_jam_kerja;
        //             $akhir_jam_kerja=$value->akhir_jam_kerja;
        //             $kode_hari='4';
        //             $nama_hari='Jumat';
        //             $status_absen='M';
        //         }else if($hari=='Sat'){
        //             $kode_hari='5';
        //             $nama_hari='Sabtu';
        //             $status_absen=null;
        //             $mulai_jam_kerja=null;
        //             $akhir_jam_kerja=null;
        //         }
        //         $x=[
        //             'uuid' => Str::uuid('uuid'.$key),
        //             'enroll_id' => $value->enroll_id,
        //             'tanggal_berjalan' => $tgl_berjalan,
        //             'kode_hari' =>$kode_hari,
        //             'nama_hari' => $nama_hari,
        //             'mulai_jam_kerja' => $mulai_jam_kerja,
        //             'akhir_jam_kerja' => $akhir_jam_kerja,
        //             'status_absen' => $status_absen,
        //             'operator' =>'system',
        //         ];
        //         MasterDataAbsenKehadiran::create($x);
        //         $tgl_berjalan = date ("Y-m-d", strtotime("+1 day", strtotime($tgl_berjalan)));

        //     }
        // }
        // dd('successaga');

    }

    public function add_cepet(){
        $employee_id = time();
        EmployeeAtribut::create([
            'employee_id' => $employee_id,
            'employee_name' => 'HANDAYANI',
            'department_name' => 'STEAM',
            'sub_dept_name' => 'STEAM',
            'enroll_id' => '5885',
            'join_date' => '2023-10-31',
            'nik' => 'NAG235885',
        ]);
        dd('success : ',$employee_id);
    }

    public function import_employees(Request $request)
    {
        $data=Excel::toArray([],$request->file('excel_file'));
        $arrayEmployee=[];
        for($i=5;$i<count($data[0]);$i++){
            $department=DepartmentAll::where('status','AKTIF')->where('site_nirwana_id','NAG')->where('department_name',$data[0][$i][9])->where('sub_dept_name',$data[0][$i][11])->count();
            $employee=EmployeeAtribut::where('enroll_id',$data[0][$i][1])->count();

            if($department==0 && ($employee==1 || $employee==0)){
                $status_department='red';
            }else{
                if($employee==0){
                    $status_department='lightblue';
                }else{
                    $status_department='white';
                }
            }
            if($data[0][$i][1]==''){
                continue;
            }
            $arrayEmployee[$i]=[
                'enroll_id'=>$data[0][$i][1],
                'nik'=>$data[0][$i][2],
                'nama_karyawan'=>$data[0][$i][3],
                'jenis_kelamin'=>$data[0][$i][4],
                'jabatan'=>$data[0][$i][7],
                'department'=>$data[0][$i][9],
                'bagian'=>$data[0][$i][11],
                'status_aktif'=>$data[0][$i][14],
                'tanggal_masuk'=>$data[0][$i][15],
                'status_department'=>$status_department
            ];
        }
        $arrEmp=[];
        $no=0;
        foreach($arrayEmployee as $key=>$value){
            $no++;
            $arrEmp[$no]=[
                'enroll_id'=>$value['enroll_id'],
                'nik'=>$value['nik'],
                'nama_karyawan'=>$value['nama_karyawan'],
                'jenis_kelamin'=>$value['jenis_kelamin'],
                'jabatan'=>$value['jabatan'],
                'department'=>$value['department'],
                'bagian'=>$value['bagian'],
                'status_aktif'=>$value['status_aktif'],
                'tanggal_masuk'=>$value['tanggal_masuk'],
                'status_department'=>$value['status_department']
            ];
        }
        return $arrEmp;
    }

    public function import_employee_to_database(Request $request){
        ini_set("max_execution_time", 0);
        ini_set("max_input_time", 0);
        $data=Excel::toArray([],$request->file('excel_file'));
        Excel::import(new EmployeeImport, request()->file('excel_file'));
    }

    public function uploadEmployee(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');
        $data=Excel::toArray([],$request->file('excel_file'));
        $arrayData=[];
        for($i=5;$i<count($data[0]);$i++){
            if($data[0][$i][44]==null||$data[0][$i][44]=='NEW 2401'){
                $tanggal_bpjs_ketenagakerjaan=null;
            }else{
                $tanggal_bpjs_ketenagakerjaan=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][44])->format('Y-m-d');
            }
            if($data[0][$i][47]==null||$data[0][$i][47]=='NEW 2401'){
                $tanggal_bpjs_kesehatan=null;
            }else{
                $tanggal_bpjs_kesehatan=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][47])->format('Y-m-d');
            }
            if($data[0][$i][11]==null){
                $join_date=null;
            }else{
                $join_date=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][11])->format('Y-m-d');
            }
            if($data[0][$i][12]==null){
                $tanggal_resign=null;
            }else{
                $tanggal_resign=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][12])->format('Y-m-d');
            }
            if($data[0][$i][54]==null){
                $tanggal_vaccine1=null;
            }else{
                $tanggal_vaccine1=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][54])->format('Y-m-d');
            }
            if($data[0][$i][56]==null){
                $tanggal_vaccine2=null;
            }else{
                $tanggal_vaccine2=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][56])->format('Y-m-d');
            }
            if($data[0][$i][58]==null){
                $tanggal_vaccine3=null;
            }else{
                $tanggal_vaccine3=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][58])->format('Y-m-d');
            }
            if($data[0][$i][62]==null){
                $tanggal_expire_sim=null;
            }else{
                $tanggal_expire_sim=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][62])->format('Y-m-d');
            }
            if($data[0][$i][64]==null){
                $tanggal_mulai_kontrak=null;
            }else{
                $tanggal_mulai_kontrak=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][64])->format('Y-m-d');
            }
            if($data[0][$i][65]==null){
                $tanggal_akhir_kontrak=null;
            }else{
                $tanggal_akhir_kontrak=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][65])->format('Y-m-d');
            }
            if($data[0][$i][16]==null){
                $tanggal_lahir=null;
            }else{
                $tanggal_lahir=\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][16])->format('Y-m-d');
            }
            EmployeeAtribut::where('enroll_id',$data[0][$i][1])->update([
                'nik'=>$data[0][$i][2],
                'employee_name'=>$data[0][$i][3],
                'jenis_kelamin'=>$data[0][$i][4],
                'status_jabatan'=>$data[0][$i][5],
                'department_id'=>$data[0][$i][6],
                'department_name'=>$data[0][$i][7],
                'sub_dept_id'=>$data[0][$i][8],
                'sub_dept_name'=>$data[0][$i][9],
                'status_aktif'=>$data[0][$i][10],
                'join_date'=>$join_date,
                'tanggal_resign'=>$tanggal_resign,
                'status_kontrak_tetap'=>$data[0][$i][13],
                'status_staff'=>$data[0][$i][14],
                'tempat_lahir'=>$data[0][$i][15],
                'tanggal_lahir'=>$tanggal_lahir,
                'agama'=>$data[0][$i][17],
                'ibu_kandung'=>$data[0][$i][18],
                'status_kawin'=>$data[0][$i][19],
                'ptkp'=>$data[0][$i][20],
                'npwp'=>$data[0][$i][21],
                'nomor_ktp'=>$data[0][$i][22],
                'nomor_kk'=>$data[0][$i][23],
                'golongan_darah'=>$data[0][$i][24],
                'nomor_tlpn'=>$data[0][$i][25],
                'email'=>$data[0][$i][26],
                'pendidikan_terakhir'=>$data[0][$i][27],
                'jurusan_pendidikan'=>$data[0][$i][28],
                'nama_bank'=>$data[0][$i][29],
                'nomor_rekening_bank'=>$data[0][$i][30],
                'alamat_rumah'=>$data[0][$i][31],
                'propinsi'=>$data[0][$i][32],
                'kota_kab'=>$data[0][$i][33],
                'kecamatan'=>$data[0][$i][34],
                'kelurahan_desa'=>$data[0][$i][35],
                'alamat_sementara'=>$data[0][$i][36],
                'tunjangan'=>$data[0][$i][37],
                'kode_grade'=>$data[0][$i][38],
                'referensi'=>$data[0][$i][41],
                'employee_name_atasan'=>$data[0][$i][42],
                'status_aktif_bpjs_tk'=>$data[0][$i][43],
                'tanggal_bpjs_ketenagakerjaan'=>$tanggal_bpjs_ketenagakerjaan,
                'nomor_bpjs_ketenagakerjaan'=>$data[0][$i][45],
                'status_aktif_bpjs_ks'=>$data[0][$i][46],
                'tanggal_bpjs_kesehatan'=>$tanggal_bpjs_kesehatan,
                'nomor_bpjs_kesehatan'=>$data[0][$i][48],
                'pengalaman_bekerja'=>$data[0][$i][49],
                'nama_kerabat'=>$data[0][$i][50],
                'nomor_tlpn_kerabat'=>$data[0][$i][51],
                'hubungan_kerabat'=>$data[0][$i][52],
                'alamat_kerabat'=>$data[0][$i][53],
                'tanggal_vaccine1'=>$tanggal_vaccine1,
                'nama_vaksin1'=>$data[0][$i][55],
                'tanggal_vaccine2'=>$tanggal_vaccine2,
                'nama_vaksin2'=>$data[0][$i][57],
                'tanggal_vaccine3'=>$tanggal_vaccine3,
                'nama_vaksin3'=>$data[0][$i][59],
                'golongan_sim'=>$data[0][$i][60],
                'nomor_sim'=>$data[0][$i][61],
                'tanggal_expire_sim'=>$tanggal_expire_sim,
                'catatan'=>$data[0][$i][63],
                'tanggal_mulai_kontrak'=>$tanggal_mulai_kontrak,
                'tanggal_akhir_kontrak'=>$tanggal_akhir_kontrak,
                'catatan_kontrak'=>$data[0][$i][66],
            ]);
        }
        return back()->with("success", 'Data berhasil di update');

        // $loggedAdmin = Auth::guard('admin')->user();
        // $operator = $loggedAdmin->email;
        // $data=Excel::toArray([],$request->file('excel_file'));
        // $enroll_id=[];
        // $allEnrollId=[];
        // $allEnrollId1=[];
        // for($i=5;$i<count($data[0]);$i++){
        //     array_push($enroll_id,$data[0][$i][1]);
        //     array_push($allEnrollId,$data[0][$i]);
        //     array_push($allEnrollId1,$data[0][$i]);
        // }
        // $arrayDeptName=DepartmentAll::where('site_nirwana_id','NAG')->pluck('department_name')->toArray();
        // $arraySubDeptName=DepartmentAll::where('site_nirwana_id','NAG')->pluck('sub_dept_name')->toArray();
        // $arrayDifSubDepartment=[];
        // $arrDifSubDept=[];
        // $arrayDifSubDept=[];
        // $no1=0;
        // foreach($allEnrollId1 as $key=>$value){
        //     if((in_array($allEnrollId1[$key][7],$arrayDeptName) && in_array($allEnrollId1[$key][9],$arraySubDeptName)) || $allEnrollId1[$key][7]=='' || $allEnrollId1[$key][9]=='' ){
        //         continue;
        //     }
        //     $no1++;
        //     $arrayDifSubDept[$no1]=$allEnrollId1[$key][9];
        //     $arrayDifSubDepartment[$no1]=['department_name'=>$value[7],'sub_department_name'=>$allEnrollId1[$key][9]];
        // }
        // $arrDifSubDept=array_unique($arrayDifSubDept);
        // $last_dept_id=substr(DepartmentAll::where('site_nirwana_id','NAG')->orderBy('department_id','DESC')->limit(1)->pluck('department_id')[0],3,5);
        // $no2=0;
        // $z=[];
        // foreach($arrDifSubDept as $key=>$value){
        //     $no2++;
        //     $y=[
        //         'department_name'=>$arrayDifSubDepartment[array_search($value, array_column($arrayDifSubDepartment, 'sub_department_name'))+1]['department_name'],
        //         'sub_department_name'=>$value
        //     ];
        //     $department_id=DepartmentAll::where('department_name',$y['department_name'])->where('site_nirwana_id','NAG')->orderBy('department_id','DESC')->limit(1)->pluck('department_id');
        //     $sub_dept_id=DepartmentAll::where('department_name',$y['department_name'])->where('site_nirwana_id','NAG')->orderBy('department_id','DESC')->limit(1)->pluck('sub_dept_id');
        //     if(isset($department_id[0])){
        //         $dept_id=$department_id[0];
        //     }else{
        //         $dept_id='DEP'.strval($last_dept_id+$no2);
        //     }
        //     if(isset($sub_dept_id[0])){
        //         $subdept_id=$dept_id.'SUB'.sprintf("%03d",intval(substr(DepartmentAll::where('site_nirwana_id','NAG')->where('department_id',$dept_id)->orderBy('sub_dept_id','DESC')->limit(1)->pluck('sub_dept_id')[0],8,9)+$no2));
        //     }else{
        //         $subdept_id=$dept_id.'SUB'.sprintf("%03d",$no2);
        //     }
        //     $z[$no2]=[
        //         'site_nirwana_id'=>'NAG',
        //         'site_nirwana_name'=>'PT. NIRWANA ALABARE GARMENT',
        //         'department_id'=>$dept_id,
        //         'department_name'=>$y['department_name'],
        //         'sub_dept_id'=>$subdept_id,
        //         'sub_dept_name'=>$y['sub_department_name']
        //     ];
        // }
        // $difZ=[];
        // foreach($z as $key=>$value){
        //     $difZ[$key]=$value['department_name'];
        // }
        // $DifZ=array_unique($difZ);
        // $arrayDifZ=[];
        // foreach($DifZ as $key=>$value){
        //     $arrayDifZ[$key]=[
        //         'site_nirwana_id'=>'NAG',
        //         'site_nirwana_name'=>'PT. NIRWANA ALABARE GARMENT',
        //         'department_id'=>$z[array_search($value, array_column($z, 'department_name'))+1]['department_id'],
        //         'department_name'=>$z[array_search($value, array_column($z, 'department_name'))+1]['department_name'],
        //         'sub_dept_id'=>$z[array_search($value, array_column($z, 'department_name'))+1]['sub_dept_id'],
        //         'sub_dept_name'=>$z[array_search($value, array_column($z, 'department_name'))+1]['sub_dept_name'],
        //     ];
        // }
        // $valueos=[];
        // foreach($arrayDifZ as $key=>$value){
        //     $valueos[$key]=$value;
        //     DepartmentAll::create($valueos[$key]);
        // }
        // $difInSubDept=[];
        // foreach($arrayDifZ as $key=>$value){
        //     $difInSubDept[$key]=$value['sub_dept_name'];
        // }
        // $difInSubDepts=[];
        // $no3=0;
        // foreach($z as $key=>$value){
        //     if(in_array($z[$key]['sub_dept_name'],$difInSubDept)){
        //         continue;
        //     }
        //     $no3++;
        //     $difInSubDepts[$no3]=['department_name'=>$value['department_name'],'sub_dept_name'=>$value['sub_dept_name']];
        // }
        // $w=[];
        // foreach($difInSubDepts as $key=>$value){
        //     $w[$key]=([
        //         'site_nirwana_id'=>'NAG',
        //         'site_nirwana_name'=>'PT. NIRWANA ALABARE GARMENT',
        //         'department_id'=>DepartmentAll::where('site_nirwana_id','NAG')->where('department_name',$difInSubDepts[$key]['department_name'])->pluck('department_id')[0],
        //         'department_name'=>$difInSubDepts[$key]['department_name'],
        //         'sub_dept_id'=>DepartmentAll::where('site_nirwana_id','NAG')->where('department_name',$difInSubDepts[$key]['department_name'])->pluck('department_id')[0].'SUB'.sprintf("%03d",intval(substr(DepartmentAll::where('site_nirwana_id','NAG')->where('department_name',$difInSubDepts[$key]['department_name'])->orderBy('sub_dept_id','DESC')->limit(1)->pluck('sub_dept_id')[0],8,9))+$key),
        //         'sub_dept_name'=>$difInSubDepts[$key]['sub_dept_name']
        //     ]);
        // }
        // $valueosa=[];
        // foreach($w as $key=>$value){
        //     $valueosa[$key]=$value;
        //     DepartmentAll::create($valueosa[$key]);
        // }
        // $arrayEnrollId=EmployeeAtribut::pluck('enroll_id')->toArray();
        // $arrayDiferent=[];
        // $no=0;
        // foreach($allEnrollId as $key=>$value){
        //     if(in_array($allEnrollId[$key][1],$arrayEnrollId)){
        //         continue;
        //     }
        //     $no++;
        //     $arrayDiferent[$no]=$value;
        // }
        // $tgl_berjalan=[];
        // $tanggal_akhir='';
        // $tanggal_sekarang=date('Y-m-d');
        // $bulan_sekarang=date('Y-m-'.'25');
        // $bulan_sebelum=date('Y-m-d',strtotime( "-1 month", strtotime( $bulan_sekarang ) ));
        // $bulan_setelah=date('Y-m-d',strtotime( "+1 month", strtotime( $bulan_sekarang ) ));
        // if($tanggal_sekarang>$bulan_sebelum && $tanggal_sekarang<=$bulan_sekarang){
        //     $tanggal_akhir=$bulan_sekarang;
        // }else if($tanggal_sekarang>$bulan_sekarang && $tanggal_sekarang<=$bulan_setelah){
        //     $tanggal_akhir=$bulan_setelah;
        // }
        // $mulai_jam_kerja='';
        // $akhir_jam_kerja='';
        // $mulai='';
        // $akhir='';
        // foreach($arrayDiferent as $key=>$all){
        //     $tanggal_akhir=$tanggal_akhir;
        //     $tgl_berjalan=date('Y-m-d',($all[9] - 25569) * 86400);
        //     while (strtotime( $tgl_berjalan) <= strtotime($tanggal_akhir)) {
        //         $hari=date('D',strtotime(  $tgl_berjalan));
        //         $staffnonstaff=$all[14];
        //         if($hari=='Sun'){
        //             $kode_hari='6';
        //             $nama_hari='Minggu';
        //             $status_absen=null;
        //             $mulai_jam_kerja=null;
        //             $akhir_jam_kerja=null;
        //         }else if($hari=='Mon'){
        //             $kode_hari='0';
        //             $nama_hari='Senin';
        //             $status_absen='M';
        //             if($staffnonstaff=='STAFF'){
        //                 $mulai_jam_kerja='07:30:00';
        //                 $akhir_jam_kerja='17:30:00';
        //             }else if($staffnonstaff=='NON STAFF'){
        //                 $mulai_jam_kerja='07:00:00';
        //                 $akhir_jam_kerja='16:00:00';
        //             }else{
        //                 $mulai_jam_kerja=null;
        //                 $akhir_jam_kerja=null;
        //             }
        //         }else if($hari=='Tue'){
        //             $kode_hari='1';
        //             $nama_hari='Selasa';
        //             $status_absen='M';
        //             if($staffnonstaff=='STAFF'){
        //                 $mulai_jam_kerja='07:30:00';
        //                 $akhir_jam_kerja='17:30:00';
        //             }else if($staffnonstaff=='NON STAFF'){
        //                 $mulai_jam_kerja='07:00:00';
        //                 $akhir_jam_kerja='16:00:00';
        //             }else{
        //                 $mulai_jam_kerja=null;
        //                 $akhir_jam_kerja=null;
        //             }
        //         }else if($hari=='Wed'){
        //             $kode_hari='2';
        //             $nama_hari='Rabu';
        //             $status_absen='M';
        //             if($staffnonstaff=='STAFF'){
        //                 $mulai_jam_kerja='07:30:00';
        //                 $akhir_jam_kerja='17:30:00';
        //             }else if($staffnonstaff=='NON STAFF'){
        //                 $mulai_jam_kerja='07:00:00';
        //                 $akhir_jam_kerja='16:00:00';
        //             }else{
        //                 $mulai_jam_kerja=null;
        //                 $akhir_jam_kerja=null;
        //             }
        //         }else if($hari=='Thu'){
        //             $kode_hari='3';
        //             $nama_hari='Kamis';
        //             $status_absen='M';
        //             if($staffnonstaff=='STAFF'){
        //                 $mulai_jam_kerja='07:30:00';
        //                 $akhir_jam_kerja='17:30:00';
        //             }else if($staffnonstaff=='NON STAFF'){
        //                 $mulai_jam_kerja='07:00:00';
        //                 $akhir_jam_kerja='16:00:00';
        //             }else{
        //                 $mulai_jam_kerja=null;
        //                 $akhir_jam_kerja=null;
        //             }
        //         }else if($hari=='Fri'){
        //             $kode_hari='4';
        //             $nama_hari='Jumat';
        //             $status_absen='M';
        //             if($staffnonstaff=='STAFF'){
        //                 $mulai_jam_kerja='07:30:00';
        //                 $akhir_jam_kerja='17:30:00';
        //             }else if($staffnonstaff=='NON STAFF'){
        //                 $mulai_jam_kerja='07:00:00';
        //                 $akhir_jam_kerja='16:00:00';
        //             }else{
        //                 $mulai_jam_kerja=null;
        //                 $akhir_jam_kerja=null;
        //             }
        //         }else if($hari=='Sat'){
        //             $kode_hari='5';
        //             $nama_hari='Sabtu';
        //             $status_absen=null;
        //             $mulai_jam_kerja=null;
        //             $akhir_jam_kerja=null;
        //         }
        //         $department_id='';
        //         $sub_dept_id='';
        //         $department=DepartmentAll::where('sub_dept_name',$all[9])->where('site_nirwana_id','NAG')->get();
        //         foreach($department as $dept){
        //             $department_id=$dept->department_id;
        //             $sub_dept_id=$dept->sub_dept_id;
        //         }
        //         $x=[
        //             'uuid' => rand().$all[1],
        //             'tanggal_berjalan' => $tgl_berjalan,
        //             'kode_hari' =>$kode_hari,
        //             'nama_hari' => $nama_hari,
        //             'mulai_jam_kerja'=>$mulai_jam_kerja,
        //             'akhir_jam_kerja'=>$akhir_jam_kerja,
        //             'employee_id' => rand().$all[1].$key,
        //             'employee_name' => $all[3],
        //             'enroll_id' => $all[1],
        //             'join_date' => $all[11]?date('Y-m-d',($all[11] - 25569) * 86400):null,
        //             'tanggal_resign' => $all[12]?date('Y-m-d',$all[12]):null,
        //             'work_status' => null,
        //             'status_aktif' => $all[10],
        //             'status_kontrak_tetap' => $all[13],
        //             'employee_status' => null,
        //             'status_jabatan' => $all[5],
        //             'posisi_name' => null,
        //             'status_staff' => $all[14],
        //             'nik' => $all[2],
        //             'site_nirwana_id' => 'NAG',
        //             'site_nirwana_name' => 'PT. NIRWANA ALABARE GARMENT',
        //             'department_id' => $department_id,
        //             'department_name' => $all[7],
        //             'sub_dept_id' => $sub_dept_id,
        //             'sub_dept_name' => $all[9],
        //             'status_absen' => $status_absen,
        //             'operator' =>$operator,
        //         ];
        //         MasterDataAbsenKehadiran::create($x);
        //         $tgl_berjalan = date ("Y-m-d", strtotime("+1 day", strtotime($tgl_berjalan)));
        //     }
        // }
        // try{
        //     Excel::import(new EmployeeImport, $request->excel_file);
        //     return redirect()->back()->with('status', 'Data Karyawan berhasil di import');
        // }catch(\Exception $e){
        //     return redirect()->back()->with('status', 'Data Karyawan berhasil di import ');
        // }
        // info($operator.' telah mengimport data excel untuk menambah atau mengupdate data karyawan');
    }

    public function export_excel(){
        $random=rand();
        return Excel::download(new newEmployeeExport, 'all_employee_'.$random.'.xlsx');
    }

    public function destroy(Request $request)
    {
        $enroll_id = $request->enroll_id;

        $findDT = EmployeeAtribut::where('enroll_id','=', $enroll_id)->count();

        if($findDT > 0) {
            $query = EmployeeAtribut::where('enroll_id','=',$enroll_id)->delete();
        } else {
            $query = false;
        }

        return $query;
    }

    public function ajax_exportexcel(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4000M');

        $fileName = 'DataKaryawan_' . time() . '.xlsx';
        return (new EmployeeAtrExport)->exportParams()->download($fileName);

    }

    //=============Andri====================
    public function format_import_grading()
    {
        $filepath = public_path('format_import/format_grading_bpjs.xlsx');
        return Response()->download($filepath);
    }

    public function import_grading(Request $request)
    {
        try{
            $data=Excel::toArray([],$request->file('file_import'));
            $data_update=[];
            $head=$data[0][0];
            if($head[0]=='NO. ABSEN' && $head[1]=='NIP' && $head[2]=='NAMA KARYAWAN' && $head[3]=='PAY BPJS TK' && $head[4]=='PAY BPJS KS' &&
                $head[5]=='NOMOR BPJS(TK)' && $head[6]=='TANGGAL KEPESERTAAN (TK)' && $head[7]=='NOMOR BPJS (KS)' && $head[8]=='TANGGAL KEPESERTAAN (KS)' &&
                $head[9]=='KODE GRADE'){
                foreach ($data[0] as $key => $row) {
                    if($key>0){
                        $error=$key;
                        if($row[6]!='-'&& $row[6]!=null && $row[6]!=''){
                            $tgl_tk =$row[6];
                            $tgl_tk = ($tgl_tk - 25569) * 86400;
                            $tgl_tk = 25569 + ($tgl_tk / 86400);
                            $tgl_tk = ($tgl_tk - 25569) * 86400;
                            $tgl_kep_tk=date('Y-m-d', $tgl_tk);
                        }
                        else{
                            $tgl_kep_tk=null;
                        }
                        if($row[8]!='-' && $row[8]!=null){
                            $tgl_ks =$row[8];
                            $tgl_ks = ($tgl_ks - 25569) * 86400;
                            $tgl_ks = 25569 + ($tgl_ks / 86400);
                            $tgl_ks = ($tgl_ks - 25569) * 86400;
                            $tgl_kep_ks=date('Y-m-d', $tgl_ks);
                        }
                        else{
                            $tgl_kep_ks=null;
                        }
                        if($row[13]!='-' && $row[13]!=null){
                            $tgl_resign =$row[13];
                            $tgl_resign = ($tgl_resign - 25569) * 86400;
                            $tgl_resign = 25569 + ($tgl_resign / 86400);
                            $tgl_resign = ($tgl_resign - 25569) * 86400;
                            $tanggal_resign=date('Y-m-d', $tgl_resign);
                        }
                        else{
                            $tanggal_resign=null;
                        }
                        $data_update[]=[
                            'enroll_id'=>$row[0],
                            'tanggal_bpjs_ketenagakerjaan'=>$tgl_kep_tk,
                            'nomor_bpjs_ketenagakerjaan'=>$row[5],
                            'status_aktif_bpjs_tk'=>$row[3],
                            'tanggal_bpjs_kesehatan'=>$tgl_kep_ks,
                            'nomor_bpjs_kesehatan'=>$row[7],
                            'status_aktif_bpjs_ks'=>$row[4],
                            'kode_grade'=>$row[9],
                            'nama_bank'=>$row[10],
                            'nomor_rekening_bank'=>$row[11],
                            'status_aktif'=>$row[12],
                            'tanggal_resign'=>$tanggal_resign,
                        ];
                    }
                }

                foreach ($data_update as $key1 => $value1) {
                    $update=[
                        'tanggal_bpjs_ketenagakerjaan'=>$value1['tanggal_bpjs_ketenagakerjaan'],
                        'nomor_bpjs_ketenagakerjaan'=>$value1['nomor_bpjs_ketenagakerjaan'],
                        'status_aktif_bpjs_tk'=>$value1['status_aktif_bpjs_tk'],
                        'tanggal_bpjs_kesehatan'=>$value1['tanggal_bpjs_kesehatan'],
                        'nomor_bpjs_kesehatan'=>$value1['nomor_bpjs_kesehatan'],
                        'status_aktif_bpjs_ks'=>$value1['status_aktif_bpjs_ks'],
                        'kode_grade'=>$value1['kode_grade'],
                        'nama_bank'=>$value1['nama_bank'],
                        'status_aktif'=>$value1['status_aktif'],
                        'tanggal_resign'=>$value1['tanggal_resign'],
                        'nomor_rekening_bank'=>$value1['nomor_rekening_bank']

                    ];
                    EmployeeAtribut::where('enroll_id',$value1['enroll_id'])->update($update);
                }
                $count=count($data_update);
                return back()->with("success", $count.' row berhasil di update');
            }
            else{
                return back()->with("error",'gagal tersimpan format salah');
            }
        }catch(\Exception $e){
            $error=$error+1;
            return back()->with("error",'gagal tersimpan terdapat kesalah di row '.$error);
        }
    }


    public function export_import(){
        $tanggal=date('Y-m-d');
        // $tanggal='2023-08-25';

        $tgl=date('d',strtotime( $tanggal));
        $bln_sekarang=date('M-Y',strtotime( $tanggal));
        $date = strtotime($bln_sekarang);
        $bulan_sebelum= strtotime("-1 month", $date);
        $bulan_sesudah= strtotime("+1 month", $date);
        $bulan_sebelum=date('M-Y', $bulan_sebelum);
        $bulan_sesudah=date('M-Y', $bulan_sesudah);

        // if($tgl>=26){
        //     $tanggal_awal_periode=date('Y-m-d', strtotime('26-'.$bln_sekarang));
        // }
        // elseif($tgl<26){
        //     $tanggal_awal_periode=date('Y-m-d', strtotime('26-'.$bulan_sebelum));
        // }

        $tanggal_awal_periode=date('Y-m-d', strtotime('26-'.$bulan_sebelum));
        //$karyawan=EmployeeAtribut::where('status_aktif','AKTIF')->ORwhere('tanggal_resign','>',$tanggal_awal_periode)->get();
       	$karyawan = EmployeeAtribut::where('status_aktif', 'AKTIF')
        ->orWhere('tanggal_resign', '>', $tanggal_awal_periode)
        ->orderByRaw("CAST(enroll_id AS UNSIGNED)")
        ->get();
        return Excel::download(new FormatImportBPJSExport($karyawan),'format_import_bpjs'.time().'.xlsx');
    }


}
