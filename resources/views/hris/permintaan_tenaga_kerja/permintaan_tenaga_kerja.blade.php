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
            <li class="active"><span>Permintaan Tenaga Kerja</span></li>
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
                        <div class="card-title">Permintaan Tenaga Kerja</div>
                    </div>
                        <div class="mt-4 ml-4 mr-5 mb-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div clasl="" style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                                        <div class="mt-5 p-0">
                                            <button class="btn btn-primary w-100" onclick="openModalBuatPengajuan()"  data-toggle="tooltip" title="Cari Data" id="recap_labor_cost_2"><i class="fa fa-plus" aria-hidden="true"></i> Buat Permintaan</button>
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
                                            <li class="text-sm" id="tab-verifikasi">Approved</li>
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
                                                                <th scope="col">Diajukan</th>
                                                                <th scope="col">Kebutuhan Department</th>
                                                                <th scope="col">Kebutuhan Bagian</th>
                                                                <th scope="col">Jumlah Kebutuhan</th>
                                                                <th scope="col">Tanggal Kebutuhan</th>
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
                                                                <th scope="col">Diajukan</th>
                                                                <th scope="col">Kebutuhan Department</th>
                                                                <th scope="col">Kebutuhan Bagian</th>
                                                                <th scope="col">Jumlah Kebutuhan</th>
                                                                <th scope="col">Tanggal Kebutuhan</th>
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
                                                                <th scope="col">Diajukan</th>
                                                                <th scope="col">Kebutuhan Department</th>
                                                                <th scope="col">Kebutuhan Bagian</th>
                                                                <th scope="col">Jumlah Kebutuhan</th>
                                                                <th scope="col">Tanggal Kebutuhan</th>
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
                            <input id="uuid" type="hidden">
                            <input id="enroll_id" type="hidden">
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
                                <div class="col-md-12">
                                      <div class="form-group">
                                        <label class="form-label">Status Permintaan : </label>
                                        <div class="radio-container" style="display: flex; gap: 0.5rem;">
                                            <label class="radio-wrapper">
                                                <input type="radio" name="status_permintaan" value="permintaan_baru" checked>
                                                <span>Permintaan Baru</span>
                                            </label>

                                            <label class="radio-wrapper">
                                                <input type="radio" name="status_permintaan" value="penggantian">
                                                <span>Penggantian</span>
                                            </label>
                                        </div>
                                    </div>
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
                                <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px; padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Rencana Kebutuhan</h5>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">DEPARTMENT : </label>
                                         <select id="selectDepartment" name="selectDepartment" class="form-control">
                                            <option value="">-- PILIH DEPARTMENT --</option>
                                            @foreach ($department as $r_department)
                                                <option value="{{$r_department->department_id}}">{{$r_department->department_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">BAGIAN : </label>
                                        <select id="selectBagian" name="selectBagian" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">TANGGAL Kebutuhan : </label>
                                         <input id="tanggal_kebutuhan" name="tanggal_kebutuhan" type="text" class="form-control fc-datepicker" placeholder="Tanggal Kebutuhan" maxlength="50" size="50">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">JUMLAH : </label>
                                        <input type="number" value="0" class="form-control create-control" id="jumlah_kebutuhan" name="jumlah_kebutuhan">
                                    </div>
                                </div>
                                <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px;  padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Rencana Jabatan</h5>
                                </div>
                                <div class="col-md-12">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="rencana_jabatan" value="manager" checked class="mr-2">
                                            <span>Manager</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="rencana_jabatan" value="chief" class="mr-2">
                                            <span>Chief</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="rencana_jabatan" value="spv" class="mr-2">
                                            <span>SPV</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="rencana_jabatan" value="leader" class="mr-2">
                                            <span>Leader</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="rencana_jabatan" value="staff" class="mr-2">
                                            <span>Staff</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="rencana_jabatan" value="operator" class="mr-2">
                                            <span>Operator</span>
                                        </label>
                                    </div>
                                </div>
                                 <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px; padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Pendidikan Minimal & Jurusan</h5>
                                </div>
                                <div class="col-md-4">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="sd" checked class="mr-2">
                                            <span>Sekolah Dasar</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="smp" class="mr-2">
                                            <span>Sekolah Lanjutan Tingkat Pertama</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="sma" class="mr-2">
                                            <span>Sekolah Menengah Atas</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="diploma_1" class="mr-2">
                                            <span>Diploma 1</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="diploma_2" class="mr-2">
                                            <span>Diploma 2</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="diploma_3" class="mr-2">
                                            <span>Diploma 3</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="strata_1" class="mr-2">
                                            <span>Strata 1</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="strata_2" class="mr-2">
                                            <span>Strata 2</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="strata_3" class="mr-2">
                                            <span>Strata 3</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="dll" class="mr-2">
                                            <span>DLL</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                       <label class="form-label">JURUSAN : </label>
                                       <input type="text" class="form-control create-control" placeholder="Rencana Jurusan" id="rencana_jurusan" name="rencana_jurusan">
                                    </div>
                                </div>
                                 <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px;  padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Pengalaman Kerja</h5>
                                </div>
                                <div class="col-md-12">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pengalaman_kerja" value="ya" checked class="mr-2">
                                            <span>Ya</span>
                                            <input type="number" class="w-75 form-control create-control pl-5 ml-5 mr-3" placeholder="Waktu Pengalaman" id="waktu_pengalaman" name="waktu_pengalaman">
                                            <span>Tahun</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pengalaman_kerja" value="tidak" class="mr-2">
                                            <span>Tidak</span>
                                        </label>
                                    </div>
                                </div>
                                 <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px;  padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Uraian Tugas Secara Umum</h5>
                                </div>
                                <div class="col-md-6">
                                   @for($i = 0; $i < 5; $i++)
                                        <div class="form-group">
                                            <input type="text" class="form-control create-control" placeholder="{{ $i + 1 }}. Uraian Tugas" name="uraian_tugas[]">
                                        </div>
                                    @endfor
                                </div>
                                <div class="col-md-6">
                                     @for($i = 5; $i < 10; $i++)
                                        <div class="form-group">
                                            <input type="text" class="form-control create-control" placeholder="{{ $i + 1 }}. Uraian Tugas" name="uraian_tugas[]">
                                        </div>
                                    @endfor
                                </div>
                                 <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px;  padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Rencana Gaji & Fasilitas</h5>
                                </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Golongan / Besaran Gaji : </label>
                                       <input id="besaran_gaji" name="besaran_gaji" type="text" class="form-control" placeholder="0" maxlength="50" size="50">
                                    </div>
                                </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Fasilitas : </label>
                                       <input type="text" class="form-control create-control" placeholder="Fasilitas" id="fasilitas" name="fasilitas">
                                    </div>
                                </div>
                                 <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px;  padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Jangka Waktu Kontrak</h5>
                                </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                       <input type="text" class="form-control create-control" placeholder="Jangak Waktu" id="jangka_waktu_kontrak" name="jangka_waktu_kontrak">
                                    </div>
                                </div>
                                 <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px;  padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Keterangan Lainnya ( Jumlah, Keterampilan Tambahan, dll )</h5>
                                </div>
                                  <div class="col-md-12">
                                    <div class="form-group">
                                       <input type="text" class="form-control create-control" placeholder="Keterangan Tambahan" id="keterangan_tambahan" name="keterangan_tambahan">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                </div>
                                  <div class="col-md-3">
                                    <button class="btn btn-primary w-100" id="btn-simpan-permintaan" data-toggle="tooltip" title="Simpan Data" style="margin-top: 10px;">
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


    {{-- MODAL APPROVE PENGAJUAN --}}
    <div class="modal fade" id="ajax-modal-approve-pengajuan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 50%;">
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2 font-weight-bold" id="title-modal-approve"></h4>
                             <div class="mt-0 p-0">
                                <button onclick="closeModalApprovePengajuan()" class="btn btn-danger btn-sm w-100" data-toggle="tooltip" title="Tutup">x</button>
                             </div>
                        </div>
                        <div class="modal-body">
                            <input id="enroll_id_approve" type="hidden">
                            <input id="id_modal_permintaan" type="hidden">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Pengajuan : </label>
                                        <input readonly disabled id="tanggal_pengajuan_approve" name="tanggal_pengajuan_approve" type="text" class="form-control fc-datepicker" placeholder="Tanggal Perizinan" maxlength="50" size="50">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-12">
                                      <div class="form-group">
                                        <label class="form-label">Status Permintaan : </label>
                                        <div class="radio-container" style="display: flex; gap: 0.5rem;">
                                            <label class="radio-wrapper">
                                                <input type="radio" name="status_permintaan" value="permintaan_baru" checked>
                                                <span>Permintaan Baru</span>
                                            </label>

                                            <label class="radio-wrapper">
                                                <input type="radio" name="status_permintaan" value="penggantian">
                                                <span>Penggantian</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Diajukan Oleh : </label>
                                        <select id="diajukanOlehIDModalApprove" name="diajukanOlehIDModalApprove" style='width: 100%;' data-placeholder="Pilih karyawan" class="form-control create-control select2 select2-show-search EmployeeID">
                                            <option value="">-- Pilih Karyawan --</option>
                                            @foreach ($selectemployee as $r_empl)
                                                <option
                                                    value="{{$r_empl->enroll_id}}"
                                                    data-department_name_pengajuan="{{$r_empl->department_name}}"
                                                    data-sub_dept_name_pengajuan="{{$r_empl->sub_dept_name}}"
                                                    data-department_data_id_pengajuan="{{$r_empl->department_id}}"
                                                    data-sub_dept_data_id_pengajuan="{{$r_empl->sub_dept_id}}"
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
                                        <input type="text" readonly value="" class="form-control create-control" id="department_approve" name="department_approve">
                                        <input type="hidden" readonly value="" class="form-control create-control" id="department_id_approve" name="department_id_approve">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">BAGIAN : </label>
                                        <input type="text" readonly value="" class="form-control create-control" id="bagian_approve" name="bagian_approve">
                                        <input type="hidden" readonly value="" class="form-control create-control" id="sub_dept_id_approve" name="sub_dept_id_approve">
                                    </div>
                                </div>
                                <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px; padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Rencana Kebutuhan</h5>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">DEPARTMENT : </label>
                                         <select id="selectDepartmentModalApprove" name="selectDepartmentModalApprove" class="form-control">
                                            <option value="">-- PILIH DEPARTMENT --</option>
                                            @foreach ($department as $r_department)
                                                <option value="{{$r_department->department_id}}">{{$r_department->department_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">BAGIAN : </label>
                                        <select id="selectBagianModalApprove" name="selectBagianModalApprove" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">TANGGAL Kebutuhan : </label>
                                         <input id="tanggal_kebutuhan_approve" name="tanggal_kebutuhan_approve" type="text" class="form-control fc-datepicker" placeholder="Tanggal Kebutuhan" maxlength="50" size="50">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">JUMLAH : </label>
                                        <input type="number" value="0" class="form-control create-control" id="jumlah_kebutuhan_approve" name="jumlah_kebutuhan_approve">
                                    </div>
                                </div>
                                <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px;  padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Rencana Jabatan</h5>
                                </div>
                                <div class="col-md-12">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="rencana_jabatan" value="manager" checked class="mr-2">
                                            <span>Manager</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="rencana_jabatan" value="chief" class="mr-2">
                                            <span>Chief</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="rencana_jabatan" value="spv" class="mr-2">
                                            <span>SPV</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="rencana_jabatan" value="leader" class="mr-2">
                                            <span>Leader</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="rencana_jabatan" value="staff" class="mr-2">
                                            <span>Staff</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="rencana_jabatan" value="operator" class="mr-2">
                                            <span>Operator</span>
                                        </label>
                                    </div>
                                </div>
                                 <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px; padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Pendidikan Minimal & Jurusan</h5>
                                </div>
                                <div class="col-md-4">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="sd" checked class="mr-2">
                                            <span>Sekolah Dasar</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="smp" class="mr-2">
                                            <span>Sekolah Lanjutan Tingkat Pertama</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="sma" class="mr-2">
                                            <span>Sekolah Menengah Atas</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="diploma_1" class="mr-2">
                                            <span>Diploma 1</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="diploma_2" class="mr-2">
                                            <span>Diploma 2</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="diploma_3" class="mr-2">
                                            <span>Diploma 3</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="strata_1" class="mr-2">
                                            <span>Strata 1</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="strata_2" class="mr-2">
                                            <span>Strata 2</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="strata_3" class="mr-2">
                                            <span>Strata 3</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pend_minimal" value="dll" class="mr-2">
                                            <span>DLL</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                       <label class="form-label">JURUSAN : </label>
                                       <input type="text" class="form-control create-control" placeholder="Rencana Jurusan" id="rencana_jurusan_approve" name="rencana_jurusan_approve">
                                    </div>
                                </div>
                                 <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px;  padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Pengalaman Kerja</h5>
                                </div>
                                <div class="col-md-12">
                                     <div class="radio-container">
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pengalaman_kerja" value="ya" checked class="mr-2">
                                            <span>Ya</span>
                                            <input type="number" class="w-75 form-control create-control pl-5 ml-5 mr-3" placeholder="Waktu Pengalaman" id="waktu_pengalaman_approve" name="waktu_pengalaman_approve">
                                            <span>Tahun</span>
                                        </label>
                                        <label class="radio-wrapper">
                                            <input type="radio" name="pengalaman_kerja" value="tidak" class="mr-2">
                                            <span>Tidak</span>
                                        </label>
                                    </div>
                                </div>
                                 <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px;  padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Uraian Tugas Secara Umum</h5>
                                </div>
                                <div class="col-md-6">
                                   @for($i = 0; $i < 5; $i++)
                                        <div class="form-group">
                                            <input type="text" class="form-control create-control" placeholder="{{ $i + 1 }}. Uraian Tugas" name="uraian_tugas_approve[]">
                                        </div>
                                    @endfor
                                </div>
                                <div class="col-md-6">
                                     @for($i = 5; $i < 10; $i++)
                                        <div class="form-group">
                                            <input type="text" class="form-control create-control" placeholder="{{ $i + 1 }}. Uraian Tugas" name="uraian_tugas_approve[]">
                                        </div>
                                    @endfor
                                </div>
                                 <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px;  padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Rencana Gaji & Fasilitas</h5>
                                </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Golongan / Besaran Gaji : </label>
                                       <input id="besaran_gaji_approve" name="besaran_gaji_approve" type="text" class="form-control" placeholder="0" maxlength="50" size="50">
                                    </div>
                                </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Fasilitas : </label>
                                       <input type="text" class="form-control create-control" placeholder="Fasilitas" id="fasilitas_approve" name="fasilitas_approve">
                                    </div>
                                </div>
                                 <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px;  padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Jangka Waktu Kontrak</h5>
                                </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                       <input type="text" class="form-control create-control" placeholder="Jangak Waktu" id="jangka_waktu_kontrak_approve" name="jangka_waktu_kontrak_approve">
                                    </div>
                                </div>
                                 <div class="col-md-12" style="border-bottom: 1px solid #ccc; margin-top: 10px; margin-bottom: 10px;  padding-top: 10px; border-top: 1px solid #ccc; background-color: #eef5fc;">
                                    <h5 style="font-weight: bold;">Keterangan Lainnya ( Jumlah, Keterampilan Tambahan, dll )</h5>
                                </div>
                                  <div class="col-md-12">
                                    <div class="form-group">
                                       <input type="text" class="form-control create-control" placeholder="Keterangan Tambahan" id="keterangan_tambahan_approve" name="keterangan_tambahan_approve">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                </div>
                                  <div class="col-md-3">
                                    <button class="btn btn-primary w-100" id="btn-update-permintaan" data-toggle="tooltip" title="Update Data" style="margin-top: 10px; display: none;">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                        Update
                                    </button>
                                    <button class="btn btn-success w-100" id="btn-approve-permintaan" data-toggle="tooltip" title="Simpan Data" style="margin-top: 10px; display: none;">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                        Selesaikan Pengajuan
                                    </button>
                                    <button class="btn btn-danger w-100" id="btn-reject-permintaan" data-toggle="tooltip" title="Tolak Pengajuan" style="margin-top: 10px; display: none;">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                        Tolak/Batalkan Pengajuan
                                    </button>
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
        $("#diajukanOlehID").select2().on("select2:select", function() {
            var selectedOption = $('#diajukanOlehID').find(':selected');
            var department = selectedOption.data('department_name');
            var department_id = selectedOption.data('department_data_id');
            var subDept = selectedOption.data('sub_dept_name');
            var subDeptID = selectedOption.data('sub_dept_data_id');

            if(department != null){
                document.getElementById('enroll_id').value = selectedOption.val();
                document.getElementById('department').value = department;
                document.getElementById('bagian').value = subDept;
                document.getElementById('department_id').value = department_id;
                document.getElementById('sub_dept_id').value = subDeptID;
            }
        });
        $("#didelegasikanID").select2().on("select2:select", function() {
            var selectedOption = $('#didelegasikanID').find(':selected');
            if(selectedOption.val()){
                document.getElementById('didelegasikan_enroll_id').value = selectedOption.val();
            }
        });

        function printPengajuanPDF(id) {
            var url = "{{ route('permintaan_tenaga_kerja.print_pengajuan_tk_pdf', ':id') }}";
            url = url.replace(':id', id);
            window.open(url, '_blank');
        }

        function openModalEditPengajuan(id) {
            $("#ajax-modal-approve-pengajuan").modal('show');
            $("#title-modal-approve").text('Edit Pengajuan Tenaga Kerja');
            $("#btn-update-permintaan").show();
            $.ajax({
                type: "POST",
                url: "{{ route('permintaan_tenaga_kerja.get_detail_permintaan_tk') }}",
                data: {
                    id: id
                },
                success: function(res) {
                   var data = res[0];
                   var tanggal_perizinan=data.tanggal_pengajuan.substr(8,2)+'-'+data.tanggal_pengajuan.substr(5,2)+'-'+data.tanggal_pengajuan.substr(0,4);
                   var tanggal_kebutuhan=data.tanggal_kebutuhan.substr(8,2)+'-'+data.tanggal_kebutuhan.substr(5,2)+'-'+data.tanggal_kebutuhan.substr(0,4);
                    $('#id_modal_permintaan').val(data.id);
                    $('#enroll_id_approve').val(data.diajukan_oleh_id);
                    $('#tanggal_pengajuan_approve').val(tanggal_perizinan);
                    $('input[name="status_permintaan"][value="' + data.status_permintaan + '"]').prop('checked', true);
                    $('#diajukanOlehIDModalApprove').val(data.diajukan_oleh_id).trigger('change');
                    $('#department_approve').val(data.department_name);
                    $('#department_id_approve').val(data.department_id);
                    $('#bagian_approve').val(data.sub_dept_name);
                    $('#sub_dept_id_approve').val(data.sub_dept_id);
                    $('#selectDepartmentModalApprove').val(data.kode_dept_id).trigger('change');

                // panggil AJAX baru untuk isi bagian dengan nilai dari database:
                    loadSubBagian(data.kode_dept_id, data.kode_bagian_id, data.nama_bagian);
                    $('#tanggal_kebutuhan_approve').val(tanggal_kebutuhan);
                    $('#jumlah_kebutuhan_approve').val(data.jumlah_kebutuhan);

                    $('input[name="rencana_jabatan"][value="' + data.rencana_jabatan + '"]').prop('checked', true);
                    $('input[name="pend_minimal"][value="' + data.pend_minimal + '"]').prop('checked', true);

                    $('#rencana_jurusan_approve').val(data.rencana_jurusan);
                    $('input[name="pengalaman_kerja"][value="' + data.pengalaman_kerja + '"]').prop('checked', true);
                    $('#waktu_pengalaman_approve').val(data.waktu_pengalaman);
                    $('#besaran_gaji_approve').val(data.besaran_gaji);
                    $('#fasilitas_approve').val(data.fasilitas);
                    $('#jangka_waktu_kontrak_approve').val(data.jangka_waktu_kontrak);
                    $('#keterangan_tambahan_approve').val(data.keterangan_tambahan);

                    let uraianTugasArray = [];

                    try {
                        uraianTugasArray = JSON.parse(data.uraian_tugas);
                    } catch (e) {
                        console.warn('Gagal parsing uraian_tugas:', e);
                    }

                    console.log('uraianTugasArray:', uraianTugasArray);
                    $('input[name="uraian_tugas_approve[]"]').each(function (i) {
                        $(this).val(uraianTugasArray[i] || '');
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
            $("#ajax-modal-approve-pengajuan").modal('show');
            $("#btn-approve-permintaan").show();
            $("#btn-reject-permintaan").show();
            $("#btn-update-permintaan").hide();
            $("#title-modal-approve").text('Approve Pengajuan Tenaga Kerja');
            $.ajax({
                type: "POST",
                url: "{{ route('permintaan_tenaga_kerja.get_detail_permintaan_tk') }}",
                data: {
                    id: id
                },
                success: function(res) {
                   var data = res[0];
                   console.log(data);
                    $('#id_modal_permintaan').val(data.id);
                    $('#enroll_id_approve').val(data.duajukan_oleh_id);
                    $('#tanggal_pengajuan_approve').val(data.tanggal_pengajuan);
                    $('input[name="status_permintaan"][value="' + data.status_permintaan + '"]').prop('checked', true);
                    $('#diajukanOlehIDModalApprove').val(data.diajukan_oleh_id).trigger('change');
                    $('#department_approve').val(data.department_name);
                    $('#department_id_approve').val(data.department_id);
                    $('#bagian_approve').val(data.sub_dept_name);
                    $('#sub_dept_id_approve').val(data.sub_dept_id);
                    $('#selectDepartmentModalApprove').val(data.kode_dept_id).trigger('change');

                // panggil AJAX baru untuk isi bagian dengan nilai dari database:
                    loadSubBagian(data.kode_dept_id, data.kode_bagian_id, data.nama_bagian);
                    $('#tanggal_kebutuhan_approve').val(data.tanggal_kebutuhan);
                    $('#jumlah_kebutuhan_approve').val(data.jumlah_kebutuhan);

                    $('input[name="rencana_jabatan"][value="' + data.rencana_jabatan + '"]').prop('checked', true);
                    $('input[name="pend_minimal"][value="' + data.pend_minimal + '"]').prop('checked', true);

                    $('#rencana_jurusan_approve').val(data.rencana_jurusan);
                    $('input[name="pengalaman_kerja"][value="' + data.pengalaman_kerja + '"]').prop('checked', true);
                    $('#waktu_pengalaman_approve').val(data.waktu_pengalaman);
                    $('#besaran_gaji_approve').val(data.besaran_gaji);
                    $('#fasilitas_approve').val(data.fasilitas);
                    $('#jangka_waktu_kontrak_approve').val(data.jangka_waktu_kontrak);
                    $('#keterangan_tambahan_approve').val(data.keterangan_tambahan);

                    let uraianTugasArray = [];

                    try {
                        uraianTugasArray = JSON.parse(data.uraian_tugas);
                    } catch (e) {
                        console.warn('Gagal parsing uraian_tugas:', e);
                    }

                    console.log('uraianTugasArray:', uraianTugasArray);
                    $('input[name="uraian_tugas_approve[]"]').each(function (i) {
                        $(this).val(uraianTugasArray[i] || '');
                    });
                }
            });
        }

        function setToNull() {
            $('#tanggal_pengajuan').val(null).prop('disabled', false);  // Format tanggal dan disable
            $('#diajukanOlehID').val(null).trigger('change').prop('disabled', false);  // Pilih karyawan dan disable
            $('#didelegasikanID').val(null).trigger('change').prop('disabled', false);  // Pilih karyawan dan disable
            $('#department').val(null).prop('disabled', false);  // Masukkan nama department dan disable
            $('#department_id').val(null).prop('disabled', false);  // Masukkan ID department dan disable
            $('#bagian').val(null).prop('disabled', false);  // Masukkan nama bagian/sub-department dan disable
            $('#sub_dept_id').val(null).prop('disabled', false);  // Masukkan ID bagian/sub-department dan disable

            $('#uuid').val(null);
            $('#uuid_master').val(null);

            $('#nomor_form_perizinan').val(null);
            $('#enroll_id').val(null);
            $('#nik').val(null);
            $('#employee_name').val(null);

            $("#kode_absen_ijin").val(null).trigger("change");
            $('#nomor_form_perizinan_izin').val(null);
            $('#tanggal_mulai_ijin').val(null);
            $('#tanggal_akhir_ijin').val(null);
            $('#absen_alasan_izin').val(null);
            $('#created_at_izin').text(null);
            $('#updated_at_izin').text(null);
            $("#kode_absen_ijin_iks").val(null).trigger("change");
            $('#absen_alasan_iks').val(null);
            $('#time_mulai_ijin').val(null);
            $('#time_akhir_ijin').val(null);
            $('#total_time_ijin').val(null);
            $('#created_at_iks').text(null);
            $('#updated_at_iks').text(null);
            $('#nomor_form_perizinan_iks').val(null);
            $('#ajax-modal-tambah').modal('hide');
        }


        var perijinanChecked = [];
        var currentPageCheck = 0;
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
                    url: '{{ route('permintaan_tenaga_kerja.ajax_data_permintaan_tk') }}',
                    type: "POST",
                    data: { status_pengajuan: 'waiting_approval' },
                    onSuccess: function(data) {
                        console.log("Data loaded successfully", data);
                    },
                },
                processing: true,
                serverSide: true,
                columns: [
                    { data: 'tanggal_perizinan',
                      render: function(data, type, row) {
                            return moment(data).format('ll');  // Formatkan tanggal ke dmy
                        }
                    },
                    { data: 'diajukan_oleh_id',
                        render: function (data, type, row) {
                            return `<p>${row.employee_name}</p>`;
                        }
                    },
                    { data: 'department_name' },
                    { data: 'sub_dept_name' },
                    { data: 'jumlah_kebutuhan' },
                    { data: 'tanggal_kebutuhan' ,
                         render: function(data, type, row) {
                            return moment(data).format('ll');  // Formatkan tanggal ke dmy
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

            // Table untuk Verifikasi (is_verifikasi == 1)
            var tableVerifikasi = $('#datatable-ajax-crud-verifikasi').DataTable({
                ajax: {
                    url: '{{ route('permintaan_tenaga_kerja.ajax_data_permintaan_tk') }}',
                    type: "POST",
                    data: { status_pengajuan: 'approved' },  // Data untuk tab "Verifikasi"
                },
                processing: true,
                serverSide: true,
                columns: [
                    { data: 'tanggal_perizinan',
                      render: function(data, type, row) {
                            return moment(data).format('ll');  // Formatkan tanggal ke dmy
                        }
                    },
                    { data: 'diajukan_oleh_id',
                        render: function (data, type, row) {
                            return `<p>${row.employee_name}</p>`;
                        }
                    },
                    { data: 'department_name' },
                    { data: 'sub_dept_name' },
                    { data: 'jumlah_kebutuhan' },
                    { data: 'tanggal_kebutuhan' ,
                         render: function(data, type, row) {
                            return moment(data).format('ll');  // Formatkan tanggal ke dmy
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
                                    `;
                        }
                    }
                ]
            });

            // Table untuk Reject (is_verifikasi == 2)
            var tableReject = $('#datatable-ajax-crud-reject').DataTable({
                ajax: {
                    url: '{{ route('permintaan_tenaga_kerja.ajax_data_permintaan_tk') }}',
                    type: "POST",
                    data: { status_pengajuan: 'cancel' },  // Data untuk tab "Verifikasi"
                },
                processing: true,
                serverSide: true,
                columns: [
                    { data: 'tanggal_perizinan',
                      render: function(data, type, row) {
                            return moment(data).format('ll');  // Formatkan tanggal ke dmy
                        }
                    },
                    { data: 'diajukan_oleh_id',
                        render: function (data, type, row) {
                            return `<p>${row.employee_name}</p>`;
                        }
                    },
                    { data: 'department_name' },
                    { data: 'sub_dept_name' },
                    { data: 'jumlah_kebutuhan' },
                    { data: 'tanggal_kebutuhan' ,
                         render: function(data, type, row) {
                            return moment(data).format('ll');  // Formatkan tanggal ke dmy
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
                            url: "{{route('permintaan_tenaga_kerja.delete_permintaan_tk')}}",
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

            $(document).on('click', '.btn-lihat', function () {
                let uuid = $(this).data('id');
                // Lakukan sesuatu, misal tampilkan modal detail
            });

            $(document).on('click', '.btn-edit', function () {
                let uuid = $(this).data('id');
                // Redirect atau tampilkan form edit
            });

            $(document).on('click', '.btn-delete', function () {
                let uuid = $(this).data('id');
                if (confirm("Yakin ingin menghapus?")) {
                    // Kirim AJAX delete ke server
                }
            });

            $('body').on('click', '#btn-approve-permintaan', function (event) {
                let id = $('#id_modal_permintaan').val();
                $.ajax({
                    type:"POST",
                    url: "{{route('permintaan_tenaga_kerja.approve_permintaan_tk')}}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        id:id,
                    },
                    success: function(res){
                        swal("", "Pengajuan berhasil diselesaikan", "success");
                        tableVerifikasi.ajax.reload();
                        tableWaiting.ajax.reload();
                        tableReject.ajax.reload();
                        $("#ajax-modal-approve-pengajuan").modal('hide');
                    },
                    error: function(res){
                        swal("", "Ooops terjadi kesalahan!", "error");
                    }
                });
            })

            $('body').on('click', '#btn-reject-permintaan', function (event) {
                let id = $('#id_modal_permintaan').val();
                $.ajax({
                    type:"POST",
                    url: "{{route('permintaan_tenaga_kerja.reject_permintaan_tk')}}",
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
                        $("#ajax-modal-approve-pengajuan").modal('hide');
                    },
                    error: function(res){
                        swal("", "Ooops terjadi kesalahan!", "error");
                    }
                });
            });

            $('body').on('click', '#btn-simpan-permintaan', function (event) {
                var tanggal_periz = $('#tanggal_pengajuan').val();
                var tanggal_perizinan=tanggal_periz.substr(6, 4)+'-'+tanggal_periz.substr(3,2)+'-'+tanggal_periz.substr(0,2);
                var status_permintaan = $('input[name="status_permintaan"]:checked').val();
                var enroll_id = $('#enroll_id').val();
                var selectDepartment = $('#selectDepartment').val();
                var selectBagian = $('#selectBagian').val();
                var jumlah_kebutuhan = $('#jumlah_kebutuhan').val();
                var rencana_jabatan = $('input[name="rencana_jabatan"]:checked').val();
                var pend_minimal = $('input[name="pend_minimal"]:checked').val();
                var rencana_jurusan = $('#rencana_jurusan').val();
                var pengalaman_kerja = $('#pengalaman_kerja').val();
                var waktu_pengalaman = $('#waktu_pengalaman').val();
                var besaran_gaji = $('#besaran_gaji').val();
                var fasilitas = $('#fasilitas').val();
                var jangka_waktu_kontrak = $('#jangka_waktu_kontrak').val();
                var keterangan_tambahan = $('#keterangan_tambahan').val();

                var tanggal_kebutuhan = $('#tanggal_kebutuhan').val();
                var tanggal_kebutuhan=tanggal_kebutuhan.substr(6, 4)+'-'+tanggal_kebutuhan.substr(3,2)+'-'+tanggal_kebutuhan.substr(0,2);

                var uraianTugas = [];

                $('input[name="uraian_tugas[]"]').each(function () {
                    uraianTugas.push($(this).val());
                });
                var besaran_gaji_bersih = besaran_gaji.replace(/[^\d]/g, '');

                if(!enroll_id){
                    swal("", "Harap isi pengajuan terlebih dahulu!", "info");
                    return;
                }
                if(!selectDepartment){
                    swal("", "Harap isi department rencana kebutuhan terlebih dahulu!", "info");
                    return;
                }
                if(!selectBagian){
                    swal("", "Harap isi bagian rencana kebutuhan terlebih dahulu!", "info");
                    return;
                }
                if(!tanggal_kebutuhan){
                    swal("", "Harap isi tanggal rencana kebutuhan terlebih dahulu!", "info");
                    return;
                }
                if(!jumlah_kebutuhan){
                    swal("", "Harap isi jumlah rencana kebutuhan terlebih dahulu!", "info");
                    return;
                }
                if(pengalaman_kerja == 'ya' && !waktu_pengalaman){
                    swal("", "Harap isi waktu pengalaman kerja terlebih dahulu!", "info");
                    return;
                }

                $.ajax({
                    type:"POST",
                    url: "{{route('permintaan_tenaga_kerja.create_permintaan_tk')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        tanggal_perizinan:tanggal_perizinan,
                        status_permintaan:status_permintaan,
                        diajukanOlehID:enroll_id,
                        selectDepartment:selectDepartment,
                        selectBagian:selectBagian,
                        tanggal_kebutuhan:tanggal_kebutuhan,
                        jumlah_kebutuhan:jumlah_kebutuhan,
                        rencana_jabatan:rencana_jabatan,
                        uraianTugas:uraianTugas,
                        pend_minimal:pend_minimal,
                        rencana_jurusan:rencana_jurusan,
                        pengalaman_kerja:pengalaman_kerja,
                        waktu_pengalaman:waktu_pengalaman,
                        besaran_gaji:besaran_gaji_bersih,
                        fasilitas:fasilitas,
                        jangka_waktu_kontrak:jangka_waktu_kontrak,
                        keterangan_tambahan:keterangan_tambahan
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
                        $('#tanggal_pengajuan').val('');
                        $('input[name="status_permintaan"]').prop('checked', false);
                        $('#enroll_id').val('');
                        $('#diajukanOlehID').val('').trigger('change'); // jika pakai select2
                        $('#selectDepartment').val('').trigger('change'); // jika pakai select2
                        $('#selectBagian').val('').trigger('change');
                        $('#jumlah_kebutuhan').val('');
                        $('input[name="rencana_jabatan"]').prop('checked', false);
                        $('input[name="pend_minimal"]').prop('checked', false);
                        $('#rencana_jurusan').val('');
                        $('#pengalaman_kerja').val('');
                        $('#waktu_pengalaman').val('');
                        $('#besaran_gaji').val('');
                        $('#fasilitas').val('');
                        $('#jangka_waktu_kontrak').val('');
                        $('#keterangan_tambahan').val('');
                        $('#tanggal_kebutuhan').val('');

                        // Kosongkan semua input uraian tugas
                        $('input[name="uraian_tugas[]"]').each(function () {
                            $(this).val('');
                        });
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

            $('body').on('click', '#btn-update-permintaan', function (event) {
                var id = $('#id_modal_permintaan').val();
                var tanggal_periz = $('#tanggal_pengajuan_approve').val();
                var tanggal_perizinan=tanggal_periz.substr(6, 4)+'-'+tanggal_periz.substr(3,2)+'-'+tanggal_periz.substr(0,2);
                var status_permintaan = $('input[name="status_permintaan"]:checked').val();
                var enroll_id = $('#enroll_id_approve').val();
                var selectDepartment = $('#selectDepartmentModalApprove').val();
                var selectBagian = $('#selectBagianModalApprove').val();
                var jumlah_kebutuhan = $('#jumlah_kebutuhan_approve').val();
                var rencana_jabatan = $('input[name="rencana_jabatan"]:checked').val();
                var pend_minimal = $('input[name="pend_minimal"]:checked').val();
                var rencana_jurusan = $('#rencana_jurusan_approve').val();
                var pengalaman_kerja = $('input[name="pengalaman_kerja"]:checked').val();
                var waktu_pengalaman = $('#waktu_pengalaman_approve').val();
                var besaran_gaji = $('#besaran_gaji_approve').val();
                var fasilitas = $('#fasilitas_approve').val();
                var jangka_waktu_kontrak = $('#jangka_waktu_kontrak_approve').val();
                var keterangan_tambahan = $('#keterangan_tambahan_approve').val();

                var tanggal_kebutuhan = $('#tanggal_kebutuhan_approve').val();
                tanggal_kebutuhan=tanggal_kebutuhan.substr(6, 4)+'-'+tanggal_kebutuhan.substr(3,2)+'-'+tanggal_kebutuhan.substr(0,2);

                var uraianTugas = [];
                $('input[name="uraian_tugas_approve[]"]').each(function () {
                    uraianTugas.push($(this).val());
                });
                var besaran_gaji_bersih = besaran_gaji.replace(/[^\d]/g, '');

                if(!enroll_id){
                    swal("", "Harap isi pengajuan terlebih dahulu!", "info");
                    return;
                }
                if(!selectDepartment){
                    swal("", "Harap isi department rencana kebutuhan terlebih dahulu!", "info");
                    return;
                }
                if(!selectBagian){
                    swal("", "Harap isi bagian rencana kebutuhan terlebih dahulu!", "info");
                    return;
                }
                if(!tanggal_kebutuhan){
                    swal("", "Harap isi tanggal rencana kebutuhan terlebih dahulu!", "info");
                    return;
                }
                if(!jumlah_kebutuhan){
                    swal("", "Harap isi jumlah rencana kebutuhan terlebih dahulu!", "info");
                    return;
                }
                if(pengalaman_kerja == 'ya' && !waktu_pengalaman){
                    swal("", "Harap isi waktu pengalaman kerja terlebih dahulu!", "info");
                    return;
                }

                $.ajax({
                    type:"POST",
                    url: "{{route('permintaan_tenaga_kerja.update_permintaan_tk')}}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: {
                        id:id,
                        tanggal_perizinan:tanggal_perizinan,
                        status_permintaan:status_permintaan,
                        diajukanOlehID:enroll_id,
                        selectDepartment:selectDepartment,
                        selectBagian:selectBagian,
                        tanggal_kebutuhan:tanggal_kebutuhan,
                        jumlah_kebutuhan:jumlah_kebutuhan,
                        rencana_jabatan:rencana_jabatan,
                        uraianTugas:uraianTugas,
                        rencana_jurusan:rencana_jurusan,
                        pengalaman_kerja:pengalaman_kerja,
                        pend_minimal:pend_minimal,
                        waktu_pengalaman:waktu_pengalaman,
                        besaran_gaji:besaran_gaji_bersih,
                        fasilitas:fasilitas,
                        jangka_waktu_kontrak:jangka_waktu_kontrak,
                        keterangan_tambahan:keterangan_tambahan
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
                        $("#ajax-modal-approve-pengajuan").modal('hide');
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
         $("#diajukanOlehID").select2().on("select2:select", function() {
            var selectedOption = $('#diajukanOlehID').find(':selected');
            var department = selectedOption.data('department_name');
            var department_id = selectedOption.data('department_data_id');
            var subDept = selectedOption.data('sub_dept_name');
            var subDeptID = selectedOption.data('sub_dept_data_id');
            if(department != null){
                document.getElementById('enroll_id').value = selectedOption.val();
                document.getElementById('department').value = department;
                document.getElementById('bagian').value = subDept;
                document.getElementById('department_id').value = department_id;
                document.getElementById('sub_dept_id').value = subDeptID;
            }
        });
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
            $('#title-modal-edit1').text('Buat Form Permintaan Tenaga Kerja');
            $('#tanggal_pengajuan').val('');
            $('#tanggal_mulai_ijin').val('');
            $('#tanggal_akhir_ijin').val('');
            $("#uuid_master").val(null);
            $("#didelegasikan_enroll_id").val(null);
        }
        function closeModalApprovePengajuan() {
           $("#ajax-modal-approve-pengajuan").modal('hide');
           $("#btn-approve-permintaan").hide();
           $("#btn-reject-permintaan").hide();
           $("#btn-update-permintaan").hide();

        }
        function openModalBuatPengajuan() {
            $("#ajax-modal-tambah").modal('show');
            $('#title-modal-edit1').text('Buat Form Permintaan Tenaga Kerja');
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
