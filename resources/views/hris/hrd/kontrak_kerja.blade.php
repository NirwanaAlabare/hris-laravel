@extends('admin.adminlayouts.adminlayout3')

@section('head')
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('assets/css/iziToast.min.css') }}">


<style>
    .modal-body {
    max-height: 90vh; /* Sesuaikan dengan tinggi yang diinginkan */
    overflow-y: auto;
}
.penilaian-radio {
    transform: scale(1.5); /* Ubah angkanya sesuai ukuran yang diinginkan */
    margin: 5px;
    cursor: pointer;
}
.penilaian-kompetensi {
    transform: scale(1.5); /* Ubah angkanya sesuai ukuran yang diinginkan */
    margin: 5px;
    cursor: pointer;
}
    </style>

@stop
@section('mainarea')

<!-- page-header -->
<div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
    <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
        <li><a href="{{route('hris.dashboard.tes')}}">DASHBOARD</a></li>
        <li class="active"><span>KONTRAK KERJA</span></li>
    </ol>
    <div class="ml-auto">
        <div class="input-group">
            <a href="#" id="btn-refresh-data" class="btn btn-icon btn-secondary p-0 m-0" data-toggle="tooltip"
                title="" data-placement="bottom" data-original-title="Refresh Halaman">
                <span>
                    <i class="fa fa-refresh"></i>
                </span>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card shadow">
            <div class="accordion mb-0" id="accordionExample">
                <div class="card mb-0">
                    <div class="card-header p-0 bg-light" id="headingTwo" style="border: 1px solid rgb(210, 210, 210);">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="font-weight:bold;font-size:12pt">
                                <i class="fa fa-filter pl-4" aria-hidden="true"></i> Filter
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body px-6" style="border: 1px solid rgb(210, 210, 210);">
                            <div class="row pb-2">
                                <div class="col-2 pt-1">
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> No. KTP</label>
                                </div>
                                <div class="col-3 pr-0">
                                    <input type="number" id="searchNoKTP" name="searchNoKTP" class="form-control" style="background-color:white" placeholder="Masukkan No. KTP">
                                </div>
                                <div class="col-1"></div>
                                <div class="col-2 pt-1">
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Status Aktif</label>
                                </div>
                                <div class="col-3 pr-0">
                                    <select class="form-control" id="status_aktif">
                                        <option value="">Pilih Status Aktif</option>
                                        <option value="AKTIF">Aktif</option>
                                        <option value="TIDAK AKTIF">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row pb-2">
                                <div class="col-2 pt-1">
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Nama Karyawan</label>
                                </div>
                                <div class="col-3 pr-0">
                                    <select id="selectEmployeeID" name="selectEmployeeID[]" multiple data-placeholder="Pilih karyawan" class="form-control select2 EmployeeID">
                                        @foreach ($selectEmployee as $r_empl)
                                            <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-2 pt-1">
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Status Kontrak</label>
                                </div>
                                <div class="col-3 pr-0">
                                    <select class="form-control" id="status_kontrak">
                                        <option value="">Pilih Status Kontrak</option>
                                        <option value="Active">Aktif</option>
                                        <option value="Nonactive">Tidak Aktif</option>
                                        <option value="One Day">1 Hari Lagi</option>
                                        <option value="Thirty Day">30 Hari Lagi</option>
                                        <option value="Not yet extended">Belum Diperpanjang</option>
                                        <option value="Unfilled">Belum Diisi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row pb-2">
                                <div class="col-2 pt-1">
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Nama Ibu Kandung</label>
                                </div>
                                <div class="col-3 pr-0">
                                    <input type="text" id="searchIbuKandung" name="searchIbuKandung" class="form-control" style="background-color:white" placeholder="Masukkan Nama Ibu Kandung">
                                </div>
                                <div class="col-1 pt-1">
                                </div>
                                <div class="col-2 pt-1">
                                    <label class="form-label" style="font-weight: bold; color:rgb(99, 99, 132);font-size:12pt"> Status Staff</label>
                                </div>
                                <div class="col-3 pr-0">
                                    <select class="form-control" id="status_staff">
                                        <option value="">Pilih Status Staff</option>
                                        <option value="STAFF">Staff</option>
                                        <option value="NON STAFF">Non Staff</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-6 pt-2 pb-5">
                <div class="row pb-2">
                    <div class="col-10">
                        <table>
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-app btn-success mr-0 mt-0 mb-0" data-target="#import_kontrak" data-toggle="modal" style="font-size:11pt"><i class="fa fa-file-excel-o" style="font-size:11pt"></i> Import Kontrak Kerja</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-app btn-success mr-0 ml-1 mt-0 mb-0" style="font-size:11pt" onclick="export_excel_kontrak()" id="btn_export_excel_kontrak"><i class="fa fa-file-excel-o" style="font-size:11pt"></i> Export Kontrak Kerja</button>
                                </td>
                                <td>
                                    <button class="btn btn-danger" id="print_kontrak_kerja" style="visibility: hidden"><span class="fa fa-file-pdf-o"></span> Print Checked Employee</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-2">
                        <table>
                            <tr>
                                <td>
                                    <button  id="btn-hapus-filter" class="btn btn-primary">Hapus Filter <i class="fa fa-close" aria-hidden="true"></i> </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-sm w-100 table-hover text-nowrap">
                                <thead class="table-info">
                                    <tr style='text-align:center;'>
                                        <th rowspan="2" style="vertical-align: middle">
                                            <input type="checkbox" id="checkAllEmployee" onchange="actionCheckAllEmployee(this)">
                                        </th>
                                        <th rowspan="2" style="vertical-align: middle;font-weight:bold">ID</th>
                                        <th rowspan="2" style="vertical-align: middle;font-weight:bold">NIK</th>
                                        <th rowspan="2" style="vertical-align: middle;font-weight:bold">Employee Name</th>
                                        <th rowspan="2" style="vertical-align: middle;font-weight:bold">Department</th>
                                        <th rowspan="2" style="vertical-align: middle;font-weight:bold">Bagian</th>
                                        <th colspan="2" style="vertical-align: middle;font-weight:bold">Kontrak</th>
                                        <th rowspan="2" style="vertical-align: middle;font-weight:bold;border:1px solid rgb(195, 195, 195)"><span class="fa fa-cog"></span></th>
                                    </tr>
                                    <tr style='text-align:center; vertical-align:middle'>
                                        <th style="font-weight:bold">Awal</th>
                                        <th style="font-weight:bold">Akhir</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="import_kontrak" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 60%;" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2">
                <label class="form-label">IMPORT KONTRAK KERJA</label>
                <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                    <i class="fa fa-remove"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <input class="form-control" ref="excel_file_kontrak" name="excel_file_kontrak" id="excel_file_kontrak" type="file" accept=".xlsx, .xls, .csv" required>
                    </div>
                </div>
                <div class="row pt-2 justify-content-center">
                    <div class="col-12">
                        <table class="table table-bordered" style="overflow:auto;height:300px;">
                            <thead id="head_kontrak_kerja">
                                <tr>
                                    <td width="100px">NIK</td>
                                    <td width="200px">Nama Karyawan</td>
                                    <td width="200px">Department</td>
                                    <td width="100px">Kontrak</td>
                                    <td width="150px">Awal</td>
                                    <td width="150px">Akhir</td>
                                </tr>
                            </thead>
                            <tbody id="tabel_kontrak_kerja">
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 text-center">
                        <div id="loading_kontrak_kerja">
                        </div>
                    </div>
                </div>
                <div class="row pt-0 pb-3 pr-3">
                    <div class="col-2"></div>
                    <div class="col-8 text-center pt-2">
                        <button type="button" id="contractImportButton" class="btn btn-success py-1" style="visibility: hidden"><i class="fa fa-upload" aria-hidden="true"></i> IMPORT</button>
                    </div>
                    <div class="col-2 pl-8 pt-1" id="keterangan" style="visibility: hidden">
                        <label class="mb-0" style="font-size:10pt">L : Waktu Lembur</label><br>
                        <label style="font-size:10pt">I &nbsp;: Waktu Istirahat</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="extendContractModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 54%;" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2">
                <label class="form-label">WORKING CONTRACT</label>
                <button type="button" id="closeExtendModal" class="close text-white ml-1">
                    <i class="fa fa-remove"></i>
                </button>
            </div>
            <div class="modal-body px-3">
                <div class='row px-3' style="overflow-y: scroll">
                    <div class='col-2 pt-2 pb-1 text-center border border-body' style='font-weight:bold'>Status</div>
                    <div class='col-3 pt-2 pb-1 text-center border border-body' style='font-weight:bold'>Kontrak Awal</div>
                    <div class='col-3 pt-2 pb-1 text-center border border-body' style='font-weight:bold'>Akhir</div>
                    <div class='col-4 pt-2 pb-1 text-center border border-body' style="font-size:9pt;font-family:Arial;font-weight:bold"><span class="fa fa-cog"></span> Option</div>
                </div>
                <div id="working_contract_extend">
                </div>
                <div id="working_contract_active" style="overflow-y: scroll;height:500px">
                </div>
            </div>
            <div class="modal-footer bg-primary">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="penilaianKinerjaModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 54%;" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary p-2">
                <label class="form-label">Form Penilaian Kinerja Karyawan</label>
                <button type="button" id="btn-close-modal" class="close text-white ml-1">
                    <i class="fa fa-remove"></i>
                </button>
            </div>
            <form id="penilaianForm" name="form" method="POST">
                @csrf
                <div class="modal-body pt-0">
                    <div class="row pt-3 pb-2 bg-light text-dark">
                        <div class="col-2 pl-4" style="font-weight: bold">
                            ID
                        </div>
                        <div class="col-3" id="enroll_id_text">
                        </div>
                        <div class="col-1 pl-4" style="font-weight: bold">
                        </div>
                        <div class="col-2 pl-4" style="font-weight: bold">
                            Bagian
                        </div>
                        <div class="col-3" id="bagian_text">
                        </div>
                        <input type="hidden" name="penilaian_kinerja_id" id="penilaian_kinerja_id">
                        <input type="hidden" id="enroll_id_input_2_val" name="enroll_id_input_2_val">
                        <input type="hidden" id="awal_kontrak_text_val" name="awal_kontrak_text_val">
                        <input type="hidden" id="akhir_kontrak_text_val" name="akhir_kontrak_text_val">
                        <input type="hidden" id="periode_penilaian_text_val" name="periode_penilaian_text_val">
                    </div>
                    <div class="row py-2">
                        <div class="col-2 pl-4" style="font-weight: bold">
                            Nama Karyawan
                        </div>
                        <div class="col-3" id="employee_name_text">
                        </div>
                        <div class="col-1 pl-4" style="font-weight: bold">
                        </div>
                        <div class="col-2 pl-4" style="font-weight: bold">
                            Jabatan
                        </div>
                        <div class="col-3" id="jabatan_text">
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-2 pl-4" style="font-weight: bold">
                            Department
                        </div>
                        <div class="col-3" id="department_text">
                        </div>
                        <div class="col-1 pl-4" style="font-weight: bold">
                        </div>
                        <div class="col-2 pl-4" style="font-weight: bold">
                            Dievaluasi Oleh
                        </div>
                        <div class="col-3" id="dievaluasi_oleh_text">
                        </div>
                    </div>
                    <div class="row py-2 bg-light text-dark">
                        <div class="col-2 pl-4" style="font-weight: bold">
                            Awal Kontrak
                        </div>
                        <div class="col-3" id="awal_kontrak_text">
                        </div>
                        <div class="col-1 pl-4" style="font-weight: bold">
                        </div>
                        <div class="col-2 pl-4" style="font-weight: bold">
                            Periode Penilaian
                        </div>
                        <div class="col-3" id="periode_penilaian_text">
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-2 pl-4" style="font-weight: bold">
                            Akhir Kontrak
                        </div>
                        <div class="col-3" id="akhir_kontrak_text">
                        </div>
                    </div>
                    <div class="row pt-3 pb-2 bg-light text-dark">
                        <div class="col-12 pl-1" style="font-weight: bold">
                        </div>
                    </div>
                    <div class="row pt-3 pb-2 bg-light text-dark">
                        <div class="col-12 pl-4" style="font-weight: bold">
                            A. Penilaian Kinerja
                        </div>
                    </div>
                    <table class="table table-bordered mt-2">
                        <thead class="text-center">
                            <tr>
                                <th style="width: 70%;">Uraian Tugas & Tanggung Jawab</th>
                                <th style="width: 30%;">Target Pencapaian Kerja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Baris 1 -->
                            <tr>
                                <td><input type="text" class="form-control" name="uraian_tugas_1" id="uraian_tugas_1" /></td>
                                <td><input type="text" class="form-control" name="target_pencapaian_1" id="target_pencapaian_1" /></td>
                            </tr>
                            <!-- Baris 2 -->
                            <tr>
                                <td><input type="text" class="form-control" name="uraian_tugas_2" id="uraian_tugas_2" /></td>
                                <td><input type="text" class="form-control" name="target_pencapaian_2" id="target_pencapaian_2" /></td>
                            </tr>
                            <!-- Baris 3 -->
                            <tr>
                                <td><input type="text" class="form-control" name="uraian_tugas_3" id="uraian_tugas_3" /></td>
                                <td><input type="text" class="form-control" name="target_pencapaian_3" id="target_pencapaian_3" /></td>
                            </tr>
                            <!-- Baris 4 -->
                            <tr>
                                <td><input type="text" class="form-control" name="uraian_tugas_4" id="uraian_tugas_4" /></td>
                                <td><input type="text" class="form-control" name="target_pencapaian_4" id="target_pencapaian_4" /></td>
                            </tr>
                            <!-- Baris 5 -->
                            <tr>
                                <td><input type="text" class="form-control" name="uraian_tugas_5" id="uraian_tugas_5" /></td>
                                <td><input type="text" class="form-control" name="target_pencapaian_5" id="target_pencapaian_5" /></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered text-center align-middle" style="font-size: 10pt;">
                        <tr>
                            <td rowspan="2" style="width: 20%; font-weight: bold; vertical-align: middle;">
                                Penilaian Kinerja
                            </td>
                            <!-- Keterangan 10-20 -->
                            <td colspan="2" style="width: 20%;">
                                <div style="font-weight: bold;">
                                    Kinerja dibawah standar,<br>tidak efisien dan efektif,<br>tidak konsisten
                                </div>
                            </td>
                            <!-- Keterangan 30 -->
                            <td style="width: 20%;">
                                <div style="font-weight: bold;">
                                    Kinerja<br>memenuhi standar,<br>efektif dan efisien
                                </div>
                            </td>
                            <!-- Keterangan 40-50 -->
                            <td colspan="2" style="width: 20%;">
                                <div style="font-weight: bold;">
                                    Kinerja diatas standar,<br>selalu mencari solusi dari setiap<br>masalah
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <!-- Skor 10 -->
                            <td style="background-color: #f8d7da;">
                                10<br>
                                <input type="radio" name="nilai_kinerja" id="nilai_kinerja" value="10" class="penilaian-radio">
                            </td>
                            <!-- Skor 20 -->
                            <td style="background-color: #ffe5b4;">
                                20<br>
                                <input type="radio" name="nilai_kinerja" id="nilai_kinerja" value="20" class="penilaian-radio">
                            </td>
                            <!-- Skor 30 -->
                            <td style="background-color: #fff3cd;">
                                30<br>
                                <input type="radio" name="nilai_kinerja" id="nilai_kinerja" value="30" class="penilaian-radio">
                            </td>
                            <!-- Skor 40 -->
                            <td style="background-color: #d4edda;">
                                40<br>
                                <input type="radio" name="nilai_kinerja" id="nilai_kinerja" value="40" class="penilaian-radio">
                            </td>
                            <!-- Skor 50 -->
                            <td style="background-color: #a6e6a6;">
                                50<br>
                                <input type="radio" name="nilai_kinerja" id="nilai_kinerja" value="50" class="penilaian-radio">
                            </td>
                        </tr>
                    </table>
                    <div class="row pt-3 pb-2 bg-light text-dark">
                        <div class="col-12 pl-4" style="font-weight: bold">
                            B. Penilaian Kompetensi
                        </div>
                    </div>
                    <table class="table table-bordered text-center align-middle" style="font-size: 10pt;">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 30%; vertical-align: middle;">Kompetensi</th>
                                <th style="background-color: #f8d7da;">Sangat di bawah Standard</th>
                                <th style="background-color: #ffe5b4;">Dibawah Standard</th>
                                <th style="background-color: #fff3cd;">Standard</th>
                                <th style="background-color: #d4edda;">Diatas Standard</th>
                                <th style="background-color: #a6e6a6;">Sangat Diatas Standard</th>
                            </tr>
                            <tr>
                                <th style="background-color: #f8d7da;">10</th>
                                <th style="background-color: #ffe5b4;">20</th>
                                <th style="background-color: #fff3cd;">30</th>
                                <th style="background-color: #d4edda;">40</th>
                                <th style="background-color: #a6e6a6;">50</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $kompetensiList = [
                                    "tanggung_jawab_tugas" => "Tanggung jawab terhadap tugas yang diberikan",
                                    "inisiatif_kerjasama" => "Inisiatif dan Kerjasama",
                                    "akurasi_pekerjaan" => "Akurasi dalam pekerjaan",
                                    "kemauan_kegigihan" => "Kemauan dan Kegigihan dalam mencapai tujuan",
                                    "penyampaian_informasi" => "Penyampaian dan Penerimaan informasi",
                                    "attitude_sikap_kerja" => "Attitude / Sikap Kerja"
                                ];
                            @endphp

                            @foreach ($kompetensiList as $index => $kompetensi)
                            <tr>
                                <td class="text-start">{{ $kompetensi }}</td>
                                @foreach ([10, 20, 30, 40, 50] as $nilai)
                                    <td>
                                        <input type="radio" class="penilaian-kompetensi" name="kompetensi[{{ $index }}]" value="{{ $nilai }}">
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>


                    <!-- C. Penilaian Kedisiplinan -->
                    <div class="row pt-3 pb-2 bg-light text-dark">
                        <div class="col-12 pl-4" style="font-weight: bold">
                            C. Penilaian Kedisiplinan (Diisi Oleh Bagian HRD)
                        </div>
                    </div>

                    <table class="table table-bordered text-center align-middle" style="font-size: 10pt;">
                        <thead>
                            <tr>
                                <th rowspan="2" style="vertical-align: middle;">Kategori Pengurangan</th>
                                <th rowspan="2" style="vertical-align: middle;">Pengurangan</th>
                                <th colspan="2">Akumulasi Kejadian</th>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $penguranganList = [
                                    ['key'=>'sp3_kali', 'label' => 'Surat Peringatan 3', 'pengurangan' => 6],
                                    ['key'=>'sp2_kali', 'label' => 'Surat Peringatan 2', 'pengurangan' => 4],
                                    ['key'=>'sp1_kali', 'label' => 'Surat Peringatan 1', 'pengurangan' => 2],
                                    ['key'=>'kecelakaan_kali', 'label' => 'Kecelakaan Kerja Karena Kelalaian', 'pengurangan' => 2],
                                    ['key'=>'mangkir_kali', 'label' => 'Mangkir', 'pengurangan' => 1],
                                    ['key'=>'ijin_kali', 'label' => 'Ijin', 'pengurangan' => 0.5],
                                ];
                            @endphp
                            @foreach($penguranganList as $i => $item)
                            <tr>
                                <td class="text-start">{{ $item['label'] }}</td>
                                <td>{{ $item['pengurangan'] }}</td>
                                <td><input type="number" class="form-control text-center" name="kejadian[{{ $item['key'] }}]" step="1"></td>
                                <td><input type="text" class="form-control text-center" name="total[{{ $item['key'] }}]" readonly></td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total Pengurangan</td>
                                <td><input type="text" class="form-control text-center fw-bold" name="total_pengurangan" readonly></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Nilai Akhir -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <table class="table table-bordered text-center align-middle" style="font-size: 10pt;">
                                <thead>
                                    <tr><th colspan="2">Nilai Akhir</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td>Penilaian Kinerja</td><td><input type="text" disabled class="form-control text-center" name="penilaian_kinerja" id="penilaian_kinerja"></td></tr>
                                    <tr><td>Penilaian Kompeten</td><td><input type="text" disabled class="form-control text-center" name="penilaian_kompeten" id="penilaian_kompeten"></td></tr>
                                    <tr><td>Penilaian Kedisiplinan</td><td><input type="text" disabled class="form-control text-center" name="penilaian_kedisiplinan" id="penilaian_kedisiplinan"></td></tr>
                                    <tr><td><strong>Nilai Akhir</strong></td><td><input type="text" disabled class="form-control text-center fw-bold" name="penilaian_akhir" id="penilaian_akhir" readonly></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered text-center align-middle" style="font-size: 10pt;">
                                <thead>
                                    <tr><th colspan="2">Diketahui</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td><strong name="diketahui_oleh" id="diketahui_oleh"></strong></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- D. Rekomendasi Tindak Lanjut -->
                    <div class="row pt-3 pb-2 bg-light text-dark">
                        <div class="col-12 pl-4" style="font-weight: bold">
                            D. Rekomendasi Tindak Lanjut
                        </div>
                    </div>
                    {{-- <div class="mt-3 ml-2">
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input mt-0" type="checkbox" name="rekomendasi_perpanjang_kontrak" value="1" id="rekomendasi_perpanjang_kontrak">
                            <label class="form-check-label mb-0" for="rekomendasi_perpanjang_kontrak">Perpanjang Kontrak</label>
                            <input type="text" class="form-control form-control-sm ml-3" style="width: 100px;" name="perpanjang_bulan" placeholder="Bulan">
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="rekomendasi_phk" value="1" id="rekomendasi_phk">
                            <label class="form-check-label" for="rekomendasi_phk">Tidak Perpanjang Kontrak / PHK</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="rekomendasi_demosi" value="1" id="rekomendasi_demosi">
                            <label class="form-check-label" for="rekomendasi_demosi">Demosi</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="rekomendasi_promosi" value="1" id="rekomendasi_promosi">
                            <label class="form-check-label" for="rekomendasi_promosi">Promosi</label>
                        </div>
                        <div class="form-check  d-flex align-items-center gap-2">
                            <input class="form-check-input mt-0" type="checkbox" name="rekomendasi_training" value="1" id="rekomendasi_training">
                            <label class="form-check-label w-auto mr-3 mb-0" for="rekomendasi_training">Training / Pengembangan</label>
                            <input type="text" class="form-control form-control-sm w-50" name="judul_training" placeholder="Sebutkan Judul / Tujuan">
                        </div>
                    </div> --}}
                    <div class="mt-3 ml-2">
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input mt-0" type="radio" name="rekomendasi" value="perpanjang" id="rekomendasi_perpanjang_kontrak">
                            <label class="form-check-label mb-0" for="rekomendasi_perpanjang_kontrak">Perpanjang Kontrak</label>
                            <input type="text" class="form-control form-control-sm ml-3" style="width: 100px;" name="perpanjang_bulan"  id="perpanjang_bulan"  placeholder="Bulan">
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rekomendasi" value="phk" id="rekomendasi_phk">
                            <label class="form-check-label" for="rekomendasi_phk">Tidak Perpanjang Kontrak / PHK</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rekomendasi" value="demosi" id="rekomendasi_demosi">
                            <label class="form-check-label" for="rekomendasi_demosi">Demosi</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rekomendasi" value="promosi" id="rekomendasi_promosi">
                            <label class="form-check-label" for="rekomendasi_promosi">Promosi</label>
                        </div>

                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input mt-0" type="radio" name="rekomendasi" value="training" id="rekomendasi_training">
                            <label class="form-check-label w-auto mr-3 mb-0" for="rekomendasi_training">Training / Pengembangan</label>
                            <input type="text" class="form-control form-control-sm w-50" name="judul_training" placeholder="Sebutkan Judul / Tujuan">
                        </div>
                    </div>


                    <div class="row gap-0 mt-3 pt-4">
                        <div class="col-md-3">
                            <table class="table table-bordered text-center align-middle" style="font-size: 10pt;">
                                <thead>
                                    <tr><th colspan="2">Penilai</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td><br><br><br></td></tr>
                                    <tr><td class="p-1"><input type="text" class="form-control text-center" name="penilai" id="penilai"></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <table class="table table-bordered text-center align-middle" style="font-size: 10pt;">
                                <thead>
                                    <tr><th colspan="2">Diketahui</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td><br><br><br></td></tr>
                                    <tr><td><strong>Chief / Manager</strong></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <table class="table table-bordered text-center align-middle" style="font-size: 10pt;">
                                <thead>
                                    <tr><th colspan="2">Diketahui</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td><br><br><br></td></tr>
                                    <tr><td><strong>HRD</strong></td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <table class="table table-bordered text-center align-middle" style="font-size: 10pt;">
                                <thead>
                                    <tr><th colspan="2">Disetujui</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td><br><br><br></td></tr>
                                    <tr><td><strong>General Manager</strong></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row gap-0 mt-3 pt-4">
                        <div class="col-md-3">
                            <button type="submit" id="btn-simpan-penilaian" class="btn btn-success">Simpan</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@section('footerjs')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{URL::asset('assets/js/iziToast.min.js')}}"></script>
