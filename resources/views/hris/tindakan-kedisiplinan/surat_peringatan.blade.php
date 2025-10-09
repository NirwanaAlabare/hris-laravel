@extends('admin.adminlayouts.adminlayout-mut-karyawan')

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
            <li class="active"><span>Form Pengajuan Tindak Pendisiplinan</span></li>
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
                        <div class="card-title">Form Pengajuan Tindak Pendisiplinan</div>
                    </div>
                        <div class="mt-4 ml-4 mr-5 mb-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div clasl="" style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                                        <div class="mt-5 p-0">
                                            <button class="btn btn-primary w-100" onclick="openModalBuatPengajuan()"  data-toggle="tooltip" title="Cari Data" id="recap_labor_cost_2"><i class="fa fa-plus" aria-hidden="true"></i> Pengajuan Pendisiplinan</button>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="m-0 p-0">
                            <div class="card-body m-0">
                                <div class="panel panel-primary  px-3 py-2 pt-5">
                                    <div class="tab_wrapper first_tab">
                                        <ul class="tab_list">
                                            <li class="text-sm" id="tab-waiting">Pending</li>
                                            <li class="text-sm" id="tab-verifikasi">Done</li>
                                            <li class="text-sm" id="tab-reject">Cancel</li>
                                        </ul>
                                        <div class="content_wrapper">
                                            <!-- Tab Waiting -->
                                            <div class="tab_content active" id="tab-content-waiting">
                                                <div class="table-responsive">
                                                    <table id="datatable-ajax-crud-waiting" class="table table-sm table-striped table-hover table-bordered w-100">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th scope="col">Tanggal Pengajuan</th>
                                                                <th scope="col">NIK</th>
                                                                <th scope="col">Diajukan Oleh</th>
                                                                <th scope="col">NIK</th>
                                                                <th scope="col">Karyawan Yang Diajukan</th>
                                                                <th scope="col">Bagian</th>
                                                                <th scope="col">Department</th>
                                                                <th scope="col">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- Tab Verifikasi -->
                                            <div class="tab_content" id="tab-content-verifikasi">
                                                <div class="table-responsive">
                                                    <table id="datatable-ajax-crud-verifikasi" class="table table-sm table-striped table-hover table-bordered w-100">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th scope="col">Tanggal Pengajuan</th>
                                                                <th scope="col">NIK</th>
                                                                <th scope="col">Diajukan Oleh</th>
                                                                <th scope="col">NIK</th>
                                                                <th scope="col">Karyawan Yang Diajukan</th>
                                                                <th scope="col">Bagian</th>
                                                                <th scope="col">Department</th>
                                                                <th scope="col">Tindakan</th>
                                                                <th scope="col">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- Tab Reject -->
                                            <div class="tab_content" id="tab-content-reject">
                                                <div class="table-responsive">
                                                    <table id="datatable-ajax-crud-reject" class="table table-sm table-striped table-hover table-bordered w-100">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th scope="col">Tanggal Pengajuan</th>
                                                                <th scope="col">NIK</th>
                                                                <th scope="col">Diajukan Oleh</th>
                                                                <th scope="col">NIK</th>
                                                                <th scope="col">Karyawan Yang Diajukan</th>
                                                                <th scope="col">Bagian</th>
                                                                <th scope="col">Department</th>
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
        </div>
    </div>


    {{-- MODAL TAMBAH --}}
    <div class="modal fade" id="ajax-modal-tambah"  role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 50%;">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Pengajuan : </label>
                                        <input readonly disabled id="tanggal_pengajuan" name="tanggal_pengajuan" type="text" class="form-control fc-datepicker" placeholder="Tanggal Perizinan" maxlength="50" size="50">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Diajukan Oleh : </label>
                                        <select id="diajukanOlehID" name="diajukanOlehID" style='width: 100%;' data-placeholder="Pilih karyawan" class="form-control create-control select2 select2-show-search EmployeeID">
                                            <option value="">-- Pilih Karyawan --</option>
                                            @foreach ($selectemployee as $r_empl)
                                                <option
                                                    value="{{$r_empl->enroll_id}}"
                                                    data-department_name="{{$r_empl->department_name}}"
                                                    data-sub_dept_name="{{$r_empl->sub_dept_name}}"
                                                    data-department_data_id="{{$r_empl->department_id}}"
                                                    data-sub_dept_data_id="{{$r_empl->sub_dept_id}}"
                                                >
                                                    {{$r_empl->select_employee}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">DEPARTMENT : </label>
                                        <input type="text" readonly value="" class="form-control create-control" id="department" name="department">
                                        <input type="hidden" readonly value="" class="form-control create-control" id="department_id" name="department_id">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">BAGIAN : </label>
                                        <input type="text" readonly value="" class="form-control create-control" id="bagian" name="bagian">
                                        <input type="hidden" readonly value="" class="form-control create-control" id="sub_dept_id" name="sub_dept_id">
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px; padding-top: 10px; border-top: 1px solid #ccc;">
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
                                <div class="col-md-10">
                                    <div style="display: block; text-align: start; height: 25px;">
                                         <span>:</span>
                                        <strong><span id="create_employee_name"></span></strong>
                                        <input id="enroll_id_karyawan_bermasalah" type="hidden">
                                    </div>
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
                                <div class="col-md-12" style="margin-top: 10px; padding-top: 5px;">
                                    <h6 style="font-weight: bold;">Dikarenakan telah melakukan pelanggaran/ kesalahan (Uraikan Pelanggaran)</h6>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                       <textarea required type="text" class="form-control create-control" placeholder="(Uraikan Pelanggaran)" id="uraian_pelanggaran" name="uraian_pelanggaran"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12" style="padding-top: 5px;">
                                    <h6 style="font-weight: bold;">Akibat dari pelanggaran tersebut</h6>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                       <textarea required type="text" class="form-control create-control" placeholder="(Akibat dari pelanggaran)" id="sumber_permasalahan" name="sumber_permasalahan"></textarea>
                                    </div>
                                </div>


                                <div class="col-md-12" style="margin-bottom: 5px;">
                                    <h6 style="font-weight: bold;">Yang diakibatkan oleh</h6>
                                </div>
                               <div class="col-md-12">
                                    <div class="form-group">
                                        @php
                                            $factors = ['Manusia', 'Metode', 'Mesin', 'Material', 'Financial', 'Lingkungan'];
                                        @endphp

                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr class="text-center">
                                                    <th style="width: 20%;">FAKTOR</th>
                                                    <th style="width: 5%;">=</th>
                                                    <th style="width: 5%;">✓</th>
                                                    <th>URAIAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($factors as $index => $factor)
                                                    <tr>
                                                        <td>{{ $factor }}</td>
                                                        <td class="text-center">=</td>
                                                        <td class="text-center">
                                                            <div class="form-check d-flex justify-content-center">
                                                                <input class="form-check-input faktor-checkbox" type="checkbox" name="faktor[{{ $index }}][checked]">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="faktor[{{ $index }}][uraian]" placeholder="Uraian...">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
                            <h4 class="modal-title pl-2 font-weight-bold" id="title-modal-edit1-approve"></h4>
                             <div class="mt-0 p-0">
                                <button onclick="closeModalApprovePengajuan()" class="btn btn-danger btn-sm w-100" data-toggle="tooltip" title="Tutup">x</button>
                             </div>
                        </div>
                        <div class="modal-body">
                            <input id="id_pengajuan_edit" type="hidden">
                            <input id="enroll_id_diajukan_oleh_edit" type="hidden">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Pengajuan : </label>
                                        <input readonly disabled id="tanggal_pengajuan_edit" name="tanggal_pengajuan_edit" type="text" class="form-control fc-datepicker" placeholder="Tanggal Perizinan" maxlength="50" size="50">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Diajukan Oleh : </label>
                                        <select id="diajukanOlehIDEdit" name="diajukanOlehIDEdit" style='width: 100%;' data-placeholder="Pilih karyawan" class="form-control create-control select2 select2-show-search EmployeeID">
                                            <option value="">-- Pilih Karyawan --</option>
                                            @foreach ($selectemployee as $r_empl)
                                                <option
                                                    value="{{$r_empl->enroll_id}}"
                                                    data-department_name_pengaju="{{$r_empl->department_name}}"
                                                    data-sub_dept_name_pengaju="{{$r_empl->sub_dept_name}}"
                                                    data-department_data_id_pengaju="{{$r_empl->department_id}}"
                                                    data-sub_dept_data_id_pengaju="{{$r_empl->sub_dept_id}}"
                                                >
                                                    {{$r_empl->select_employee}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">DEPARTMENT : </label>
                                        <input type="text" readonly value="" class="form-control create-control" id="department_edit" name="department_edit">
                                        <input type="hidden" readonly value="" class="form-control create-control" id="department_id_edit" name="department_id_edit">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">BAGIAN : </label>
                                        <input type="text" readonly value="" class="form-control create-control" id="bagian_edit" name="bagian_edit">
                                        <input type="hidden" readonly value="" class="form-control create-control" id="sub_dept_id_edit" name="sub_dept_id_edit">
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px; padding-top: 10px; border-top: 1px solid #ccc;">
                                    <h5 style="font-weight: bold;">Dengan ini mengajukan karyawan :</h5>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Pilih Karyawan : </label>
                                        <select id="karyawanBermasalahIDEdit" name="karyawanBermasalahIDEdit" style='width: 100%;' data-placeholder="Pilih karyawan" class="form-control create-control select2 select2-show-search EmployeeID">
                                            <option value="">-- Pilih Karyawan --</option>
                                            @foreach ($selectemployee as $r_empl)
                                                <option
                                                    value="{{$r_empl->enroll_id}}"
                                                    data-department_name_bermasalah="{{$r_empl->department_name}}"
                                                    data-sub_dept_name_bermasalah="{{$r_empl->sub_dept_name}}"
                                                    data-department_data_id_bermasalah="{{$r_empl->department_id}}"
                                                    data-sub_dept_data_id_bermasalah="{{$r_empl->sub_dept_id}}"
                                                    data-status_jabatan_bermasalah="{{$r_empl->status_jabatan}}"
                                                    data-employee_name_bermasalah="{{$r_empl->employee_name}}"
                                                    data-employee_nik_bermasalah="{{$r_empl->nik}}"
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
                                 <div class="col-md-10">
                                    <div style="display: block; text-align: start; height: 25px;">
                                         <span>:</span>
                                        <strong><span id="edit_employee_name_bermasalah"></span></strong>
                                        <input id="enroll_id_karyawan_bermasalah_edit" type="hidden">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div style="display: block; text-align: start;  height: 25px;">
                                            <span>NIK</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div style="display: block; text-align: start;  height: 25px;">
                                        <span>:</span>
                                        <strong><span id="edit_employee_nik_bermasalah"></span></strong>
                                    </div>
                                </div>
                                <div class="col-md-7">

                                </div>
                                <div class="col-md-2">
                                    <div style="display: block; text-align: start;  height: 25px;">
                                            <span>BAGIAN</span>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div style="display: block; text-align: start; height: 25px;">
                                        <span>:</span>
                                        <strong><span id="edit_employee_sub_dept_bermasalah"></span></strong>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div style="display: block; text-align: start; height: 25px;">
                                            <span>DEPARTMENT</span>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div style="display: block; text-align: start; height: 25px;">
                                        <span>:</span>
                                        <strong><span id="edit_employee_department_bermasalah"></span></strong>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div style="display: block; text-align: start; height: 25px;">
                                            <span>JABATAN</span>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div style="display: block; text-align: start; height: 25px;">
                                        <span>:</span>
                                        <strong><span id="edit_employee_jabatan_bermasalah"></span></strong>
                                    </div>
                                </div>
                                @if ($user === 'fadli' || $user === 'mega@ptnag.com' || $user === 'rudy@ptnag.com' || $user === 'ersa@ptnag.com' || $user === 'indri@nag.nirwanaindonesia.com' || $user === 'pujiprana@nag.nirwanaindonesia.com')
                                 <div id="section-tindakan-pendisiplinan" class="col-md-12" style="margin-top: 10px; margin-bottom: 10px; padding-top: 10px;">
                                    <h5 style="font-weight: bold;">Untuk diberikan tindakan pendisiplinan dalam bentuk :</h5>
                                </div>
                                <div class="col-md-12" id="radio-tindakan-pendisiplinan" >
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="tindakan_pendisiplinan_edit" value="counseling" checked class="mr-2">
                                            <span>Counseling</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="tindakan_pendisiplinan_edit" value="surat_peringatan" class="mr-2">
                                            <span>Surat Peringatan</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="tindakan_pendisiplinan_edit" value="coaching" class="mr-2">
                                            <span>Coaching</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="tindakan_pendisiplinan_edit" value="mutasi_demosi" class="mr-2">
                                            <span>Mutasi/Demosi</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="tindakan_pendisiplinan_edit" value="phk" class="mr-2">
                                            <span>PHK</span>
                                        </label>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-12" style="margin-top: 10px; margin-bottom: 10px; padding-top: 10px;">
                                    <h6 style="font-weight: bold;">Dikarenakan telah melakukan pelanggaran/ kesalahan (Uraikan Pelanggaran)</h6>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                       <textarea type="text" class="form-control create-control" placeholder="(Uraikan Pelanggaran)" id="pelanggaran_edit" name="pelanggaran_edit"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-bottom: 10px; ">
                                    <h6 style="font-weight: bold;">Akibat dari pelanggaran tersebut</h6>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                       <textarea type="text" class="form-control create-control" placeholder="(Akibat dari pelanggaran)" id="sumber_permasalahan_edit" name="sumber_permasalahan_edit"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        @php
                                            $factors = ['Manusia', 'Metode', 'Mesin', 'Material', 'Financial', 'Lingkungan'];
                                        @endphp

                                        <table class="table table-bordered align-middle" id="table-faktor-edit">
                                            <thead class="table-light text-center">
                                                <tr>
                                                    <th style="width: 20%;">FAKTOR</th>
                                                    <th style="width: 5%;">=</th>
                                                    <th style="width: 5%;">✓</th>
                                                    <th>URAIAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($factors as $index => $factor)
                                                    <tr>
                                                        <td>{{ $factor }}</td>
                                                        <td class="text-center">=</td>
                                                        <td class="text-center">
                                                            <div class="form-check d-flex justify-content-center">
                                                                <input type="checkbox" class="form-check-input faktor-checkbox-edit" name="faktor_edit[{{ $index }}][checked]" data-faktor="{{ $factor }}">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control uraian-faktor-edit" name="faktor_edit[{{ $index }}][uraian]" placeholder="Uraian..." data-faktor="{{ $factor }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                </div>
                                <div class="col-md-3">
                                      <button class="btn btn-success w-100" id="btn-approve-pengajuan" data-toggle="tooltip" title="Update Data" style="margin-top: 10px;">
                                          <i class="fa fa-save" aria-hidden="true"></i>
                                          Approve
                                      </button>
                                    <button class="btn btn-primary w-100" id="btn-update-pengajuan" data-toggle="tooltip" title="Update Data" style="margin-top: 10px;">
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

    <style>
        .checkbox-xl .form-check-input {
            scale: 1.5;
        }
    </style>

    <script>
        function validateCheckboxes() {
            const checked = document.querySelectorAll('.faktor-checkbox:checked');
            if (checked.length < 1) {
                swal("", "Minimal 1 faktor harus dicentang.", "info");
                return false;
            }
            return true;
        }
        function validateEditableCheckboxes() {
            const checked = document.querySelectorAll('.faktor-checkbox-edit:checked');
            if (checked.length < 1) {
                swal("", "Minimal 1 faktor harus dicentang.", "info");
                return false;
            }
            return true;
        }

        $('body').on('change', '#selectDepartment', function () {
                var department_id = $('#selectDepartment').val();

                $("#selectBagian").empty();

                $("#selectBagian").append(new Option("-- PILIH BAGIAN --", ""));

                if(department_id){
                    $.ajax({
                        type:"POST",
                        url: "{{route('hris.departmentall.getDepartmentName')}}",
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: {
                            id:department_id,
                        },
                        dataType: 'json',
                        success: function(resA){
                            if(resA){
                                for(i=0;i<resA.length;i++) {
                                    $("#selectBagian").append(new Option(resA[i].sub_dept_name, resA[i].sub_dept_id));
                                }
                            }
                        }
                    });
                }
        });
    </script>

    <script>
         $('.fc-datepicker').datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: 'dd-mm-yy'
        });
    </script>


    <script>

         var user_email = @json($user);

        $("#diajukanOlehIDEdit").select2().on("select2:select", function() {
            var selectedOption = $('#diajukanOlehIDEdit').find(':selected');
            var department = selectedOption.data('department_name_pengaju');
            var department_id = selectedOption.data('department_data_id_pengaju');
            var subDept = selectedOption.data('sub_dept_name_pengaju');
            var subDeptID = selectedOption.data('sub_dept_data_id_pengaju');
            if(department != null){
                $('#enroll_id_diajukan_oleh_edit').val(selectedOption.val());
                $('#department_edit').val(department);
                $('#bagian_edit').val(subDept);
                $('#department_id_edit').val(department_id);
                $('#sub_dept_id_edit').val(subDeptID);
            }
        });


         $("#karyawanBermasalahIDEdit").select2().on("select2:select", function() {
            var selectedOption = $('#karyawanBermasalahIDEdit').find(':selected');
            var department = selectedOption.data('department_name_bermasalah');
            var department_id = selectedOption.data('department_data_id_bermasalah');
            var subDept = selectedOption.data('sub_dept_name_bermasalah');
            var subDeptID = selectedOption.data('sub_dept_data_id_bermasalah');
            var statusJabatan = selectedOption.data('status_jabatan_bermasalah');
            var employee_name = selectedOption.data('employee_name_bermasalah');
            var employee_nik = selectedOption.data('employee_nik_bermasalah');

            if(selectedOption.val() != null){
                $("#enroll_id_karyawan_bermasalah_edit").val(selectedOption.val());
                $("#edit_employee_name_bermasalah").text(employee_name);
                $("#edit_employee_nik_bermasalah").text(employee_nik);
                $("#edit_employee_sub_dept_bermasalah").text(subDept);
                $("#edit_employee_department_bermasalah").text(department);
                $("#edit_employee_jabatan_bermasalah").text(statusJabatan);
                document.getElementById('enroll_id_karyawan_bermasalah').value =  selectedOption.val();
            }
        });


        $("#diajukanOlehID").select2().on("select2:select", function() {
            var selectedOption = $('#diajukanOlehID').find(':selected');
            var department = selectedOption.data('department_name');
            var department_id = selectedOption.data('department_data_id');
            var subDept = selectedOption.data('sub_dept_name');
            var subDeptID = selectedOption.data('sub_dept_data_id');

            if(department != null){
                document.getElementById('enroll_id_diajukan_oleh').value = selectedOption.val();
                document.getElementById('department').value = department;
                document.getElementById('bagian').value = subDept;
                document.getElementById('department_id').value = department_id;
                document.getElementById('sub_dept_id').value = subDeptID;
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
            var url = "{{ route('tindakan_kedisiplinan.print_pengajuan_sp_pdf', ':id') }}";
            url = url.replace(':id', id);
            window.open(url, '_blank');
        }

        function openModalEditPengajuan(id) {
            $("#ajax-modal-edit-pengajuan").modal('show');
            $("#title-modal-edit1-approve").text('Edit Pendisiplinan');
            $("#btn-update-pengajuan").show();
            $("#btn-approve-pengajuan").hide();
            // Kosongkan dulu semua faktor (reset state)
            $('.faktor-checkbox-edit').prop('checked', false);
            $('.uraian-faktor-edit').val('');
            // Reset semua radio button tindakan_pendisiplinan_edit
            $('input[name="tindakan_pendisiplinan_edit"]').prop('checked', false);
            $('#section-tindakan-pendisiplinan').hide();
            $('#radio-tindakan-pendisiplinan').hide();


            $.ajax({
                type: "POST",
                url: "{{ route('tindakan_kedisiplinan.get_detail_tindakan_kedisiplinan') }}",
                data: {
                    id: id
                },
                success: function(res) {
                    var data = res;
                    console.log(data);
                    var tanggal_pengajuan=data.tanggal_pengajuan.substr(8,2)+'-'+data.tanggal_pengajuan.substr(5,2)+'-'+data.tanggal_pengajuan.substr(0,4);
                    $('#enroll_id_diajukan_oleh_edit').val(data.enroll_id_diajukan_oleh);
                    $('#id_pengajuan_edit').val(data.id);
                    $('#tanggal_pengajuan_edit').val(tanggal_pengajuan);
                    $('#diajukanOlehIDEdit').val(data.enroll_id_diajukan_oleh).trigger('change');
                    $('#department_edit').val(data.department_name_pengaju);
                    $('#bagian_edit').val(data.bagian_name_pengaju);
                    $('#sub_dept_id_edit').val(data.sub_dept_id);
                    $('#karyawanBermasalahIDEdit').val(data.enroll_id_karyawan_bermasalah).trigger('change');

                    $("#enroll_id_karyawan_bermasalah_edit").val(data.enroll_id_karyawan_bermasalah);
                    $("#edit_employee_name_bermasalah").text(data.employee_name);
                    $("#edit_employee_nik_bermasalah").text(data.nik);
                    $("#edit_employee_sub_dept_bermasalah").text(data.bagian_name_diajukan);
                    $("#edit_employee_department_bermasalah").text(data.department_name_diajukan);
                    $("#edit_employee_jabatan_bermasalah").text(data.status_jabatan);
                    $('input[name="tindakan_pendisiplinan_edit"][value="' + data.tindakan_pendisiplinan + '"]').prop('checked', true);
                    $('#pelanggaran_edit').val(data.pelanggaran);
                    $('#sumber_permasalahan_edit').val(data.sumber_permasalahan);
                    // Loop data faktor dari backend
                    data.faktor_list.forEach(function(item) {
                        const faktor = item.faktor;
                        const uraian = item.uraian;

                        // Cari checkbox & input berdasarkan data-faktor
                        $('.faktor-checkbox-edit[data-faktor="' + faktor + '"]').prop('checked', true);
                        $('.uraian-faktor-edit[data-faktor="' + faktor + '"]').val(uraian);
                    });

                }
            });
        }

        function loadSubBagian(department_id, selected_sub_dept_id = null, selected_sub_dept_name = null) {
            $("#selectBagianModalApprove").empty().append(new Option("-- PILIH BAGIAN --", ""));

            if (department_id) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('hris.departmentall.getDepartmentName') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: department_id,
                    },
                    dataType: 'json',
                    success: function (resA) {
                        let isSelectedFound = false;

                        if (resA && resA.length) {
                            for (let i = 0; i < resA.length; i++) {
                                let option = new Option(resA[i].sub_dept_name, resA[i].sub_dept_id);
                                if (selected_sub_dept_id && resA[i].sub_dept_id == selected_sub_dept_id) {
                                    option.selected = true;
                                    isSelectedFound = true;
                                }
                                $('#selectBagianModalApprove').append(option);
                            }
                        }

                        if (selected_sub_dept_id && !isSelectedFound) {
                            let option = new Option((selected_sub_dept_name || "Bagian Lama") + " (tidak ditemukan)", selected_sub_dept_id, true, true);
                            $('#selectBagianModalApprove').append(option);
                        }

                        $('#selectBagianModalApprove').trigger('change');
                    }
                });
            }
        }

        function openModalApprovePengajuan(id) {
            $("#ajax-modal-edit-pengajuan").modal('show');
            $("#title-modal-edit1-approve").text('Approve Pengajuan Tenaga Kerja');
            $("#btn-approve-pengajuan").show();
            $("#btn-update-pengajuan").hide();
             $('.faktor-checkbox-edit').prop('checked', false);
            $('.uraian-faktor-edit').val('');
            $('input[name="tindakan_pendisiplinan_edit"]').prop('checked', false);

            $('#section-tindakan-pendisiplinan').show();
            $('#radio-tindakan-pendisiplinan').show();

            $.ajax({
                type: "POST",
                url: "{{ route('tindakan_kedisiplinan.get_detail_tindakan_kedisiplinan') }}",
                data: {
                    id: id
                },
                success: function(res) {
                   var data = res;
                      var tanggal_pengajuan=data.tanggal_pengajuan.substr(8,2)+'-'+data.tanggal_pengajuan.substr(5,2)+'-'+data.tanggal_pengajuan.substr(0,4);
                    $('#enroll_id_diajukan_oleh_edit').val(data.enroll_id_diajukan_oleh);
                    $('#id_pengajuan_edit').val(data.id);
                    $('#tanggal_pengajuan_edit').val(tanggal_pengajuan);
                    $('#diajukanOlehIDEdit').val(data.enroll_id_diajukan_oleh).trigger('change');
                    $('#department_edit').val(data.department_name_pengaju);
                    $('#bagian_edit').val(data.bagian_name_pengaju);
                    $('#sub_dept_id_edit').val(data.sub_dept_id);
                    $('#karyawanBermasalahIDEdit').val(data.enroll_id_karyawan_bermasalah).trigger('change');

                    $("#enroll_id_karyawan_bermasalah_edit").val(data.enroll_id_karyawan_bermasalah);
                    $("#edit_employee_name_bermasalah").text(data.employee_name);
                    $("#edit_employee_nik_bermasalah").text(data.nik);
                    $("#edit_employee_sub_dept_bermasalah").text(data.bagian_name_diajukan);
                    $("#edit_employee_department_bermasalah").text(data.department_name_diajukan);
                    $("#edit_employee_jabatan_bermasalah").text(data.status_jabatan);
                    $('input[name="tindakan_pendisiplinan_edit"][value="' + data.tindakan_pendisiplinan + '"]').prop('checked', true);
                    $('#pelanggaran_edit').val(data.pelanggaran);
                    $('#sumber_permasalahan_edit').val(data.sumber_permasalahan);
                     // Loop data faktor dari backend
                    data.faktor_list.forEach(function(item) {
                        const faktor = item.faktor;
                        const uraian = item.uraian;

                        // Cari checkbox & input berdasarkan data-faktor
                        $('.faktor-checkbox-edit[data-faktor="' + faktor + '"]').prop('checked', true);
                        $('.uraian-faktor-edit[data-faktor="' + faktor + '"]').val(uraian);
                    });
                }
            });
        }

        function setToNull() {
               $('#tanggal_pengajuan').val('');
                $('input[name="tindakan_pendisiplinan"]').prop('checked', false);
                $('#enroll_id_diajukan_oleh').val('');
                $('#enroll_id_karyawan_bermasalah').val('');
                $('#sumber_permasalahan').val('');
                $('#pelanggaran').val('');
                $('#diajukanOlehID').val('').trigger('change');
                $('#karyawanBermasalahID').val('').trigger('change');
                $("#create_employee_name").text('');
                $("#create_employee_nik").text('');
                $("#create_employee_sub_dept").text('');
                $("#create_employee_department").text('');
                $("#create_employee_jabatan").text('');
        }


        var perijinanChecked = [];
        var currentPageCheck = 0;

        function renderTindakanName(data) {
            let tindakanName = "";
            switch(data) {
            case 'surat_peringatan':
                tindakanName = "Surat Peringatan";
                break;
            case 'counseling':
                tindakanName = "Counseling";
                break;
            case 'coaching':
                tindakanName = "Coaching";
                break;
            case 'mutasi_demosi':
                tindakanName = "Mutasi/Demosi";
                break;
            case 'phk':
                tindakanName = "PHK";
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
                    url: '{{ route('tindakan_kedisiplinan.ajax_data_pengajuan_sp') }}',
                    type: "POST",
                    data: { status_pengajuan: 'diajukan' },
                    onSuccess: function(data) {
                        console.log("Data loaded successfully", data);
                    },
                },
                processing: true,
                serverSide: true,
                columns: [
                    { data: 'tanggal_pengajuan',
                      render: function(data, type, row) {
                            return moment(data).format('ll');  // Formatkan tanggal ke dmy
                        }
                    },
                    { data: 'data_diajukan_nik'},
                    { data: 'data_diajukan_name'},
                    { data: 'data_karyawan_bermasalah_nik'},
                    { data: 'data_karyawan_bermasalah_name' },
                    { data: 'data_karyawan_bermasalah_bagian_name' },
                    { data: 'data_karyawan_bermasalah_dept_name' },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: "text-center",
                        render: function (data, type, row) {
                                const uuidNo = encodeURIComponent(row.id);
                                let exportUrl;
                                let btnClass;
                                const allowedEmails = ['mega@ptnag.com', 'fadli', 'rudy@ptnag.com','indri@nag.nirwanaindonesia.com','pujiprana@nag.nirwanaindonesia.com','ersa@ptnag.com','ronald@ptnag.com','bobby']; // daftarkan yang diizinkan
                                const isAllowed = allowedEmails.includes(user_email);
                                    return `
                                         ${isAllowed ? `
                                            <button class="btn btn-sm mr-1 btn-success" onclick="openModalApprovePengajuan('${row.id}')" data-id="${row.id}" title="Approve">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        ` : ''}
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


            // Table untuk Verifikasi (is_verifikasi == 1)
            var tableVerifikasi = $('#datatable-ajax-crud-verifikasi').DataTable({
                ajax: {
                    url: '{{ route('tindakan_kedisiplinan.ajax_data_pengajuan_sp') }}',
                    type: "POST",
                    data: { status_pengajuan: 'done' },  // Data untuk tab "Verifikasi"
                },
                processing: true,
                serverSide: true,
                columns: [
                     { data: 'tanggal_pengajuan',
                      render: function(data, type, row) {
                            return moment(data).format('ll');  // Formatkan tanggal ke dmy
                        }
                    },
                    { data: 'data_diajukan_nik'},
                    { data: 'data_diajukan_name'},
                    { data: 'data_karyawan_bermasalah_nik'},
                    { data: 'data_karyawan_bermasalah_name' },
                    { data: 'data_karyawan_bermasalah_bagian_name' },
                    { data: 'data_karyawan_bermasalah_dept_name' },
                    { data: 'tindakan_pendisiplinan',
                        render: function (data, type, row) {
                            return renderTindakanName(data);
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
                                const allowedEmails = ['mega@ptnag.com', 'fadli', 'rudy@ptnag.com','indri@nag.nirwanaindonesia.com','pujiprana@nag.nirwanaindonesia.com','ersa@ptnag.com','ronald@ptnag.com','bobby']; // daftarkan yang diizinkan
                                const isAllowed = allowedEmails.includes(user_email);
                                    return `
                                        <button class="btn btn-sm mr-1 btn-danger" onclick="printPengajuanPDF('${row.id}')" data-id="${row.id}" title="Print">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </button>
                                        ${isAllowed ? `
                                        <button class="btn btn-sm mr-1 btn-primary" onclick="openModalEditPengajuan('${row.id}')" data-id="${row.id}" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm mr-1 btn-danger" id="btn-remove" data-id_pengajuan="${row.id}" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        ` : ''}
                                    `;
                        }
                    }
                ]
            });

            // Table untuk Reject (is_verifikasi == 2)
            var tableReject = $('#datatable-ajax-crud-reject').DataTable({
                ajax: {
                    url: '{{ route('tindakan_kedisiplinan.ajax_data_pengajuan_sp') }}',
                    type: "POST",
                    data: { status_pengajuan: 'cancel' },  // Data untuk tab "Verifikasi"
                },
                processing: true,
                serverSide: true,
                columns: [
                    { data: 'tanggal_pengajuan',
                      render: function(data, type, row) {
                            return moment(data).format('ll');  // Formatkan tanggal ke dmy
                        }
                    },
                    { data: 'data_diajukan_nik'},
                    { data: 'data_diajukan_name'},
                    { data: 'data_karyawan_bermasalah_nik'},
                    { data: 'data_karyawan_bermasalah_name' },
                    { data: 'data_karyawan_bermasalah_bagian_name' },
                    { data: 'data_karyawan_bermasalah_dept_name' },
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
                                        <button class="btn btn-sm mr-1 btn-danger" id="btn-remove" data-id_pengajuan="${row.id}" title="Hapus">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    `;
                        }
                    }
                ]
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
                            url: "{{route('tindakan_kedisiplinan.delete_pengajuan_kedisiplinan')}}",
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
                                tableVerifikasi.ajax.reload();
                                tableWaiting.ajax.reload();
                                tableReject.ajax.reload();
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


            // Pastikan tab yang aktif saat ini memiliki DataTable yang diinisialisasi
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var target = $(e.target).attr("href"); // Get the target tab
                if (target === "#tab-content-verifikasi") {
                    tableVerifikasi.ajax.reload();
                } else if (target === "#tab-content-waiting") {
                    tableWaiting.ajax.reload();
                } else if (target === "#tab-content-reject") {
                    tableReject.ajax.reload();
                }
            });

            $('body').on('click', '#btn-approve-pengajuan', function (event) {
                let id = $('#id_pengajuan_edit').val();
                var tindakan_pendisiplinan_edit = $('input[name="tindakan_pendisiplinan_edit"]:checked').val();
                $.ajax({
                    type:"POST",
                    url: "{{route('tindakan_kedisiplinan.approve_pengajuan_kedisiplinan')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        id:id,
                        tindakan_pendisiplinan_edit:tindakan_pendisiplinan_edit,
                    },
                    success: function(res){
                        swal("", "Pengajuan berhasil diselesaikan", "success");
                        tableVerifikasi.ajax.reload();
                        tableWaiting.ajax.reload();
                        tableReject.ajax.reload();
                        $("#ajax-modal-edit-pengajuan").modal('hide');
                    },
                    error: function(res){
                        swal("", "Ooops terjadi kesalahan!", "error");
                    }
                });
            })

            $('body').on('click', '#btn-reject-permintaan', function (event) {
                let id = $('#id_pengajuan_edit').val();
                console.log("ID Pengajuan:", id);
                $.ajax({
                    type:"POST",
                    url: "{{route('tindakan_kedisiplinan.reject_pengajuan_kedisiplinan')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        id:id,
                    },
                    success: function(res){
                        swal("", "Pengajuan berhasil ditolak", "success");
                        tableVerifikasi.ajax.reload();
                        tableWaiting.ajax.reload();
                        tableReject.ajax.reload();
                        $("#ajax-modal-edit-pengajuan").modal('hide');
                    },
                    error: function(res){
                        swal("", "Ooops terjadi kesalahan!", "error");
                    }
                });
            });

            $('body').on('click', '#btn-simpan-pengajuan', function (event) {
                var tanggal_periz = $('#tanggal_pengajuan').val();
                var tanggal_perizinan=tanggal_periz.substr(6, 4)+'-'+tanggal_periz.substr(3,2)+'-'+tanggal_periz.substr(0,2);
                var tindakan_pendisiplinan = $('input[name="tindakan_pendisiplinan"]:checked').val();
                var enroll_id_diajukan_oleh = $('#enroll_id_diajukan_oleh').val();
                var enroll_id_karyawan_bermasalah = $('#enroll_id_karyawan_bermasalah').val();
                var uraian_pelanggaran = $('#uraian_pelanggaran').val();
                var sumber_permasalahan = $('#sumber_permasalahan').val();
                // Ambil semua data faktor yang diceklis beserta uraian-nya


                if(!enroll_id_diajukan_oleh){
                    swal("", "Harap isi pengajuan oleh terlebih dahulu!", "info");
                    return;
                }
                if(!enroll_id_karyawan_bermasalah){
                    swal("", "Harap isi karyawan bermasalah terlebih dahulu!", "info");
                    return;
                }
                if(!uraian_pelanggaran){
                    swal("", "Harap isi permasalahan terlebih dahulu!", "info");
                    return;
                }
                if(!sumber_permasalahan){
                    swal("", "Harap isi sumber masalah terlebih dahulu!", "info");
                    return;
                }
                if (!validateCheckboxes()) {
                    return;
                }

                var faktorList = [];

                $('input.form-check-input:checked').each(function () {
                    var $row = $(this).closest('tr');
                    var uraian = $row.find('input[type="text"]').val();
                    var faktor = $row.find('td:first').text().trim();

                    faktorList.push({
                        faktor: faktor,
                        uraian: uraian
                    });
                });



                $.ajax({
                    type:"POST",
                    url: "{{route('tindakan_kedisiplinan.create_form_tindakan_kedisiplinan')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        tanggal_pengajuan:tanggal_perizinan,
                        enroll_id_diajukan_oleh:enroll_id_diajukan_oleh,
                        enroll_id_karyawan_bermasalah:enroll_id_karyawan_bermasalah,
                        pelanggaran:uraian_pelanggaran,
                        sumber_permasalahan:sumber_permasalahan,
                        tindakan_pendisiplinan:tindakan_pendisiplinan,
                        faktorList: faktorList
                    },
                    dataType: 'json',
                    success: function(res){
                        notif({
                            msg: "<b>Info:</b> Data berhasil di simpan.",
                            type: "info"
                        });
                        tableVerifikasi.ajax.reload();
                        tableWaiting.ajax.reload();
                        tableReject.ajax.reload();
                        $("#ajax-modal-tambah").modal('hide');
                        setToNull();
                        setTimeout(function myFunction() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(res){
                        notif({
                            msg: "<b>Error:</b> Oops data gagal di simpan.",
                            type: "error"
                        });
                        tableVerifikasi.ajax.reload();
                        tableWaiting.ajax.reload();
                        tableReject.ajax.reload();
                    }
                });
            });

            $('body').on('click', '#btn-update-pengajuan', function (event) {
                var tanggal_pengajuan = $('#tanggal_pengajuan_edit').val();
                var tanggal_pengajuan=tanggal_pengajuan.substr(6, 4)+'-'+tanggal_pengajuan.substr(3,2)+'-'+tanggal_pengajuan.substr(0,2);

                var id_pengajuan_edit = $('#id_pengajuan_edit').val();
                var enroll_id_diajukan_oleh_edit = $('#enroll_id_diajukan_oleh_edit').val();
                var enroll_id_karyawan_bermasalah_edit = $('#enroll_id_karyawan_bermasalah_edit').val();
                var tindakan_pendisiplinan_edit = $('input[name="tindakan_pendisiplinan_edit"]:checked').val();
                var pelanggaran_edit = $('#pelanggaran_edit').val();
                var sumber_permasalahan_edit = $('#sumber_permasalahan_edit').val();


                if(!enroll_id_diajukan_oleh_edit){
                    swal("", "Harap isi pengajuan oleh terlebih dahulu!", "info");
                    return;
                }
                if(!enroll_id_karyawan_bermasalah_edit){
                    swal("", "Harap isi karyawan bermasalah terlebih dahulu!", "info");
                    return;
                }
                if(!tindakan_pendisiplinan_edit && user_email === 'fadli' && user_email === 'mega@ptnag.com' && user_email === 'rudy@ptnag.com' && user_email === 'ersa@ptnag.com' && user_email === 'indri@nag.nirwanaindonesia.com' && user_email === 'pujiprana@nag.nirwanaindonesia.com'){
                    swal("", "Harap isi permasalahan terlebih dahulu!", "info");
                    return;
                }
                if(!pelanggaran_edit){
                    swal("", "Harap isi sumber masalah terlebih dahulu!", "info");
                    return;
                }
                if(!sumber_permasalahan_edit){
                    swal("", "Harap isi tindakan kedisiplinan terlebih dahulu!", "info");
                    return;
                }

                if (!validateEditableCheckboxes()) {
                    return;
                }
                var faktorListEdit = [];

                $('input.faktor-checkbox-edit:checked').each(function () {
                    var $row = $(this).closest('tr');
                    var uraian = $row.find('input.uraian-faktor-edit').val();
                    var faktor = $(this).data('faktor');

                    faktorListEdit.push({
                        faktor: faktor,
                        uraian: uraian
                    });
                });

                $.ajax({
                    type:"POST",
                    url: "{{route('tindakan_kedisiplinan.update_form_tindakan_kedisiplinan')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        id:id_pengajuan_edit,
                        tanggal_pengajuan:tanggal_pengajuan,
                        enroll_id_diajukan_oleh:enroll_id_diajukan_oleh_edit,
                        enroll_id_karyawan_bermasalah:enroll_id_karyawan_bermasalah_edit,
                        pelanggaran:pelanggaran_edit,
                        sumber_permasalahan:sumber_permasalahan_edit,
                        tindakan_pendisiplinan:tindakan_pendisiplinan_edit,
                        faktorList: faktorListEdit,
                    },
                    dataType: 'json',
                    success: function(res){
                        notif({
                            msg: "<b>Info:</b> Data berhasil di simpan.",
                            type: "info"
                        });
                        tableVerifikasi.ajax.reload();
                        tableWaiting.ajax.reload();
                        tableReject.ajax.reload();
                        $("#ajax-modal-edit-pengajuan").modal('hide');
                        $('#enroll_id_diajukan_oleh_edit').val(null);
                        $('#id_pengajuan_edit').val(null);
                        $('#tanggal_pengajuan_edit').val(null);
                        $('#diajukanOlehIDEdit').val(null).trigger('change');
                        $('#department_edit').val(null);
                        $('#bagian_edit').val(null);
                        $('#sub_dept_id_edit').val(null);
                        $('#karyawanBermasalahIDEdit').val(null).trigger('change');

                        $("#enroll_id_karyawan_bermasalah_edit").val(null);
                        $("#edit_employee_name_bermasalah").text(null);
                        $("#edit_employee_nik_bermasalah").text(null);
                        $("#edit_employee_sub_dept_bermasalah").text(null);
                        $("#edit_employee_department_bermasalah").text(null);
                        $("#edit_employee_jabatan_bermasalah").text(null);
                        $('input[name="tindakan_pendisiplinan_edit"][value="' + null + '"]').prop('checked', true);
                        $('#pelanggaran_edit').val(null);
                        $('#sumber_permasalahan_edit').val(null);
                        setTimeout(function myFunction() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(res){
                        notif({
                            msg: "<b>Error:</b> Oops data gagal di simpan.",
                            type: "error"
                        });
                        tableVerifikasi.ajax.reload();
                        tableWaiting.ajax.reload();
                        tableReject.ajax.reload();
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
            $('#title-modal-edit1').text('Buat Form Pengajuan Pendisiplinan');
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
            $('#title-modal-edit1').text('Buat Form Pengajuan Pendisiplinan');
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
