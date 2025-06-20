<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Events\TestEvent;
use Illuminate\Support\Facades\Storage;
Route::get('/base64', function(){
    $image = public_path('installer/img/pattern.png');;
    $img = \Image::make($image);
    return response()->make($img->encode($img->mime()), 200, array('Content-Type' => $img->mime(),'Cache-Control'=>'max-age=86400, public'));
});
# Employee Login
Route::get('/',['as'=>'front.login','uses'=>'Front\LoginController@index']);
Route::post('/login',['as'=>'login','uses'=>'Front\LoginController@ajaxLogin']);
Route::get('logout', ['as'=>'front.logout','uses'=>'Front\LoginController@logout']);

// contoh route yang bisa ini
//Route::get('logs',['as'=>'logs','uses'=>'Logs\LogsController@index']);
Route::group(['middleware' => ['auth.admin'], 'prefix' => 'logs','namespace' => 'Logs'], function()
{
    Route::get('data',['as'=>'logs.data','uses'=>'LogsController@index']);
    Route::post('ajax_logs',['as'=>'logs.ajax_logs','uses'=> 'LogsController@ajax_logs']);
});

# Employee Panel After Login
Route::group(['middleware' => ['auth.employees'],'namespace' => 'Front'], function()
{
    Route::get('/change_password_modal',['as'=>'front.change_password_modal','uses'=>'DashboardController@changePasswordModal']);
    Route::post('/change_password',['as'=>'front.change_password','uses'=>'DashboardController@change_password']);
    Route::get('ajaxApplications',['as'=>'front.leave_applications','uses'=> 'DashboardController@ajaxApplications']);

    Route::get('leave',['as'=>'front.leave','uses'=>'DashboardController@leave']);

    Route::post('dashboard/notice/{id}',['as'=>'front.notice_ajax','uses'=>'DashboardController@notice_ajax']);

    Route::post('leave_store',['as'=>'front.leave_store','uses'=>'DashboardController@leave_store']);

    Route::resource('dashboard','DashboardController');
});


# Admin Login
Route::group(['namespace' => 'Admin'], function()
{
    Route::get('/',['as'=>'admin.getlogin','uses'=>'AdminLoginController@index']);
    Route::get('logout',['as'=>'admin.logout','uses'=> 'AdminLoginController@logout']);
    Route::post('login',['as'=>'admin.login','uses'=> 'AdminLoginController@ajaxAdminLogin']);


});
Route::group(['middleware' => [ 'lock'], 'prefix' => 'hris','namespace' => 'Hris'], function()
{
    Route::get('identity/card_employee_form_identity',['as'=>'hris.hrd.card_employee_form_identity','uses'=>'HRDController@card_employee_form_identity']);
    // Route::get('identity/export_pdf_sk_kerja',['as'=>'hris.hrd.export_pdf_sk_kerja','uses'=>'HRDController@export_pdf_sk_kerja']);
    // Route::get('identity/export_pdf_paklaring',['as'=>'hris.hrd.export_pdf_paklaring','uses'=>'HRDController@export_pdf_paklaring']);
});









Route::fallback(function () {
    return response()->view('errors.403', [], 404);
});










// SEDANG DALAM PERBAIKAN PRIVATE ROUTE

// DASHBOARD MENU UTAMA
Route::group(['middleware' => ['auth.admin', 'lock'], 'prefix' => 'hris','namespace' => 'Hris'], function()
{
    Route::get('dashboard',['as'=>'hris.dashboard.tes','uses'=>'DashboardController@tes']);
});


// PAYROLL & ATTANDANCE
Route::group(['middleware' => ['auth.admin', 'lock','role:all,attendance_payroll_general_affair_administrasi,attendance_payroll'], 'prefix' => 'hris','namespace' => 'Hris'], function()
{
    Route::get('dashboard/index',['as'=>'hris.dashboard.index','uses'=>'DashboardController@index']);
    Route::get('gradingsalary/index',['as'=>'hris.gradingsalary.index','uses'=>'GradingSalaryController@index']);
    Route::get('employeegrading/index',['as'=>'hris.employeegrading.index','uses'=>'EmployeeGradingController@index']);
    Route::get('tunjangankaryawan/index',['as'=>'hris.tunjangankaryawan.index','uses'=>'TunjanganKaryawanController@index']);
    Route::get('rekapperhitungandtpc/index',['as'=>'hris.rekapperhitungandtpc.index','uses'=>'RekapPerhitunganDtpcController@index']);
    Route::get('rekapperhitunganiks/index',['as'=>'hris.rekapperhitunganiks.index','uses'=>'RekapPerhitunganIksController@index']);
    Route::get('rekapperhitunganpayroll/index',['as'=>'hris.rekapperhitunganpayroll.index','uses'=>'RekapPerhitunganPayrollController@index']);
    Route::get('dataclosingpayroll/index',['as'=>'hris.dataclosingpayroll.index','uses'=>'DataClosingPayrollController@index']);
    Route::get('aktifitasperubahan/index',['as'=>'hris.aktifitasperubahan.index','uses'=>'AktifitasPerubahan@index']);
    Route::get('employeeatr/index',['as'=>'hris.employeeatr.index','uses'=>'EmployeeAtrController@index']);
    Route::get('mdabsenhadir/datahadir',['as'=>'hris.mdabsenhadir.datahadir','uses'=>'MdAbsenHadirController@datahadir']);
    Route::get('datalembur/index/',['as'=>'hris.datalembur.index','uses'=> 'DataLemburController@index']);
    Route::get('datalembur/add_datalembur/',['as'=>'hris.datalembur.add_datalembur','uses'=> 'DataLemburController@add_datalembur']);
    Route::get('koreksiupah/index',['as'=>'hris.koreksiupah.index','uses'=>'KoreksiUpahController@index']);
    Route::get('koreksipotongan/index',['as'=>'hris.koreksipotongan.index','uses'=>'KoreksiPotonganController@index']);
    Route::get('gagalabsen/index/',['as'=>'hris.gagalabsen.index','uses'=> 'GagalAbsenController@index']);
    Route::get('dataabsenperijinan/index',['as'=>'hris.dataabsenperijinan.index','uses'=>'DataAbsenPerijinanController@index']);
    Route::get('rekapperhitunganlembur/index',['as'=>'hris.rekapperhitunganlembur.index','uses'=>'RekapPerhitunganLemburController@index']);
    Route::get('dasarpotbpjs/index',['as'=>'hris.dasarpotbpjs.index','uses'=>'DasarPotBpjsController@index']);
    Route::get('bpjssetting/index',['as'=>'hris.bpjssetting.index','uses'=>'BpjsSettingController@index']);
    Route::get('employeebpjs/index',['as'=>'hris.employeebpjs.index','uses'=>'EmployeeBpjsController@index']);
    Route::get('refabsenijin/index',['as'=>'hris.refabsenijin.index','uses'=>'RefAbsenIjinController@index']);
    Route::get('refharilibur/index',['as'=>'hris.refharilibur.index','uses'=>'RefHariLiburController@index']);
    Route::get('datajadwalkerjalog/index',['as'=>'hris.datajadwalkerjalog.index','uses'=>'DataJadwalKerjaLogController@index']);
    Route::get('departmentall/index',['as'=>'hris.departmentall.index','uses'=>'DepartmentAllController@index']);
    Route::get('/cuti_karyawan', ['as' => 'cuti_karyawan.index','uses' => 'MasterData\CutiKaryawanController@index']);



});

// KEPERSONALIAAN / PENILAIAN KINERJA

Route::group(['middleware' => ['auth.admin', 'lock','role:all'], 'prefix' => 'hris','namespace' => 'Hris'], function()
{
    Route::get('hrd/index',['as'=>'hris.hrd.index','uses'=>'HRDController@index']);
    Route::get('hrd/kontrak_kerja',['as'=>'hris.hrd.kontrak_kerja','uses'=>'HRDController@kontrak_kerja']);
    Route::get('hrd/layoff_termination',['as'=>'hris.hrd.layoff_termination','uses'=>'HRDController@layoff_termination']);

});

Route::group(['middleware' => ['auth.admin', 'lock'], 'prefix' => 'hris','namespace' => 'Hris'], function()
{
    Route::get('penilaian_kinerja_staff/get_employee_contract_staff',['as'=>'hris.penilaian_kinerja_staff.get_employee_contract_staff','uses'=>'PenilaianKinerjaStaffController@get_employee_contract_staff']);
    Route::post('penilaian_kinerja_staff/get_employee_contract_staff_by_id',['as'=>'hris.penilaian_kinerja_staff.get_employee_contract_staff_by_id','uses'=>'PenilaianKinerjaStaffController@get_employee_contract_staff_by_id']);
    Route::post('penilaian_kinerja_staff/store_penilaian_kinerja_staff',['as'=>'hris.penilaian_kinerja_staff.store_penilaian_kinerja_staff','uses'=>'PenilaianKinerjaStaffController@store_penilaian_kinerja_staff']);
    Route::put('penilaian_kinerja_staff/update_penilaian_kinerja_staff/{id}',['as'=>'hris.penilaian_kinerja_staff.update_penilaian_kinerja_staff','uses'=>'PenilaianKinerjaStaffController@update_penilaian_kinerja_staff']);
    Route::post('penilaian_kinerja_staff/import_penilaian_kinerja_staff',['as'=>'hris.penilaian_kinerja_staff.import_penilaian_kinerja_staff','uses'=>'PenilaianKinerjaStaffController@import_penilaian_kinerja_staff']);
    Route::post('penilaian_kinerja_staff/import_rencana_adjustment_sallary',['as'=>'hris.penilaian_kinerja_staff.import_rencana_adjustment_sallary','uses'=>'PenilaianKinerjaStaffController@import_rencana_adjustment_sallary']);
    Route::post('penilaian_kinerja_staff/import_penilaian_kinerja_staff_to_database',['as'=>'hris.penilaian_kinerja_staff.import_penilaian_kinerja_staff_to_database','uses'=>'PenilaianKinerjaStaffController@import_penilaian_kinerja_staff_to_database']);
    Route::post('penilaian_kinerja_staff/import_rencana_adjustment_sallary_to_database',['as'=>'hris.penilaian_kinerja_staff.import_rencana_adjustment_sallary_to_database','uses'=>'PenilaianKinerjaStaffController@import_rencana_adjustment_sallary_to_database']);
});