<script src="{{URL::asset('assets/js/script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    #head_kontrak_kerja, #tabel_kontrak_kerja { display: block; }

    #tabel_kontrak_kerja {
        height: 1px;
        overflow-y: auto;    /* Trigger vertical scroll    */
        overflow-x: hidden;
        font-size: 9pt; /* Hide the horizontal scroll */
    }
</style>

<script>
$(document).ready(function() {
    $('#penilaianForm').submit(function(e) {
        e.preventDefault();

        const nilaiKinerja = $('input[name="nilai_kinerja"]:checked').val();

        if (!nilaiKinerja) {
            iziToast.warning({
                title: 'Peringatan',
                message: 'Mohon pilih salah satu nilai penilaian kinerja.',
            });
            return;
        }

        const kompetensiFields = [
            "tanggung_jawab_tugas",
            "inisiatif_kerjasama",
            "akurasi_pekerjaan",
            "kemauan_kegigihan",
            "penyampaian_informasi",
            "attitude_sikap_kerja"
        ];

        let kompetensiValid = true;

        kompetensiFields.forEach(field => {
            if (!$(`input[name="kompetensi[${field}]"]:checked`).val()) {
                kompetensiValid = false;
            }
        });

        if (!kompetensiValid) {
            iziToast.warning({
                title: 'Peringatan',
                message: 'Mohon isi semua nilai kompetensi terlebih dahulu.',
            });
            return;
        }



        const rekomendasi = $('input[name="rekomendasi"]:checked').val();
        const bulan = $('#perpanjang_bulan').val().trim();

        if (!rekomendasi) {
            iziToast.warning({
                title: 'Peringatan',
                message: 'Mohon pilih salah satu rekomendasi tindak lanjut.',
            });
            return;
        }

        if (rekomendasi === 'perpanjang' && bulan === '') {
            iziToast.warning({
                title: 'Peringatan',
                message: 'Mohon isi jumlah bulan perpanjangan kontrak.',
            });
            $('#perpanjang_bulan').focus();
            return; // Hentikan proses submit
        }

        var formData = $(this).serialize();
        var penilaian_kinerja_id = $('input[name="penilaian_kinerja_id"]').val()

        var url = penilaian_kinerja_id
                  ? '{{ route("hris.penilaian_kinerja_staff.update_penilaian_kinerja_staff", ":id") }}'.replace(':id', penilaian_kinerja_id)
                  : '{{ route("hris.penilaian_kinerja_staff.store_penilaian_kinerja_staff") }}';

        var type = penilaian_kinerja_id ? 'PUT' : 'POST';
        $('#btn-simpan-penilaian').attr('disabled', true);
        $('#btn-simpan-penilaian').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
        $('#btn-simpan-penilaian').css('background-color', '#ccc');
        $('#btn-simpan-penilaian').css('border-color', '#ccc');
        $('#btn-simpan-penilaian').css('color', '#000');
        $('#btn-simpan-penilaian').css('cursor', 'not-allowed');
        $.ajax({
            url: url,
            type: type,
            data: formData,
            success: function(response) {
                iziToast.success({
                    title: 'Berhasil!',
                    message: response.msg,
                });
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    title: 'Error!',
                    message: 'Ada masalah dalam pengiriman data.',
                });
                $('#btn-simpan-penilaian').attr('disabled', false);
                $('#btn-simpan-penilaian').html('Simpan');
                $('#btn-simpan-penilaian').css('background-color', '#28a745');
                $('#btn-simpan-penilaian').css('border-color', '#28a745');
                $('#btn-simpan-penilaian').css('color', '#fff');
                $('#btn-simpan-penilaian').css('cursor', 'pointer');
            }
        });
    });
});

