@php use Illuminate\Support\Str; @endphp


@extends('admin.adminlayouts.adminlayout3')

@section('head')
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

	<!---Sweetalert Css-->
	<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
	<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

    <!-- Date Picker css-->
    <link href="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.css')}}" rel="stylesheet" />

    <!-- Time picker css-->
    <link href="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.css')}}" rel="stylesheet" />

    <!-- Tabs css-->
	<link href="{{URL::asset('assets/plugins/tabs/tabs-style.css')}}" rel="stylesheet" />


@stop
<style>
 .timestamp {
            display: inline-block;
            background: #4CAF50;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }
.is-invalid {
    border: 2px solid red;
    background-color: #ffe6e6;
}

/* Misalnya, modal edit memiliki z-index lebih tinggi daripada modal list */
#ajax-modal-edit1 {
    z-index: 1060 !important;
}
#ajax-modal-edit1 .modal-dialog {
    z-index: 1070 !important;
}

#datatable-ajax-crud thead th {
    background-color: var(--primary);
    color: white;
}
.primary-button {
    background-color: var(--primary) !important;
    color: white !important;
}

#table_detail_cuti_karyawan thead th {
    background-color: var(--primary);
    color: white;
}
#footer-primary {
    background-color: var(--primary);
    color: white;
}

.wrapper {
  margin: auto;
  text-align: center;
}

h1 {
  color: #130f40;
  font-family: 'Varela Round', sans-serif;
  letter-spacing: -.5px;
  font-weight: 700;
  padding-bottom: 10px;
}

.upload-container {
  background-color: rgb(239, 239, 239);
  border-radius: 6px;
  padding: 10px;
}

.border-container {
  border: 2px dashed rgba(198, 198, 198, 0.65);
  padding: 20px;
}

.border-container p {
  color: #130f40;
  font-weight: 600;
  font-size: 1.1em;
  letter-spacing: -1px;
  margin-top: 10px;
  margin-bottom: 0;
  opacity: 0.65;
}

#file-browser {
  text-decoration: none;
  color: rgb(22,42,255);
  border-bottom: 3px dotted rgba(22, 22, 255, 0.85);
}

#file-browser:hover {
  color: rgb(0, 0, 255);
  border-bottom: 3px dotted rgba(0, 0, 255, 0.85);
}

.icons {
  color: #95afc0;
  opacity: 0.55;
}

.drag-over {
    border: 2px dashed #007bff;
    background-color: #f8f9fa;
}

#drop-zone {
    border: 2px dashed #007bff;
    padding: 20px;
    text-align: center;
    margin-bottom: 10px;
    cursor: pointer;
}
.drag-over {
    background-color: #f0f8ff;
}
.file-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    border: 1px solid #ccc;
    margin: 5px 0;
    border-radius: 5px;
}
.file-item img {
    width: 50px;
    height: 50px;
    margin-right: 10px;
}
.file-name {
    flex-grow: 1;
}
.remove-btn {
    background: red;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 5px;
}

#total-nominal {
    font-weight: bold;
}
#table_detail_cuti_karyawan {
    border-collapse: separate;
    border-spacing: 0 2px;
}
#datatable-ajax-crud-verifikasi th,
#datatable-ajax-crud-verifikasi td {
    white-space: nowrap;
}
</style>
<style>
    .radio-wrapper {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    cursor: pointer;
    transition: border-color 0.2s, background-color 0.2s, color 0.2s;
    color: #333;
    width: 100%;
    }

    .radio-wrapper:hover {
        border-color: #007bff;
        background-color: #e6f0ff;
    }

    .radio-wrapper input[type="radio"] {
        accent-color: #007bff; /* modern browsers: warna bulatan */
    }

    .radio-wrapper input[type="radio"]:checked + span {
        font-weight: bold;
    }

    .radio-wrapper input[type="radio"]:checked ~ span,
    .radio-wrapper input[type="radio"]:checked {
        color: white;
    }

    .radio-wrapper input[type="radio"]:checked ~ span {
        color: white;
    }

    /* Highlight wrapper jika radio-nya dipilih */
    .radio-wrapper input[type="radio"]:checked ~ span::before {
        background-color: #007bff;
    }

    .radio-wrapper input[type="radio"]:checked ~ span,
    .radio-wrapper input[type="radio"]:checked {
        background-color: #008cff;
    }

    .radio-wrapper:has(input[type="radio"]:checked) {
        border-color: #007bff;
        background-color: #008cff;
        color: white;
    }



    .radio-label {
        display: flex;
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        transition: 0.3s;
        width: 100%;
    }

    .radio-container {
        display: flex;
        gap: 1rem;
    }

    .radio-label.active {
        border-color: #007bff;
        background-color: #e9f5ff;
    }
</style>