Route::group(['middleware' => ['auth.admin', 'lock'], 'prefix' => 'hris','namespace' => 'Hris'], function()
{
    Route::get('hrd/sp_hadir',['as'=>'hris.hrd.sp_hadir','uses'=>'HRDController@sp_hadir']);
    Route::post('hrd/tandai_sp_kerja',['as'=>'hris.hrd.tandai_sp_kerja','uses'=>'HRDController@tandai_sp_kerja']);
    Route::get('hrd/sp_hadir_adjustment',['as'=>'hris.hrd.sp_hadir_adjustment','uses'=>'HRDController@sp_hadir_adjustment']);
    Route::get('hrd/export_sp_kehadiran_karyawan',['as'=>'hris.hrd.export_sp_kehadiran_karyawan','uses'=>'HRDController@export_sp_kehadiran_karyawan']);
    Route::get('hrd/export_sp_kehadiran_karyawan_adjustment',['as'=>'hris.hrd.export_sp_kehadiran_karyawan_adjustment','uses'=>'HRDController@export_sp_kehadiran_karyawan_adjustment']);
    Route::get('hrd/export_rekap_hadir_layoff',['as'=>'hris.hrd.export_rekap_hadir_layoff','uses'=>'HRDController@export_rekap_hadir_layoff']);
    Route::get('hrd/export_pdf_sk_kerja',['as'=>'hris.hrd.export_pdf_sk_kerja','uses'=>'HRDController@export_pdf_sk_kerja']);
    Route::get('hrd/export_pdf_paklaring',['as'=>'hris.hrd.export_pdf_paklaring','uses'=>'HRDController@export_pdf_paklaring']);

    Route::get('hrd/export_penilaian_kinerja_staff_pdf', ['as' => 'hris.hrd.export_penilaian_kinerja_staff_pdf','uses' => 'PenilaianKinerjaStaffController@export_penilaian_kinerja_staff_pdf']);
    Route::get('hrd/download_excel_penilaian_kinerja_nonstaff',['as'=>'hris.hrd.download_excel_penilaian_kinerja_nonstaff','uses'=>'PenilaianKinerjaStaffController@download_excel_penilaian_kinerja_nonstaff']);
    Route::get('hrd/download_excel_rencana_adjustment_grade',['as'=>'hris.hrd.download_excel_rencana_adjustment_grade','uses'=>'PenilaianKinerjaStaffController@download_excel_rencana_adjustment_grade']);
    Route::get('hrd/download-excel-rekap-penilaian',['as'=>'hris.hrd.download_excel_rekap_penilaian','uses'=>'PenilaianKinerjaStaffController@download_excel_rekap_penilaian']);

    Route::get('hrd/download-foto/{filename}', function ($filename) {
        $filePath = 'app/public/images/' . $filename; // NOTE: ini tidak ideal, solusi sementara

        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        return Storage::disk('public')->download($filePath);
    });


    // MUTASI KARYAWAN
    Route::get('mutasi-karyawan/dashboard', ['as' => 'hris.mutasi-karyawan.dashboard', 'uses' => 'MutasiKaryawan\MutasiKaryawanController@index']);
    Route::get('/line-dashboard-mutasi-karyawan', ['as' => 'hris.line-dashboard-mutasi-karyawan', 'uses' => 'MutasiKaryawan\MutasiKaryawanController@line_dashboard']);
    Route::get('mutasi-karyawan', ['as' => 'hris.mutasi-karyawan', 'uses' => 'MutasiKaryawan\MutasiKaryawanController@mutasi_karyawan']);
    Route::get('mutasi-karyawan/create', ['as' => 'hris.mutasi-karyawan-create', 'uses' => 'MutasiKaryawan\MutasiKaryawanController@create_mut_karyawan']);
    Route::get('mutasi-karyawan-list', ['as' => 'hris.mutasi-karyawan-list','uses' => 'MutasiKaryawan\MutasiKaryawanController@get_mutasi_list']);
    Route::get('/export_line/{line?}', ['as' => 'hris.export-mutasi-karyawan','uses' => 'MutasiKaryawan\MutasiKaryawanController@export_line']);
    Route::get('/export_excel_mutasi', ['as' => 'export-excel-mutasi-karyawan','uses' => 'MutasiKaryawan\MutasiKaryawanController@export_excel']);
    Route::get('/export_excel_mut_karyawan', ['as' => 'export_excel_mut_karyawan','uses' => 'MutasiKaryawan\MutasiKaryawanController@export_excel_mut_karyawan']);
    Route::get('/getdatalinekaryawan', ['as' => 'getdatalinekaryawan','uses' => 'MutasiKaryawan\MutasiKaryawanController@getdatalinekaryawan']);
    Route::post('/store_add_non_qr', ['as' => 'store_add_non_qr','uses' => 'MutasiKaryawan\MutasiKaryawanController@store_add_non_qr']);
    Route::get('/getdatakaryawan_nonqr', ['as' => 'getdatakaryawan_nonqr','uses' => 'MutasiKaryawan\MutasiKaryawanController@getdatakaryawan_nonqr']);
    Route::post('/delete_mutasi', ['as' => 'delete_mutasi','uses' => 'MutasiKaryawan\MutasiKaryawanController@delete_mutasi']);
    Route::post('/store-mut-karyawan', ['as' => 'store-mut-karyawan','uses' => 'MutasiKaryawan\MutasiKaryawanController@store']);
    Route::put('/update-mut-karyawan', ['as' => 'update-mut-karyawan','uses' => 'MutasiKaryawan\MutasiKaryawanController@update']);
    Route::delete('/destroy-mut-karyawan', ['as' => 'destroy-mut-karyawan','uses' => 'MutasiKaryawan\MutasiKaryawanController@destroy']);
    Route::get('/getdataline', ['as' => 'getdataline','uses' => 'MutasiKaryawan\MutasiKaryawanController@getdataline']);
    Route::get('/gettotal', ['as' => 'gettotal','uses' => 'MutasiKaryawan\MutasiKaryawanController@gettotal']);
    Route::get('/getdatanik', ['as' => 'getdatanik','uses' => 'MutasiKaryawan\MutasiKaryawanController@getdatanik']);

    // FORM LEMBUR SEWING MUTASI KARYAWAN
    Route::get('/fls', ['as' => 'fls.index','uses' => 'FormLembur\FormLemburSewingController@index']);
    Route::get('/fls/create', ['as' => 'fls.create','uses' => 'FormLembur\FormLemburSewingController@create']);
    Route::get('/fls/show_list_karyawan',  ['as' => 'fls.show_list_karyawan','uses' => 'FormLembur\FormLemburSewingController@show_list_karyawan']);
    Route::post('/fls/store', ['as' => 'fls.store','uses' => 'FormLembur\FormLemburSewingController@store']);
    Route::post('/fls/cek_data_lembur', ['as' => 'fls.cek_data_lembur','uses' => 'FormLembur\FormLemburSewingController@cek_data_lembur']);
    Route::get('/fls/cek_data_karyawan_tmp', ['as' => 'fls.cek_data_karyawan_tmp','uses' => 'FormLembur\FormLemburSewingController@cek_data_karyawan_tmp']);
    Route::post('/fls/store_data_karyawan_tmp', ['as' => 'fls.store_data_karyawan_tmp','uses' => 'FormLembur\FormLemburSewingController@store_data_karyawan_tmp']);
    Route::post('/fls/hapus_data_karyawan_tmp', ['as' => 'fls.hapus_data_karyawan_tmp','uses' => 'FormLembur\FormLemburSewingController@hapus_data_karyawan_tmp']);
    Route::get('/fls/show_list_karyawan_tmp', ['as' => 'fls.show_list_karyawan_tmp','uses' => 'FormLembur\FormLemburSewingController@show_list_karyawan_tmp']);
    Route::get('/fls/getdatakaryawanspl', ['as' => 'fls.getdatakaryawanspl','uses' => 'FormLembur\FormLemburSewingController@getdatakaryawanspl']);
    Route::get('/fls/getket', ['as' => 'fls.getket','uses' => 'FormLembur\FormLemburSewingController@getket']);
    Route::post('/fls/del_tmp', ['as' => 'fls.del_tmp','uses' => 'FormLembur\FormLemburSewingController@del_tmp']);
    Route::post('/fls/del_karyawan', ['as' => 'fls.del_karyawan','uses' => 'FormLembur\FormLemburSewingController@del_karyawan']);
    Route::get('/fls/export_spl', ['as' => 'fls.export_spl','uses' => 'FormLembur\FormLemburSewingController@export_spl']);
    Route::get('/fls/export_excel_spl_all', ['as' => 'fls.export_excel_spl_all','uses' => 'FormLembur\FormLemburSewingController@export_excel_spl_all']);
    Route::get('/fls/export_spl_import', ['as' => 'fls.export_spl_import','uses' => 'FormLembur\FormLemburSewingController@export_spl_import']);
    Route::post('/fls/update_form_lembur', ['as' => 'fls.update_form_lembur','uses' => 'FormLembur\FormLemburSewingController@update_form_lembur']);
    Route::post('/fls/view_tambahan_data_karyawan_lembur_sewing', ['as' => 'fls.view_tambahan_data_karyawan_lembur_sewing','uses' => 'FormLembur\FormLemburSewingController@view_tambahan_data_karyawan_lembur_sewing']);
    Route::post('/fls/get_time_from_id_data_lembur_sewing',['as' => 'fls.get_time_from_id_data_lembur_sewing','uses' => 'FormLembur\FormLemburSewingController@get_time_from_id_data_lembur_sewing']);
    Route::post('/fls/del_tmp_non_sewing_enroll_id_sewing', ['as' => 'fls.del_tmp_non_sewing_enroll_id_sewing','uses' => 'FormLembur\FormLemburSewingController@del_tmp_non_sewing_enroll_id_sewing']);
    Route::post('/fls/store_tambahan_data_karyawan_lembur_sewing', ['as' => 'fls.store_tambahan_data_karyawan_lembur_sewing','uses' => 'FormLembur\FormLemburSewingController@store_tambahan_data_karyawan_lembur_sewing']);
    Route::post('/fls/cek_data_koreksi_upah_sewing', ['as' => 'fls.cek_data_koreksi_upah_sewing','uses' => 'FormLembur\FormLemburSewingController@cek_data_koreksi_upah_sewing']);
    Route::get('/fls/export_pdf_insentif', ['as' => 'fls.export_pdf_insentif','uses' => 'FormLembur\FormLemburSewingController@export_pdf_insentif']);
    Route::get('/fls/export_excel_insentif', ['as' => 'fls.export_excel_insentif','uses' => 'FormLembur\FormLemburSewingController@export_excel_insentif']);
    Route::get('/fls/export_pdf_sewing_spl', ['as' => 'fls.export_pdf_sewing_spl','uses' => 'FormLembur\FormLemburSewingController@export_pdf_sewing_spl']);
    Route::get('/fls/export_excel_konsumsi',  ['as' => 'fls.export_excel_konsumsi','uses' => 'FormLembur\FormLemburSewingController@export_excel_konsumsi']);

    // FORM LEMBUR NON SEWING MUTASI KARYAWAN
    Route::get('/flns', ['as' => 'flns.index','uses' => 'FormLembur\FormLemburNonSewingController@index']);
    Route::get('/flns/create', ['as' => 'flns.create','uses' => 'FormLembur\FormLemburNonSewingController@create']);
    Route::get('/flns/show_list_karyawan_non_sewing', ['as' => 'flns.show_list_karyawan_non_sewing','uses' => 'FormLembur\FormLemburNonSewingController@show_list_karyawan_non_sewing']);
    Route::post('/flns/store', ['as' => 'flns.store','uses' => 'FormLembur\FormLemburNonSewingController@store']);
    Route::get('/flns/cek_data_karyawan_tmp_non_sewing', ['as' => 'flns.cek_data_karyawan_tmp_non_sewing','uses' => 'FormLembur\FormLemburNonSewingController@cek_data_karyawan_tmp_non_sewing']);
    Route::post('/flns/store_data_karyawan_tmp_non_sewing', ['as' => 'flns.store_data_karyawan_tmp_non_sewing','uses' => 'FormLembur\FormLemburNonSewingController@store_data_karyawan_tmp_non_sewing']);
    Route::get('/flns/show_list_karyawan_tmp_non_sewing', ['as' => 'flns.show_list_karyawan_tmp_non_sewing','uses' => 'FormLembur\FormLemburNonSewingController@show_list_karyawan_tmp_non_sewing']);
    Route::post('/flns/hapus_data_karyawan_tmp_non_sewing', ['as' => 'flns.hapus_data_karyawan_tmp_non_sewing','uses' => 'FormLembur\FormLemburNonSewingController@hapus_data_karyawan_tmp_non_sewing']);
    Route::post('/flns/del_tmp_non_sewing', ['as' => 'flns.del_tmp_non_sewing','uses' => 'FormLembur\FormLemburNonSewingController@del_tmp_non_sewing']);
    Route::post('/flns/del_tmp_non_sewing_enroll_id', ['as' => 'flns.del_tmp_non_sewing_enroll_id','uses' => 'FormLembur\FormLemburNonSewingController@del_tmp_non_sewing_enroll_id']);
    Route::get('/flns/getdatakaryawanspl_non_sewing', ['as' => 'flns.getdatakaryawanspl_non_sewing','uses' => 'FormLembur\FormLemburNonSewingController@getdatakaryawanspl_non_sewing']);
    Route::get('/flns/export_spl_non_sewing', ['as' => 'flns.export_spl_non_sewing','uses' => 'FormLembur\FormLemburNonSewingController@export_spl_non_sewing']);
    Route::get('/flns/export_excel_non_sewing_spl_all', ['as' => 'flns.export_excel_non_sewing_spl_all','uses' => 'FormLembur\FormLemburNonSewingController@export_excel_non_sewing_spl_all']);
    Route::post('/flns/update_form_lembur_non_sewing', ['as' => 'flns.update_form_lembur_non_sewing','uses' => 'FormLembur\FormLemburNonSewingController@update_form_lembur_non_sewing']);
    Route::get('/flns/getdept_name', ['as' => 'flns.getdept_name','uses' => 'FormLembur\FormLemburNonSewingController@getdept_name']);
    Route::post('/flns/del_karyawan_non_sewing', ['as' => 'flns.del_karyawan_non_sewing','uses' => 'FormLembur\FormLemburNonSewingController@del_karyawan_non_sewing']);
    Route::post('/flns/store_tambahan_data_karyawan_lembur',['as' => 'flns.store_tambahan_data_karyawan_lembur','uses' => 'FormLembur\FormLemburNonSewingController@store_tambahan_data_karyawan_lembur']);
    Route::post('/flns/view_tambahan_data_karyawan_lembur',['as' => 'flns.view_tambahan_data_karyawan_lembur','uses' => 'FormLembur\FormLemburNonSewingController@view_tambahan_data_karyawan_lembur']);
    Route::post('/flns/get_time_from_id_data_lembur',['as' => 'flns.get_time_from_id_data_lembur','uses' => 'FormLembur\FormLemburNonSewingController@get_time_from_id_data_lembur']);
    Route::get('/flns/export_excel_spl_all_non_sewing', ['as' => 'flns.export_excel_spl_all_non_sewing','uses' => 'FormLembur\FormLemburNonSewingController@export_excel_spl_all_non_sewing']);
    Route::post('/flns/cek_data_koreksi_upah', ['as' => 'flns.cek_data_koreksi_upah','uses' => 'FormLembur\FormLemburNonSewingController@cek_data_koreksi_upah']);
    Route::post('/flns/cek_data_lembur', ['as' => 'flns.cek_data_lembur','uses' => 'FormLembur\FormLemburNonSewingController@cek_data_lembur']);
    Route::get('/flns/export_pdf_non_sewing_insentif',['as' => 'flns.export_pdf_non_sewing_insentif','uses' => 'FormLembur\FormLemburNonSewingController@export_pdf_non_sewing_insentif']);
    Route::get('/flns/export_excel_insentif_non_sewing',['as' => 'flns.export_excel_insentif_non_sewing','uses' => 'FormLembur\FormLemburNonSewingController@export_excel_insentif_non_sewing']);
    Route::get('/flns/export_pdf_non_sewing_spl', ['as' => 'flns.export_pdf_non_sewing_spl','uses' => 'FormLembur\FormLemburNonSewingController@export_pdf_non_sewing_spl']);

    // ESTIMASI ANGGARAN MAKAN
    Route::get('/anggaran-makan',  ['as' => 'anggaran_makan.index','uses' => 'FormLembur\AnggaranMakanController@index']);
    Route::get('/anggaran-makan/create',  ['as' => 'anggaran_makan.create','uses' => 'FormLembur\AnggaranMakanController@create']);
    Route::post('/anggaran-makan/store',  ['as' => 'anggaran_makan.store','uses' => 'FormLembur\AnggaranMakanController@store']);
    Route::post('/anggaran-makan/edit',  ['as' => 'anggaran_makan.edit','uses' => 'FormLembur\AnggaranMakanController@edit']);
    Route::post('/anggaran-makan/update',  ['as' => 'anggaran_makan.update','uses' => 'FormLembur\AnggaranMakanController@update']);
    Route::post('/anggaran-makan/delete',  ['as' => 'anggaran_makan.delete','uses' => 'FormLembur\AnggaranMakanController@delete']);
    Route::get('/anggaran-makan/export_excel_konsumsi_estimasi', ['as' => 'anggaran_makan.export_excel_konsumsi_estimasi','uses' => 'FormLembur\AnggaranMakanController@export_excel_konsumsi_estimasi']);
    Route::get('/anggaran-makan/export_excel_overtime_recap',  ['as' => 'anggaran_makan.export_excel_overtime_recap','uses' => 'FormLembur\AnggaranMakanController@export_excel_overtime_recap']);
    Route::get('/anggaran-makan/export_excel_overtime_recap2',  ['as' => 'anggaran_makan.export_excel_overtime_recap2','uses' => 'FormLembur\AnggaranMakanController@export_excel_overtime_recap2']);
    Route::get('/anggaran-makan/export_pdf_konsumsi', ['as' => 'anggaran_makan.export_pdf_konsumsi','uses' => 'FormLembur\AnggaranMakanController@export_pdf_konsumsi']);

    // BAZZAR
    Route::get('/bazzar', ['as' => 'bazzar.index','uses' => 'Bazzar\BazzarController@index']);
    Route::get('/bazzar/export_excel', ['as' => 'bazzar.export_excel','uses' => 'Bazzar\BazzarController@export_excel']);
    Route::get('/bazzar/get_bazzar', ['as' => 'bazzar.get_bazzar','uses' => 'Bazzar\BazzarController@get_bazzar']);
    Route::get('/bazzar/get_bazzar_detail', ['as' => 'bazzar.get_bazzar_detail','uses' => 'Bazzar\BazzarController@get_bazzar_detail']);
    Route::post('/bazzar/store', ['as' => 'bazzar.store','uses' => 'Bazzar\BazzarController@store']);
    Route::post('/bazzar/hapus', ['as' => 'bazzar.hapus','uses' => 'Bazzar\BazzarController@hapus']);
    Route::post('/bazzar/hapus_per_bagian', ['as' => 'bazzar.hapus_per_bagian','uses' => 'Bazzar\BazzarController@hapus_per_bagian']);
    Route::post('/bazzar/approve', ['as' => 'bazzar.approve','uses' => 'Bazzar\BazzarController@approve']);
    Route::post('/bazzar/reject', ['as' => 'bazzar.reject','uses' => 'Bazzar\BazzarController@reject']);
    Route::post('/bazzar/edit_pengajuan', ['as' => 'bazzar.edit_pengajuan','uses' => 'Bazzar\BazzarController@edit_pengajuan']);
    Route::get('/bazzar/export_laporan_pengajuan', ['as' => 'bazzar.export_laporan_pengajuan','uses' => 'Bazzar\BazzarController@export_laporan_pengajuan']);
    Route::get('/bazzar/export_laporan_tanda_terima_bagian', ['as' => 'bazzar.export_laporan_tanda_terima_bagian','uses' => 'Bazzar\BazzarController@export_laporan_tanda_terima_bagian']);
    Route::get('/bazzar/export_laporan_pengajuan_ids', ['as' => 'bazzar.export_laporan_pengajuan_ids','uses' => 'Bazzar\BazzarController@export_laporan_pengajuan_ids']);
    Route::post('/bazzar/already_print', ['as' => 'bazzar.already_print','uses' => 'Bazzar\BazzarController@already_printed']);
    Route::post('/bazzar/not_yet_printed', ['as' => 'bazzar.not_yet_printed','uses' => 'Bazzar\BazzarController@not_yet_printed']);
    Route::get('/bazzar/export_voucher', ['as' => 'bazzar.export_voucher','uses' => 'Bazzar\BazzarController@export_voucher']);


    // LICENSE PERMIT
    Route::get('/dokumen_legal', ['as' => 'dokumen_legal.index','uses' => 'LicensePermit\LicensePermitController@index']);
    Route::post('/dokumen_legal/store', ['as' => 'dokumen_legal.store','uses' => 'LicensePermit\LicensePermitController@store']);
    Route::get('/get-dokumen-legal', ['as' => 'dokumen_legal.get_dokumen_legal','uses' => 'LicensePermit\LicensePermitController@get_dokumen_legal']);
    Route::delete('/delete-dokumen-legal/{id}', ['as' => 'dokumen_legal.delete_dokumen_legal','uses' => 'LicensePermit\LicensePermitController@delete_dokumen_legal']);
    Route::get('/get-dokumen-legal/{id}', ['as' => 'dokumen_legal.get_edit_dokumen_legal','uses' => 'LicensePermit\LicensePermitController@getDokumenLegal']);
    Route::post('/update-dokumen-legal', ['as' => 'dokumen_legal.update_dokumen_legal','uses' => 'LicensePermit\LicensePermitController@updateDokumenLegal']);
    Route::get('/download_watermark/{id}', ['as' => 'dokumen_legal.download_watermark','uses' => 'LicensePermit\LicensePermitController@download_watermark']);

    // ENTERTAINT
    Route::get('/entertaint-tamu', ['as' => 'entertaint_tamu.index','uses' => 'Entertaint\EntertaintController@index']);
    Route::post('/entertaint_tamu/store', ['as' => 'entertaint_tamu.store','uses' => 'Entertaint\EntertaintController@store']);
    Route::post('/entertaint_tamu/realisasi', ['as' => 'entertaint_tamu.realisasi','uses' => 'Entertaint\EntertaintController@realisasi']);
    Route::get('/entertaint_tamu/show', ['as' => 'entertaint_tamu.show','uses' => 'Entertaint\EntertaintController@getData']);
    Route::get('/entertaint_tamu/export_pengajuan_permintaan_kas', ['as' => 'entertaint_tamu.export_pengajuan_permintaan_kas','uses' => 'Entertaint\EntertaintController@export_pengajuan_permintaan_kas']);
    Route::get('/entertaint_tamu/export_realisasi_permintaan_kas', ['as' => 'entertaint_tamu.export_realisasi_permintaan_kas','uses' => 'Entertaint\EntertaintController@export_realisasi_permintaan_kas']);

    // CUTI KARYAWAN
    Route::get('/cuti_karyawan/pengajuan_perizinan_admin', ['as' => 'cuti_karyawan.pengajuan_perizinan_admin','uses' => 'MasterData\CutiKaryawanController@index_pengajuan_perizinan_admin']);
    Route::post('/cuti_karyawan/store', ['as' => 'cuti_karyawan.store','uses' => 'MasterData\CutiKaryawanController@store']);
    Route::get('/cuti_karyawan/show', ['as' => 'cuti_karyawan.show','uses' => 'MasterData\CutiKaryawanController@getData']);
    Route::post('/cuti_karyawan/show_export', ['as' => 'cuti_karyawan.show_export','uses' => 'MasterData\CutiKaryawanController@show_export']);
    Route::post('/cuti_karyawan/show_export_detail_cuti_karyawan', ['as' => 'cuti_karyawan.show_export_detail_cuti_karyawan','uses' => 'MasterData\CutiKaryawanController@show_export_detail_cuti_karyawan']);
    Route::get('/cuti_karyawan/show_by_id', ['as' => 'cuti_karyawan.show_by_id','uses' => 'MasterData\CutiKaryawanController@show_by_id']);
    Route::post('/cuti_karyawan/update_perizinan_menu_admin', ['as' => 'cuti_karyawan.update_perizinan_menu_admin','uses' => 'MasterData\CutiKaryawanController@update_perizinan_menu_admin']);
    Route::post('/cuti_karyawan/create_perizinan_menu_admin', ['as' => 'cuti_karyawan.create_perizinan_menu_admin','uses' => 'MasterData\CutiKaryawanController@create_perizinan_menu_admin']);
    Route::post('/cuti_karyawan/create_iks_menu_admin', ['as' => 'cuti_karyawan.create_iks_menu_admin','uses' => 'MasterData\CutiKaryawanController@create_iks_menu_admin']);
    Route::post('/cuti_karyawan/update_iks_menu_admin', ['as' => 'cuti_karyawan.update_iks_menu_admin','uses' => 'MasterData\CutiKaryawanController@update_iks_menu_admin']);
    Route::get('/cuti_karyawan/showing_list_year_period', ['as' => 'cuti_karyawan.showing_list_year_period','uses' => 'MasterData\CutiKaryawanController@showing_list_year_period']);
    Route::post('/cuti_karyawan/show_export_by_join_date', ['as' => 'cuti_karyawan.show_export_by_join_date','uses' => 'MasterData\CutiKaryawanController@show_export_by_join_date']);
    Route::post('/cuti_karyawan/show_export_by_user', ['as' => 'cuti_karyawan.show_export_by_user','uses' => 'MasterData\CutiKaryawanController@show_export_by_user']);
    Route::get('/cuti_karyawan/export_pengajuan_permintaan_kas', ['as' => 'cuti_karyawan.export_pengajuan_permintaan_kas','uses' => 'MasterData\CutiKaryawanController@export_pengajuan_permintaan_kas']);
    Route::get('/cuti_karyawan/export_realisasi_permintaan_kas', ['as' => 'cuti_karyawan.export_realisasi_permintaan_kas','uses' => 'MasterData\CutiKaryawanController@export_realisasi_permintaan_kas']);
    Route::get('/cuti_karyawan/export_form_pengajuan_cuti_pdf', ['as' => 'cuti_karyawan.export_form_pengajuan_cuti_pdf','uses' => 'MasterData\CutiKaryawanController@export_form_pengajuan_cuti_pdf']);
    Route::get('/cuti_karyawan/export_form_pengajuan_izin_pdf', ['as' => 'cuti_karyawan.export_form_pengajuan_izin_pdf','uses' => 'MasterData\CutiKaryawanController@export_form_pengajuan_izin_pdf']);

    // PERMINTAAN TENAGA KERJA HR
    Route::get('/permintaan_tenaga_kerja_hr/index', ['as' => 'permintaan_tenaga_kerja_hr.permintaan_tenaga_kerja_hr','uses' => 'Administrasi\PermintaanTenagaKerjaController@index_hr']);

    // PERMINTAAN TENAGA KERJA
    Route::get('/permintaan_tenaga_kerja/index', ['as' => 'permintaan_tenaga_kerja.permintaan_tenaga_kerja','uses' => 'Administrasi\PermintaanTenagaKerjaController@index']);
    Route::get('/permintaan_tenaga_kerja/print_pengajuan_tk_pdf/{id}', ['as' => 'permintaan_tenaga_kerja.print_pengajuan_tk_pdf','uses' => 'Administrasi\PermintaanTenagaKerjaController@print_pengajuan_tk_pdf']);
    Route::post('/permintaan_tenaga_kerja/ajax_data_permintaan_tk', ['as' => 'permintaan_tenaga_kerja.ajax_data_permintaan_tk','uses' => 'Administrasi\PermintaanTenagaKerjaController@ajax_data_permintaan_tk']);
    Route::post('/permintaan_tenaga_kerja/create_permintaan_tk', ['as' => 'permintaan_tenaga_kerja.create_permintaan_tk','uses' => 'Administrasi\PermintaanTenagaKerjaController@create_permintaan_tk']);
    Route::post('/permintaan_tenaga_kerja/update_permintaan_tk', ['as' => 'permintaan_tenaga_kerja.update_permintaan_tk','uses' => 'Administrasi\PermintaanTenagaKerjaController@update_permintaan_tk']);
    Route::post('/permintaan_tenaga_kerja/get_detail_permintaan_tk', ['as' => 'permintaan_tenaga_kerja.get_detail_permintaan_tk','uses' => 'Administrasi\PermintaanTenagaKerjaController@get_detail_permintaan_tk']);
    Route::post('/permintaan_tenaga_kerja/approve_permintaan_tk', ['as' => 'permintaan_tenaga_kerja.approve_permintaan_tk','uses' => 'Administrasi\PermintaanTenagaKerjaController@approve_permintaan_tk']);
    Route::post('/permintaan_tenaga_kerja/reject_permintaan_tk', ['as' => 'permintaan_tenaga_kerja.reject_permintaan_tk','uses' => 'Administrasi\PermintaanTenagaKerjaController@reject_permintaan_tk']);
    Route::post('/permintaan_tenaga_kerja/delete_permintaan_tk', ['as' => 'permintaan_tenaga_kerja.delete_permintaan_tk','uses' => 'Administrasi\PermintaanTenagaKerjaController@delete_permintaan_tk']);
    Route::post('/permintaan_tenaga_kerja/get_employee_fptk', ['as' => 'permintaan_tenaga_kerja.get_employee_fptk','uses' => 'Administrasi\PermintaanTenagaKerjaController@get_employee_fptk']);

    Route::post('/permintaan_tenaga_kerja/simpan_no_fptk_karyawan', ['as' => 'permintaan_tenaga_kerja.simpan_no_fptk_karyawan','uses' => 'Administrasi\PermintaanTenagaKerjaController@simpan_no_fptk_karyawan']);
    Route::post('/permintaan_tenaga_kerja/simpan_selesai_no_fptk_karyawan', ['as' => 'permintaan_tenaga_kerja.simpan_selesai_no_fptk_karyawan','uses' => 'Administrasi\PermintaanTenagaKerjaController@simpan_selesai_no_fptk_karyawan']);
    Route::post('/permintaan_tenaga_kerja/set_to_pending_no_fptk_karyawan', ['as' => 'permintaan_tenaga_kerja.set_to_pending_no_fptk_karyawan','uses' => 'Administrasi\PermintaanTenagaKerjaController@set_to_pending_no_fptk_karyawan']);
    Route::post('/permintaan_tenaga_kerja/move_to_pending_permintaan', ['as' => 'permintaan_tenaga_kerja.move_to_pending_permintaan','uses' => 'Administrasi\PermintaanTenagaKerjaController@move_to_pending_permintaan']);



    // PENGAJUAN IZIN
    Route::post('cuti_karyawan/ajax_dataabsenperizinan',['as'=>'cuti_karyawan.dataabsenperijinan.ajax_dataabsenperizinan','uses'=>'MasterData\CutiKaryawanController@ajax_dataabsenperizinan']);
    Route::post('cuti_karyawan/get_data_perizinan',['as'=>'cuti_karyawan.dataabsenperijinan.get_data_perizinan','uses'=>'MasterData\CutiKaryawanController@get_data_perizinan']);
    Route::post('cuti_karyawan/approve_hr_perizinan_menu',['as'=>'cuti_karyawan.dataabsenperijinan.approve_hr_perizinan_menu','uses'=>'MasterData\CutiKaryawanController@approve_hr_perizinan_menu']);
    Route::post('cuti_karyawan/approve_iks',['as'=>'cuti_karyawan.dataabsenperijinan.approve_iks','uses'=>'MasterData\CutiKaryawanController@approve_iks']);
    Route::post('cuti_karyawan/reject_hr_perizinan_menu',['as'=>'cuti_karyawan.dataabsenperijinan.reject_hr_perizinan_menu','uses'=>'MasterData\CutiKaryawanController@reject_hr_perizinan_menu']);
    Route::post('cuti_karyawan/cek_dtpc',['as'=>'cuti_karyawan.dataabsenperijinan.cek_dtpc','uses'=>'MasterData\CutiKaryawanController@cek_dtpc']);
    Route::post('cuti_karyawan/update_dtpc_menu',['as'=>'cuti_karyawan.dataabsenperijinan.update_dtpc_menu','uses'=>'MasterData\CutiKaryawanController@update_dtpc_menu']);
    Route::post('cuti_karyawan/create_dtpc_menu',['as'=>'cuti_karyawan.dataabsenperijinan.create_dtpc_menu','uses'=>'MasterData\CutiKaryawanController@create_dtpc_menu']);
    Route::post('cuti_karyawan/destroy',['as'=>'cuti_karyawan.dataabsenperijinan.destroy','uses'=>'MasterData\CutiKaryawanController@destroy']);
    Route::post('cuti_karyawan/destroy_dtpc',['as'=>'cuti_karyawan.dataabsenperijinan.destroy_dtpc','uses'=>'MasterData\CutiKaryawanController@destroy_dtpc']);
    Route::post('cuti_karyawan/approve_perijinan_all',['as'=>'cuti_karyawan.dataabsenperijinan.approve_perijinan_all','uses'=>'MasterData\CutiKaryawanController@approve_perijinan_all']);

    Route::get('hrd/export_pdf_sk_bni',['as'=>'hris.hrd.export_pdf_sk_bni','uses'=>'HRDController@export_pdf_sk_bni']);
    Route::post('hrd/export_pdf_print_sk',['as'=>'hris.hrd.export_pdf_print_sk','uses'=>'HRDController@export_pdf_print_sk']);
    Route::get('hrd/get_employee_contract',['as'=>'hris.hrd.get_employee_contract','uses'=>'HRDController@get_employee_contract']);
    Route::post('hrd/get_employee_contract2',['as'=>'hris.hrd.get_employee_contract2','uses'=>'HRDController@get_employee_contract2']);
    Route::post('hrd/update_employee_contract',['as'=>'hris.hrd.update_employee_contract','uses'=>'HRDController@update_employee_contract']);
    Route::post('hrd/new_employee_contract',['as'=>'hris.hrd.new_employee_contract','uses'=>'HRDController@new_employee_contract']);
    Route::post('hrd/delete_employee_contract',['as'=>'hris.hrd.delete_employee_contract','uses'=>'HRDController@delete_employee_contract']);
    Route::post('hrd/import_kontrak_kerja',['as'=>'hris.hrd.import_kontrak_kerja','uses'=>'HRDController@import_kontrak_kerja']);
    Route::post('hrd/import_kontrak_kerja_to_database',['as'=>'hris.hrd.import_kontrak_kerja_to_database','uses'=>'HRDController@import_kontrak_kerja_to_database']);
    Route::get('hrd/export_excel_kontrak',['as'=>'hris.hrd.export_excel_kontrak','uses'=>'HRDController@export_excel_kontrak']);
    Route::get('hrd/print_pdf_kontrak',['as'=>'hris.hrd.print_pdf_kontrak','uses'=>'HRDController@print_pdf_kontrak']);
    Route::post('hrd/print_all_pdf_kontrak',['as'=>'hris.hrd.print_all_pdf_kontrak','uses'=>'HRDController@print_all_pdf_kontrak']);
    Route::post('hrd/print_selected_form_penilaian',['as'=>'hris.hrd.print_selected_form_penilaian','uses'=>'PenilaianKinerjaStaffController@print_selected_form_penilaian']);
    Route::get('hrd/print_pdf_kontrak_2',['as'=>'hris.hrd.print_pdf_kontrak_2','uses'=>'HRDController@print_pdf_kontrak_2']);
    Route::get('hrd/print_pdf_kompensasi_pkwt',['as'=>'hris.hrd.print_pdf_kompensasi_pkwt','uses'=>'HRDController@print_pdf_kompensasi_pkwt']);
    Route::post('hrd/ajax_getemployeeidbyfilter',['as'=>'hris.hrd.ajax_getemployeeidbyfilter','uses'=>'HRDController@ajax_getemployeeidbyfilter']);

    Route::get('ga/form_pengajuan_transportasi',['as'=>'hris.ga.form_pengajuan_transportasi','uses'=>'GAController@form_pengajuan_transportasi']);
    Route::post('ga/get_province',['as'=>'hris.ga.get_province','uses'=>'GAController@get_province']);
    Route::post('ga/get_all_zone_name',['as'=>'hris.ga.get_all_zone_name','uses'=>'GAController@get_all_zone_name']);
    Route::post('ga/get_cities',['as'=>'hris.ga.get_cities','uses'=>'GAController@get_cities']);
    Route::post('ga/get_cities_name',['as'=>'hris.ga.get_cities_name','uses'=>'GAController@get_cities_name']);
    Route::post('ga/get_districts',['as'=>'hris.ga.get_districts','uses'=>'GAController@get_districts']);
    Route::post('ga/get_districts_name',['as'=>'hris.ga.get_districts_name','uses'=>'GAController@get_districts_name']);
    Route::post('ga/get_subdistricts',['as'=>'hris.ga.get_subdistricts','uses'=>'GAController@get_subdistricts']);
    Route::post('ga/get_subdistricts_name',['as'=>'hris.ga.get_subdistricts_name','uses'=>'GAController@get_subdistricts_name']);
    Route::post('ga/post_car_request',['as'=>'hris.ga.post_car_request','uses'=>'GAController@post_car_request']);
    Route::post('ga/store_car_request',['as'=>'hris.ga.store_car_request','uses'=>'GAController@store_car_request']);
    Route::post('ga/get_data_detail',['as'=>'hris.ga.get_data_detail','uses'=>'GAController@get_data_detail']);
    Route::post('ga/get_tujuan_detail',['as'=>'hris.ga.get_tujuan_detail','uses'=>'GAController@get_tujuan_detail']);
    Route::post('ga/get_employee_dinas',['as'=>'hris.ga.get_employee_dinas','uses'=>'GAController@get_employee_dinas']);
    Route::get('ga/data_pengajuan_transportasi',['as'=>'hris.ga.data_pengajuan_transportasi','uses'=>'GAController@data_pengajuan_transportasi']);
    Route::post('ga/delete_pengajuan_transportasi',['as'=>'hris.ga.delete_pengajuan_transportasi','uses'=>'GAController@delete_pengajuan_transportasi']);
    Route::get('ga/summary_driver_task',['as'=>'hris.ga.summary_driver_task','uses'=>'GAController@summary_driver_task']);
    Route::post('ga/get_all_destination_history',['as'=>'hris.ga.get_all_destination_history','uses'=>'GAController@get_all_destination_history']);
    Route::get('ga/get_data_pengajuan_transportasi',['as'=>'hris.ga.get_data_pengajuan_transportasi','uses'=>'GAController@get_data_pengajuan_transportasi']);
    Route::get('ga/get_data_summary_driver',['as'=>'hris.ga.get_data_summary_driver','uses'=>'GAController@get_data_summary_driver']);
    Route::post('ga/get_pengajuan_status',['as'=>'hris.ga.get_pengajuan_status','uses'=>'GAController@get_pengajuan_status']);
    Route::post('ga/approve_car_request',['as'=>'hris.ga.approve_car_request','uses'=>'GAController@approve_car_request']);
    Route::post('ga/approve_this_car_request',['as'=>'hris.ga.approve_this_car_request','uses'=>'GAController@approve_this_car_request']);
    Route::post('ga/reject_car_request',['as'=>'hris.ga.reject_car_request','uses'=>'GAController@reject_car_request']);
    Route::get('ga/print_penugasan_transportasi',['as'=>'hris.ga.print_penugasan_transportasi','uses'=>'GAController@print_penugasan_transportasi']);
    Route::get('ga/print_pdf_summary_driver',['as'=>'hris.ga.print_pdf_summary_driver','uses'=>'GAController@print_pdf_summary_driver']);
    Route::post('ga/export_excel_transportasi',['as'=>'hris.ga.export_excel_transportasi','uses'=>'GAController@export_excel_transportasi']);
    Route::get('ga/lihat_detail',['as'=>'hris.ga.lihat_detail','uses'=>'GAController@lihat_detail']);
    Route::get('ga/edit_detail',['as'=>'hris.ga.edit_detail','uses'=>'GAController@edit_detail']);
    Route::post('ga/get_route_from_user',['as'=>'hris.ga.get_route_from_user','uses'=>'GAController@get_route_from_user']);
    Route::post('ga/get_route_from_user_choice',['as'=>'hris.ga.get_route_from_user_choice','uses'=>'GAController@get_route_from_user_choice']);
    Route::post('ga/get_all_history_alamat',['as'=>'hris.ga.get_all_history_alamat','uses'=>'GAController@get_all_history_alamat']);
    Route::post('ga/add_another_route',['as'=>'hris.ga.add_another_route','uses'=>'GAController@add_another_route']);
    Route::post('ga/add_another_route_2',['as'=>'hris.ga.add_another_route_2','uses'=>'GAController@add_another_route_2']);
    Route::post('ga/add_another_route_3',['as'=>'hris.ga.add_another_route_3','uses'=>'GAController@add_another_route_3']);
    Route::post('ga/update_car_request',['as'=>'hris.ga.update_car_request','uses'=>'GAController@update_car_request']);
    Route::post('ga/check_car_request',['as'=>'hris.ga.check_car_request','uses'=>'GAController@check_car_request']);
    Route::post('ga/show_another_route',['as'=>'hris.ga.show_another_route','uses'=>'GAController@show_another_route']);
    Route::post('ga/update_car_request_user',['as'=>'hris.ga.update_car_request_user','uses'=>'GAController@update_car_request_user']);
    Route::post('ga/update_car_request_administrator',['as'=>'hris.ga.update_car_request_administrator','uses'=>'GAController@update_car_request_administrator']);
    Route::post('ga/change_status_car_request',['as'=>'hris.ga.change_status_car_request','uses'=>'GAController@change_status_car_request']);
    Route::get('ga/pemeliharaan_kendaraan',['as'=>'hris.ga.pemeliharaan_kendaraan','uses'=>'PemeliharaanKendaraanController@index']);
    Route::get('ga/get_data_kendaraan',['as'=>'hris.ga.get_data_kendaraan','uses'=>'PemeliharaanKendaraanController@get_data_kendaraan']);
    Route::get('ga/get_data_jenis_pemeliharaan',['as'=>'hris.ga.get_data_jenis_pemeliharaan','uses'=>'PemeliharaanKendaraanController@get_data_jenis_pemeliharaan']);
    Route::post('ga/store_vehicle_maintenance',['as'=>'hris.ga.store_vehicle_maintenance','uses'=>'PemeliharaanKendaraanController@store_vehicle_maintenance']);
    Route::post('ga/delete_vehicle_maintenance',['as'=>'hris.ga.delete_vehicle_maintenance','uses'=>'PemeliharaanKendaraanController@delete_vehicle_maintenance']);
    Route::post('ga/store_vehicle_item_master',['as'=>'hris.ga.store_vehicle_item_master','uses'=>'PemeliharaanKendaraanController@store_vehicle_item_master']);
    Route::get('ga/get_vehicle_item_data',['as'=>'hris.ga.get_vehicle_item_data','uses'=>'PemeliharaanKendaraanController@get_vehicle_item_data']);
    Route::post('ga/delete_vehicle_item',['as'=>'hris.ga.delete_vehicle_item','uses'=>'PemeliharaanKendaraanController@delete_vehicle_item']);
    Route::get('ga/get_vehicle_maintenance_data',['as'=>'hris.ga.get_vehicle_maintenance_data','uses'=>'PemeliharaanKendaraanController@get_vehicle_maintenance_data']);
    Route::post('ga/store_vehicle_item_maintenance',['as'=>'hris.ga.store_vehicle_item_maintenance','uses'=>'PemeliharaanKendaraanController@store_vehicle_item_maintenance']);
    Route::post('ga/delete_vehicle_maintenance',['as'=>'hris.ga.delete_vehicle_maintenance','uses'=>'PemeliharaanKendaraanController@delete_vehicle_maintenance']);
    Route::get('ga/print_vehicle_maintenance',['as'=>'hris.ga.print_vehicle_maintenance','uses'=>'PemeliharaanKendaraanController@print_vehicle_maintenance']);
    Route::post('ga/delete_vehicle_maintenance_schedule',['as'=>'hris.ga.delete_vehicle_maintenance_schedule','uses'=>'PemeliharaanKendaraanController@delete_vehicle_maintenance_schedule']);
    Route::post('ga/get_vehicle_item_maintenance_price',['as'=>'hris.ga.get_vehicle_item_maintenance_price','uses'=>'PemeliharaanKendaraanController@get_vehicle_item_maintenance_price']);
    Route::get('ga/vehicle_monitoring',['as'=>'hris.ga.vehicle_monitoring','uses'=>'PemeliharaanKendaraanController@vehicle_monitoring']);
    Route::get('ga/get_vehicle_item_monitoring',['as'=>'hris.ga.get_vehicle_item_monitoring','uses'=>'PemeliharaanKendaraanController@get_vehicle_item_monitoring']);
    Route::post('ga/store_kategori_item',['as'=>'hris.ga.store_kategori_item','uses'=>'PemeliharaanKendaraanController@store_kategori_item']);
    Route::get('ga/get_vehicle_category_name',['as'=>'hris.ga.get_vehicle_category_name','uses'=>'PemeliharaanKendaraanController@get_vehicle_category_name']);
    Route::get('ga/get_kategori_item',['as'=>'hris.ga.get_kategori_item','uses'=>'PemeliharaanKendaraanController@get_kategori_item']);
    Route::post('ga/store_item',['as'=>'hris.ga.store_item','uses'=>'PemeliharaanKendaraanController@store_item']);

    //Route::resource('mdabsenhadir', 'MdAbsenHadirController',['as' => 'hris']);
    Route::post('mdabsenhadir/ajax_datahadir/',['as'=>'hris.mdabsenhadir.ajax_datahadir','uses'=> 'MdAbsenHadirController@ajax_datahadir']);
    Route::post('mdabsenhadir/ajax_caridatahadir/',['as'=>'hris.mdabsenhadir.ajax_caridatahadir','uses'=> 'MdAbsenHadirController@ajax_caridatahadir']);
    Route::post('mdabsenhadir/import_datahadir/',['as'=>'hris.mdabsenhadir.import_datahadir','uses'=> 'MdAbsenHadirController@import_datahadir']);
    Route::post('mdabsenhadir/importing_datahadir/',['as'=>'hris.mdabsenhadir.importing_datahadir','uses'=> 'MdAbsenHadirController@importing_datahadir']);
    Route::get('mdabsenhadir/lihatabsen',['as'=>'hris.mdabsenhadir.lihatabsen','uses'=>'MdAbsenHadirController@lihatabsen']);
    Route::post('mdabsenhadir/ajax_lihatabsen/',['as'=>'hris.mdabsenhadir.ajax_lihatabsen','uses'=> 'MdAbsenHadirController@ajax_lihatabsen']);
    Route::post('mdabsenhadir/ajax_datawaktuabsen/',['as'=>'hris.mdabsenhadir.ajax_datawaktuabsen','uses'=> 'MdAbsenHadirController@ajax_datawaktuabsen']);
    Route::post('mdabsenhadir/save_datawaktuabsen/',['as'=>'hris.mdabsenhadir.save_datawaktuabsen','uses'=> 'MdAbsenHadirController@save_datawaktuabsen']);
    Route::post('mdabsenhadir/ajax_getselectdepart/',['as'=>'hris.mdabsenhadir.ajax_getselectdepart','uses'=> 'MdAbsenHadirController@ajax_getselectdepart']);
    Route::post('mdabsenhadir/ajax_getselectsubdept/',['as'=>'hris.mdabsenhadir.ajax_getselectsubdept','uses'=> 'MdAbsenHadirController@ajax_getselectsubdept']);
    Route::post('mdabsenhadir/ajax_getselectemployee/',['as'=>'hris.mdabsenhadir.ajax_getselectemployee','uses'=> 'MdAbsenHadirController@ajax_getselectemployee']);
    Route::post('mdabsenhadir/ajax_getemployeselectposisi/',['as'=>'hris.mdabsenhadir.ajax_getemployeselectposisi','uses'=> 'MdAbsenHadirController@ajax_getemployeselectposisi']);
    Route::post('mdabsenhadir/ajax_getallemployeeatribut/',['as'=>'hris.mdabsenhadir.ajax_getallemployeeatribut','uses'=> 'MdAbsenHadirController@ajax_getallemployeeatribut']);
    Route::post('mdabsenhadir/show_kehadiran/',['as'=>'hris.mdabsenhadir.show_kehadiran','uses'=> 'MdAbsenHadirController@show_kehadiran']);
    Route::post('mdabsenhadir/ajax_exportexcel/',['as'=>'hris.mdabsenhadir.ajax_exportexcel','uses'=> 'MdAbsenHadirController@ajax_exportexcel']);
    Route::post('mdabsenhadir/print/',['as'=>'hris.mdabsenhadir.print','uses'=> 'MdAbsenHadirController@print']);
    Route::get('mdabsenhadir/laporan_harian_kehadiran/',['as'=>'hris.mdabsenhadir.laporan_harian_kehadiran','uses'=> 'MdAbsenHadirController@laporan_harian_kehadiran']);
    Route::post('mdabsenhadir/ajax_exportexceldeptall/',['as'=>'hris.mdabsenhadir.ajax_exportexceldeptall','uses'=> 'MdAbsenHadirController@ajax_exportexceldeptall']);
    Route::post('mdabsenhadir/ajax_getemployeselectdeptid/',['as'=>'hris.mdabsenhadir.ajax_getemployeselectdeptid','uses'=> 'MdAbsenHadirController@ajax_getemployeselectdeptid']);
    Route::get('mdabsenhadir/proses/',['as'=>'hris.mdabsenhadir.proses','uses'=> 'MdAbsenHadirController@proses']);
    Route::post('mdabsenhadir/ajax_proses/',['as'=>'hris.mdabsenhadir.ajax_proses','uses'=> 'MdAbsenHadirController@ajax_proses']);
    Route::post('mdabsenhadir/download_mesin_kehadiran/',['as'=>'hris.mdabsenhadir.download_mesin_kehadiran','uses'=> 'MdAbsenHadirController@download_mesin_kehadiran']);
    Route::post('mdabsenhadir/ajax_getdashkehadiran',['as'=>'hris.mdabsenhadir.ajax_getdashkehadiran','uses'=>'MdAbsenHadirController@ajax_getdashkehadiran']);
    Route::post('mdabsenhadir/ajax_getTanggalKehadiranSekarang',['as'=>'hris.mdabsenhadir.ajax_getTanggalKehadiranSekarang','uses'=>'MdAbsenHadirController@ajax_getTanggalKehadiranSekarang']);
    Route::get('mdabsenhadir/export_pdf',['as'=>'hris.mdabsenhadir.export_pdf','uses'=>'MdAbsenHadirController@export_pdf']);
    Route::post('mdabsenhadir/view_excel',['as'=>'hris.mdabsenhadir.view_excel','uses'=>'MdAbsenHadirController@view_excel']);
    Route::post('mdabsenhadir/rekap_kehadiran',['as'=>'hris.mdabsenhadir.rekap_kehadiran','uses'=>'MdAbsenHadirController@rekap_kehadiran']);

    // DATA GAGAL ABSEN
    Route::post('gagalabsen/ajax_gagalabsen/',['as'=>'hris.gagalabsen.ajax_gagalabsen','uses'=> 'GagalAbsenController@ajax_gagalabsen']);
    Route::post('gagalabsen/ajax_loggagalabsen/',['as'=>'hris.gagalabsen.ajax_loggagalabsen','uses'=> 'GagalAbsenController@ajax_loggagalabsen']);
    Route::post('gagalabsen/form_gagalabsen/',['as'=>'hris.gagalabsen.form_gagalabsen','uses'=> 'GagalAbsenController@form_gagalabsen']);
    Route::post('gagalabsen/update/',['as'=>'hris.gagalabsen.update','uses'=> 'GagalAbsenController@update']);
    Route::post('logdatagagalabsen/store/',['as'=>'hris.logdatagagalabsen.store','uses'=> 'LogDataGagalAbsenController@store']);
    Route::post('gagalabsen/ajax_exportexcel/',['as'=>'hris.gagalabsen.ajax_exportexcel','uses'=> 'GagalAbsenController@ajax_exportexcel']);

    // VERIFIKASI INSENTIF LEMBUR
    Route::get('verifikasi_insentif_lembur/index/',['as'=>'hris.verifikasi_insentif_lembur.index','uses'=> 'VerifikasiInsentifLemburController@index']);
    Route::post('verifikasi_insentif_lembur/ajax_datahadir/',['as'=>'hris.verifikasi_insentif_lembur.ajax_datahadir','uses'=> 'VerifikasiInsentifLemburController@ajax_datahadir']);
    Route::post('verifikasi_insentif_lembur/form_verifikasi_insentif_lembur/',['as'=>'hris.verifikasi_insentif_lembur.form_verifikasi_insentif_lembur','uses'=> 'VerifikasiInsentifLemburController@form_verifikasi_insentif_lembur']);
    Route::post('verifikasi_insentif_lembur/getEmployeeLembur/',['as'=>'hris.verifikasi_insentif_lembur.getEmployeeLembur','uses'=> 'VerifikasiInsentifLemburController@getEmployeeLembur']);
    Route::post('verifikasi_insentif_lembur/ajax_getNomorFormLembur/',['as'=>'hris.verifikasi_insentif_lembur.ajax_getNomorFormLembur','uses'=> 'VerifikasiInsentifLemburController@ajax_getNomorFormLembur']);
    Route::get('verifikasi_insentif_lembur/add_verifikasi_insentif_lembur/',['as'=>'hris.verifikasi_insentif_lembur.add_verifikasi_insentif_lembur','uses'=> 'VerifikasiInsentifLemburController@add_verifikasi_insentif_lembur']);
    Route::post('verifikasi_insentif_lembur/ajax_getemployeselectdeptid/',['as'=>'hris.verifikasi_insentif_lembur.ajax_getemployeselectdeptid','uses'=> 'VerifikasiInsentifLemburController@ajax_getemployeselectdeptid']);
    Route::post('verifikasi_insentif_lembur/ajax_getemployeselectnfl/',['as'=>'hris.verifikasi_insentif_lembur.ajax_getemployeselectnfl','uses'=> 'VerifikasiInsentifLemburController@ajax_getemployeselectnfl']);
    Route::post('verifikasi_insentif_lembur/ajax_gettanggalnfl/',['as'=>'hris.verifikasi_insentif_lembur.ajax_gettanggalnfl','uses'=> 'VerifikasiInsentifLemburController@ajax_gettanggalnfl']);
    Route::post('verifikasi_insentif_lembur/store_multi/',['as'=>'hris.verifikasi_insentif_lembur.store_multi','uses'=> 'VerifikasiInsentifLemburController@store_multi']);
    Route::post('verifikasi_insentif_lembur/update/',['as'=>'hris.verifikasi_insentif_lembur.update','uses'=> 'VerifikasiInsentifLemburController@update']);
    Route::post('verifikasi_insentif_lembur/delete/',['as'=>'hris.verifikasi_insentif_lembur.delete','uses'=> 'VerifikasiInsentifLemburController@delete']);
    Route::post('verifikasi_insentif_lembur/remove/',['as'=>'hris.verifikasi_insentif_lembur.remove','uses'=> 'VerifikasiInsentifLemburController@remove']);
    Route::post('verifikasi_insentif_lembur/ajax_exportexcel/',['as'=>'hris.verifikasi_insentif_lembur.ajax_exportexcel','uses'=> 'VerifikasiInsentifLemburController@ajax_exportexcel']);
    Route::post('verifikasi_insentif_lembur/ajax_getemployee',['as'=>'hris.verifikasi_insentif_lembur.ajax_getemployee','uses'=>'VerifikasiInsentifLemburController@ajax_getemployee']);
    Route::post('verifikasi_insentif_lembur/ajax_getsubdept',['as'=>'hris.verifikasi_insentif_lembur.ajax_getsubdept','uses'=>'VerifikasiInsentifLemburController@ajax_getsubdept']);
    Route::post('verifikasi_insentif_lembur/ajax_getnomorspl',['as'=>'hris.verifikasi_insentif_lembur.ajax_getnomorspl','uses'=>'VerifikasiInsentifLemburController@ajax_getnomorspl']);
    Route::post('verifikasi_insentif_lembur/ajax_verifikasi_insentif_lembur',['as'=>'hris.verifikasi_insentif_lembur.ajax_verifikasi_insentif_lembur','uses'=>'VerifikasiInsentifLemburController@ajax_verifikasi_insentif_lembur']);
    Route::post('verifikasi_insentif_lembur/ajax_verifikasi_insentif_lembur2',['as'=>'hris.verifikasi_insentif_lembur.ajax_verifikasi_insentif_lembur2','uses'=>'VerifikasiInsentifLemburController@ajax_verifikasi_insentif_lembur2']);
    Route::post('verifikasi_insentif_lembur/verificating',['as'=>'hris.verifikasi_insentif_lembur.verificating','uses'=>'VerifikasiInsentifLemburController@verificating']);
    Route::post('verifikasi_insentif_lembur/replace',['as'=>'hris.verifikasi_insentif_lembur.replace','uses'=>'VerifikasiInsentifLemburController@replace']);
    Route::post('verifikasi_insentif_lembur/updatelembur',['as'=>'hris.verifikasi_insentif_lembur.updatelembur','uses'=>'VerifikasiInsentifLemburController@updatelembur']);
    Route::post('verifikasi_insentif_lembur/updatelemburall',['as'=>'hris.verifikasi_insentif_lembur.updatelemburall','uses'=>'VerifikasiInsentifLemburController@updatelemburall']);
    Route::post('verifikasi_insentif_lembur/tambahkaryawan',['as'=>'hris.verifikasi_insentif_lembur.tambahkaryawan','uses'=>'VerifikasiInsentifLemburController@tambahkaryawan']);
    Route::post('verifikasi_insentif_lembur/removenospl',['as'=>'hris.verifikasi_insentif_lembur.removenospl','uses'=>'VerifikasiInsentifLemburController@removenospl']);
    Route::post('verifikasi_insentif_lembur/getnomorform',['as'=>'hris.verifikasi_insentif_lembur.getnomorform','uses'=>'VerifikasiInsentifLemburController@getnomorform']);
    Route::post('verifikasi_insentif_lembur/getnomorformnonsewing',['as'=>'hris.verifikasi_insentif_lembur.getnomorformnonsewing','uses'=>'VerifikasiInsentifLemburController@getnomorformnonsewing']);
    Route::post('verifikasi_insentif_lembur/getkaryawanlembur',['as'=>'hris.verifikasi_insentif_lembur.getkaryawanlembur','uses'=>'VerifikasiInsentifLemburController@getkaryawanlembur']);
    Route::post('verifikasi_insentif_lembur/importkaryawanlembur',['as'=>'hris.verifikasi_insentif_lembur.importkaryawanlembur','uses'=>'VerifikasiInsentifLemburController@importkaryawanlembur']);
    Route::post('verifikasi_insentif_lembur/import_data_lembur',['as'=>'hris.verifikasi_insentif_lembur.import_data_lembur','uses'=>'VerifikasiInsentifLemburController@import_data_lembur']);
    Route::post('verifikasi_insentif_lembur/importing_data_lembur',['as'=>'hris.verifikasi_insentif_lembur.importing_data_lembur','uses'=>'VerifikasiInsentifLemburController@importing_data_lembur']);
    Route::get('verifikasi_insentif_lembur/get_last_nomor_form_lembur',['as'=>'hris.verifikasi_insentif_lembur.get_last_nomor_form_lembur','uses'=>'VerifikasiInsentifLemburController@get_last_nomor_form_lembur']);
    // DATA LEMBUR
    Route::post('datalembur/ajax_datahadir/',['as'=>'hris.datalembur.ajax_datahadir','uses'=> 'DataLemburController@ajax_datahadir']);
    Route::post('datalembur/form_datalembur/',['as'=>'hris.datalembur.form_datalembur','uses'=> 'DataLemburController@form_datalembur']);
    Route::post('datalembur/getEmployeeLembur/',['as'=>'hris.datalembur.getEmployeeLembur','uses'=> 'DataLemburController@getEmployeeLembur']);
    Route::post('datalembur/ajax_getNomorFormLembur/',['as'=>'hris.datalembur.ajax_getNomorFormLembur','uses'=> 'DataLemburController@ajax_getNomorFormLembur']);

    Route::post('datalembur/ajax_getemployeselectdeptid/',['as'=>'hris.datalembur.ajax_getemployeselectdeptid','uses'=> 'DataLemburController@ajax_getemployeselectdeptid']);
    Route::post('datalembur/ajax_getemployeselectnfl/',['as'=>'hris.datalembur.ajax_getemployeselectnfl','uses'=> 'DataLemburController@ajax_getemployeselectnfl']);
    Route::post('datalembur/ajax_gettanggalnfl/',['as'=>'hris.datalembur.ajax_gettanggalnfl','uses'=> 'DataLemburController@ajax_gettanggalnfl']);
    Route::post('datalembur/store_multi/',['as'=>'hris.datalembur.store_multi','uses'=> 'DataLemburController@store_multi']);
    Route::post('datalembur/update/',['as'=>'hris.datalembur.update','uses'=> 'DataLemburController@update']);
    Route::post('datalembur/delete/',['as'=>'hris.datalembur.delete','uses'=> 'DataLemburController@delete']);
    Route::post('datalembur/remove/',['as'=>'hris.datalembur.remove','uses'=> 'DataLemburController@remove']);
    Route::post('datalembur/ajax_exportexcel/',['as'=>'hris.datalembur.ajax_exportexcel','uses'=> 'DataLemburController@ajax_exportexcel']);
    Route::post('datalembur/ajax_getemployee',['as'=>'hris.datalembur.ajax_getemployee','uses'=>'DataLemburController@ajax_getemployee']);
    Route::post('datalembur/ajax_getsubdept',['as'=>'hris.datalembur.ajax_getsubdept','uses'=>'DataLemburController@ajax_getsubdept']);
    Route::post('datalembur/ajax_getnomorspl',['as'=>'hris.datalembur.ajax_getnomorspl','uses'=>'DataLemburController@ajax_getnomorspl']);
    Route::post('datalembur/ajax_datalembur',['as'=>'hris.datalembur.ajax_datalembur','uses'=>'DataLemburController@ajax_datalembur']);
    Route::post('datalembur/ajax_datalembur2',['as'=>'hris.datalembur.ajax_datalembur2','uses'=>'DataLemburController@ajax_datalembur2']);
    Route::post('datalembur/verificating',['as'=>'hris.datalembur.verificating','uses'=>'DataLemburController@verificating']);
    Route::post('datalembur/replace',['as'=>'hris.datalembur.replace','uses'=>'DataLemburController@replace']);
    Route::post('datalembur/updatelembur',['as'=>'hris.datalembur.updatelembur','uses'=>'DataLemburController@updatelembur']);
    Route::post('datalembur/updatelemburall',['as'=>'hris.datalembur.updatelemburall','uses'=>'DataLemburController@updatelemburall']);
    Route::post('datalembur/tambahkaryawan',['as'=>'hris.datalembur.tambahkaryawan','uses'=>'DataLemburController@tambahkaryawan']);
    Route::post('datalembur/removenospl',['as'=>'hris.datalembur.removenospl','uses'=>'DataLemburController@removenospl']);
    Route::post('datalembur/getnomorform',['as'=>'hris.datalembur.getnomorform','uses'=>'DataLemburController@getnomorform']);
    Route::post('datalembur/getnomorformnonsewing',['as'=>'hris.datalembur.getnomorformnonsewing','uses'=>'DataLemburController@getnomorformnonsewing']);
    Route::post('datalembur/getkaryawanlembur',['as'=>'hris.datalembur.getkaryawanlembur','uses'=>'DataLemburController@getkaryawanlembur']);
    Route::post('datalembur/importkaryawanlembur',['as'=>'hris.datalembur.importkaryawanlembur','uses'=>'DataLemburController@importkaryawanlembur']);
    Route::post('datalembur/import_data_lembur',['as'=>'hris.datalembur.import_data_lembur','uses'=>'DataLemburController@import_data_lembur']);
    Route::post('datalembur/importing_data_lembur',['as'=>'hris.datalembur.importing_data_lembur','uses'=>'DataLemburController@importing_data_lembur']);
    Route::get('datalembur/get_last_nomor_form_lembur',['as'=>'hris.datalembur.get_last_nomor_form_lembur','uses'=>'DataLemburController@get_last_nomor_form_lembur']);

    // DATA PAYROLL
    Route::get('payroll/lembur/',['as'=>'hris.payroll.lembur','uses'=> 'PayrollController@lembur']);

    // DEPARTMENT ALL
    Route::post('departmentall/ajax_departmentall/',['as'=>'hris.departmentall.ajax_departmentall','uses'=> 'DepartmentAllController@ajax_departmentall']);
    Route::post('departmentall/store/',['as'=>'hris.departmentall.store','uses'=> 'DepartmentAllController@store']);
    Route::post('departmentall/show_data/',['as'=>'hris.departmentall.show_data','uses'=> 'DepartmentAllController@show_data']);
    Route::post('departmentall/edit_data/',['as'=>'hris.departmentall.edit_data','uses'=> 'DepartmentAllController@edit_data']);
    Route::post('departmentall/getSelectSubDept/',['as'=>'hris.departmentall.getSelectSubDept','uses'=> 'DepartmentAllController@getSelectSubDept']);
    Route::post('departmentall/getDepartmentName/',['as'=>'hris.departmentall.getDepartmentName','uses'=> 'DepartmentAllController@getDepartmentName']);
    Route::post('departmentall/getSelectSubDeptIn/',['as'=>'hris.departmentall.getSelectSubDeptIn','uses'=> 'DepartmentAllController@getSelectSubDeptIn']);
    Route::post('departmentall/getSelectDeptId/',['as'=>'hris.departmentall.getSelectDeptId','uses'=> 'DepartmentAllController@getSelectDeptId']);
    Route::post('departmentall/getJumlahKaryawan/',['as'=>'hris.departmentall.getJumlahKaryawan','uses'=> 'DepartmentAllController@getJumlahKaryawan']);
    Route::get('departmentall/export_excel_department_all',['as'=>'hris.departmentall.export_excel_department_all','uses'=>'DepartmentAllController@export_excel_department_all']);
    Route::post('departmentall/import_department',['as'=>'hris.departmentall.import_department','uses'=>'DepartmentAllController@import_department']);
    Route::post('departmentall/import_department_to_database',['as'=>'hris.departmentall.import_department_to_database','uses'=>'DepartmentAllController@import_department_to_database']);
    Route::post('departmentall/save_department_id',['as'=>'hris.departmentall.save_department_id','uses'=>'DepartmentAllController@save_department_id']);
    Route::post('departmentall/save_sub_department',['as'=>'hris.departmentall.save_sub_department','uses'=>'DepartmentAllController@save_sub_department']);
    Route::get('departmentall/get_last_dept_id',['as'=>'hris.departmentall.get_last_dept_id','uses'=>'DepartmentAllController@get_last_dept_id']);
    Route::get('departmentall/get_dept_name',['as'=>'hris.departmentall.get_dept_name','uses'=>'DepartmentAllController@get_dept_name']);

    // EMPLOYEE ATTRIBUTE
    Route::post('employeeatr/ajax_getemployeeatr/',['as'=>'hris.employeeatr.ajax_getemployeeatr','uses'=> 'EmployeeAtrController@ajax_getemployeeatr']);
    Route::post('employeeatr/ajax_getemployeeatr2/',['as'=>'hris.employeeatr.ajax_getemployeeatr2','uses'=> 'EmployeeAtrController@ajax_getemployeeatr2']);
    Route::post('employeeatr/ajax_getemployeeatr3/',['as'=>'hris.employeeatr.ajax_getemployeeatr3','uses'=> 'EmployeeAtrController@ajax_getemployeeatr3']);
    Route::post('employeeatr/ajax_getemployeeatr4/',['as'=>'hris.employeeatr.ajax_getemployeeatr4','uses'=> 'EmployeeAtrController@ajax_getemployeeatr4']);
    Route::post('employeeatr/ajax_getemployeeids/',['as'=>'hris.employeeatr.ajax_getemployeeids','uses'=> 'EmployeeAtrController@ajax_getemployeeids']);
    Route::post('employeeatr/ajax_getemployeeid/',['as'=>'hris.employeeatr.ajax_getemployeeid','uses'=> 'EmployeeAtrController@ajax_getemployeeid']);
    Route::post('employeeatr/ajax_getcheckedemployee/',['as'=>'hris.employeeatr.ajax_getcheckedemployee','uses'=> 'EmployeeAtrController@ajax_getcheckedemployee']);
    Route::post('employeeatr/ajax_getempatr/',['as'=>'hris.employeeatr.ajax_getempatr','uses'=> 'EmployeeAtrController@ajax_getempatr']);
    Route::post('employeeatr/ajax_getselectdept/',['as'=>'hris.employeeatr.ajax_getselectdept','uses'=> 'EmployeeAtrController@ajax_getselectdept']);
    Route::post('employeeatr/ajax_getselectsubdept/',['as'=>'hris.employeeatr.ajax_getselectsubdept','uses'=> 'EmployeeAtrController@ajax_getselectsubdept']);
    Route::post('employeeatr/ajax_periksaenroll_id/',['as'=>'hris.employeeatr.ajax_periksaenroll_id','uses'=> 'EmployeeAtrController@ajax_periksaenroll_id']);
    Route::post('employeeatr/ajax_periksanik/',['as'=>'hris.employeeatr.ajax_periksanik','uses'=> 'EmployeeAtrController@ajax_periksanik']);
    Route::post('employeeatr/create/',['as'=>'hris.employeeatr.create','uses'=> 'EmployeeAtrController@create']);
    Route::post('employeeatr/replace/',['as'=>'hris.employeeatr.replace','uses'=> 'EmployeeAtrController@replace']);
    Route::post('employeeatr/destroy/',['as'=>'hris.employeeatr.destroy','uses'=> 'EmployeeAtrController@destroy']);
    Route::post('employeeatr/ajax_exportexcel/',['as'=>'hris.employeeatr.ajax_exportexcel','uses'=> 'EmployeeAtrController@ajax_exportexcel']);
    Route::post('employeeatr/import_employees/',['as'=>'hris.employeeatr.import_employees','uses'=> 'EmployeeAtrController@import_employees']);
    Route::post('employeeatr/import_employee_to_database/',['as'=>'hris.employeeatr.import_employee_to_database','uses'=> 'EmployeeAtrController@import_employee_to_database']);
    Route::get('employeeatr/export_pdf_id_card/',['as'=>'hris.employeeatr.export_pdf_id_card','uses'=> 'EmployeeAtrController@export_pdf_id_card']);
    Route::get('employeeatr/export_pdf_id_card_department/',['as'=>'hris.employeeatr.export_pdf_id_card_department','uses'=> 'EmployeeAtrController@export_pdf_id_card_department']);
    Route::get('employeeatr/export_pdf_id_card_employee/',['as'=>'hris.employeeatr.export_pdf_id_card_employee','uses'=> 'EmployeeAtrController@export_pdf_id_card_employee']);
    Route::post('employeeatr/store_photo/',['as'=>'hris.employeeatr.store_photo','uses'=> 'EmployeeAtrController@store_photo']);
    Route::post('employeeatr/get_photo/',['as'=>'hris.employeeatr.get_photo','uses'=>'EmployeeAtrController@get_photo']);
    Route::post('employeeatr/select_employee/',['as'=>'hris.employeeatr.select_employee','uses'=>'EmployeeAtrController@select_employee']);
    Route::post('employeeatr/change_photo_profile/',['as'=>'hris.employeeatr.change_photo_profile','uses'=> 'EmployeeAtrController@change_photo_profile']);
    Route::post('employeeatr/get_employee/',['as'=>'hris.employeeatr.get_employee','uses'=>'EmployeeAtrController@get_employee']);
    Route::post('employeeatr/already_print/',['as'=>'hris.employeeatr.already_print','uses'=> 'EmployeeAtrController@already_print']);
    Route::post('employeeatr/not_yet_printed/',['as'=>'hris.employeeatr.not_yet_printed','uses'=> 'EmployeeAtrController@not_yet_printed']);
    Route::post('employeeatr/set_already_print_sk/',['as'=>'hris.employeeatr.set_already_print_sk','uses'=> 'EmployeeAtrController@set_already_print_sk']);
    Route::post('employeeatr/set_back_print_sk/',['as'=>'hris.employeeatr.set_back_print_sk','uses'=> 'EmployeeAtrController@set_back_print_sk']);
    Route::post('employeeatr/import_employee_excel/',['as'=>'hris.employeeatr.import_employee_excel','uses'=> 'EmployeeAtrController@uploadEmployee']);

    // REF ABSEN IJIN
    Route::post('refabsenijin/ajax_refabsenijin',['as'=>'hris.refabsenijin.ajax_refabsenijin','uses'=>'RefAbsenIjinController@ajax_refabsenijin']);
    //Route::post('refabsenijin/index',['as'=>'hris.refabsenijin.ajax_refabsenijin','uses'=>'RefAbsenIjinController@ajax_refabsenijin']);
    Route::post('refabsenijin/replace',['as'=>'hris.refabsenijin.replace','uses'=>'RefAbsenIjinController@replace']);
    Route::post('refabsenijin/destroy',['as'=>'hris.refabsenijin.destroy','uses'=>'RefAbsenIjinController@destroy']);
    //Route::post('refabsenijin/ajax_getmodalabsenijin/',['as'=>'hris.refabsenijin.ajax_getmodalabsenijin','uses'=> 'RefAbsenIjinController@ajax_getmodalabsenijin']);

    // REF ABSEN IJIN
    Route::post('refharilibur/ajax_refharilibur',['as'=>'hris.refharilibur.ajax_refharilibur','uses'=>'RefHariLiburController@ajax_refharilibur']);
    Route::post('refharilibur/replace',['as'=>'hris.refharilibur.replace','uses'=>'RefHariLiburController@replace']);
    Route::post('refharilibur/destroy',['as'=>'hris.refharilibur.destroy','uses'=>'RefHariLiburController@destroy']);

    Route::post('datajadwalkerjalog/ajax_datajadwalkerjalog',['as'=>'hris.datajadwalkerjalog.ajax_datajadwalkerjalog','uses'=>'DataJadwalKerjaLogController@ajax_datajadwalkerjalog']);
    Route::post('datajadwalkerjalog/ajax_datahadir',['as'=>'hris.datajadwalkerjalog.ajax_datahadir','uses'=>'DataJadwalKerjaLogController@ajax_datahadir']);
    Route::post('datajadwalkerjalog/replace',['as'=>'hris.datajadwalkerjalog.replace','uses'=>'DataJadwalKerjaLogController@replace']);
    Route::post('datajadwalkerjalog/process',['as'=>'hris.datajadwalkerjalog.process','uses'=>'DataJadwalKerjaLogController@process']);
    Route::post('datajadwalkerjalog/ajax_getemployeselectdeptid/',['as'=>'hris.datajadwalkerjalog.ajax_getemployeselectdeptid','uses'=> 'DataJadwalKerjaLogController@ajax_getemployeselectdeptid']);
    Route::post('datajadwalkerjalog/ajax_getallemployeeatribut/',['as'=>'hris.datajadwalkerjalog.ajax_getallemployeeatribut','uses'=> 'DataJadwalKerjaLogController@ajax_getallemployeeatribut']);
    Route::post('datajadwalkerjalog/getEmployeeKehadiran/',['as'=>'hris.datajadwalkerjalog.getEmployeeKehadiran','uses'=> 'DataJadwalKerjaLogController@getEmployeeKehadiran']);

    Route::post('dataabsenperijinan/ajax_dataabsenperizinan',['as'=>'hris.dataabsenperijinan.ajax_dataabsenperizinan','uses'=>'DataAbsenPerijinanController@ajax_dataabsenperizinan']);
    Route::post('dataabsenperijinan/ajax_getkehadiran/',['as'=>'hris.dataabsenperijinan.ajax_getkehadiran','uses'=> 'DataAbsenPerijinanController@ajax_getkehadiran']);
    Route::post('dataabsenperijinan/create_perizinan/',['as'=>'hris.dataabsenperijinan.create_perizinan','uses'=> 'DataAbsenPerijinanController@create_perizinan']);
    Route::post('dataabsenperijinan/create_iks/',['as'=>'hris.dataabsenperijinan.create_iks','uses'=> 'DataAbsenPerijinanController@create_iks']);
    Route::post('dataabsenperijinan/create_perizinan_menu/',['as'=>'hris.dataabsenperijinan.create_perizinan_menu','uses'=> 'DataAbsenPerijinanController@create_perizinan_menu']);
    Route::post('dataabsenperijinan/update_perizinan_menu/',['as'=>'hris.dataabsenperijinan.update_perizinan_menu','uses'=> 'DataAbsenPerijinanController@update_perizinan_menu']);
    Route::post('dataabsenperijinan/create_iks_menu/',['as'=>'hris.dataabsenperijinan.create_iks_menu','uses'=> 'DataAbsenPerijinanController@create_iks_menu']);
    Route::post('dataabsenperijinan/update_iks_menu/',['as'=>'hris.dataabsenperijinan.update_iks_menu','uses'=> 'DataAbsenPerijinanController@update_iks_menu']);
    Route::post('dataabsenperijinan/cekperizinan/',['as'=>'hris.dataabsenperijinan.cekperizinan','uses'=> 'DataAbsenPerijinanController@cekperizinan']);
    Route::post('dataabsenperijinan/cekiks/',['as'=>'hris.dataabsenperijinan.cekiks','uses'=> 'DataAbsenPerijinanController@cekiks']);
    Route::post('dataabsenperijinan/destroy',['as'=>'hris.dataabsenperijinan.destroy','uses'=>'DataAbsenPerijinanController@destroy']);
    Route::post('dataabsenperijinan/ajax_exportexcel/',['as'=>'hris.dataabsenperijinan.ajax_exportexcel','uses'=> 'DataAbsenPerijinanController@ajax_exportexcel']);
    Route::post('dataabsenperijinan/ajax_exportexcel2/',['as'=>'hris.dataabsenperijinan.ajax_exportexcel2','uses'=> 'DataAbsenPerijinanController@ajax_exportexcel2']);
    Route::post('dataabsenperijinan/import_data_perizinan/',['as'=>'hris.dataabsenperijinan.import_data_perizinan','uses'=> 'DataAbsenPerijinanController@import_data_perizinan']);
    Route::post('dataabsenperijinan/import_perizinan_to_database/',['as'=>'hris.dataabsenperijinan.import_perizinan_to_database','uses'=> 'DataAbsenPerijinanController@import_perizinan_to_database']);
    Route::post('dataabsenperijinan/get_last_nomor_form_perizinan/',['as'=>'hris.dataabsenperijinan.get_last_nomor_form_perizinan','uses'=> 'DataAbsenPerijinanController@get_last_nomor_form_perizinan']);
    Route::post('dataabsenperijinan/get_last_nomor_form_perizinan_iks/',['as'=>'hris.dataabsenperijinan.get_last_nomor_form_perizinan_iks','uses'=> 'DataAbsenPerijinanController@get_last_nomor_form_perizinan_iks']);

    Route::post('koreksiupah/ajax_datakoreksiupah',['as'=>'hris.koreksiupah.ajax_datakoreksiupah','uses'=>'KoreksiUpahController@ajax_datakoreksiupah']);
    Route::post('koreksiupah/ajax_datainsjabatan',['as'=>'hris.koreksiupah.ajax_datainsjabatan','uses'=>'KoreksiUpahController@ajax_datainsjabatan']);
    Route::post('koreksiupah/create',['as'=>'hris.koreksiupah.create','uses'=>'KoreksiUpahController@create']);
    Route::post('koreksiupah/update',['as'=>'hris.koreksiupah.update','uses'=>'KoreksiUpahController@update']);
    Route::post('koreksiupah/destroy',['as'=>'hris.koreksiupah.destroy','uses'=>'KoreksiUpahController@destroy']);
    Route::post('koreksiupah/cek_koreksi_upah',['as'=>'hris.koreksiupah.cek_koreksi_upah','uses'=>'KoreksiUpahController@cek_koreksi_upah']);
    Route::post('koreksiupah/add_position_insentif',['as'=>'hris.koreksiupah.add_position_insentif','uses'=>'KoreksiUpahController@add_position_insentif']);
    Route::post('koreksiupah/delete_position_insentif',['as'=>'hris.koreksiupah.delete_position_insentif','uses'=>'KoreksiUpahController@delete_position_insentif']);
    Route::post('koreksiupah/get_active_employee',['as'=>'hris.koreksiupah.get_active_employee','uses'=>'KoreksiUpahController@get_active_employee']);
    Route::get('koreksiupah/datatable_ins_jabatan',['as'=>'hris.koreksiupah.datatable_ins_jabatan','uses'=>'KoreksiUpahController@datatable_ins_jabatan']);

    Route::post('koreksipotongan/ajax_datakoreksipotongan',['as'=>'hris.koreksipotongan.ajax_datakoreksipotongan','uses'=>'KoreksiPotonganController@ajax_datakoreksipotongan']);
    Route::post('koreksipotongan/create',['as'=>'hris.koreksipotongan.create','uses'=>'KoreksiPotonganController@create']);
    Route::post('koreksipotongan/update',['as'=>'hris.koreksipotongan.update','uses'=>'KoreksiPotonganController@update']);
    Route::post('koreksipotongan/destroy',['as'=>'hris.koreksipotongan.destroy','uses'=>'KoreksiPotonganController@destroy']);

    Route::get('bgprocess/index',['as'=>'hris.bgprocess.index','uses'=>'BgProcessController@index']);
    Route::post('bgprocess/ajax_bgprocess',['as'=>'hris.bgprocess.ajax_bgprocess','uses'=>'BgProcessController@ajax_bgprocess']);
    Route::post('bgprocess/create',['as'=>'hris.bgprocess.create','uses'=>'BgProcessController@create']);
    Route::post('bgprocess/update',['as'=>'hris.bgprocess.update','uses'=>'BgProcessController@update']);
    Route::post('bgprocess/destroy',['as'=>'hris.bgprocess.destroy','uses'=>'BgProcessController@destroy']);

    Route::post('rekapperhitunganlembur/ajax_rekap',['as'=>'hris.rekapperhitunganlembur.ajax_rekap','uses'=>'RekapPerhitunganLemburController@ajax_rekap']);
    Route::post('rekapperhitunganlembur/ajax_exportexcel/',['as'=>'hris.rekapperhitunganlembur.ajax_exportexcel','uses'=> 'RekapPerhitunganLemburController@ajax_exportexcel']);

    Route::get('rekapkehadirankaryawan/index',['as'=>'hris.rekapkehadirankaryawan.index','uses'=>'RekapKehadiranKaryawanController@index']);
    Route::post('rekapkehadirankaryawan/ajax_rekap',['as'=>'hris.rekapkehadirankaryawan.ajax_rekap','uses'=>'RekapKehadiranKaryawanController@ajax_rekap']);
    Route::post('rekapkehadirankaryawan/ajax_exportexcel/',['as'=>'hris.rekapkehadirankaryawan.ajax_exportexcel','uses'=> 'RekapKehadiranKaryawanController@ajax_exportexcel']);
    Route::post('rekapkehadirankaryawan/ajax_getemployeselectstaff/',['as'=>'hris.rekapkehadirankaryawan.ajax_getemployeselectstaff','uses'=> 'RekapKehadiranKaryawanController@ajax_getemployeselectstaff']);
    Route::post('rekapkehadirankaryawan/ajax_getallemployeeatribut/',['as'=>'hris.rekapkehadirankaryawan.ajax_getallemployeeatribut','uses'=> 'RekapKehadiranKaryawanController@ajax_getallemployeeatribut']);
    Route::post('rekapkehadirankaryawan/daily_report/',['as'=>'hris.rekapkehadirankaryawan.daily_report','uses'=> 'RekapKehadiranKaryawanController@excel_rekap_absen']);
    Route::post('rekapkehadirankaryawan/summary_report/',['as'=>'hris.rekapkehadirankaryawan.summary_report','uses'=> 'RekapKehadiranKaryawanController@summary_report']);
    Route::post('rekapkehadirankaryawan/update_rekap_absen/',['as'=>'hris.rekapkehadirankaryawan.update_rekap_absen','uses'=> 'RekapKehadiranKaryawanController@proses_rekap']);

    Route::post('employeebpjs/ajax_empbpjs',['as'=>'hris.employeebpjs.ajax_empbpjs','uses'=>'EmployeeBpjsController@ajax_empbpjs']);
    Route::post('employeebpjs/ajax_exportexcel/',['as'=>'hris.employeebpjs.ajax_exportexcel','uses'=> 'EmployeeBpjsController@ajax_exportexcel']);
    Route::post('employeebpjs/ajax_getemployeselectstaff/',['as'=>'hris.employeebpjs.ajax_getemployeselectstaff','uses'=> 'EmployeeBpjsController@ajax_getemployeselectstaff']);
    Route::post('employeebpjs/ajax_getallemployeeatribut/',['as'=>'hris.employeebpjs.ajax_getallemployeeatribut','uses'=> 'EmployeeBpjsController@ajax_getallemployeeatribut']);
    Route::post('employeebpjs/getempbpjsbyid/',['as'=>'hris.employeebpjs.getempbpjsbyid','uses'=> 'EmployeeBpjsController@getempbpjsbyid']);
    Route::post('employeebpjs/update_bpjs/',['as'=>'hris.employeebpjs.update_bpjs','uses'=> 'EmployeeBpjsController@update_bpjs']);
    Route::post('employeebpjs/ajax_bpjssetting/',['as'=>'hris.employeebpjs.ajax_bpjssetting','uses'=> 'EmployeeBpjsController@ajax_bpjssetting']);
    Route::post('employeebpjs/update/',['as'=>'hris.employeebpjs.update','uses'=> 'EmployeeBpjsController@update']);

    Route::post('dasarpotbpjs/ajax_data',['as'=>'hris.dasarpotbpjs.ajax_data','uses'=>'DasarPotBpjsController@ajax_data']);
    Route::post('dasarpotbpjs/replace',['as'=>'hris.dasarpotbpjs.replace','uses'=>'DasarPotBpjsController@replace']);
    Route::post('dasarpotbpjs/destroy',['as'=>'hris.dasarpotbpjs.destroy','uses'=>'DasarPotBpjsController@destroy']);

    Route::post('bpjssetting/ajax_data',['as'=>'hris.bpjssetting.ajax_data','uses'=>'BpjsSettingController@ajax_data']);
    Route::post('bpjssetting/replace',['as'=>'hris.bpjssetting.replace','uses'=>'BpjsSettingController@replace']);
    Route::post('bpjssetting/destroy',['as'=>'hris.bpjssetting.destroy','uses'=>'BpjsSettingController@destroy']);


    Route::post('gradingsalary/ajax_data',['as'=>'hris.gradingsalary.ajax_data','uses'=>'GradingSalaryController@ajax_data']);
    Route::post('gradingsalary/replace',['as'=>'hris.gradingsalary.replace','uses'=>'GradingSalaryController@replace']);
    Route::post('gradingsalary/destroy',['as'=>'hris.gradingsalary.destroy','uses'=>'GradingSalaryController@destroy']);




    Route::post('employeegrading/ajax_data',['as'=>'hris.employeegrading.ajax_data','uses'=>'EmployeeGradingController@ajax_data']);
    Route::post('employeegrading/replace',['as'=>'hris.employeegrading.replace','uses'=>'EmployeeGradingController@replace']);
    Route::post('employeegrading/destroy',['as'=>'hris.employeegrading.destroy','uses'=>'EmployeeGradingController@destroy']);
    Route::post('employeegrading/update_grading',['as'=>'hris.employeegrading.update_grading','uses'=>'EmployeeGradingController@update_grading']);

    Route::post('tunjangankaryawan/ajax_data',['as'=>'hris.tunjangankaryawan.ajax_data','uses'=>'TunjanganKaryawanController@ajax_data']);
    Route::post('tunjangankaryawan/ajax_getemployee',['as'=>'hris.tunjangankaryawan.ajax_getemployee','uses'=>'TunjanganKaryawanController@ajax_getemployee']);
    Route::post('tunjangankaryawan/ajax_getnamatunjangan',['as'=>'hris.tunjangankaryawan.ajax_getnamatunjangan','uses'=>'TunjanganKaryawanController@ajax_getnamatunjangan']);
    Route::post('tunjangankaryawan/replace',['as'=>'hris.tunjangankaryawan.replace','uses'=>'TunjanganKaryawanController@replace']);
    Route::post('tunjangankaryawan/destroy',['as'=>'hris.tunjangankaryawan.destroy','uses'=>'TunjanganKaryawanController@destroy']);
    Route::post('tunjangankaryawan/edit',['as'=>'hris.tunjangankaryawan.edit','uses'=>'TunjanganKaryawanController@edit']);

    Route::post('rekapperhitungandtpc/ajax_rekap',['as'=>'hris.rekapperhitungandtpc.ajax_rekap','uses'=>'RekapPerhitunganDtpcController@ajax_rekap']);
    Route::post('rekapperhitungandtpc/ajax_exportexcel/',['as'=>'hris.rekapperhitungandtpc.ajax_exportexcel','uses'=> 'RekapPerhitunganDtpcController@ajax_exportexcel']);


    Route::post('rekapperhitunganiks/ajax_rekap',['as'=>'hris.rekapperhitunganiks.ajax_rekap','uses'=>'RekapPerhitunganIksController@ajax_rekap']);
    Route::post('rekapperhitunganiks/ajax_exportexcel/',['as'=>'hris.rekapperhitunganiks.ajax_exportexcel','uses'=> 'RekapPerhitunganIksController@ajax_exportexcel']);


    Route::post('rekapperhitunganpayroll/ajax_exportexcel/',['as'=>'hris.rekapperhitunganpayroll.ajax_exportexcel','uses'=> 'RekapPerhitunganPayrollController@ajax_exportexcel']);

    Route::get('rekapperhitunganpayrollcutoff/index',['as'=>'hris.rekapperhitunganpayrollcutoff.index','uses'=>'RekapPerhitunganPayrollCutOffController@index']);
    Route::post('rekapperhitunganpayrollcutoff/ajax_exportexcel/',['as'=>'hris.rekapperhitunganpayrollcutoff.ajax_exportexcel','uses'=> 'RekapPerhitunganPayrollCutOffController@ajax_exportexcel']);
    Route::post('rekapperhitunganpayrollcutoff/ajax_prosescutoff/',['as'=>'hris.rekapperhitunganpayrollcutoff.ajax_prosescutoff','uses'=> 'RekapPerhitunganPayrollCutOffController@ajax_prosescutoff']);

    Route::get('rekapperhitunganpayroll/export_excel_transfer',['as'=>'hris.rekapperhitunganpayroll.export_excel_transfer','uses'=>'ProsesPayrollController@export_excel_transfer']);
    Route::post('rekapperhitunganpayroll/ajax_data/',['as'=>'hris.rekapperhitunganpayroll.ajax_data','uses'=> 'RekapPerhitunganPayrollController@ajax_data']);
    Route::post('rekapperhitunganpayroll/get_payroll_department/',['as'=>'hris.rekapperhitunganpayroll.get_payroll_department','uses'=> 'RekapPerhitunganPayrollController@get_payroll_department']);
    Route::get('rekapperhitunganpayroll/get_sub_dept_name/{id}',['as'=>'hris.rekapperhitunganpayroll.get_sub_dept_name','uses'=> 'RekapPerhitunganPayrollController@get_sub_dept_name']);
    Route::get('rekapperhitunganpayroll/export_excel_summary_department',['as'=>'hris.rekapperhitunganpayroll.export_excel_summary_department','uses'=> 'RekapPerhitunganPayrollController@export_excel_summary_department']);
    Route::get('rekapperhitunganpayroll/get_last_update_proses_payroll',['as'=>'hris.rekapperhitunganpayroll.get_last_update_proses_payroll','uses'=> 'RekapPerhitunganPayrollController@get_last_update_proses_payroll']);
    Route::get('rekapperhitunganpayroll/get_last_update_labor',['as'=>'hris.rekapperhitunganpayroll.get_last_update_labor','uses'=> 'RekapPerhitunganPayrollController@get_last_update_labor']);
    Route::post('rekapperhitunganpayroll/export_excel_daily_labor',['as'=>'hris.rekapperhitunganpayroll.export_excel_daily_labor','uses'=> 'RekapPerhitunganPayrollController@export_excel_daily_labor']);
    Route::post('rekapperhitunganpayroll/proses_payroll_harian',['as'=>'hris.rekapperhitunganpayroll.proses_payroll_harian','uses'=> 'RekapPerhitunganPayrollController@proses_payroll_harian']);
    Route::post('rekapperhitunganpayroll/recap_labor_cost',['as'=>'hris.rekapperhitunganpayroll.recap_labor_cost','uses'=> 'RekapPerhitunganPayrollController@recap_labor_cost']);
    Route::post('rekapperhitunganpayroll/recap_labor_cost_2',['as'=>'hris.rekapperhitunganpayroll.recap_labor_cost_2','uses'=> 'RekapPerhitunganPayrollController@recap_labor_cost_2']);



    Route::post('dataclosingpayroll/ajax_data',['as'=>'hris.dataclosingpayroll.ajax_data','uses'=>'DataClosingPayrollController@ajax_data']);
    Route::post('dataclosingpayroll/ajax_getclosing/',['as'=>'hris.dataclosingpayroll.ajax_getclosing','uses'=> 'DataClosingPayrollController@ajax_getclosing']);
    Route::post('dataclosingpayroll/ajax_getclosing_datahadir/',['as'=>'hris.dataclosingpayroll.ajax_getclosing_datahadir','uses'=> 'DataClosingPayrollController@ajax_getclosing_datahadir']);
    Route::post('dataclosingpayroll/create/',['as'=>'hris.dataclosingpayroll.create','uses'=> 'DataClosingPayrollController@create']);
    Route::post('dataclosingpayroll/update/',['as'=>'hris.dataclosingpayroll.update','uses'=> 'DataClosingPayrollController@update']);
    Route::post('dataclosingpayroll/destroy/',['as'=>'hris.dataclosingpayroll.destroy','uses'=> 'DataClosingPayrollController@destroy']);
    Route::post('daily_labor/proses/',['as'=>'hris.daily_labor.proses','uses'=> 'DailyLaborController@proses']);
    Route::post('daily_labor/export_excel/',['as'=>'hris.daily_labor.export_excel','uses'=> 'DailyLaborController@export_excel']);
    Route::post('daily_labor/get_last_update_labor_cost/',['as'=>'hris.daily_labor.get_last_update_labor_cost','uses'=> 'DailyLaborController@get_last_update_labor_cost']);


    Route::post('aktifitasperubahan/ajax_data/',['as'=>'hris.aktifitasperubahan.ajax_data','uses'=> 'AktifitasPerubahan@ajax_data']);

    include "routechunks/new.php";
});

