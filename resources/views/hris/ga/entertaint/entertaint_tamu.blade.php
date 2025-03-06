@extends('admin.adminlayouts.adminlayout4')

@section('head')
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
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

#entertaintTable thead th {
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


</style>
@section('mainarea')
<?php ini_set('date.timezone', 'Asia/Jakarta'); ?>
    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('entertaint_tamu.index')}}">Entertaint</a></li>
            <li class="active"><span>Entertaint Tamu</span></li>
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
                        <div class="card-title">Entertaint Tamu</div>
                    </div>
                    <div class="mt-4 ml-4 mr-5 mb-0">
                        <div class=""  aria-labelledby="">
                            <div clasl="card-header m-0 p-0" style="display: flex; justify-content: space-between; align-items: center;">
                                <div class="m-0 p-0">
                                    <button class="btn btn-primary w-100" onclick="open_modal_buat_dokumen()"  data-toggle="tooltip" title="Tambah dokumen" id="recap_labor_cost_2"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Pembelian</button>
                                </div>
                            </div>
                        </div>

                        </div>
                        <div class="m-0 p-0">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="entertaintTable" class="table table-bordered table-sm w-100 table-hover">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>ID</th>
                                                <th>Diajukan Oleh</th>
                                                <th>Department</th>
                                                <th>Bagian</th>
                                                <th>Instansi</th>
                                                <th>Nama Tamu</th>
                                                <th>Jabatan</th>
                                                <th>Jumlah Tamu</th>
                                                <th>Keperluan</th>
                                                {{-- <th>Pendamping</th> --}}
                                                <th>Jumlah</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>

                            </div>
                        </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="ajax-modal-tambah"  role="dialog" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 50%;">
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2 font-weight-bold" id="title-modal-edit1">TAMBAH PEMBELIAN</h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">DIAJUKAN OLEH : </label>
                                        <select id="diajukanOlehID" name="diajukanOlehID" style='width: 100%;' data-placeholder="Pilih karyawan" class="form-control create-control select2 select2-show-search EmployeeID">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">TANGGAL KEDATANGAN TAMU : </label>
                                        <div class="input-group">
                                            <input type="date" class="form-control create-control" id="tanggal_kedatangan_tamu" name="tanggal_kedatangan_tamu"
                                            value="{{ date('Y-m-d') }}" onchange="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">NAMA INSTANSI : </label>
                                        <div class="input-group">
                                            <input type="text" value="" class="form-control create-control" id="nama_instansi" name="nama_instansi">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">NAMA TAMU : </label>
                                        <div class="input-group">
                                            <input type="text" value="" class="form-control create-control" id="nama_tamu" name="nama_tamu">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">JABATAN TAMU : </label>
                                        <div class="input-group">
                                            <input type="text" value="" class="form-control create-control" id="jabatan_tamu" name="jabatan_tamu">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">JML TAMU : </label>
                                        <div class="input-group">
                                            <input type="number" maxlength="2" value="" class="form-control create-control" id="jumlah_tamu" name="jumlah_tamu">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">KEPERLUAN : </label>
                                        <div class="input-group">
                                            <input type="text" value="" class="form-control create-control" id="keperluan" name="keperluan">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <button id="add-form" class="btn btn-primary">Tambah Detail Nominal<i class="fa fa-plus ml-2"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div id="form-container">
                                        <!-- Default input yang tidak bisa dihapus -->
                                        <div class="row form-group-item">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label class="form-label">KETERANGAN :</label>
                                                    <input type="text" class="form-control keterangan" name="keterangan[]">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">JUMLAH :</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control jml_permintaan_uang" name="jml_permintaan_uang[]" value="">
                                                        <button class="btn btn-danger ml-3 remove-form" disabled><i class="fa fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="form-label">TOTAL</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                            <label class="form-label " id="total-nominal">RP.0</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <hr class="m-0 p-0"/>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">PENDAMPING TAMU : </label>
                                        <select id="pendampingTamuID" name="pendampingTamuID[]" multiple data-placeholder="Pilih karyawan" class="form-control select2 select2-show-search EmployeeID" style="width: 100%;" required>
                                            @foreach ($selectemployee as $r_empl)
                                                <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-primary p-1">
                            <div class="btn-list">
                                <button type="button" id="submit-form" class="btn btn-secondary btn-app">Simpan</button>
                                <button type="button" id="btn-close_edit1" class="btn btn-warning btn-app" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Dokumen</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="edit_id" name="id">

                        <div class="form-group">
                            <label>Jenis Dokumen</label>
                            <select class="form-control" id="edit_jenis_dokumen" name="jenis_dokumen">
                                <option value="PERIJINAN">PERIJINAN</option>
                                <option value="LAPORAN">LAPORAN</option>
                                <option value="PROSEDUR">PROSEDUR</option>
                                <option value="FORMULIR">FORMULIR</option>
                                <option value="SURAT">SURAT</option>
                            </select>
                            <small class="error-message text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label>Kode Dokumen</label>
                            <input type="text" class="form-control" id="edit_kode_dokumen" name="kode_dokumen">
                            <small class="error-message text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label>Nama Dokumen</label>
                            <input type="text" class="form-control" id="edit_nama_dokumen" name="nama_dokumen">
                        </div>

                        <div class="form-group">
                            <label>Tanggal Berlaku</label>
                            <input type="date" class="form-control" id="edit_tanggal_berlaku" name="tanggal_berlaku">
                            <small class="error-message text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Kadaluarsa</label>
                            <input type="date" class="form-control" id="edit_tanggal_kadaluarsa" name="tanggal_kadaluarsa">
                            <small class="error-message text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label>Penanggung Jawab</label>
                            <input type="text" class="form-control" id="edit_penanggung_jawab" name="penanggung_jawab">
                            <small class="error-message text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label>Revisi Ke</label>
                            <input type="number" class="form-control" id="edit_revisi_ke" name="revisi_ke">
                            <small class="error-message text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea class="form-control" id="edit_keterangan" name="keterangan"></textarea>
                            <small class="error-message text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label>File Dokumen</label>
                            <div id="edit-file-preview"></div> <!-- Preview file -->
                            <input type="file" id="edit-file-upload" name="dokumen_file" class="form-control">
                            <small class="error-message text-danger"></small>
                        </div>


                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
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

    <style>
    .checkbox-xl .form-check-input {
        scale: 1.5;
    }
    </style>
    <script>
        function updateFields() {
            let inputTgl = document.getElementById('tanggal_kedatangan_tamu');
            let tanggal = new Date(inputTgl.value);

            if (isNaN(tanggal)) return;

            let tahun = tanggal.getFullYear();
            let bulan = ('0' + (tanggal.getMonth() + 1)).slice(-2);
            let hari = tanggal.toLocaleDateString('id-ID', { weekday: 'long' }).toUpperCase();
            let kode = `${tahun}-${bulan}`;

            document.getElementById('tahun').value = tahun;
            document.getElementById('bulan').value = bulan;
            document.getElementById('kode').value = kode;
            document.getElementById('hari').value = hari;
        }

        document.addEventListener("DOMContentLoaded", function () {
            let today = new Date();
            let formattedDate = today.toISOString().split('T')[0];

            let inputTgl = document.getElementById('tanggal_kedatangan_tamu');
            inputTgl.value = formattedDate;

            updateFields();
        });

        document.getElementById('tanggal_kedatangan_tamu').addEventListener('change', updateFields);



        $("#diajukanOlehID").select2().on("select2:select", function() {
            var selectedOption = $('#diajukanOlehID').find(':selected');
            var department = selectedOption.data('department_name');
            var department_id = selectedOption.data('department_data_id');
            var subDept = selectedOption.data('sub_dept_name');
            var subDeptID = selectedOption.data('sub_dept_data_id');
            if(department != null){
                document.getElementById('department').value = department;
                document.getElementById('bagian').value = subDept;
                document.getElementById('department_id').value = department_id;
                document.getElementById('sub_dept_id').value = subDeptID;
            }
        });

    </script>


    <script>
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function parseRupiah(value) {
            return parseInt(value.replace(/[^\d]/g, ""), 10) || 0;
        }
        $(document).on("input", ".jml_permintaan_uang", function () {
            let value = $(this).val().replace(/[^0-9]/g, ""); // Hanya angka
            if (value) {
                $(this).val("Rp " + formatRupiah(value));
            } else {
                $(this).val("");
            }
        });

        $(document).on("blur", ".jml_permintaan_uang", function () {
            let value = $(this).val().replace(/[^0-9]/g, "");
            $(this).val(value ? "Rp " + formatRupiah(value) : "Rp 0");
        });

        $(document).on("focus", ".jml_permintaan_uang", function () {
            let value = $(this).val().replace(/[^0-9]/g, "");
            $(this).val(value ? value : "");
        });
        document.addEventListener("DOMContentLoaded", function () {
            let formContainer = document.getElementById("form-container");
            let addButton = document.getElementById("add-form");
            let totalLabel = document.getElementById("total-label");


            function updateTotal() {
                let total = 0;
                $(".jml_permintaan_uang").each(function () {
                    let value = parseRupiah($(this).val()); // Ambil hanya angka
                    total += value ? parseInt(value) : 0;
                });

                $("#total-nominal").text("Rp " + formatRupiah(total)); // Update total
            }

            addButton.addEventListener("click", function (e) {
                e.preventDefault();
                let newForm = document.createElement("div");
                newForm.classList.add("row", "form-group-item");
                newForm.innerHTML = `
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-label">KETERANGAN :</label>
                            <input type="text" class="form-control keterangan" name="keterangan[]">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">JUMLAH :</label>
                            <div class="input-group">
                                <input type="text" class="form-control jml_permintaan_uang" name="jml_permintaan_uang[]" value="">
                                <button class="btn btn-danger ml-3 remove-form"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                `;
                formContainer.appendChild(newForm);
                updateTotal();
            });


            formContainer.addEventListener("click", function (e) {
                if (e.target.classList.contains("remove-form") || e.target.closest(".remove-form")) {
                    e.preventDefault();
                    let allForms = document.querySelectorAll(".form-group-item");
                    if (allForms.length > 1) {
                        e.target.closest(".form-group-item").remove();
                        updateTotal();
                    }
                }
            });

            formContainer.addEventListener("input", function (e) {
                if (e.target.classList.contains("jml_permintaan_uang")) {
                    updateTotal();
                }
            });

            updateTotal();
        });

        $("#submit-form").click(function (e) {
            e.preventDefault(); // Mencegah form submit default

            let data = {
                enroll_id: $('#diajukanOlehID').val(),
                department_id: $("#department_id").val(),
                sub_dept_id: $("#sub_dept_id").val(),
                tanggal_kedatangan_tamu: $("#tanggal_kedatangan_tamu").val(),
                tamu_instansi: $("#nama_instansi").val(),
                nama_tamu: $("#nama_tamu").val(),
                jabatan_tamu: $("#jabatan_tamu").val(),
                qty_tamu: $("#jumlah_tamu").val(),
                keperluan: $("#keperluan").val(),
                pendamping_tamu: $('#pendampingTamuID').val() || [], // Pastikan array
                keterangan_list: []
            };

            $(".form-group-item").each(function () {
                let keterangan = $(this).find(".keterangan").val();
                let jumlah = $(this).find(".jml_permintaan_uang").val();
                if (keterangan && jumlah) {
                    data.keterangan_list.push({ keterangan, jumlah });
                }
            });

            $.ajax({
                url: '{{ route('entertaint_tamu.store') }}',
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(data),
                success: function (response) {
                    console.log(response);
                    notif({
                        msg: "<b>Success:</b> Data berhasil disimpan.",
                        type: "success"
                    });
                    $('#diajukanOlehID').val(null).trigger('change');
                    $('#department, #department_id, #bagian, #sub_dept_id, #tanggal_kedatangan_tamu, #nama_instansi, #nama_tamu, #jabatan_tamu, #jumlah_tamu, #keperluan').val('');
                    $('#pendampingTamuID').val(null).trigger('change');

                    // Hapus semua baris tambahan dari keterangan_list kecuali satu
                    $("#form-container").find(".form-group-item:not(:first)").remove();
                    $("#form-container .form-group-item:first input").val('');

                    // Reset total nominal
                    $('#total-nominal').text('RP.0');
                    $("#ajax-modal-tambah").modal('hide');
                    $('#entertaintTable').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    console.log(xhr);
                    notif({
                        msg: "<b>Info:</b> Terjadi kesalahan saat mengirim data!",
                        type: "error"
                    });
                },
            });
        });


        function open_modal_buat_dokumen() {
            $("#ajax-modal-tambah").modal('show');
            $('#title-modal-list').text('PENGAJUAN KUPON KARYAWAN');
        }


        $(document).ready(function() {
            $('#entertaintTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('entertaint_tamu.show') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'enroll_id', name: 'enroll_id' },
                    { data: 'department_name', name: 'department_name' },
                    { data: 'bagian_name', name: 'bagian_name' },
                    { data: 'tamu_instansi', name: 'tamu_instansi' },
                    { data: 'nama_tamu', name: 'nama_tamu' },
                    { data: 'jabatan_tamu', name: 'jabatan_tamu' },
                    { data: 'qty_tamu', name: 'qty_tamu' },
                    { data: 'keperluan', name: 'keperluan' },
                    // { data: 'pendamping', name: 'pendamping', orderable: false, searchable: false },
                    {
                        data: 'jumlah',
                        name: 'jumlah',
                        render: function(data, type, row) {
                            return "Rp" + formatRupiah(data);
                        }
                    },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });
        });


        function export_pengajuan_permintaan_kas(entertain_id) {
            var url = 'entertaint_tamu/export_pengajuan_permintaan_kas?entertain_id='+entertain_id;
            window.open(url, '_blank');
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