</script>


<script>
    document.querySelectorAll('.penilaian-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.penilaian-radio').forEach(function(otherRadio) {
                if (otherRadio !== radio) {
                    otherRadio.checked = false; // Uncheck all other radios
                }
            });
        });
    });
</script>


<script type="text/javascript">
    $(document).ready(function() {
        let datatableFilter = document.getElementById("datatable_filter");
        datatableFilter.innerHTML = `<span> Search : </span><input type="text" class="form-control form-control-sm" id="search_variable" onkeyup="dataTableReload()">`;
    });
    function export_excel_kontrak(){
        $("#btn_export_excel_kontrak").addClass("btn-loading");
        $("#btn_export_excel_kontrak").html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Loading...&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        $("#btn_export_excel_kontrak").attr("disabled", true);
        let search_variable=$('#search_variable').val();
        let no_ktp = document.getElementById("searchNoKTP").value;
        let enroll_id = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
        let ibu_kandung = document.getElementById("searchIbuKandung").value;
        let status_aktif = document.getElementById("status_aktif").value;
        let status_staff = document.getElementById("status_staff").value;
        let status_kontrak = document.getElementById("status_kontrak").value;
        var today=new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        var hour = today.getHours();
        var minutes = today.getMinutes();
        var seconds = today.getSeconds();
        today_date = yyyy + '-' + mm + '-' + dd + ' '+ hour +'.'+minutes+'.'+seconds;
        $.ajax({
            type: "get",
            url: '{{ route('hris.hrd.export_excel_kontrak') }}',
            data: {
                search_variable: search_variable,
                no_ktp: no_ktp,
                enroll_id: enroll_id,
                ibu_kandung: ibu_kandung,
                status_aktif: status_aktif,
                status_staff: status_staff,
                status_kontrak: status_kontrak
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                {
                    $('#btn_export_excel_kontrak').removeClass("btn-loading");
                    $("#btn_export_excel_kontrak").html('<i class="fa fa-file-excel-o" style="font-size:11pt"></i> Export Kontrak Kerja');
                    $("#btn_export_excel_kontrak").attr("disabled", false);
                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Kontrak kerja "+today_date+" "+Math.ceil(Math.random()*1000000)+".xlsx";
                    link.click();
                }
            },
            error: function(res){
                swal("", "Export kontrak kerja gagal", "error");
                $('#btn_export_excel_kontrak').removeClass("btn-loading");
                $("#btn_export_excel_kontrak").attr("disabled", false);
                $("#btn_export_excel_kontrak").html('<i class="fa fa-file-excel-o" style="font-size:11pt"></i> Export Kontrak Kerja');
            }
        });
    }
    $('.data_range').daterangepicker({
        ranges: {
            'Hari ini': [moment(), moment()],
            'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 Hari Kemarin': [moment().subtract(6, 'days'), moment()],
            '30 Hari Kemarin': [moment().subtract(29, 'days'), moment()],
            'Bulan Sekarang': [moment().startOf('month'), moment().endOf('month')],
            'Bulan Kemarin': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
        // startDate: moment().startOf('month'),
        // endDate: moment().endOf('month')
        }, function(start, end) {
            $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
    })
    $('#excel_file_kontrak').change(function() {
        fill_the_table();
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function fill_the_table(){
        $('#loading_kontrak_kerja').addClass("spinner-border");
        $('#tabel_kontrak_kerja').empty();
        var formData = new FormData();
        var excelFile=document.getElementById("excel_file_kontrak");
        var myFile=excelFile.files[0];
        formData.append("excel_file",myFile);
        if(typeof myFile=='undefined'){
            notif({
                msg: "<b>Error:</b> Pilih File terlebih dahulu!",
                type: "error"
            });
            document.getElementById('tabel_kontrak_kerja').style.height='1px';
            document.getElementById('contractImportButton').style.visibility='hidden';
            document.getElementById('keterangan').style.visibility='hidden';
            $('#loading_kontrak_kerja').removeClass("spinner-border");
        }else{
            $.ajax({
                type: 'POST',
                url: '{{route('hris.hrd.import_kontrak_kerja')}}',
                contentType: false,
                processData: false,
                data: formData,
                success:function(data){
                    $('#loading_kontrak_kerja').removeClass("spinner-border");
                    no=2;
                    jQuery.each(data, function(key,value){
                        contract = new Date(value.contract).toLocaleDateString('id-ID', { weekday: 'long', year:"numeric", month:"long", day:"numeric"});
                        contract_end = new Date(value.contract_end).toLocaleDateString('id-ID', { weekday: 'long', year:"numeric", month:"long", day:"numeric"});
                        if(key!=0){
                            if(value.nik==data[key-1].nik[0]){
                                $('#tabel_kontrak_kerja').append("<tr>\
                                    <td width='100px'></td>\
                                    <td width='200px'></td>\
                                    <td width='200px'></td>\
                                    <td width='100px'>Kontrak ke "+(no++)+"</td>\
                                    <td width='150px'>"+contract+"</td>\
                                    <td width='150px'>"+contract_end+"</td>\
                                </tr>");
                            }else{
                                no=2;
                                $('#tabel_kontrak_kerja').append("<tr>\
                                    <td width='100px'>"+value.nik+"</td>\
                                    <td width='200px'>"+value.employee_name+"</td>\
                                    <td width='200px'>"+value.department+"</td>\
                                    <td width='100px'>Kontrak ke 1</td>\
                                    <td width='150px'>"+contract+"</td>\
                                    <td width='150px'>"+contract_end+"</td>\
                                </tr>");
                            }
                        }else{
                            $('#tabel_kontrak_kerja').append("<tr>\
                                <td width='100px'>"+value.nik+"</td>\
                                <td width='200px'>"+value.employee_name+"</td>\
                                <td width='200px'>"+value.department+"</td>\
                                <td width='100px'>Kontrak ke 1</td>\
                                <td width='150px'>"+contract+"</td>\
                                <td width='150px'>"+contract_end+"</td>\
                            </tr>");
                        }
                    });
                    document.getElementById('tabel_kontrak_kerja').style.height='300px';
                    document.getElementById('contractImportButton').style.visibility='visible';
                },
                error: function(res){
                    swal("", "IMPORT KONTRAK KERJA GAGAL!", "error")
                    document.getElementById('tabel_kontrak_kerja').style.height='1px';
                    document.getElementById('contractImportButton').style.visibility='hidden';
                    document.getElementById('keterangan').style.visibility='hidden';
                    $('#loading_kontrak_kerja').removeClass("spinner-border");
                }
            });
        }
    }

    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const enrollIds = urlParams.get('notification_id');

        if (enrollIds) {
            // Kalau ada enroll_ids, tampilkan tombol
            $('#btn-hapus-filter').show();
        } else {
            // Kalau tidak ada, sembunyikan tombol
            $('#btn-hapus-filter').hide();
        }

        $('#btn-hapus-filter').on('click', function() {
            urlParams.delete("notification_id");

            // Update URL tanpa reload halaman
            const newUrl = window.location.pathname + '?' + urlParams.toString();
            window.history.replaceState({}, '', newUrl);

            // Reload datatable biar filter enroll_ids hilang
            $('#datatable').DataTable().ajax.reload();

            // Sembunyikan tombol setelah dihapus
            $('#btn-hapus-filter').hide();
        });
    });


    $('#contractImportButton').on('click',function(){
        $("#contractImportButton").addClass("btn-loading");
        $("#contractImportButton").html('Loading...');
        $("#contractImportButton").attr("disabled", true);
        var formData = new FormData();
        var excelFile=document.getElementById("excel_file_kontrak");
        var myFile=excelFile.files[0];
        formData.append("excel_file",myFile);
        $.ajax({
            type: 'POST',
            url: '{{route('hris.hrd.import_kontrak_kerja_to_database')}}',
            contentType: false,
            processData: false,
            data: formData,
            success:function(data){
                $('#tabel_kontrak_kerja').empty();
                document.getElementById('contractImportButton').style.visibility='hidden';
                document.getElementById('tabel_kontrak_kerja').style.height='1px';
                $("#contractImportButton").removeClass("btn-loading");
                $("#contractImportButton").html('<i class="fa fa-upload"></i> IMPORT');
                $("#contractImportButton").attr("disabled", false);
                $('#excel_file_kontrak').val('');
                $("#import_kontrak").modal('hide');
                swal({
                    title: "Kontrak Kerja",
                    text: "Kontrak kerja berhasil di import",
                    icon: "success",
                    button : false,
                });
                datatable.ajax.reload();
            },
            error: function(res){
                swal("", "IMPORT KONTRAK KERJA GAGAL!", "error")
                $("#contractImportButton").removeClass("btn-loading");
                $("#contractImportButton").html('<i class="fa fa-upload"></i> IMPORT');
                $("#contractImportButton").attr("disabled", false);
            }
        });
    });


    $(function(){
        'use strict';

        $('.select2').select2({
            minimumResultsForSearch: Infinity
        });

        // Select2 by showing the search
        $('.select2-show-search').select2({
            minimumResultsForSearch: ''
        });

        // Colored Hover
        $('#select2').select2({
            dropdownCssClass: 'hover-success',
            minimumResultsForSearch: Infinity // disabling search
        });

        $('#select3').select2({
            dropdownCssClass: 'hover-danger',
            minimumResultsForSearch: Infinity // disabling search
        });

        // Outline Select
        $('#select4').select2({
            containerCssClass: 'select2-outline-success',
            dropdownCssClass: 'bd-success hover-success',
            minimumResultsForSearch: Infinity // disabling search
        });

        $('#select5').select2({
            containerCssClass: 'select2-outline-info',
            dropdownCssClass: 'bd-info hover-info',
            minimumResultsForSearch: Infinity // disabling search
        });

        // Full Colored Select Box
        $('#select6').select2({
            containerCssClass: 'select2-full-color select2-primary',
            minimumResultsForSearch: Infinity // disabling search
        });

        $('#select7').select2({
            containerCssClass: 'select2-full-color select2-danger',
            dropdownCssClass: 'hover-danger',
            minimumResultsForSearch: Infinity // disabling search
        });

        // Full Colored Dropdown
        $('#select8').select2({
            dropdownCssClass: 'select2-drop-color select2-drop-primary',
            minimumResultsForSearch: Infinity // disabling search
        });

        $('#select9').select2({
            dropdownCssClass: 'select2-drop-color select2-drop-indigo',
            minimumResultsForSearch: Infinity // disabling search
        });

        // Full colored for both box and dropdown
        $('#select10').select2({
            containerCssClass: 'select2-full-color select2-primary',
            dropdownCssClass: 'select2-drop-color select2-drop-primary',
            minimumResultsForSearch: Infinity // disabling search
        });

        $('#select11').select2({
            containerCssClass: 'select2-full-color select2-indigo',
            dropdownCssClass: 'select2-drop-color select2-drop-indigo',
            minimumResultsForSearch: Infinity // disabling search
        });
    });
    var currentPageCheck = 0;
    var checkedEmployeeArr = [];
    let datatable = $("#datatable").DataTable({
        ordering: true,
        processing: true,
        serverSide: true,
        paging: true,
        searching: true,
        destroy: true,
        scrollX: true,
        ajax: {
            url: '{{ route('hris.hrd.get_employee_contract') }}',
            data: function(d) {
                const urlParams = new URLSearchParams(window.location.search);
                const notificationIdFromUrl = urlParams.get('notification_id');

                if (notificationIdFromUrl) {
                    d.notification_id = notificationIdFromUrl.split(','); // ubah ke array
                } else {
                    // kalau tidak ada notification_id di URL, kirim data biasa
                    d.notification_id = $("select[name='selectEmployeeID[]']").map(function() {
                        return $(this).val();
                    }).get();
                }
                // d.enroll_id = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
                d.ibu_kandung = $('#searchIbuKandung').val();
                d.no_ktp = $('#searchNoKTP').val();
                d.status_kontrak = $('#status_kontrak').val();
                d.status_aktif = $('#status_aktif').val();
                d.status_staff = $('#status_staff').val();
                d.search_variable = $('#search_variable').val();
            },
        },
        columns: [
            {
                data: 'enroll_id',
                orderable: false
            },
            {
                data: 'enroll_id'
            }, {
                data: 'nik'
            },
            {
                data: 'employee_name'
            },
            {
                data: 'department_name'
            },
            {
                data: 'sub_dept_name'
            },
            {
                data: 'enroll_id'
            },
            {
                data: 'enroll_id'
            },
            {
                data: 'enroll_id',
                orderable: false
            },
        ],
        order: [
            [1, 'asc']
        ],
        columnDefs: [
            {
                targets: [0],
                render: (data, type, row, meta) => {
                    return `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" style='width: 20px; height: 20px;' value="`+data+`" style='width: 20px; height: 20px;' id="checked_enroll_id_` + row.enroll_id + `" onchange="actionThisEmployeeCheck(this)" >
                        </div>
                    `
                }
            },
            {
                targets: [6],
                render: (data, type, row, meta) => {
                    var options = { weekday: 'long',  year: 'numeric', month: 'long', day: 'numeric' };
                    var tes=new Date(row.contract);
                    if(row.contract==null){
                        return '';
                    }else{
                        return tes.toLocaleDateString("id-ID", options)
                    }
                }
            },
            {
                targets: [7],
                render: (data, type, row, meta) => {
                    var options = { weekday: 'long',  year: 'numeric', month: 'long', day: 'numeric' };
                    var tes=new Date(row.contract_end);
                    if(row.contract_end==null){
                        return '';
                    }else{
                        return tes.toLocaleDateString("id-ID", options)
                    }
                }
            },
            {
                targets: [8],
                render: (data, type, row, meta) => {
                    return `
                        <div class='d-flex gap-1'>
                            <a onclick="openExtendModal('` + row.enroll_id + `')">
                                <i class='fa fa-pencil-square-o' style='color:black;background-color:orange;font-size:14pt;border:1px solid #838584;padding:2pt;cursor:pointer'></i>
                            </a>
                            <a onclick="print_pdf('` + row.enroll_id + `');">
                                <i class='fa fa-file-pdf-o' style='color:white;background-color:red;font-size:14pt;border:1px solid #838584;padding:2pt;cursor:pointer'></i>
                            </a>
                        </div>
                    `
                }
            }
        ],
        "createdRow": function (row, data, dataIndex) {
            if ((data['tanggal_resign'] != null)) {
                if(new Date(data['tanggal_resign']).getTime()<=new Date()){
                    $(row).css('background', 'red');
                }else{
                    $(row).css('background', 'white');
                }
            }else{
                $(row).css('background', 'white');
            }
        },
        rowCallback: function(row, data, dataIndex){
            let currentEnrollId = data['enroll_id'];

            checkedEmployeeArr.forEach((item, index, array) => {
                if(item==currentEnrollId){
                    currentPageCheck++;
                    $(row).find('input[id="checked_enroll_id_'+item+'"]').prop('checked', true);
                }
            });
        },
        drawCallback: function (settings) {
            if (currentPageCheck == 0) {
                $('#checkAllEmployee').prop("checked", false);
            } else {
                $('#checkAllEmployee').prop("checked", true);
            }

            currentPageCheck = 0;
        }
    });
    function actionThisEmployeeCheck(element) {
        if (element.checked) {
            console.log('element.value',element.value);
            if(!checkedEmployeeArr.find((value) => value == element.value)) {
                checkedEmployeeArr.push(element.value);
            }
        } else {
            if(checkedEmployeeArr.find((value) => value == element.value)) {
                const index = checkedEmployeeArr.indexOf(element.value);
                if (index > -1) { // only splice array when item is found
                    checkedEmployeeArr.splice(index, 1); // 2nd parameter means remove one item only
                }
            }
        }
        if(checkedEmployeeArr.length>0){
            document.getElementById("print_kontrak_kerja").style.visibility = "visible";
        }else{
            document.getElementById("print_kontrak_kerja").style.visibility = "hidden";
        }
    }
    $('#print_kontrak_kerja').on('click',function(){
        var enroll_id=checkedEmployeeArr;
        var today=new Date();
        var month_now=today.getMonth();
        var year_now=today.getFullYear();
        var no_form='HRD-NAG/PKWT'+'/'+integerToRoman(month_now+1)+'/'+year_now;
        var url = 'print_all_pdf_kontrak?enroll_id='+enroll_id+'&no_form='+no_form;
        window.open(url, '_blank');
    });
    function actionCheckAllEmployee(element) {
        var enroll_id = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
        var ibu_kandung = $('#searchIbuKandung').val();
        var no_ktp = $('#searchNoKTP').val();
        var status_kontrak = $('#status_kontrak').val();
        var status_aktif = $('#status_aktif').val();
        var status_staff = $('#status_staff').val();
        var search_variable = $('#search_variable').val();
        if (element.checked) {
            $.ajax({
                type:"POST",
                url: "{{route('hris.hrd.ajax_getemployeeidbyfilter')}}",
                dataType: 'json',
                data: {
                    enroll_id: enroll_id,
                    ibu_kandung: ibu_kandung,
                    no_ktp: no_ktp,
                    status_kontrak: status_kontrak,
                    status_aktif: status_aktif,
                    status_staff: status_staff,
                    search_variable: search_variable,
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res){
                    if(res){
                        checkedEmployeeArr = res;

                        $('#datatable').DataTable().ajax.reload(null, false);
                        document.getElementById("print_kontrak_kerja").style.visibility = "visible";
                    }
                }
            });
        } else {
            checkedEmployeeArr = [];
            document.getElementById("print_kontrak_kerja").style.visibility = "hidden";
            $('#datatable').DataTable().ajax.reload(null, false);
        }
    }

    $('#selectEmployeeID').on('change',function(){
        datatable.ajax.reload();
    });
    $('#searchIbuKandung').on('keyup',function(){
        datatable.ajax.reload();
    });
    $('#searchNoKTP').on('keyup',function(){
        datatable.ajax.reload();
    });
    $('#status_kontrak').on('change',function(){
        datatable.ajax.reload();
    });
    $('#status_aktif').on('change',function(){
        datatable.ajax.reload();
    });
    $('#status_staff').on('change',function(){
        datatable.ajax.reload();
    });
    function dataTableReload() {
        datatable.ajax.reload();
    }
    function getDetail(enroll_id){
        $('#working_contract_active').empty();
        $('#working_contract_extend').empty();
        $.ajax({
            type: "post",
            url: '{{ route('hris.hrd.get_employee_contract2') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: enroll_id,
            },
            success: function(res) {
                var this_day=new Date();
                this_day.setHours(0, 0, 0, 0);
                for(var i=res.length-1;i>=0;i--){
                    var options = { weekday: 'long',  year: 'numeric', month: 'long', day: 'numeric' };
                    var start=new Date(res[i]['contract']);
                    var end=new Date(res[i]['contract_end']);
                    end.setHours(0, 0, 0, 0);

                    if(i==res.length-1){
                        if(res.length===1){
                            if(this_day.getTime()>end.getTime()){
                                $('#working_contract_active').append("<div class='row px-3'>\
                                    <div class='col-2 pt-2 pb-1 border border-body border-left-0 bg-danger text-dark' style='font-weight:bold'>Nonactive Contract</div>\
                                    <div class='col-3 pt-2 pb-1 border border-body text-dark'>"+start.toLocaleDateString("id-ID", options)+"<input type='hidden' class='form-control form-control-sm' id='start_contract' value="+res[i]['contract']+"></div>\
                                    <div class='col-3 py-1 border border-body text-dark' style='font-weight:bold'><input type='hidden' class='form-control form-control-sm' id='last_contract_end' value="+res[i]['contract_end']+" style='display:block'><h6 id='warning_fill' style='display:none;margin-bottom:0;color:red'>please fill this</h6><h6 id='warning_date' style='display:none;margin-bottom:0;color:red'>too small</h6><input type='hidden' value="+res[i]['id']+" id='last_id'><label class='py-1 mb-0' id='last_label_end' style='display:none'>"+end.toLocaleDateString("id-ID", options)+"</label></div>\
                                    <div class='col-4 py-1 border border-body text-dark'><a href='#' class='btn btn-sm btn-primary py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='extendContract("+res[i]['enroll_id']+");' id='extendButton' style='visibility:visible'><span class='fa fa-arrow-up' style='font-size:9pt'></span> Extend</a>  <a href='#' class='btn btn-sm btn-danger py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial;display:none' onClick='deleteContract("+res[i]['id']+","+res[i]['enroll_id']+");' id='deleteButton' style='visibility:visible'><span class='fa fa-trash' style='font-size:9pt'></span></a><a href='#' class='btn ml-1 btn-sm btn-primary py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='exportPenilaian("+res[i]['enroll_id']+","+"\""+res[i]['contract']+"\""+","+"\""+res[i]['contract_end']+"\""+");'>&nbsp;<span class='fa fa-file-pdf-o' style='font-size:9pt'></span> Penilaian&nbsp;&nbsp;</a>\<a href='#' class='btn btn-sm btn-warning py-0 px-2 ml-1' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='editPenilaianKinerja("+res[i]['enroll_id']+","+"\""+res[i]['contract']+"\""+","+"\""+res[i]['contract_end']+"\""+");'>&nbsp;<span class='fa fa-edit' style='font-size:9pt'></span> Nilai&nbsp;&nbsp;</a>\</div>\
                                </div>");
                            }else{
                                $('#working_contract_active').append("<div class='row px-3'>\
                                    <div class='col-2 pt-2 pb-1 border border-body border-left-0 text-dark'>Active Contract</div>\
                                    <div class='col-3 pt-2 pb-1 border border-body text-dark'>"+start.toLocaleDateString("id-ID", options)+"<input type='hidden' class='form-control form-control-sm' id='start_contract' value="+res[i]['contract']+"></div>\
                                    <div class='col-3 py-1 border border-body text-dark'>"+end.toLocaleDateString("id-ID", options)+"<input type='hidden' class='form-control form-control-sm' id='last_contract_end' value="+res[i]['contract_end']+" style='display:block'><h6 id='warning_fill' style='display:none;margin-bottom:0;color:red'>please fill this</h6><h6 id='warning_date' style='display:none;margin-bottom:0;color:red'>too small</h6><input type='hidden' value="+res[i]['id']+" id='last_id'><label class='py-1 mb-0' id='last_label_end' style='display:none'>"+end.toLocaleDateString("id-ID", options)+"</label></div>\
                                    <div class='col-4 py-1 border border-body text-dark'><a href='#' class='btn btn-sm btn-primary py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='extendContract("+res[i]['enroll_id']+");' id='extendButton' style='visibility:visible'><span class='fa fa-arrow-up' style='font-size:9pt'></span> Extend</a> <a href='#' class='btn btn-sm btn-danger py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial;display:none' onClick='deleteContract("+res[i]['id']+","+res[i]['enroll_id']+");' id='deleteButton' style='visibility:visible'><span class='fa fa-trash' style='font-size:9pt'></span></a><a href='#' class='btn ml-1 btn-sm btn-primary py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='exportPenilaian("+res[i]['enroll_id']+","+"\""+res[i]['contract']+"\""+","+"\""+res[i]['contract_end']+"\""+");'>&nbsp;<span class='fa fa-file-pdf-o' style='font-size:9pt'></span> Penilaian&nbsp;&nbsp;</a>\<a href='#' class='btn btn-sm btn-warning py-0 px-2 ml-1' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='editPenilaianKinerja("+res[i]['enroll_id']+","+"\""+res[i]['contract']+"\""+","+"\""+res[i]['contract_end']+"\""+");'>&nbsp;<span class='fa fa-edit' style='font-size:9pt'></span> Nilai&nbsp;&nbsp;</a>\</div>\
                                </div>");
                            }
                        }else{
                            if(this_day.getTime()>end.getTime()){
                                $('#working_contract_active').append("<div class='row px-3'>\
                                    <div class='col-2 pt-2 pb-1 border border-body border-left-0 bg-danger text-dark' style='font-weight:bold'>Nonactive Contract</div>\
                                    <div class='col-3 pt-2 pb-1 border border-body text-dark'>"+start.toLocaleDateString("id-ID", options)+"<input type='hidden' class='form-control form-control-sm' id='start_contract' value="+res[i]['contract']+"></div>\
                                    <div class='col-3 py-1 border border-body text-dark' style='font-weight:bold'>"+start.toLocaleDateString("id-ID", options)+"<input type='hidden' class='form-control form-control-sm' id='last_contract_end' value="+res[i]['contract_end']+" style='display:block'><h6 id='warning_fill' style='display:none;margin-bottom:0;color:red'>please fill this</h6><h6 id='warning_date' style='display:none;margin-bottom:0;color:red'>too small</h6><input type='hidden' value="+res[i]['id']+" id='last_id'><label class='py-1 mb-0' id='last_label_end' style='display:none'>"+end.toLocaleDateString("id-ID", options)+"</label></div>\
                                    <div class='col-4 py-1 border border-body text-dark'><a href='#' class='btn btn-sm btn-primary py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='extendContract("+res[i]['enroll_id']+");' id='extendButton' style='visibility:visible'><span class='fa fa-arrow-up' style='font-size:9pt'></span> Extend</a>  <a href='#' class='btn btn-sm btn-danger py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='deleteContract("+res[i]['id']+","+res[i]['enroll_id']+");' id='deleteButton' style='visibility:visible'><span class='fa fa-trash' style='font-size:9pt'></span></a><a href='#' class='btn ml-1 btn-sm btn-primary py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='exportPenilaian("+res[i]['enroll_id']+","+"\""+res[i]['contract']+"\""+","+"\""+res[i]['contract_end']+"\""+");'>&nbsp;<span class='fa fa-file-pdf-o' style='font-size:9pt'></span> Penilaian&nbsp;&nbsp;</a>\<a href='#' class='btn btn-sm btn-warning py-0 px-2 ml-1' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='editPenilaianKinerja("+res[i]['enroll_id']+","+"\""+res[i]['contract']+"\""+","+"\""+res[i]['contract_end']+"\""+");'>&nbsp;<span class='fa fa-edit' style='font-size:9pt'></span> Nilai&nbsp;&nbsp;</a>\</div>\
                                </div>");
                            }else{
                                $('#working_contract_active').append("<div class='row px-3'>\
                                    <div class='col-2 pt-2 pb-1 border border-body border-left-0 text-dark'>Active Contract</div>\
                                    <div class='col-3 pt-2 pb-1 border border-body text-dark'>"+start.toLocaleDateString("id-ID", options)+"<input type='hidden' class='form-control form-control-sm' id='start_contract' value="+res[i]['contract']+"></div>\
                                    <div class='col-3 py-1 border border-body text-dark'>"+end.toLocaleDateString("id-ID", options)+"<input type='hidden' class='form-control form-control-sm' id='last_contract_end' value="+res[i]['contract_end']+" style='display:block'><h6 id='warning_fill' style='display:none;margin-bottom:0;color:red'>please fill this</h6><h6 id='warning_date' style='display:none;margin-bottom:0;color:red'>too small</h6><input type='hidden' value="+res[i]['id']+" id='last_id'><label class='py-1 mb-0' id='last_label_end' style='display:none'>"+end.toLocaleDateString("id-ID", options)+"</label></div>\
                                    <div class='col-4 py-1 border border-body text-dark'><a href='#' class='btn btn-sm btn-primary py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='extendContract("+res[i]['enroll_id']+");' id='extendButton' style='visibility:visible'><span class='fa fa-arrow-up' style='font-size:9pt'></span> Extend</a>  <a href='#' class='btn btn-sm btn-danger py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='deleteContract("+res[i]['id']+","+res[i]['enroll_id']+");' id='deleteButton' style='visibility:visible'><span class='fa fa-trash' style='font-size:9pt'></span></a><a href='#' class='btn ml-1 btn-sm btn-primary py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='exportPenilaian("+res[i]['enroll_id']+","+"\""+res[i]['contract']+"\""+","+"\""+res[i]['contract_end']+"\""+");'>&nbsp;<span class='fa fa-file-pdf-o' style='font-size:9pt'></span> Penilaian&nbsp;&nbsp;</a>\<a href='#' class='btn btn-sm btn-warning py-0 px-2 ml-1' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='editPenilaianKinerja("+res[i]['enroll_id']+","+"\""+res[i]['contract']+"\""+","+"\""+res[i]['contract_end']+"\""+");'>&nbsp;<span class='fa fa-edit' style='font-size:9pt'></span> Nilai&nbsp;&nbsp;</a>\</div>\
                                </div>");
                            }
                        }
                    }else{
                        $('#working_contract_active').append("<div class='row px-3'>\
                            <div class='col-2 py-1 border border-left-0 border-body'>Kontrak ke -"+(i+1)+"</div>\
                            <div class='col-3 py-1 border border-body'>"+start.toLocaleDateString("id-ID", options)+"</div>\
                            <div class='col-3 py-1 border border-body'>"+end.toLocaleDateString("id-ID", options)+"</div>\
                            <div class='col-4 py-1 border border-body'>\
                                <a href='#' class='btn btn-sm btn-danger py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='printContract("+res[i]['enroll_id']+","+"\""+res[i]['contract']+"\""+","+"\""+res[i]['contract_end']+"\""+");'>&nbsp;<span class='fa fa-file-pdf-o' style='font-size:9pt'></span> Print&nbsp;&nbsp;</a>\
                                <a href='#' class='btn btn-sm btn-primary py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='exportPenilaian("+res[i]['enroll_id']+","+"\""+res[i]['contract']+"\""+","+"\""+res[i]['contract_end']+"\""+");'>&nbsp;<span class='fa fa-file-pdf-o' style='font-size:9pt'></span> Penilaian&nbsp;&nbsp;</a>\
                                <a href='#' class='btn btn-sm btn-warning py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' onClick='editPenilaianKinerja("+res[i]['enroll_id']+","+"\""+res[i]['contract']+"\""+","+"\""+res[i]['contract_end']+"\""+");'>&nbsp;<span class='fa fa-edit' style='font-size:9pt'></span> Nilai&nbsp;&nbsp;</a>\
                            </div>\
                        </div>");
                    }
                }
            }
        });
    }


    function openExtendModal(enroll_id) {
        getDetail(enroll_id); // ambil data seperti sebelumnya
        $('#extendContractModal').modal('show');
    }


    $(document).ready(function () {
        // Buka modal pertama
        $('#openExtendModal').click(function () {
            $('#extendContractModal').modal('show');
        });
        $('#closeExtendModal').click(function () {
            $('#extendContractModal').modal('hide');
            resetPenilaianKinerja();
            resetPenilaianKinerja();
        });
        // Tombol tutup modal penilaian
        $(document).on('click', '#btn-close-modal', function () {
            $('#penilaianKinerjaModal').modal('hide');
            resetPenilaianKinerja();
            resetPenilaianKinerja();
        });
    });

    function resetPenilaianKaryawan() {
        // Text atau Label
        $('#enroll_id_text,#enroll_id_input_2_val, #awal_kontrak_text_val, #akhir_kontrak_text_val, #periode_penilaian_text_val, #employee_name_text, #awal_kontrak_text, #akhir_kontrak_text, #department_text, #bagian_text, #jabatan_text, #periode_penilaian_text, #diketahui_oleh').text('');
    }
    function resetPenilaianKinerja() {
        // Input hidden atau lainnya
        $('#enroll_id_input_2, #penilaian_kinerja_id ,#penilai').val('');

        // Uraian tugas & target
        for (let i = 1; i <= 5; i++) {
            $('#uraian_tugas_' + i).val('');
            $('#target_pencapaian_' + i).val('');
        }

        // Reset radio button nilai kinerja
        $('input[name="nilai_kinerja"]').prop('checked', false);

        // Reset kompetensi
        const kompetensiFields = [
            "tanggung_jawab_tugas",
            "inisiatif_kerjasama",
            "akurasi_pekerjaan",
            "kemauan_kegigihan",
            "penyampaian_informasi",
            "attitude_sikap_kerja"
        ];
        kompetensiFields.forEach(function(field) {
            $('input[name="kompetensi[' + field + ']"]').prop('checked', false);
        });

        // Rekomendasi
        $('#rekomendasi_perpanjang_kontrak, #rekomendasi_phk, #rekomendasi_demosi, #rekomendasi_promosi, #rekomendasi_training').prop('checked', false);
        $('input[name="perpanjang_bulan"]').val('');
        $('input[name="judul_training"]').val('');

        // Penilaian
        $('#penilaian_kinerja, #penilaian_kompeten, #penilaian_kedisiplinan, #penilaian_akhir').val('');

        // Kejadian
        const keys = ['sp3_kali', 'sp2_kali', 'sp1_kali', 'kecelakaan_kali', 'mangkir_kali', 'ijin_kali'];
        keys.forEach(function(key) {
            $('input[name="kejadian[' + key + ']"]').val('');
            $('input[name="total[' + key + ']"]').val('');
        });

        $('input[name="total_pengurangan"]').val('');
    }

    function exportPenilaian(enroll_id, contract, contract_end) {
            var url = 'export_penilaian_kinerja_staff_pdf?enroll_id='+enroll_id+'&contract='+contract+'&contract_end='+contract_end;
            window.open(url, '_blank');
        }

    function editPenilaianKinerja(enroll_id,contract,contract_end){
        $('#extendContractModal').modal('hide');
        resetPenilaianKinerja();
        $('#penilaianKinerjaModal').modal('show');

        $.ajax({
            type: "post",
            url: '{{ route('hris.penilaian_kinerja_staff.get_employee_contract_staff_by_id') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: enroll_id,
                contract: contract,
                contract_end: contract_end,
            },
            success: function(res) {
                var data = res.data;
                var data_penilaian = res.data_penilaian;
                $('#enroll_id_text').text(data[0]['enroll_id']);
                $('#employee_name_text').text(data[0]['employee_name']);
                $('#enroll_id_input_2').val(data[0]['enroll_id']);
                $('#awal_kontrak_text').text(moment(contract).format('DD MMMM YYYY'));
                $('#akhir_kontrak_text').text(moment(contract_end).format('DD MMMM YYYY'));
                $('#department_text').text(data[0]['department_name']);
                $('#bagian_text').text(data[0]['sub_dept_name']);
                $('#jabatan_text').text(data[0]['status_jabatan']);
                $('#periode_penilaian_text').text(moment(contract).format('DD MMMM YYYY')+' - '+moment(contract_end).format('DD MMMM YYYY'));
                $('#diketahui_oleh').text(data[0]['employee_name']);

                $('#enroll_id_input_2_val').val(data[0]['enroll_id']);
                $('#awal_kontrak_text_val').val(contract);
                $('#akhir_kontrak_text_val').val(contract_end);
                $('#periode_penilaian_text_val').val(contract+'/'+contract_end);
                console.log('data_penilaian', data_penilaian);
                if(data_penilaian == null){
                    resetPenilaianKinerja();
                }else{
                        $('#penilaian_kinerja_id').val(data_penilaian.id);

                        $('#uraian_tugas_1').val(data_penilaian.uraian_tugas_1);
                        $('#target_pencapaian_1').val(data_penilaian.target_pencapaian_1);
                        $('#uraian_tugas_2').val(data_penilaian.uraian_tugas_2);
                        $('#target_pencapaian_2').val(data_penilaian.target_pencapaian_2);
                        $('#uraian_tugas_3').val(data_penilaian.uraian_tugas_3);
                        $('#target_pencapaian_3').val(data_penilaian.target_pencapaian_3);
                        $('#uraian_tugas_4').val(data_penilaian.uraian_tugas_4);
                        $('#target_pencapaian_4').val(data_penilaian.target_pencapaian_4);
                        $('#uraian_tugas_5').val(data_penilaian.uraian_tugas_5);
                        $('#target_pencapaian_5').val(data_penilaian.target_pencapaian_5);

                        $('input[name="nilai_kinerja"]').each(function() {
                        if ($(this).val() == data_penilaian.nilai_kinerja) {
                            $(this).prop('checked', true); // Check the radio button with the matching value
                        } else {
                            $(this).prop('checked', false); // Uncheck other radio buttons
                        }

                        const kompetensiFields = [
                            "tanggung_jawab_tugas",
                            "inisiatif_kerjasama",
                            "akurasi_pekerjaan",
                            "kemauan_kegigihan",
                            "penyampaian_informasi",
                            "attitude_sikap_kerja"
                        ];

                        kompetensiFields.forEach(function(field) {
                            $('input[name="kompetensi[' + field + ']"]').each(function () {
                                if ($(this).val() == data_penilaian[field]) {
                                    $(this).prop('checked', true);
                                } else {
                                    $(this).prop('checked', false);
                                }
                            });
                        });
                        if (data_penilaian.rekomendasi_perpanjang_kontrak == 1) {
                            $('#rekomendasi_perpanjang_kontrak').prop('checked', true);
                        } else {
                            $('#rekomendasi_perpanjang_kontrak').prop('checked', false);
                        }

                        // Set jumlah bulan perpanjangan
                        $('input[name="perpanjang_bulan"]').val(data_penilaian.perpanjang_bulan || '');

                                        // Rekomendasi: PHK
                        $('#rekomendasi_phk').prop('checked', data_penilaian.rekomendasi_phk == 1);

                        // Rekomendasi: Demosi
                        $('#rekomendasi_demosi').prop('checked', data_penilaian.rekomendasi_demosi == 1);

                        // Rekomendasi: Promosi
                        $('#rekomendasi_promosi').prop('checked', data_penilaian.rekomendasi_promosi == 1);

                        // Rekomendasi: Training
                        $('#rekomendasi_training').prop('checked', data_penilaian.rekomendasi_training == 1);
                        $('input[name="judul_training"]').val(data_penilaian.judul_training || '');


                        $('#penilaian_kinerja').val(data_penilaian.nilai_kinerja || '');
                        $('#penilaian_kompeten').val(data_penilaian.rata_rata_kompetensi || '');
                        $('#penilaian_kedisiplinan').val(data_penilaian.total_pengurangan || '');
                        $('#penilaian_akhir').val(data_penilaian.nilai_akhir || '');
                        $('#penilai').val(data_penilaian.penilai || '');
                        $('input[name="rekomendasi"][value="' + data_penilaian.rekomendasi_tindak_lanjut + '"]').prop('checked', true);



                        if (data_penilaian.kejadian) {
                                Object.entries(data_penilaian.kejadian).forEach(([key, val]) => {
                                    $('input[name="kejadian[' + key + ']"]').val(val);
                                });
                            }

                            if (data_penilaian.total) {
                                Object.entries(data_penilaian.total).forEach(([key, val]) => {
                                    $('input[name="total[' + key + ']"]').val(val);
                                });
                            }

                            $('input[name="total_pengurangan"]').val(data_penilaian.total_pengurangan);

                    });
                }

            }
        });
    }

    function extendContract(enroll_id){
        document.getElementById("extendButton").style.visibility="hidden";

        document.getElementById("deleteButton").style.visibility="hidden";
        document.getElementById("last_contract_end").style.display="none";
        document.getElementById("last_label_end").style.display="none";
        $.ajax({
            type: "post",
            url: '{{ route('hris.hrd.get_employee_contract2') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: enroll_id,
            },
            success: function(res) {
                var last_date=new Date(res[res.length-1]['contract_end']);
                last_date.setDate(last_date.getDate()+1);
                var dd = String(last_date.getDate()).padStart(2, '0');
                var mm = String(last_date.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = last_date.getFullYear();

                today = yyyy + '-' + mm + '-' + dd;

                var start=new Date(today);
                var options = {  year: 'numeric', month: 'long', day: 'numeric' };
                $('#working_contract_extend').append("<div class='row px-3'>\
                    <div class='col-2 pt-2 pb-1 border border-body bg-warning text-dark' style='font-weight:bold'>New Contract</div>\
                    <div class='col-3 pt-2 pb-1 border border-body text-dark'><input type='hidden' class='form-control form-control-sm' id='new_start_contract' value="+today+">"+start.toLocaleDateString("id-ID", options)+"</div>\
                    <div class='col-3 py-1 border border-body text-dark'><input type='date' class='form-control form-control-sm' id='new_end_contract'><h6 id='warning_fill2' style='display:none;margin-bottom:0;color:red'>please fill this</h6><h6 id='warning_date2' style='display:none;margin-bottom:0;color:red'>too small</h6></div>\
                    <div class='col-4 py-1 border border-body text-dark'><a href='#' class='btn btn-sm btn-warning text-dark py-0 px-2 border border-body' style='font-weight:bold;font-size:9pt;font-family:Arial' id='cancelExtendButton' style='visibility:visible' onclick='cancelExtend()'><i class='fa fa-times-circle' style='font-size:9pt'></i> Cancel</a> <a href='#' class='btn btn-sm btn-warning text-dark border border-body py-0 px-2' style='font-weight:bold;font-size:9pt;font-family:Arial' id='saveExtendButton' style='visibility:visible' onclick='newExtend("+res[0]['enroll_id']+")'><i class='fa fa-arrow-circle-down' style='font-size:9pt'></i> Save</a></div>\
                </div>");
            }
        });
    }
    function cancelExtend(){
        document.getElementById('cancelExtendButton').style.visibility='hidden';
        document.getElementById('extendButton').style.visibility='visible';
        document.getElementById("deleteButton").style.visibility="visible";

        document.getElementById("last_contract_end").style.display="block";
        document.getElementById("last_label_end").style.display="none";
        $('#working_contract_extend').empty();
    }
    function updateContract(enroll_id){
        var last_contract=($('#last_contract_end').val());
        if(last_contract==''){
            document.getElementById('last_contract_end').style.border='1px solid red';
            document.getElementById('last_contract_end').style.textDecorationColor='red';
            document.getElementById('warning_fill').style.display='block';
        }else{
            document.getElementById('last_contract_end').style.border='';
            document.getElementById('last_contract_end').style.textDecorationColor='';
            document.getElementById('warning_fill').style.display='none';
            var start_contract=new Date($('#start_contract').val());
            var end_contract=new Date($('#last_contract_end').val());
            if(start_contract>end_contract){
                document.getElementById('last_contract_end').style.border='1px solid red';
                document.getElementById('last_contract_end').style.textDecorationColor='red';
                document.getElementById('warning_date').style.display='block';
            }else{
                $.ajax({
                    type: "post",
                    url: '{{ route('hris.hrd.update_employee_contract') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: enroll_id,
                        last_date:$('#last_contract_end').val(),
                        last_id:$('#last_id').val(),
                    },
                    success: function(res) {
                        iziToast.success({
                            message: 'update kontrak berhasil',
                            position: 'center',
                            timeout:1300,
                        });
                        getDetail(res);
                        datatable.ajax.reload();
                    }
                });
            }
        }
    }
    function newExtend(enroll_id){
        var last_contract=($('#new_end_contract').val());
        if(last_contract==''){
            document.getElementById('new_end_contract').style.border='1px solid red';
            document.getElementById('new_end_contract').style.textDecorationColor='red';
            document.getElementById('warning_fill2').style.display='block';
        }else{
            document.getElementById('new_end_contract').style.border='';
            document.getElementById('new_end_contract').style.textDecorationColor='';
            document.getElementById('warning_fill2').style.display='none';
            var start_contract=new Date($('#new_start_contract').val());
            var end_contract=new Date($('#new_end_contract').val());
            if(start_contract>end_contract){
                document.getElementById('new_end_contract').style.border='1px solid red';
                document.getElementById('new_end_contract').style.textDecorationColor='red';
                document.getElementById('warning_date2').style.display='block';
            }else{
                $.ajax({
                    type: "post",
                    url: '{{ route('hris.hrd.new_employee_contract') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: enroll_id,
                        contract:$('#new_start_contract').val(),
                        end_contract:$('#new_end_contract').val(),
                    },
                    success: function(res) {
                        iziToast.success({
                            message: 'tambah kontrak berhasil',
                            position: 'center',
                            timeout:1300,
                        });
                        getDetail(res);
                    }
                });
            }
        }
    }
    function deleteContract(id,enroll_id){
        $.ajax({
            type: "post",
            url: '{{ route('hris.hrd.delete_employee_contract') }}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id,
                enroll_id:enroll_id
            },
            success: function(res) {
                iziToast.success({
                    message: 'delete kontrak berhasil',
                    position: 'center',
                    timeout:1300,
                });
                getDetail(res);
            }
        });
    }
    function print_pdf(enroll_id){
        var today=new Date();
        var month_now=today.getMonth();
        var year_now=today.getFullYear();
        var no_form='HRD-NAG/PKWT'+'/'+integerToRoman(month_now+1)+'/'+year_now;
        var url = 'print_pdf_kontrak?enroll_id='+enroll_id+'&no_form='+no_form;
        window.open(url, '_blank');
    }
    function printContract(enroll_id,contract,contract_end){
        var today=new Date();
        var month_now=today.getMonth();
        var year_now=today.getFullYear();
        var contract2=contract;
        var contract_end2=contract_end;
        var no_form='HRD-NAG/PKWT'+'/'+integerToRoman(month_now+1)+'/'+year_now;
        var url = 'print_pdf_kontrak_2?enroll_id='+enroll_id+'&no_form='+no_form+'&contract='+contract2+'&contract_end='+contract_end2;
        window.open(url, '_blank');
    }
    function integerToRoman(num) {
        const romanValues = {
            M: 1000,
            CM: 900,
            D: 500,
            CD: 400,
            C: 100,
            XC: 90,
            L: 50,
            XL: 40,
            X: 10,
            IX: 9,
            V: 5,
            IV: 4,
            I: 1
        };
        let roman = '';
        for (let key in romanValues) {
            while (num >= romanValues[key]) {
                roman += key;
                num -= romanValues[key];
            }
        }
        return roman;
    }
</script>
@endsection
