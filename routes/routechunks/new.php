<?php

Route::prefix('datalembur')->group(function() {
    Route::post('verifikasi', 'DataLemburController@verifikasi')->name("hris.datalembur.verifikasi");
    Route::get('unverifikasi/{uuid}', 'DataLemburController@unverifikasi')->name("hris.datalembur.unverifikasi");
    Route::post('create','DataLemburController@CreateSpl')->name("hris.datalembur.create");
}); 

Route::prefix('employeeatr')->group(function() {
    //Route::get('format-import', 'EmployeeAtrController@format_import_grading')->name("hris.employeeatr.format");
    Route::get('format-import', 'EmployeeAtrController@export_import')->name("hris.employeeatr.format");

    Route::post('import-grading', 'EmployeeAtrController@import_grading')->name("hris.employeeatr.import.grading");
}); 

Route::prefix('dataabsenperijinan')->group(function() {
    Route::get('data-perijinan-verifikasi','DataAbsenPerijinanController@perijinan_verifikasi')->name("hris.dataabsenperijinan.verifikasi");
    Route::post('data-perijinan-get','DataAbsenPerijinanController@perijinan_verifikasi_get')->name("hris.dataabsenperijinan.get");
    Route::post('perijinan-verifikasi','DataAbsenPerijinanController@perijinan_verifikasi_store')->name("hris.verifikasiperijinan.store");

    Route::post('perijinan-export','DataAbsenPerijinanController@perijinan_export')->name("hris.exportperijinan.store");

}); 

Route::prefix('koreksiupah')->group(function() {
    Route::get('format-koreksiupah','KoreksiUpahController@format_import_koreksiupah')->name("hris.koreksiupah.format");
    Route::post('import-koreksiupah', 'KoreksiUpahController@import_koreksiupah')->name("hris.employeeatr.import.koreksiupah");
    Route::post('export-koreksiupah', 'KoreksiUpahController@export_koreksiupah')->name("hris.employeeatr.export.koreksiupah");

}); 

Route::prefix('koreksipotongan')->group(function() {
    Route::get('format-koreksipotongan','KoreksiPotonganController@format_import_koreksipotongan')->name("hris.koreksipotongan.format");
    Route::post('import-koreksipotongan', 'KoreksiPotonganController@import_koreksipotongan')->name("hris.import.koreksipotongan");
    Route::post('export-koreksipotongan', 'KoreksiPotonganController@export_koreksipotongan')->name("hris.export.koreksipotongan");

}); 

Route::prefix('mdabsenhadir')->group(function() {
    Route::post('download_mesin_kehadiran-lintas', 'MdAbsenHadirController@download_mesin_kehadiran_lintas')->name("hris.mdabsenhadir.download_mesin_kehadiran_lintas");
    Route::post('edit-jadwal', 'MdAbsenHadirController@edit_jadwal')->name("hris.mdabsenhadir.edit_jadwal");

}); 



Route::prefix('payroll')->group(function() {
    Route::post('proses-rekap', 'ProsesPayrollController@index')->name("hris.proses.payroll.rekap");
    Route::post('proses-rekap2', 'ProsesPayrollController@index2')->name("hris.proses.payroll.rekap2");
    Route::post('proses-rekap3', 'ProsesPayrollController@index3')->name("hris.proses.payroll.rekap3");
    Route::post('proses-lembur', 'ProsesPayrollController@index4')->name("hris.proses.lembur.rekap");
    Route::get('test2', 'ProsesPayrollController@index2')->name("hris.coba2");


});
Route::prefix('estimasi')->group(function() {
    Route::get('index', 'EstimasiPayrollController@index')->name("hris.estimasinilaipayroll.index");
    Route::post('ajax_exportexcel', 'EstimasiPayrollController@ajax_exportexcel')->name("hris.estimasinilaipayroll.export");

});
// Route::prefix('daily_labor')->group(function() {
//     Route::get('index', 'DailyLaborController@index')->name("hris.nilaipayrollperhari.index");
// });

Route::prefix('junal')->group(function() {
    Route::get('index', 'JurnalController@index')->name("hris.jurnal.index");
    Route::post('ajax_exportexcel', 'JurnalController@ajax_exportexcel')->name("hris.jurnal.export");

});

?>