@section('mainarea')
<?php ini_set('date.timezone', 'Asia/Jakarta'); ?>
    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('entertaint_tamu.index')}}">Administrasi</a></li>
            <li class="active"><span>Surat Peringatan</span></li>
            <input type="hidden" value="{{$user}}" id="username_who_access">
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


    <div class="row p-3">
        <div class="col-md-12">
            <div class="card card-primary card-outline tab-content">
                    <div class="card-header bg-primary p-3">
                        <div class="card-title">Surat Peringatan</div>
                    </div>
                        <div class="mt-4 ml-4 mr-5 mb-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div clasl="" style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                                        <div class="mt-5 p-0">
                                            <button class="btn btn-primary w-100" onclick="openModalBuatPengajuan()"  data-toggle="tooltip" title="Cari Data" id="recap_labor_cost_2"><i class="fa fa-plus" aria-hidden="true"></i> Buat Surat Peringatan</button>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="m-0 p-0">
                            <div class="card-body m-0">
                                <div class=" px-3 py-2 pt-5">
                                            <!-- Tab Waiting -->
                                            <div class="tab_content active" id="tab-content-waiting">
                                                <div class="table-responsive">
                                                    <table id="datatable-ajax-crud-waiting" class="table table-sm table-striped table-hover table-bordered w-100">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th scope="col">Nik</th>
                                                                <th scope="col">Nama</th>
                                                                <th scope="col">Bagian</th>
                                                                <th scope="col">Department</th>
                                                                <th scope="col">Peringatan</th>
                                                                <th scope="col">Pasal</th>
                                                                <th scope="col">Tanggal Mulai</th>
                                                                <th scope="col">Tanggal Sampai</th>
                                                                <th scope="col">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </div>


    {{-- MODAL TAMBAH --}}
    <div class="modal fade" id="ajax-modal-tambah"  role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-scrollable" role="document" style="max-width: 50%;">
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2 font-weight-bold" id="title-modal-edit1"></h4>
                             <div class="mt-0 p-0">
                                <button onclick="closeModalBuatPengajuan()" class="btn btn-danger btn-sm w-100" data-toggle="tooltip" title="Tutup">x</button>
                             </div>
                        </div>
                        <div class="modal-body">
                            <input id="enroll_id_diajukan_oleh" type="hidden">
                            <div class="row">
                                <div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px;">
                                    <h6 style="font-weight: bold;">Dengan ini mengajukan karyawan :</h6>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Pilih Karyawan : </label>
                                        <select id="karyawanBermasalahID" name="karyawanBermasalahID" style='width: 100%;' data-placeholder="Pilih karyawan" class="form-control create-control select2 select2-show-search EmployeeID">
                                            <option value="">-- Pilih Karyawan --</option>
                                            @foreach ($selectemployee as $r_empl)
                                                <option
                                                    value="{{$r_empl->enroll_id}}"
                                                    data-department_name="{{$r_empl->department_name}}"
                                                    data-sub_dept_name="{{$r_empl->sub_dept_name}}"
                                                    data-department_data_id="{{$r_empl->department_id}}"
                                                    data-sub_dept_data_id="{{$r_empl->sub_dept_id}}"
                                                    data-status_jabatan="{{$r_empl->status_jabatan}}"
                                                    data-employee_name="{{$r_empl->employee_name}}"
                                                    data-employee_nik="{{$r_empl->nik}}"
                                                >
                                                    {{$r_empl->select_employee}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div style="display: block; text-align: start; height: 25px;">
                                            <span>NAMA</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="display: block; text-align: start; height: 25px;">
                                         <span>:</span>
                                        <strong><span id="create_employee_name"></span></strong>
                                        <input id="enroll_id_karyawan_bermasalah" type="hidden">
                                    </div>
                                </div>
                                <div class="col-md-7">

                                </div>
                                <div class="col-md-2">
                                    <div style="display: block; text-align: start;  height: 25px;">
                                            <span>NIK</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="display: block; text-align: start;  height: 25px;">
                                        <span>:</span>
                                        <strong><span id="create_employee_nik"></span></strong>
                                    </div>
                                </div>
                                <div class="col-md-7">

                                </div>
                                <div class="col-md-2">
                                    <div style="display: block; text-align: start;  height: 25px;">
                                            <span>BAGIAN</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="display: block; text-align: start; height: 25px;">
                                        <span>:</span>
                                        <strong><span id="create_employee_sub_dept"></span></strong>
                                    </div>
                                </div>
                                <div class="col-md-7">

                                </div>
                                <div class="col-md-2">
                                    <div style="display: block; text-align: start; height: 25px;">
                                            <span>DEPARTMENT</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="display: block; text-align: start; height: 25px;">
                                        <span>:</span>
                                        <strong><span id="create_employee_department"></span></strong>
                                    </div>
                                </div>
                                <div class="col-md-7">

                                </div>
                                <div class="col-md-2">
                                    <div style="display: block; text-align: start; height: 25px;">
                                            <span>JABATAN</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="display: block; text-align: start; height: 25px;">
                                        <span>:</span>
                                        <strong><span id="create_employee_jabatan"></span></strong>
                                    </div>
                                </div>
                                <div class="col-md-7">

                                </div>
                                 <div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px; padding-top: 10px;">
                                    <h6 style="font-weight: bold;">Untuk diberikan Surat Peringatan :</h6>
                                </div>

                                <div class="col-md-12">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="tindakan_pendisiplinan" value="sp_1" checked class="mr-2">
                                            <span>SP 1</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="tindakan_pendisiplinan" value="sp_2" class="mr-2">
                                            <span>SP 2</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="tindakan_pendisiplinan" value="sp_3" class="mr-2">
                                            <span>SP 3</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px; margin-bottom: 3px; padding-top: 10px;">
                                    <h6 style="font-weight: bold;">Dikarenakan Karyawan tersebut telah melakukan pelanggaran Peraturan Perusahaan pasal :</h6>
                                </div>
                                <div class="col-md-11">
                                    <div class="form-group">
                                        <select id="pasalKaryawan" name="pasalKaryawan" style='width: 100%; font-weight: bold;' data-placeholder="Pilih Pasal" class="form-control create-control select2-show-search EmployeeID">
                                            <option value="">-- Pilih Pasal --</option>
                                            {{-- @foreach ($pasal_data as $r_empl)
                                                <option
                                                    value="{{$r_empl->kode_pasal}}"
                                                >
                                                    {{$r_empl->pasal_select}}

                                                </option>
                                            @endforeach --}}
                                        </select>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                  <div class="col-md-1">
                                    <button onclick="copyPasal()" class="btn btn-primary w-100"><i class="fa fa-copy"></i></button>
                                </div>
                                <div class="col-md-12" style="margin-top: 1px; margin-bottom: 3px; padding-top: 10px;">
                                    <h6 style="font-weight: bold;">Dengan alasan :</h6>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea id="alasan_pelanggaran" name="alasan_pelanggaran" class="form-control" rows="3" placeholder="Alasan Pelanggaran" maxlength="500"></textarea>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                       <h6 style="font-weight: bold;">Sanksi yang diberikan berlaku mulai tanggal:</h6>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input id="tanggal_berlaku_mulai" name="tanggal_berlaku_mulai" type="text" class="form-control fc-datepicker" placeholder="Tanggal Mulai" maxlength="50" size="50">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group text-center">
                                        <label class="form-label">Sampai </label>
                                    </div>
                                </div>
                                 <div class="col-md-3">
                                    <div class="form-group">
                                        <input id="tanggal_berlaku_sampai" name="tanggal_berlaku_sampai" type="text" class="form-control fc-datepicker" placeholder="Tanggal Sampai" maxlength="50" size="50">
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top: 1px; margin-bottom: 3px; padding-top: 10px;">
                                    <h6 style="font-weight: bold;">No Form :</h6>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input  id="no_form" name="no_form" class="form-control" rows="3" placeholder="Nomor Form" maxlength="500"></input>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                </div>
                                  <div class="col-md-3">
                                    <button class="btn btn-primary w-100" id="btn-simpan-pengajuan" data-toggle="tooltip" title="Simpan Data" style="margin-top: 10px;">
                                        <i class="fa fa-save" aria-hidden="true"></i>
                                        Simpan
                                    </button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- EDIT SP --}}
    <div class="modal fade" id="ajax-modal-edit-pengajuan"  role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 50%;">
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2 font-weight-bold" id="title-modal-edit-surat-peringatan"></h4>
                             <div class="mt-0 p-0">
                                <button onclick="closeModalEditPengajuan()" class="btn btn-danger btn-sm w-100" data-toggle="tooltip" title="Tutup">x</button>
                             </div>
                        </div>
                        <div class="modal-body">
                            <input id="enroll_id_diajukan_oleh" type="hidden">
                            <div class="row">
                                <div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px;">
                                    <h6 style="font-weight: bold;">Dengan ini mengajukan karyawan :</h6>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Pilih Karyawan : </label>
                                        <select required id="karyawanBermasalahIDEdit" name="karyawanBermasalahIDEdit" style='width: 100%;' data-placeholder="Pilih karyawan" class="form-control create-control select2 select2-show-search EmployeeID">
                                            <option value="">-- Pilih Karyawan --</option>
                                            @foreach ($selectemployee as $r_empl)
                                                <option
                                                    value="{{$r_empl->enroll_id}}"
                                                    data-department_name_edit="{{$r_empl->department_name}}"
                                                    data-sub_dept_name_edit="{{$r_empl->sub_dept_name}}"
                                                    data-department_data_id_edit="{{$r_empl->department_id}}"
                                                    data-sub_dept_data_id_edit="{{$r_empl->sub_dept_id}}"
                                                    data-status_jabatan_edit="{{$r_empl->status_jabatan}}"
                                                    data-employee_name_edit="{{$r_empl->employee_name}}"
                                                    data-employee_nik_edit="{{$r_empl->nik}}"
                                                >
                                                    {{$r_empl->select_employee}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div style="display: block; text-align: start; height: 25px;">
                                            <span>NAMA</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="display: block; text-align: start; height: 25px;">
                                         <span>:</span>
                                        <strong><span id="edit_employee_name"></span></strong>
                                        <input id="edit_enroll_id_karyawan_bermasalah" type="hidden">
                                        <input id="edit_id_pengajuan" type="hidden">
                                    </div>
                                </div>
                                <div class="col-md-7">

                                </div>
                                <div class="col-md-2">
                                    <div style="display: block; text-align: start;  height: 25px;">
                                            <span>NIK</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="display: block; text-align: start;  height: 25px;">
                                        <span>:</span>
                                        <strong><span id="edit_employee_nik"></span></strong>
                                    </div>
                                </div>
                                <div class="col-md-7">

                                </div>
                                <div class="col-md-2">
                                    <div style="display: block; text-align: start;  height: 25px;">
                                            <span>BAGIAN</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="display: block; text-align: start; height: 25px;">
                                        <span>:</span>
                                        <strong><span id="edit_employee_sub_dept"></span></strong>
                                    </div>
                                </div>
                                <div class="col-md-7">

                                </div>
                                <div class="col-md-2">
                                    <div style="display: block; text-align: start; height: 25px;">
                                            <span>DEPARTMENT</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="display: block; text-align: start; height: 25px;">
                                        <span>:</span>
                                        <strong><span id="edit_employee_department"></span></strong>
                                    </div>
                                </div>
                                <div class="col-md-7">

                                </div>
                                <div class="col-md-2">
                                    <div style="display: block; text-align: start; height: 25px;">
                                            <span>JABATAN</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="display: block; text-align: start; height: 25px;">
                                        <span>:</span>
                                        <strong><span id="edit_employee_jabatan"></span></strong>
                                    </div>
                                </div>
                                <div class="col-md-7">

                                </div>
                                 <div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px; padding-top: 10px;">
                                    <h6 style="font-weight: bold;">Untuk diberikan Surat Peringatan :</h6>
                                </div>

                                <div class="col-md-12">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio"  name="edit_tindakan_pendisiplinan" value="sp_1" checked class="mr-2">
                                            <span>SP 1</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="edit_tindakan_pendisiplinan" value="sp_2" class="mr-2">
                                            <span>SP 2</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="edit_tindakan_pendisiplinan" value="sp_3" class="mr-2">
                                            <span>SP 3</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px; margin-bottom: 3px; padding-top: 10px;">
                                    <h6 style="font-weight: bold;">Dikarenakan Karyawan tersebut telah melakukan pelanggaran Peraturan Perusahaan pasal :</h6>
                                </div>
                                <div class="col-md-11">
                                    <div class="form-group">
                                        <select required id="EditpasalKaryawan" name="EditpasalKaryawan" style='width: 100%; font-weight: bold;' data-placeholder="Pilih Pasal" class="form-control create-control select2-show-search EmployeeID">
                                            <option value="">-- Pilih Pasal --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button onclick="copyEditPasal()" class="btn btn-primary w-100"><i class="fa fa-copy"></i></button>
                                </div>
                                <div class="col-md-12" style="margin-top: 1px; margin-bottom: 3px; padding-top: 10px;">
                                    <h6 style="font-weight: bold;">Dengan alasan :</h6>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea id="edit_alasan_pelanggaran" name="edit_alasan_pelanggaran" class="form-control" rows="3" placeholder="Alasan Pelanggaran" maxlength="500"></textarea>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                       <h6 style="font-weight: bold;">Sanksi yang diberikan berlaku mulai tanggal:</h6>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input required id="edit_tanggal_berlaku_mulai" name="edit_tanggal_berlaku_mulai" type="text" class="form-control fc-datepicker" placeholder="Tanggal Mulai" maxlength="50" size="50">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group text-center">
                                        <label class="form-label">Sampai </label>
                                    </div>
                                </div>
                                 <div class="col-md-3">
                                    <div class="form-group">
                                        <input required id="edit_tanggal_berlaku_sampai" name="edit_tanggal_berlaku_sampai" type="text" class="form-control fc-datepicker" placeholder="Tanggal Sampai" maxlength="50" size="50">
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top: 1px; margin-bottom: 3px; padding-top: 10px;">
                                    <h6 style="font-weight: bold;">No Form :</h6>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input id="edit_no_form" required name="edit_no_form" class="form-control" rows="3" placeholder="Nomor Form" maxlength="500"></input>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                </div>
                                  <div class="col-md-3">
                                    <button class="btn btn-primary w-100" id="btn-update-pengajuan" data-toggle="tooltip" title="Update Data" style="margin-top: 10px;">
                                        <i class="fa fa-save" aria-hidden="true"></i>
                                        Update
                                    </button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footerjs')
    <!-- DataTables & Plugins -->
    <script src="{{URL::asset('assets/js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/iziToast.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/html5-qrcode/html5-qrcode.min.js') }}"></script>
    <script src="{{URL::asset('assets/js/timepicker.js') }}"></script>
    <!-- Sweet alert js-->
    <script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>


    <!-- Datepicker js -->
    <script src="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/spectrum-date-picker/jquery-ui.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>

    <!-- Timepicker js -->
    <script src="{{URL::asset('assets/plugins/time-picker/jquery.timepicker.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/time-picker/toggles.min.js')}}"></script>

    <!---Tabs js-->
    <script src="{{URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/tabs/tabs.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/locale/id.min.js"></script>
    <style>
        .checkbox-xl .form-check-input {
            scale: 1.5;
        }
    </style>

    <script>
         $('.fc-datepicker').datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'dd-mm-yy'
        });
    </script>
    <script>
        const semuaPasal = @json($pasal_data);
    </script>
    <script>
    $(document).ready(function () {
        function tampilkanPasal(spFilter) {
            // kosongkan dulu isi dropdown
            const select = $('#pasalKaryawan');
            select.empty().append('<option value="">-- Pilih Pasal --</option>');

            // filter berdasarkan nilai sp (sp_1, sp_2, sp_3)
            const hasilFilter = semuaPasal.filter(pasal => pasal.sp === spFilter?.replace('sp_',''));

            // tambahkan ke select
            hasilFilter.forEach(pasal => {
                const potongText = pasal.pasal_select.length > 140
                    ? pasal.pasal_select.substring(0, 137) + '...'
                    : pasal.pasal_select;

                select.append(`
                    <option value="${pasal.kode_pasal}" data-full-text="${pasal.pasal_select}">
                        ${potongText}
                    </option>
                `);

            });

            // refresh Select2 jika pakai
            select.trigger('change.select2');
        }

        // saat halaman pertama kali load, isi sesuai radio terpilih
        tampilkanPasal($('input[name="tindakan_pendisiplinan"]:checked').val());

        // saat radio SP berubah
        $('input[name="tindakan_pendisiplinan"]').on('change', function () {
            const spVal = $(this).val();
            tampilkanPasal(spVal);
        });
    });
    $(document).ready(function () {
        function tampilkanPasal(spFilter) {
            // kosongkan dulu isi dropdown
            const select = $('#EditpasalKaryawan');
            select.empty().append('<option value="">-- Pilih Pasal --</option>');

            // filter berdasarkan nilai sp (sp_1, sp_2, sp_3)
            const hasilFilter = semuaPasal.filter(pasal => pasal.sp === spFilter?.replace('sp_',''));

            // tambahkan ke select
            hasilFilter.forEach(pasal => {
                const potongText = pasal.pasal_select.length > 140
                    ? pasal.pasal_select.substring(0, 137) + '...'
                    : pasal.pasal_select;

                select.append(`
                    <option value="${pasal.kode_pasal}" data-full-text="${pasal.pasal_select}">
                        ${potongText}
                    </option>
                `);
            });

            // refresh Select2 jika pakai
            select.trigger('change.select2');
        }

        // saat halaman pertama kali load, isi sesuai radio terpilih
        tampilkanPasal($('input[name="edit_tindakan_pendisiplinan"]:checked').val());

        // saat radio SP berubah
        $('input[name="edit_tindakan_pendisiplinan"]').on('change', function () {
            const spVal = $(this).val();
            tampilkanPasal(spVal);
        });
    });
    </script>


    <script>

    function copyPasal() {
        const pasalSelect = $('#pasalKaryawan');
        const selectedOption = pasalSelect.find(':selected');
        const pasalText = selectedOption.data('full-text'); // ambil dari data attribute
        navigator.clipboard.writeText(pasalText);
    }

    function copyEditPasal() {
        const pasalSelect = $('#EditpasalKaryawan');
       const selectedOption = pasalSelect.find(':selected');
        const pasalText = selectedOption.data('full-text'); // ambil dari data attribute
        navigator.clipboard.writeText(pasalText);
    }

        $(document).ready(function () {
        function formatTanggal(tanggal) {
            const dd = String(tanggal.getDate()).padStart(2, '0');
            const mm = String(tanggal.getMonth() + 1).padStart(2, '0'); // Januari = 0
            const yyyy = tanggal.getFullYear();
            return `${dd}-${mm}-${yyyy}`;
        }

        function updateTanggalBerlaku() {
            const today = new Date();
            const startDate = formatTanggal(today);
            const spValue = $('input[name="tindakan_pendisiplinan"]:checked').val();

            let sampaiDate = new Date(today);
            if (spValue === 'sp_3') {
                sampaiDate.setMonth(sampaiDate.getMonth() + 6);
            } else {
                sampaiDate.setMonth(sampaiDate.getMonth() + 3);
            }
            const endDate = formatTanggal(sampaiDate);

            $('#tanggal_berlaku_mulai').val(startDate);
            $('#tanggal_berlaku_sampai').val(endDate);
        }

        // Set default saat halaman dimuat
        updateTanggalBerlaku();

        // Ubah tanggal saat SP 1, 2, atau 3 dipilih
        $('input[name="tindakan_pendisiplinan"]').on('change', function () {
            updateTanggalBerlaku();
        });
    });


         $("#karyawanBermasalahIDEdit").select2().on("select2:select", function() {
            var selectedOption = $('#karyawanBermasalahIDEdit').find(':selected');
            var department = selectedOption.data('department_name_edit');
            var department_id = selectedOption.data('department_data_id_edit');
            var subDept = selectedOption.data('sub_dept_name_edit');
            var subDeptID = selectedOption.data('sub_dept_data_id_edit');
            var statusJabatan = selectedOption.data('status_jabatan_edit');
            var employee_name = selectedOption.data('employee_name_edit');
            var employee_nik = selectedOption.data('employee_nik_edit');

            if(selectedOption.val() != null){
                $("#edit_employee_name").text(employee_name);
                $("#edit_employee_nik").text(employee_nik);
                $("#edit_employee_sub_dept").text(subDept);
                $("#edit_employee_department").text(department);
                $("#edit_employee_jabatan").text(statusJabatan);
                document.getElementById('edit_enroll_id_karyawan_bermasalah').value =  selectedOption.val();
            }
        });


        $("#karyawanBermasalahID").select2().on("select2:select", function() {
            var selectedOption = $('#karyawanBermasalahID').find(':selected');
            var department = selectedOption.data('department_name');
            var department_id = selectedOption.data('department_data_id');
            var subDept = selectedOption.data('sub_dept_name');
            var subDeptID = selectedOption.data('sub_dept_data_id');
            var statusJabatan = selectedOption.data('status_jabatan');
            var employee_name = selectedOption.data('employee_name');
            var employee_nik = selectedOption.data('employee_nik');

            if(selectedOption.val() != null){
                $("#create_employee_name").text(employee_name);
                $("#create_employee_nik").text(employee_nik);
                $("#create_employee_sub_dept").text(subDept);
                $("#create_employee_department").text(department);
                $("#create_employee_jabatan").text(statusJabatan);
                document.getElementById('enroll_id_karyawan_bermasalah').value =  selectedOption.val();
            }
        });

        function printPengajuanPDF(id) {
            var url = "{{ route('tindakan_kedisiplinan.print_sp_karyawan', ':id') }}";
            url = url.replace(':id', id);
            window.open(url, '_blank');
        }

        function openModalEditPengajuan(id) {
            console.log(id);
            $("#ajax-modal-edit-pengajuan").modal('show');
            $("#title-modal-edit-surat-peringatan").text('Edit Surat Peringatan');
            $("#btn-update-pengajuan").show();
            $.ajax({
                type: "POST",
                url: "{{ route('tindakan_kedisiplinan.get_detail_surat_peringatan') }}",
                data: {
                    id: id
                },
                success: function(res) {
                   var data = res;
                   console.log(data);
                    var tanggal_berlaku_mulai=data.tanggal_mulai.substr(8,2)+'-'+data.tanggal_mulai.substr(5,2)+'-'+data.tanggal_mulai.substr(0,4);
                    var tanggal_berlaku_sampai=data.tanggal_sampai.substr(8,2)+'-'+data.tanggal_sampai.substr(5,2)+'-'+data.tanggal_sampai.substr(0,4);
                    $('#EditpasalKaryawan').val(data.kode_pasal).trigger('change');
                    $('#karyawanBermasalahIDEdit').val(data.enroll_id).trigger('change');
                    $('#edit_enroll_id_karyawan_bermasalah').val(data.enroll_id);
                    $('#edit_employee_name').text(data.employee_name);
                    $('#edit_employee_nik').text(data.nik);
                    $('#edit_employee_sub_dept').text(data.sub_dept_name);
                    $('#edit_employee_department').text(data.department_name);
                    $('#edit_employee_jabatan').text(data.status_jabatan);
                    $('input[name="edit_tindakan_pendisiplinan"][value="' + data.surat_peringatan + '"]').prop('checked', true);
                    $('#edit_alasan_pelanggaran').val(data.alasan_pelanggaran);
                    $('#edit_tanggal_berlaku_mulai').val(tanggal_berlaku_mulai);
                    $('#edit_tanggal_berlaku_sampai').val(tanggal_berlaku_sampai);
                    $('#edit_no_form').val(data.no_form);
                    $('#edit_id_pengajuan').val(id);
                }
            });
        }



        function setToNull() {
                $('#tanggal_berlaku_mulai').val('');
                $('#tanggal_berlaku_sampai').val('');
                $('input[name="tindakan_pendisiplinan"]').prop('checked', false);
                $('#enroll_id_karyawan_bermasalah').val('');
                $('#create_employee_nik').text('');
                $('#create_employee_name').text('');
                $('#create_employee_sub_dept').text('');
                $('#create_employee_department').text('');
                $('#create_employee_jabatan').text('');

                $('#no_form').val('');
                $('#alasan_pelanggaran').val('');

                $('#pasalKaryawan').val('').trigger('change');
                $('#karyawanBermasalahID').val('').trigger('change');
        }


        var perijinanChecked = [];
        var currentPageCheck = 0;

        function renderTindakanName(data) {
            let tindakanName = "";
            switch(data) {
            case 'sp_1':
                tindakanName = "SP 1";
                break;
            case 'sp_2':
                tindakanName = "SP 2";
                break;
            case 'sp_3':
                tindakanName = "SP 3";
                break;
            default:
                tindakanName = data.tindakan_pendisiplinan;
            }
            return tindakanName;
        }
        $(document).ready(function() {
            var start = moment().subtract(29, 'days');
            var end = moment();
            var htmlDateRange = '<span><i class="fa fa-calendar"></i> ' + start.format("D MMM YYYY").toUpperCase() + ' s/d ' + end.format("D MMM YYYY").toUpperCase() + '</span><i class="fa fa-angle-down ml-1"></i>'
            var daterange1 = start.format("YYYY-MM-DD") + " s/d " + end.format("YYYY-MM-DD");
            var dateUpdateKehadiran = end.format("DD-MM-YYYY");

            $('#daterange-btn1').html(htmlDateRange);
            $('#daterange1').val(daterange1);
            $('#daterange2').val(daterange1);

            var tableWaiting = $('#datatable-ajax-crud-waiting').DataTable({
                ajax: {
                    url: '{{ route('tindakan_kedisiplinan.get_surat_peringatan') }}',
                    type: "POST",
                    data: { status_pengajuan: 'pending' },
                    onSuccess: function(data) {
                        console.log("Data loaded successfully", data);
                    },
                },
                processing: true,
                serverSide: true,
                columns: [
                    { data: 'nik'},
                    { data: 'employee_name'},
                    { data: 'sub_dept_name' },
                    { data: 'department_name' },
                    { data: 'surat_peringatan',
                        render: function(data, type, row) {
                            return renderTindakanName(data);
                        }
                     },
                    { data: 'kode_pasal'},
                    { data: 'tanggal_mulai',
                      render: function(data, type, row) {
                            return moment(data).locale('id').format('DD MMMM YYYY');
                            }
                    },
                    { data: 'tanggal_sampai',
                      render: function(data, type, row) {
                            return moment(data).locale('id').format('DD MMMM YYYY');
                            }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: "text-center",
                        render: function (data, type, row) {
                                const uuidNo = encodeURIComponent(row.id);
                                let exportUrl;
                                let btnClass;
                                    return `
                                        <button class="btn btn-sm mr-1 btn-danger" onclick="printPengajuanPDF('${row.id}')" data-id="${row.id}" title="Print">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </button>
                                        <button class="btn btn-sm mr-1 btn-primary" onclick="openModalEditPengajuan('${row.id}')" data-id="${row.id}" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm mr-1 btn-danger" id="btn-remove" data-id_pengajuan="${row.id}" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    `;
                        }
                    }
                ],
            });


            $('body').on('click', '#btn-remove', function (event) {
                var id_pengajuan = $(this).data('id_pengajuan');

                let message = "Anda Yakin Ingin Menghapus Data Ini !!!";
                let type = "warning";
                swal({
                    title: message,
                    type: type,
                    showCancelButton: true,
                    confirmButtonText: 'Saya Yakin',
                    cancelButtonText: 'Tutup'
                },function(isConfirm){
                    if(isConfirm) {
                        $.ajax({
                            type:"POST",
                            url: "{{route('tindakan_kedisiplinan.delete_surat_peringatan')}}",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: {
                                id_pengajuan:id_pengajuan,
                            },
                            dataType: 'json',
                            success: function(res){
                                notif({
                                    msg: "<b>Info:</b> Data berhasil di hapus.",
                                    type: "info"
                                });
                                tableWaiting.ajax.reload();
                            },
                            error: function(res){
                                notif({
                                    msg: "<b>Error:</b> Oops data gagal di hapus.",
                                    type: "error"
                                });
                            }
                        });
                    }
                  }
                );

            });


            $('body').on('click', '#btn-simpan-pengajuan', function (event) {
                var tanggal_berlaku_mulai = $('#tanggal_berlaku_mulai').val();
                var tanggal_berlaku_mulai=tanggal_berlaku_mulai.substr(6, 4)+'-'+tanggal_berlaku_mulai.substr(3,2)+'-'+tanggal_berlaku_mulai.substr(0,2);
                var tanggal_berlaku_sampai = $('#tanggal_berlaku_sampai').val();
                var tanggal_berlaku_sampai=tanggal_berlaku_sampai.substr(6, 4)+'-'+tanggal_berlaku_sampai.substr(3,2)+'-'+tanggal_berlaku_sampai.substr(0,2);
                var tindakan_pendisiplinan = $('input[name="tindakan_pendisiplinan"]:checked').val();
                let selectedKodePasal = $('#pasalKaryawan').val();
                var enroll_id_karyawan_bermasalah = $('#enroll_id_karyawan_bermasalah').val();
                var alasan_pelanggaran = $('#alasan_pelanggaran').val();
                var no_form = $('#no_form').val();


                if(tindakan_pendisiplinan == '') {
                    notif({
                        msg: "<b>Error:</b> Mohon lengkapi jenis surat peringatan.",
                        type: "error"
                    });
                    return;
                }
                if(tanggal_berlaku_mulai == '') {
                    notif({
                        msg: "<b>Error:</b> Mohon lengkapi tanggal mulai berlaku.",
                        type: "error"
                    });
                    return;
                }
                if(tanggal_berlaku_sampai == '') {
                    notif({
                        msg: "<b>Error:</b> Mohon lengkapi tanggal sampai berlaku.",
                        type: "error"
                    });
                    return;
                }
                if(enroll_id_karyawan_bermasalah == '') {
                    notif({
                        msg: "<b>Error:</b> Mohon pilih karyawan terlebih dahulu.",
                        type: "error"
                    });
                    return;
                }
                  if(selectedKodePasal == '') {
                    notif({
                        msg: "<b>Error:</b> Pasal wajib dipilih.",
                        type: "error"
                    });
                    return;
                }

                  if(alasan_pelanggaran == '') {
                      notif({
                          msg: "<b>Error:</b> Alasan pelanggaran wajib diisi.",
                          type: "error"
                      });
                      return;
                  }
                if(no_form == '') {
                    notif({
                        msg: "<b>Error:</b> No form wajib diisi.",
                        type: "error"
                    });
                    return;
                }

                $.ajax({
                    type:"POST",
                    url: "{{route('tindakan_kedisiplinan.create_surat_peringatan')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        enroll_id:enroll_id_karyawan_bermasalah,
                        tindakan_pendisiplinan:tindakan_pendisiplinan,
                        tanggal_berlaku_mulai:tanggal_berlaku_mulai,
                        tanggal_berlaku_sampai:tanggal_berlaku_sampai,
                        selectedKodePasal:selectedKodePasal,
                        alasan_pelanggaran:alasan_pelanggaran,
                        no_form:no_form,
                    },
                    dataType: 'json',
                    success: function(res){
                        notif({
                            msg: "<b>Info:</b> Data berhasil di simpan.",
                            type: "info"
                        });
                        tableWaiting.ajax.reload();
                        $("#ajax-modal-tambah").modal('hide');
                        setToNull();
                        closeModalBuatPengajuan();
                    },
                    error: function(res){
                        notif({
                            msg: "<b>Error:</b> Oops data gagal di simpan.",
                            type: "error"
                        });
                        tableWaiting.ajax.reload();
                    }
                });
            });

            $('body').on('click', '#btn-update-pengajuan', function (event) {
                 var tanggal_berlaku_mulai = $('#edit_tanggal_berlaku_mulai').val();
                var tanggal_berlaku_mulai=tanggal_berlaku_mulai.substr(6, 4)+'-'+tanggal_berlaku_mulai.substr(3,2)+'-'+tanggal_berlaku_mulai.substr(0,2);
                var tanggal_berlaku_sampai = $('#edit_tanggal_berlaku_sampai').val();
                var tanggal_berlaku_sampai=tanggal_berlaku_sampai.substr(6, 4)+'-'+tanggal_berlaku_sampai.substr(3,2)+'-'+tanggal_berlaku_sampai.substr(0,2);
                var tindakan_pendisiplinan = $('input[name="edit_tindakan_pendisiplinan"]:checked').val();
                let selectedKodePasal = $('#EditpasalKaryawan').val();
                var enroll_id_karyawan_bermasalah = $('#edit_enroll_id_karyawan_bermasalah').val();
                var alasan_pelanggaran = $('#edit_alasan_pelanggaran').val();
                var no_form = $('#edit_no_form').val();
                var edit_id_pengajuan = $('#edit_id_pengajuan').val();

                if(tanggal_berlaku_mulai == '') {
                    notif({
                        msg: "<b>Error:</b> Mohon lengkapi tanggal mulai berlaku.",
                        type: "error"
                    });
                    return;
                }
                if(tanggal_berlaku_sampai == '') {
                    notif({
                        msg: "<b>Error:</b> Mohon lengkapi tanggal sampai berlaku.",
                        type: "error"
                    });
                    return;
                }
                if(enroll_id_karyawan_bermasalah == '') {
                    notif({
                        msg: "<b>Error:</b> Mohon pilih karyawan terlebih dahulu.",
                        type: "error"
                    });
                    return;
                }
                if(no_form == '') {
                    notif({
                        msg: "<b>Error:</b> No form wajib diisi.",
                        type: "error"
                    });
                    return;
                }
                if(selectedKodePasal == '') {
                    notif({
                        msg: "<b>Error:</b> Pasal wajib dipilih.",
                        type: "error"
                    });
                    return;
                }

                $.ajax({
                    type:"POST",
                    url: "{{route('tindakan_kedisiplinan.update_surat_peringatan')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        id_pengajuan:edit_id_pengajuan,
                        enroll_id:enroll_id_karyawan_bermasalah,
                        tindakan_pendisiplinan:tindakan_pendisiplinan,
                        tanggal_berlaku_mulai:tanggal_berlaku_mulai,
                        tanggal_berlaku_sampai:tanggal_berlaku_sampai,
                        selectedKodePasal:selectedKodePasal,
                        alasan_pelanggaran:alasan_pelanggaran,
                        no_form:no_form,
                    },
                    dataType: 'json',
                    success: function(res){
                        notif({
                            msg: "<b>Info:</b> Data berhasil di simpan.",
                            type: "info"
                        });
                        tableWaiting.ajax.reload();
                        $("#ajax-modal-tambah").modal('hide');
                        setToNull();
                        closeModalEditPengajuan();
                    },
                    error: function(res){
                        notif({
                            msg: "<b>Error:</b> Oops data gagal di simpan.",
                            type: "error"
                        });
                        tableWaiting.ajax.reload();
                    }
                });
            });



        });
    </script>

    <script>

         $("#diajukanOlehIDModalApprove").select2().on("select2:select", function() {
            var selectedOption = $('#diajukanOlehIDModalApprove').find(':selected');
            var department = selectedOption.data('department_name_pengajuan');
            var department_id = selectedOption.data('department_data_id_pengajuan');
            var subDept = selectedOption.data('sub_dept_name_pengajuan');
            var subDeptID = selectedOption.data('sub_dept_data_id_pengajuan');
            if(department != null){
                document.getElementById('enroll_id_approve').value = selectedOption.val();
                document.getElementById('department_approve').value = department;
                document.getElementById('bagian_approve').value = subDept;
                document.getElementById('department_id_approve').value = department_id;
                document.getElementById('sub_dept_id_approve').value = subDeptID;
            }
        });

         $('body').on('keyup', '#besaran_gaji', function (event) {
            let value = $(this).val().replace(/[^0-9]/g, "");
            $(this).val(formatRupiah(value));
        });

         function formatRupiah(angka) {
            return "Rp " + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }



    </script>

    <script>
        function closeModalBuatPengajuan() {
            $("#ajax-modal-tambah").modal('hide');
            $('#title-modal-edit1').text('Buat Form Surat Peringatan');
            $('#tanggal_pengajuan').val('');
            $('#tanggal_mulai_ijin').val('');
            $('#tanggal_akhir_ijin').val('');
            $("#uuid_master").val(null);
            $("#didelegasikan_enroll_id").val(null);
        }
        function closeModalEditPengajuan() {
            $("#ajax-modal-edit-pengajuan").modal('hide');
            $('#title-modal-edit1').text('Buat Form Surat Peringatan');
            $('#tanggal_pengajuan').val('');
            $('#tanggal_mulai_ijin').val('');
            $('#tanggal_akhir_ijin').val('');
            $("#uuid_master").val(null);
            $("#didelegasikan_enroll_id").val(null);
        }
        function closeModalApprovePengajuan() {
           $("#ajax-modal-edit-pengajuan").modal('hide');
           $("#btn-approve-permintaan").hide();
           $("#btn-reject-permintaan").hide();
           $("#btn-update-permintaan").hide();

        }
        function openModalBuatPengajuan() {
            $("#ajax-modal-tambah").modal('show');
            $('#title-modal-edit1').text('Buat Form Surat Peringatan');
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = dd + '-' + mm + '-' + yyyy;

            $('#tanggal_pengajuan').val(today);
            $('#tanggal_mulai_ijin').val(today);
            $('#tanggal_akhir_ijin').val(today);
            $("#uuid_master").val(null);
            $("#didelegasikan_enroll_id").val(null);
        }
        function searchData() {
            var selectEmployeeID = $('#selectEmployeeID').val();
        }
    </script>

    <script>
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
    </script>

@endsection