// Admin Panel After Login
Route::group(['middleware' => ['auth.admin', 'lock'], 'prefix' => 'admin','namespace' => 'Admin'], function()
{

    //	Dashboard Routing
    //Route::resource('dashboard', 'AdminDashboardController');
    Route::resource('dashboard', 'AdminDashboardController',['as' => 'admin']);
    Route::get('admin/index',['as'=>'admin.admin.index','uses'=>'AdminController@index']);

    //   Admin user Routing
    Route::post('admin/ajaxAdmin/',['as'=>'admin.admin.ajaxAdmin','uses'=> 'AdminController@ajaxAdmin']);
    Route::post('admin/show_data/',['as'=>'admin.admin.show_data','uses'=> 'AdminController@show_data']);
    Route::post('admin/edit_data/',['as'=>'admin.admin.edit_data','uses'=> 'AdminController@edit_data']);
    Route::post('admin/store/',['as'=>'admin.admin.store','uses'=> 'AdminController@store']);
    Route::post('admin/update/',['as'=>'admin.admin.update','uses'=> 'AdminController@update']);
    Route::post('admin/destroy/',['as'=>'admin.admin.destroy','uses'=> 'AdminController@destroy']);
    Route::post('admin/keepalive/',['as'=>'admin.admin.keepalive','uses'=> 'AdminController@keepalive']);
    Route::get('admin/editprofile/',['as'=>'admin.admin.editprofile','uses'=> 'AdminController@editprofile']);
    Route::post('admin/ajax_resetpwd/',['as'=>'admin.admin.ajax_resetpwd','uses'=> 'AdminController@ajax_resetpwd']);

    // NOTIFIKASI
    Route::get('/notifications',['as'=>'admin.admin.get_notifications','uses'=>'AdminController@get_notifications']);

    Route::post('/notifications/mark-as-read/{id}',['as'=>'admin.admin.markAsRead','uses'=>'AdminController@markAsRead']);
    Route::post('/notifications/mark-as-delete/{id}',['as'=>'admin.admin.markAsDelete','uses'=>'AdminController@markAsDelete']);

    Route::get('datakehadiraninoutedited/abseninout/',['as'=>'admin.datakehadiraninoutedited.abseninout','uses'=> 'DataKehadiranInOutEditedController@abseninout']);
    Route::post('datakehadiraninoutedited/ajax_abseninout/',['as'=>'admin.datakehadiraninoutedited.ajax_abseninout','uses'=> 'DataKehadiranInOutEditedController@ajax_abseninout']);
    Route::post('datakehadiraninoutedited/ajax_abseninout_edited/',['as'=>'admin.datakehadiraninoutedited.ajax_abseninout_edited','uses'=> 'DataKehadiranInOutEditedController@ajax_abseninout_edited']);
    Route::post('datakehadiraninoutedited/form_kehadiraninout_edited/',['as'=>'admin.datakehadiraninoutedited.form_kehadiraninout_edited','uses'=> 'DataKehadiranInOutEditedController@form_kehadiraninout_edited']);
    Route::post('datakehadiraninoutedited/store/',['as'=>'admin.datakehadiraninoutedited.store','uses'=> 'DataKehadiranInOutEditedController@store']);
    Route::post('datakehadiraninoutedited/status_kerja/',['as'=>'admin.datakehadiraninoutedited.status_kerja','uses'=> 'DataKehadiranInOutEditedController@status_kerja']);



});
Route::get('/tes2','Hris\EmployeeAtrController@creat_master_absen_26');
Event::listen('auth.login', function($user)
{
    $user->last_login = new DateTime;
    $user->save();
});
// Lock Screen Routing
Route::get('screenlock', 'Admin\AdminController@screenlock');


Route::get('/show-absensi', function(){
    return view('hris.show-absensi.show_absensi');
});

Route::get('/controller-show-absensi','HandleAbsensiController@index');


Route::get('/trigger', 'Admin\AdminController@trigger_event');



    // NOTIFIKASI
