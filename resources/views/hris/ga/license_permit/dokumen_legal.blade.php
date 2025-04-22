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

#datatable thead th {
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

</style>
@section('mainarea')
<?php ini_set('date.timezone', 'Asia/Jakarta'); ?>
    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('dokumen_legal.index')}}">License & Permit</a></li>
            <li class="active"><span>Dokumen Legal</span></li>
            <input type="hidden" value="{{$user}}" id="username_who_access">
        </ol>
        <div class="ml-auto">
            <div class="input-group">
                <a href="#" id="btn-refresh-data" onclick="undo()" class="btn btn-icon btn-secondary p-0 m-0" data-toggle="tooltip"
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
                        <div class="card-title">Dokumen Legal</div>
                    </div>
                    <div class="mt-4 ml-4 mr-5 mb-0">
                        <div class=""  aria-labelledby="">
                            <div clasl="card-header m-0 p-0" style="display: flex; justify-content: space-between; align-items: center;">
                                <div class="m-0 p-0">
                                    <button class="btn btn-primary w-100" onclick="open_modal_buat_dokumen()"  data-toggle="tooltip" title="Tambah dokumen" id="recap_labor_cost_2"><i class="fa fa-plus" aria-hidden="true"></i> Tambah Dokumen</button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">JENIS DOKUMEN : </label>
                                    <select class='form-control filter-control' style='width: 100%;' name='jenis_dokumen_filter' id='jenis_dokumen_filter' required>
                                        <option value="">PILIH JENIS DOKUMEN</option>
                                        <option value="PERIJINAN">PERIJINAN</option>
                                        <option value="LAPORAN">LAPORAN</option>
                                        <option value="PROSEDUR">PROSEDUR</option>
                                        <option value="FORMULIR">FORMULIR</option>
                                        <option value="SURAT">SURAT</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="m-0 p-0">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-bordered table-sm w-100 table-hover">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>ID</th>
                                                <th>Jenis Dokumen</th>
                                                <th>Kode Dokumen</th>
                                                <th>Nama Dokumen</th>
                                                <th>Tgl Berlaku</th>
                                                <th>Tgl Kadaluarsa</th>
                                                <th>Penanggung Jawab</th>
                                                <th>Email</th>
                                                <th>Revisi</th>
                                                <th>Keterangan</th>
                                                <th>Aksi</th>
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
                            <h4 class="modal-title pl-2 font-weight-bold" id="title-modal-edit1">TAMBAH DOKUMEN</h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">JENIS DOKUMEN : </label>
                                        <select class='form-control create-control' style='width: 100%;' name='jenis_dokumen' id='jenis_dokumen' required>
                                            <option value="">PILIH JENIS DOKUMEN</option>
                                            <option value="PERIJINAN">PERIJINAN</option>
                                            <option value="LAPORAN">LAPORAN</option>
                                            <option value="PROSEDUR">PROSEDUR</option>
                                            <option value="FORMULIR">FORMULIR</option>
                                            <option value="SURAT">SURAT</option>
                                        </select>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">KODE DOKUMEN : </label>
                                        <div class="input-group">
                                            <input  class="form-control create-control" id="kode_dokumen" name="kode_dokumen" type="text">
                                        </div>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">NAMA DOKUMEN : </label>
                                        <div class="input-group">
                                            <input  class="form-control create-control" id="nama_dokumen" name="nama_dokumen" type="text">
                                        </div>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">TGL BERLAKU : </label>
                                        <div class="input-group">
                                            {{-- <input  class="form-control create-control" id="tanggal_berlaku" name="tanggal_berlaku" type="date"> --}}
                                            <input type="text" id="tanggal_berlaku" name="tanggal_berlaku" class="form-control create-control fc-datepicker">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">TGL KADALUARSA : </label>
                                        <div class="input-group">
                                            <input  class="form-control create-control fc-datepicker" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" type="text">
                                        </div>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">PENANGGUNG JAWAB : </label>
                                        <div class="input-group">
                                            <input  class="form-control create-control" id="penanggung_jawab" name="penanggung_jawab" type="text">
                                        </div>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">PENANGGUNG JAWAB & EMAIL : </label>
                                        <div class="input-group">
                                            <input  class="form-control create-control" id="penanggung_jawab_email" name="penanggung_jawab_email" type="text">
                                        </div>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">REVISI KE : </label>
                                        <div class="input-group">
                                            <input default-value="1" value="1" class="form-control create-control" id="revisi_ke" name="revisi_ke" type="number" maxlength="1">
                                        </div>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">KETERANGAN : </label>
                                        <div class="input-group">
                                            <textarea  class="form-control create-control" id="keterangan" name="keterangan" type="text"></textarea>
                                        </div>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">DOKUMEN FILE : </label>
                                        <div class="wrapper">
                                              <div class="upload-container">
                                                <div class="border-container" id="drop-zone">
                                                    <div class="icons fa-4x">
                                                        <i class="fa fa-file-image-o icon-style shrink-3 down-2 left-6 rotate--45"></i>
                                                        <i class="fa fa-file-excel-o icon-style shrink-2 up-4"></i>
                                                        <i class="fa fa-file-pdf-o icon-style shrink-3 down-2 right-6 rotate-45"></i>
                                                    </div>

                                                  <p>Drag and drop files here, or
                                                    <a href="#" id="file-browser">browse</a> your computer.</p>
                                                </div>
                                                <input type="file" id="file-upload" multiple style="display: none;">
                                              </div>
                                              <div id="file-list"></div>
                                        </div>
                                        <small class="error-message text-danger"></small>
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
                            <input type="text" class="form-control fc-datepicker" id="edit_tanggal_berlaku" name="tanggal_berlaku">
                            <small class="error-message text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Kadaluarsa</label>
                            <input type="text" class="form-control fc-datepicker" id="edit_tanggal_kadaluarsa" name="tanggal_kadaluarsa">
                            <small class="error-message text-danger"></small>
                        </div>

                        <div class="form-group">
                            <label>Penanggung Jawab</label>
                            <input type="text" class="form-control" id="edit_penanggung_jawab" name="penanggung_jawab">
                            <small class="error-message text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Email Penanggung Jawab</label>
                            <input type="text" class="form-control" id="edit_penanggung_jawab_email" name="penanggung_jawab_email">
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

       <!-- Datepicker js -->
       <script src="{{URL::asset('assets/plugins/spectrum-date-picker/spectrum.js')}}"></script>
       <script src="{{URL::asset('assets/plugins/spectrum-date-picker/jquery-ui.js')}}"></script>
       <script src="{{URL::asset('assets/plugins/input-mask/jquery.maskedinput.js')}}"></script>

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
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        $('.select2').select2();

    </script>

    <script>

        $('body').on('change', '#jenis_dokumen_filter', function (event) {
            $('#datatable').DataTable().ajax.reload();
        });

        $(document).ready(function () {
            $("#submit-form").click(function (e) {
                e.preventDefault();
                $(".error-message").text(""); // Bersihkan pesan error sebelumnya

                let isValid = true;
                let formData = new FormData();

                // Loop melalui semua input dan validasi
                $(".create-control").each(function () {
                    let input = $(this);
                    let value = input.val().trim();

                    let fieldName = input.attr("name");
                    if(fieldName === "tanggal_berlaku" || fieldName === "tanggal_kadaluarsa") {
                        value = value.substr(6, 4)+'-'+value.substr(3,2)+'-'+value.substr(0,2);
                    }
                    if (value === "" && fieldName !== "keterangan") {
                        input.siblings(".error-message").text("Field ini wajib diisi!");
                        isValid = false;
                    } else {
                        if (input.hasClass("datepicker") && value.match(/^\d{2}-\d{2}-\d{4}$/)) {
                            let parts = value.split("-"); // Pisahkan berdasarkan "-"
                            value = `${parts[2]}-${parts[1]}-${parts[0]}`; // Ubah ke yyyy-MM-dd
                        }
                        formData.append(fieldName, value);
                    }
                });

                // Validasi file upload
                let file = $("#file-upload")[0].files[0]; // Ambil 1 file saja
                console.log(file);
                if (!file) {
                    $("#file-upload").siblings(".error-message").text("Harap unggah file!");
                    isValid = false;
                } else {
                    formData.append("dokumen_file", file); // ✅ Tidak pakai array
                }

                if (isValid) {
                    $.ajax({
                        url: '{{ route('dokumen_legal.store') }}',
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            console.log(response);
                            notif({
                                msg: "<b>Success:</b> Data berhasil di simpan.",
                                type: "success"
                            });
                            setTimeout(function myFunction() {
                                undo();
                            }, 2000);

                        },
                        error: function (xhr) {
                            let errors = xhr.responseJSON.errors;
                            console.log(xhr);
                            if (errors) {
                                $.each(errors, function (key, message) {
                                    $(`[name="${key}"]`).siblings(".error-message").text(message);
                                    notif({
                                        msg: "<b>Info:</b>" + message,
                                        type: "error"
                                    });
                                });
                            } else {
                                notif({
                                    msg: "<b>Info:</b> Terjadi kesalahan saat mengirim data!",
                                    type: "error"
                                });
                            }
                        },
                    });
                }
            });
            $("#file-browser").on("click", function (e) {
                e.preventDefault(); // Mencegah navigasi ke #
                $("#file-upload").click(); // Memicu input file
            });
            // Drag & Drop file upload
            let dropZone = $("#drop-zone");

            dropZone.on("dragover", function (e) {
                e.preventDefault();
                dropZone.addClass("drag-over");
            });

            dropZone.on("dragleave", function (e) {
                e.preventDefault();
                dropZone.removeClass("drag-over");
            });

            dropZone.on("drop", function (e) {
                e.preventDefault();
                dropZone.removeClass("drag-over");

                let files = e.originalEvent.dataTransfer.files;
                handleFiles(files);
            });

            $("#file-upload").change(function (e) {
                handleFiles(e.target.files);
            });

            function handleFiles(files) {
                $("#file-list").empty();

                for (let i = 0; i < files.length; i++) {
                    let file = files[i];
                    let fileType = file.type.startsWith("image") ? `<img src="${URL.createObjectURL(file)}" width="50">` : `<i class="fa fa-file"></i>`;
                    let fileItem = `
                        <div class="file-item">
                            ${fileType} <span>${file.name}</span>
                            <button class="remove-btn" data-index="${i}">Hapus</button>
                        </div>
                    `;
                    $("#file-list").append(fileItem);
                }
            }

            $("#file-list").on("click", ".remove-btn", function () {
                $(this).closest(".file-item").remove();
                $("#file-upload").val("");
            });
        });


        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('dokumen_legal.get_dokumen_legal') }}',
                    data: function(d) {
                        d.jenis_dokumen = document.getElementById("jenis_dokumen_filter").value;
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'jenis_dokumen', name: 'jenis_dokumen' },
                    { data: 'kode_dokumen', name: 'kode_dokumen' },
                    { data: 'nama_dokumen', name: 'nama_dokumen' },
                    { data: 'tanggal_berlaku', name: 'tanggal_berlaku' },
                    { data: 'tanggal_kadaluarsa', name: 'tanggal_kadaluarsa' },
                    { data: 'penanggung_jawab', name: 'penanggung_jawab' },
                    { data: 'penanggung_jawab_email', name: 'penanggung_jawab_email' },
                    { data: 'revisi_ke', name: 'revisi_ke' },
                    { data: 'keterangan', name: 'keterangan' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            // Hapus Data
            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');
                if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    $.ajax({
                        url: '{{ route('dokumen_legal.delete_dokumen_legal', '') }}/' + id,
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(response) {
                            alert(response.message);
                            $('#datatable').DataTable().ajax.reload();
                        }
                    });
                }
            });
        });

        $(document).on("click", ".edit-btn", function () {
            let id = $(this).data("id");

            $.ajax({
                url: '{{ route('dokumen_legal.get_edit_dokumen_legal', '') }}/' + id, // Route untuk mendapatkan data
                type: "GET",
                success: function (response) {
                    if (response.success) {
                        let data = response.data;
                        // Set nilai form di modal edit
                        $("#edit_id").val(data.id);
                        $("#edit_jenis_dokumen").val(data.jenis_dokumen);
                        $("#edit_kode_dokumen").val(data.kode_dokumen);
                        $("#edit_nama_dokumen").val(data.nama_dokumen);
                        $("#edit_tanggal_berlaku").val(data.tanggal_berlaku.substr(8,2)+'-'+data.tanggal_berlaku.substr(5,2)+'-'+data.tanggal_berlaku.substr(0,4));
                        $("#edit_tanggal_kadaluarsa").val(data.tanggal_kadaluarsa.substr(8,2)+'-'+data.tanggal_kadaluarsa.substr(5,2)+'-'+data.tanggal_kadaluarsa.substr(0,4));
                        $("#edit_penanggung_jawab").val(data.penanggung_jawab);
                        $("#edit_penanggung_jawab_email").val(data.penanggung_jawab_email);
                        $("#edit_revisi_ke").val(data.revisi_ke);
                        $("#edit_keterangan").val(data.keterangan);
                          let fileUrl = data.dokumen_url;
                        let fileName = fileUrl.split("/").pop(); // Ambil nama file

                        let fileType = fileUrl.endsWith(".png") || fileUrl.endsWith(".jpg") || fileUrl.endsWith(".jpeg")
                            ? `<img src="${fileUrl}" width="50">`
                            : `<i class="fa fa-file"></i>`;

                        let fileItem = `
                            <div class="file-item">
                                ${fileType} <span>${fileName}</span>
                                <button class="remove-btn btn btn-danger btn-sm">Hapus</button>
                            </div>
                        `;

                        $("#edit-file-preview").html(fileItem);
                        selectedFile = fileUrl;

                                $("#editModal").modal("show");

                            }
                        },
                        error: function () {
                            notif({
                                        msg: "<b>Info:</b> Terjadi kesalahan saat mengambil data!",
                                        type: "error"
                                    });
                        },
            });
        });

        $(document).on("click", ".download-btn", function () {
            let id = $(this).data("id");
            let url = '{{ route('dokumen_legal.download_watermark', '') }}/' + id;

            // Buka URL di tab baru untuk memulai download
            window.open(url, '_blank');
        });

        function formatTanggal(dateString) {
            let parts = dateString.split("-"); // Pisahkan berdasarkan "-"
            return `${parts[2]}-${parts[1]}-${parts[0]}`; // Susun kembali sebagai dd-MM-yyyy
        }

        $("#remove-file").click(function () {
            $("#file-preview").hide(); // Sembunyikan preview file
            $("#edit_dokumen_file").prop("disabled", false); // Aktifkan input upload
        });


        $("#edit-file-upload").on("change", function () {
                let file = this.files[0];
                let preview = $("#edit-file-preview");

                if (file) {
                    let fileType = file.type.startsWith("image")
                        ? `<img src="${URL.createObjectURL(file)}" width="100">`
                        : `<span>${file.name}</span>`;

                    preview.html(`
                        <div class="file-item">
                            ${fileType}
                            <button type="button" class="btn btn-danger btn-sm remove-btn" id="remove-file-btn">Hapus</button>
                        </div>
                    `);
                }
            });

        $(document).on("click", "#remove-file-btn", function () {
            $("#edit-file-preview").html("<p class='text-muted'>Tidak ada file yang dipilih</p>");
            $("#edit-file-upload").val(""); // Hapus file yang dipilih dari input
        });


        $("#editForm").submit(function (e) {
            e.preventDefault();

            let formData = new FormData(this); // Gunakan FormData untuk menangani file upload
            let isValid = true;

            // Ambil nilai input
            let jenisDokumen = $("#edit_jenis_dokumen").val().trim();
            let kodeDokumen = $("#edit_kode_dokumen").val().trim();
            let namaDokumen = $("#edit_nama_dokumen").val().trim();
            let penanggungJawab = $("#edit_penanggung_jawab").val().trim();
            let penanggungJawabEmail = $("#edit_penanggung_jawab_email").val().trim();
            let revisiKe = $("#edit_revisi_ke").val().trim();
            let dokumenFile = $("#edit-file-upload")[0].files[0]; // Ambil file jika ada

            let rawTanggalBerlaku = $("#edit_tanggal_berlaku").val().trim();
            let rawTanggalKadaluarsa = $("#edit_tanggal_kadaluarsa").val().trim();

            let tanggalBerlaku = rawTanggalBerlaku ? rawTanggalBerlaku.substr(6, 4) + '-' + rawTanggalBerlaku.substr(3, 2) + '-' + rawTanggalBerlaku.substr(0, 2) : "";
            let tanggalKadaluarsa = rawTanggalKadaluarsa ? rawTanggalKadaluarsa.substr(6, 4) + '-' + rawTanggalKadaluarsa.substr(3, 2) + '-' + rawTanggalKadaluarsa.substr(0, 2) : "";

            // Setelah validasi dan sebelum AJAX:
            formData.set("tanggal_berlaku", tanggalBerlaku);
            formData.set("tanggal_kadaluarsa", tanggalKadaluarsa);

            // Reset error messages
            $(".error-message").text("");

            // **Validasi Wajib Isi**
            if (jenisDokumen === "") {
                $("#edit_jenis_dokumen").siblings(".error-message").text("Jenis dokumen wajib diisi!");
                isValid = false;
            }
            if (kodeDokumen === "") {
                $("#edit_kode_dokumen").siblings(".error-message").text("Kode dokumen wajib diisi!");
                isValid = false;
            }
            if (namaDokumen === "") {
                $("#edit_nama_dokumen").siblings(".error-message").text("Nama dokumen wajib diisi!");
                isValid = false;
            }
            if (tanggalBerlaku === "") {
                $("#edit_tanggal_berlaku").siblings(".error-message").text("Tanggal berlaku wajib diisi!");
                isValid = false;
            }
            if (tanggalKadaluarsa === "") {
                $("#edit_tanggal_kadaluarsa").siblings(".error-message").text("Tanggal kadaluarsa wajib diisi!");
                isValid = false;
            }
            if (penanggungJawab === "") {
                $("#edit_penanggung_jawab").siblings(".error-message").text("Penanggung jawab wajib diisi!");
                isValid = false;
            }
            if (penanggungJawabEmail === "") {
                $("#edit_penanggung_jawab_email").siblings(".error-message").text("Penanggung jawab email wajib diisi!");
                isValid = false;
            }
            if (revisiKe === "") {
                $("#edit_revisi_ke").siblings(".error-message").text("Revisi ke wajib diisi!");
                isValid = false;
            }

            // **Validasi File (Opsional, hanya jika ada file baru)**
            if (dokumenFile) {
                let allowedExtensions = ["pdf", "doc", "docx", "xls", "xlsx", "jpg", "png"];
                let fileExtension = dokumenFile.name.split(".").pop().toLowerCase();
                let maxSize = 50 * 1024 * 1024; // 10MB


                if (!allowedExtensions.includes(fileExtension)) {
                    $("#edit-file-upload").siblings(".error-message").text("Format file tidak valid! (pdf, doc, docx, xls, xlsx, jpg, png)");
                    isValid = false;
                }

                if (dokumenFile.size > maxSize) {
                    $("#edit-file-upload").siblings(".error-message").text("Ukuran file maksimal 50MB!");
                    isValid = false;
                }
            }

            // **Jalankan AJAX jika valid**
            if (isValid) {
                $.ajax({
                    url: '{{ route('dokumen_legal.update_dokumen_legal') }}',
                    type: "POST",
                    data: formData,
                    processData: false, // Perlu untuk FormData
                    contentType: false, // Perlu untuk FormData
                    success: function (response) {
                        if (response.success) {
                            notif({
                                msg: "<b>Success:</b> Dokumen berhasil diperbarui!",
                                type: "success"
                            });
                            // $("#editModal").modal("hide");
                            // $("#datatable").DataTable().ajax.reload();
                            setTimeout(function myFunction() {
                                undo();
                            }, 2000);
                        }
                    },
                    error: function () {
                        notif({
                            msg: "<b>Info:</b> Terjadi kesalahan saat mengupdate dokumen!",
                            type: "error"
                        });
                    },
                });
            }
        });


        function open_modal_buat_dokumen() {
            $("#ajax-modal-tambah").modal('show');
            $('#title-modal-list').text('PENGAJUAN KUPON KARYAWAN');
        }

        function undo() {
            window.location.reload();
        }


        $(document).ready(function() {
            $("#selectEmployeeID").val('');
            $("#txt_name").val('');
            $("#txt_enroll_id").val('');
            $("#txt_department").val('');
            $("#txt_bagian").val('');
            $("#txt_staff").val('');
            $("#txt_nik").val('');
            $("#txt_jumlah").val('');
            dataTableDepartmentReload();
            $("#txt_jumlah").on("input", function() {
                let value = $(this).val().replace(/[^\d]/g, ""); // Hanya angka
                if (value) {
                    value = parseInt(value, 10); // Ubah ke angka
                    $(this).val("Rp " + value.toLocaleString("id-ID")); // Format ke Rupiah
                } else {
                    $(this).val(""); // Kosongkan jika tidak ada angka
                }
            });
            $("#jumlah_edit1").on("input", function() {
                let value = $(this).val().replace(/[^\d]/g, ""); // Hanya angka
                if (value) {
                    value = parseInt(value, 10); // Ubah ke angka
                    $(this).val("Rp " + value.toLocaleString("id-ID")); // Format ke Rupiah
                } else {
                    $(this).val(""); // Kosongkan jika tidak ada angka
                }
            });

            $("#selectEmployeeID").select2().on("select2:select", function() {
                cek_filter_modal();
            });

        })

        function dataTableDepartmentReload() {
            let activeTab = $(".nav-tabs .list.active").attr("id"); // Dapatkan tab yang aktif saat ini
            let status = activeTab ? activeTab.replace("-tab", "") : "default";
            let tableId = "#datatable_" + status;

            // Hapus DataTable lama jika ada
            if ($.fn.DataTable.isDataTable(tableId)) {
                $(tableId).DataTable().clear().destroy();
            }

            // Tambahkan kolom lainnya
            let columns = [
                {
                    data: 'id',
                    className: "text-center",
                    width:'15%',
                    render: (data, type, row, meta) => {
                        return `
                            <div>
                                <a style="text-align:center; color:white;" class="btn btn-primary btn-sm" onclick="showModalDepartment('${row.sub_dept_name}', '${row.sub_dept_id}')">
                                    <i class="fa fa-search"></i>
                                </a>
                                <a class="btn btn-gray btn-sm mt-1" style="color:white;" data-toggle="tooltip" title="Export Data ke File Transfer PDF" id="recap_labor_cost_2" onClick="export_voucher_bagian('${row.sub_dept_id}')">
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </a>
                                <a class="btn btn-success btn-sm mt-1" style="color:white;" data-toggle="tooltip" title="Export Data ke File Transfer PDF" id="recap_labor_cost_2" onClick="export_pengajuan_excel_bagian('${row.sub_dept_id}','${row.status}')">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                </a>
                            </div>
                        `;
                    }
                },
                {
                    data: 'created_at',
                    className: "text-center",
                    render: (data, type, row, meta) => {
                            return `
                            <div class="">
                                        `+moment(data.created_at).format('DD MMMM YYYY - HH:mm')+`
                            </div>
                            `
                    }
                 },
                { data: 'sub_dept_name' },
                { data: 'jml_data', className: "text-center", },
                {
                    data: 'jumlah',
                    className: "text-center",
                    render: function(data, type, row) {
                        if (!data) return "Rp 0"; // Jika kosong, tampilkan Rp 0
                        return "Rp " + parseInt(data, 10).toLocaleString("id-ID");
                    }
                },

            ];
            // Inisialisasi DataTable
            $(tableId).DataTable({
                processing: true,
                paging: false,
                searching: false,
                ordering: false,
                destroy: true,
                ajax: {
                    url: '{{ route('bazzar.get_bazzar_detail') }}',
                    dataSrc: "data",
                    data: {
                        status: status,
                    },
                    onSuccess: function (response) {
                        console.log(response);
                    }
                },
                columns: columns
            });
        }

        function hapus(a) {
            let id_tmp = a;
            Swal.fire({
            icon: 'error',
            title: 'Hapus data?',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#fa4456',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('bazzar.hapus') }}',
                        type: 'POST',
                        data: {
                            id_bazzar: id_tmp
                        },
                        success: function (res) {
                            notif({
                                msg: "<b>Info:</b> Data berhasil di hapus",
                                type: "info"
                            });
                            dataTableDepartmentReload();
                            $('.modal').modal('hide');

                        }, error: function (jqXHR) {
                            let res = jqXHR.responseJSON;
                            let message = '';

                            for (let key in res.errors) {
                                message = res.errors[key];
                            }
                            dataTableDepartmentReload();
                            notif({
                                    msg: "<b>Info:</b> Terjadi kesalahan",
                                    type: "error"
                                })
                        }
                    })
                }
            })


        }
        function edit_data(editData)
        {
            editData = JSON.parse(decodeURIComponent(editData));
            console.log('editData',editData)
            if (editData) {
                $("#ajax-modal-edit1").modal('show');

                $('#title-modal-edit1').text('EDIT PENGAJUAN KUPON : '+ editData.employee_name);
                $('#id_pengajuan').val(editData.id);
                $('#employee_name_edit1').val(editData.employee_name);
                $('#department_edit1').val(editData.department_name);
                $('#bagain_edit1').val(editData.sub_dept_name);
                $('#status_staff_edit1').val(editData.status_staff);
                $('#nik_edit1').val(editData.nik);
                $('#jumlah_edit1').val(editData.jumlah);
            }

        };

        $('body').on('click', '#btn-update_edit1', function (event) {
            var jumlah_edit = $('#jumlah_edit1').val();
            var id_pengajuan = $('#id_pengajuan').val();
            let jumlah_value = jumlah_edit.trim().replace(/Rp\s?|[^0-9]/g, "");
            $('#btn-update_edit1').addClass("btn-loading");
            $('#btn-update_edit1').html('Loading...');
            $('#btn-update_edit1').attr("disabled", true);
            console.log('id_pengajuan',id_pengajuan)
            console.log('jumlah_value',jumlah_value)
            $.ajax({
                type:"POST",
                url: "{{route('bazzar.edit_pengajuan')}}",
                data: {
                    id_pengajuan:id_pengajuan,
                    jumlah_edit:jumlah_value
                },
                success: function(res){
                    notif({
                        msg: "<b>Success:</b> Data berhasil di simpan.",
                        type: "success"
                    });

                    $("#btn-update_edit1").removeClass("btn-loading");
                    $("#btn-update_edit1").html('Simpan');
                    $("#btn-update_edit1").attr("disabled", false);

                    $("#ajax-modal-edit1").modal('hide');
                    dataTableReload();
                    dataTableDepartmentReload();
                    setTimeout(function myFunction() {
                        undo();
                    }, 2000);
                },
                error: function(res){
                    notif({
                        msg: "<b>Oops!</b> Simpan data lembur gagal.",
                        type: "error",
                        position: "center"
                    });

                    $("#btn-update_edit1").removeClass("btn-loading");
                    $("#btn-update_edit1").html('Simpan');
                    $("#btn-update_edit1").attr("disabled", false);

                }
            });
        });

    </script>
@endsection
