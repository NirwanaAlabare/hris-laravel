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
            <li><a href="{{route('hris.ga.pemeriksaan_kendaraan')}}">Tranportasi</a></li>
            <li class="active"><span>Pemeriksaan Kendaraan</span></li>
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
                    <div class="card-title">Pemeriksaan Kendaraan</div>
                </div>
                <div class="mt-4 ml-4 mr-5 mb-0">
                    <div class=""  aria-labelledby="">
                        <div clasl="card-header m-0 p-0" style="display: flex; justify-content: space-between; align-items: center;">
                            {{-- <div class="m-0 p-0">
                                <button class="btn btn-primary w-100" onclick="open_modal_buat_dokumen()"  data-toggle="tooltip" title="Tambah dokumen" id="recap_labor_cost_2"><i class="fa fa-plus" aria-hidden="true"></i> Buat Pemeriksaan</button>
                            </div> --}}
                            <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label">TGL PEMERIKSAAN : </label>
                                        <div class="input-group">
                                            <input type="text" id="filter_tanggal" name="filter_tanggal" value="{{ date('Y-m-d') }}" class="form-control create-control fc-datepicker">
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    </div>
                    <div class="m-0 p-0">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable_ajax_pemeriksaan_kendaraan_list" class="table table-bordered table-sm w-100 table-hover">
                                    <thead class="table-primary bg-primary">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Pemeriksaan</th>
                                            <th>Tipe Kendaraan</th>
                                            <th>Pemeriksa</th>
                                            <th>Oddometer</th>
                                            <th>Kerusakan</th>
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
                <input type="hidden" id="form_mode" value="create">
                <input type="hidden" id="record_id" value="">
                <div class="col-md-12">
                    <div class="modal-content">
                        <div class="modal-header bg-primary p-2">
                            <h4 class="modal-title pl-2 font-weight-bold" id="title-modal-edit1">FORM PEMERIKSAAN KENDARAAN</h4>
                            <button type="button" id="btn-close" class="close text-white ml-1" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Tutup Dialog">
                                <i class="fa fa-remove"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                 <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">PILIH KENDARAAN : </label>
                                        <select id="PilihKendaraanID" name="PilihKendaraanID" class="form-control" id="vehicle_id">
                                            <option value="">Pilih Kendaraan</option>
                                            @foreach ($vehicles as $key=>$value)
                                                <option value="{{$value->id}}">{{$value->plat_no}} || {{$value->merk}} {{$value->tipe}}</option>
                                            @endforeach
                                        </select>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">PEMERIKSA : </label>
                                        <select id="diajukanOlehID" name="diajukanOlehID" style='width: 100%;' data-placeholder="Pilih Pemeriksa" class="form-control create-control select2 select2-show-search EmployeeID">
                                            <option value="">
                                                    Pilih Pemeriksa
                                                </option>
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">ODDOMETER : </label>
                                        <div class="input-group">
                                            <input  class="form-control create-control" id="oddometer" name="oddometer" type="text">
                                        </div>
                                        <small class="error-message text-danger"></small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">TGL PEMERIKSAAN : </label>
                                        <div class="input-group">
                                            <input type="text" id="tanggal_pemeriksaan" name="tanggal_pemeriksaan" class="form-control create-control fc-datepicker">
                                        </div>
                                    </div>
                                </div>
                                    @foreach($komponent_pemerikasaan_kendaraan as $key => $komponen)
                                        <div class="col-md-12 mb-3">
                                            <div class="card p-0 m-0 shadow-sm">
                                                 <div class="card-header bg-primary p-3">
                                                    <div class="card-title">{{ $komponen->nama_item_pemeriksaan }}</div>
                                                </div>
                                                @foreach($komponen->inputs as $key => $komponen_detail)
                                                @php
                                                    // Decode JSON menjadi array PHP
                                                    $items = json_decode($komponen_detail->nama_item_list, true);
                                                @endphp
                                                <div class="p-3 border-bottom">
                                                    <label class="form-label font-weight-bold">{{$key+1}}. {{ $komponen_detail->nama_item_pemeriksaan_detail }}</label>
                                                    @if(!empty($items))
                                                        <ul>
                                                            @foreach($items as $item)
                                                                <li>- {{ $item }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                    <div class="form-check form-check-lg mt-2 mb-2">
                                                        <input class="form-check-input pilihan-pemeriksaan"
                                                            type="radio"
                                                            name="pemeriksaan[{{ $komponen_detail->id }}]"
                                                            value="baik"
                                                            data-target="#detail-{{ $komponen_detail->id }}"
                                                            checked
                                                            id="baik-{{ $komponen_detail->id }}">
                                                        <label class="form-check-label" for="baik-{{ $komponen_detail->id }}">
                                                            ✅ Baik
                                                        </label>
                                                    </div>

                                                    <div class="form-check form-check-lg">
                                                        <input class="form-check-input pilihan-pemeriksaan"
                                                            type="radio"
                                                            name="pemeriksaan[{{ $komponen_detail->id }}]"
                                                            value="tidak_baik"
                                                            data-target="#detail-{{ $komponen_detail->id }}"
                                                            id="tidak-{{ $komponen_detail->id }}">
                                                        <label class="form-check-label" for="tidak-{{ $komponen_detail->id }}">
                                                            ❌ Tidak Baik
                                                        </label>
                                                    </div>

                                                    <div id="detail-{{ $komponen_detail->id }}" class="mt-3 detail-box" style="display: none;">
                                                        <div class="form-group mb-2">
                                                            <label class="form-label">JELASKAN JIKA DALAM KONDISI TIDAK BAIK / (KURANG) Upload Foto bagian kerusakan:</label>
                                                            <textarea class="form-control"
                                                                    name="catatan[{{ $komponen_detail->id }}]"
                                                                    rows="2"></textarea>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Upload Foto:</label>
                                                            <div class="wrapper border p-3 text-center">
                                                                <div class="upload-container">
                                                                    <p>Drag and drop files here, or
                                                                        <a href="#" class="file-browser">browse</a>
                                                                    </p>
                                                                    <input type="file"
                                                                        name="foto[{{ $komponen_detail->id }}][]"
                                                                        class="file-upload"
                                                                        multiple style="display: none;">
                                                                </div>
                                                                <div class="file-list"></div>
                                                            </div>
                                                            <small class="error-message text-danger"></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
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


    <div id="modalDetailPemeriksaan" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Perbaikan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable_ajax_pemeriksaan_kendaraan_detail" class="table table-bordered table-sm w-100 table-hover">
                                <thead class="table-primary bg-primary">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Pemeriksaan</th>
                                        <th>Tipe Kendaraan</th>
                                        <th>Pemeriksa</th>
                                        <th>Item Pemeriksaan</th>
                                        <th>Status</th>
                                        <th>Keterangan</th>
                                        <th>Foto</th>
                                    </tr>
                                </thead>
                            </table>
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
        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll('.pilihan-pemeriksaan');

            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const targetId = this.dataset.target;
                    const detailBox = document.querySelector(targetId);

                    if(this.value === 'tidak_baik') {
                        detailBox.style.display = 'block';
                    } else {
                        detailBox.style.display = 'none';
                    }
                });
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // handle file browse link
            document.querySelectorAll('.file-browser').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    this.closest('.wrapper').querySelector('.file-upload').click();
                });
            });

            $(".file-upload").on("change", function () {
                let fileListContainer = $(this).closest(".wrapper").find(".file-list");
                handleFiles(this.files, fileListContainer, $(this));
            });

            function handleFiles(files, fileListContainer, fileInput) {
                fileListContainer.empty();

                for (let i = 0; i < files.length; i++) {
                    let file = files[i];
                    let fileType = file.type.startsWith("image")
                        ? `<img src="${URL.createObjectURL(file)}" width="50" class="me-2">`
                        : `<i class="fa fa-file me-2"></i>`;

                    let fileItem = `
                        <div class="file-item d-flex align-items-center mb-2">
                            ${fileType}
                            <span class="me-2">${file.name}</span>
                            <button type="button" class="btn btn-sm btn-danger remove-btn" data-index="${i}">Hapus</button>
                        </div>
                    `;
                    fileListContainer.append(fileItem);
                }

                // Event hapus
                fileListContainer.find(".remove-btn").on("click", function () {
                    $(this).closest(".file-item").remove();

                    // Reset file input biar bisa upload ulang file yang sama
                    fileInput.val("");
                });
            }
        });
    </script>


    <script>
        $('body').on('change', '#jenis_dokumen_filter', function (event) {
            $('#datatable').DataTable().ajax.reload();
        });

        $(document).ready(function () {
            $("#submit-form").click(function (e) {
                e.preventDefault();
                let mode = $(this).data('mode');
                let id = $(this).data('id');


                let isValid = true;
                let formData = new FormData();

                let kendaraan_id = $('#PilihKendaraanID').val();
                let enroll_id   = $('#diajukanOlehID').val();
                let oddometer   = $('#oddometer').val();
                let tanggal     = $('#tanggal_pemeriksaan').val();

                // Validasi satu per satu
                if (!kendaraan_id) {
                    $("#PilihKendaraanID").next(".error-message").text("Kendaraan wajib dipilih.");
                    isValid = false;
                }
                if (!enroll_id) {
                    $("#diajukanOlehID").next(".error-message").text("Pengaju wajib dipilih.");
                    isValid = false;
                }
                if (!oddometer) {
                    $("#oddometer").next(".error-message").text("Odometer wajib diisi.");
                    isValid = false;
                }
                if (!tanggal) {
                    $("#tanggal_pemeriksaan").next(".error-message").text("Tanggal pemeriksaan wajib diisi.");
                    isValid = false;
                }

                // Kalau ada error, jangan lanjut submit
                if (!isValid) {
                    return;
                }

                formData.append("kendaraan_id", kendaraan_id);
                formData.append("enroll_id", enroll_id);
                formData.append("oddometer", oddometer);
                formData.append("tanggal_pemeriksaan", tanggal);

                // loop semua komponen pemeriksaan
                $(".pilihan-pemeriksaan:checked").each(function () {
                    // let komponenId = $(this).attr("name").match(/\[(.*?)\]/)[1];
                    let komponenId = $(this).attr("name").match(/\[(\d+)\]$/)[1];
                    // status pemeriksaan
                    formData.append(`pemeriksaan[${komponenId}]`, $(this).val());

                    // catatan kalau ada
                    let catatan = $(`textarea[name="catatan[${komponenId}]"]`).val();
                    if (catatan) {
                        formData.append(`catatan[${komponenId}]`, catatan);
                    }

                    // foto kalau ada
                    let fileInput = $(`input[name="foto[${komponenId}][]"]`)[0];
                    if (fileInput && fileInput.files.length > 0) {
                        for (let i = 0; i < fileInput.files.length; i++) {
                            formData.append(`foto[${komponenId}][]`, fileInput.files[i]);
                        }
                    }
                });
                console.log(...formData);
                console.log(mode, id);
                let url = (mode === 'edit')
                    ? "{{ route('hris.ga.ajax_update_pemeriksaan_kendaraan', '') }}/" + id
                    : "{{ route('hris.ga.store_pemeriksaan_kendaraan') }}";
                        $(".error-message").text("");
                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        notif({
                            msg: "<b>Info:</b> Rekap berhasil disimpan!",
                            type: "info"
                        });
                        $('#PilihKendaraanID').val('').trigger('change');
                        $('#diajukanOlehID').val('').trigger('change');
                        $('#oddometer').val('');
                        $('#tanggal_pemeriksaan').val('');

                        $(".pilihan-pemeriksaan").prop("checked", false);
                        $("input[type='radio'][value='baik']").prop("checked", true);

                        // reset textarea catatan
                        $("textarea[name^='catatan']").val('');

                        // reset file input
                        $("input[type='file'][name^='foto']").val('');

                        // reload datatable
                        $('#datatable_ajax_pemeriksaan_kendaraan_list').DataTable().ajax.reload();
                        $('#ajax-modal-tambah').modal('hide');
                    },
                    error: function (xhr) {
                        notif({
                            msg: "<b>Info:</b> gagal menyimpan rekap!",
                            type: "error"
                        });
                        console.log(xhr.responseText);
                    }
                });
            });


            $("#file-browser").on("click", function (e) {
                e.preventDefault(); // Mencegah navigasi ke #
                $("#file-upload").click(); // Memicu input file
            });
        });


        $(document).ready(function() {
            var table_list = $('#datatable_ajax_pemeriksaan_kendaraan_list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('hris.ga.ajax_get_pemeriksaan_kendaraan_list') }}',
                    data: function (d) {
                        d.tanggal = $('#filter_tanggal').val(); // kirim tanggal ke backend
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'tanggal_pemeriksaan', name: 'tanggal_pemeriksaan' },
                    { data: 'kendaraan', name: 'kendaraan' },
                    { data: 'diajukan_oleh', name: 'diajukan_oleh' },
                    { data: 'oddometer', name: 'oddometer' },
                    { data: 'jumlah_tidak_baik', name: 'jumlah_tidak_baik',
                        render: function(data, type, row) {
                            console.log(row);
                            if (type === 'display' && row.employee_name !==null) {
                                if (data != 0) {
                                    return '<span class="badge bg-danger">Kerusakan: ' + data + '</span>';
                                } else {
                                    return '<span class="badge bg-success">Semua Baik</span>';
                                }
                            }else{
                                return 'Belum Diperiksa';
                            }
                            return data;
                        }
                     },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
                ]
            });

            // Refresh table saat ganti tanggal
            $('#filter_tanggal').on('change', function() {
                table_list.ajax.reload();
            });


         // handle klik tombol detail
            $(document).on('click', '.btn-detail', function () {
                var id = $(this).data('id');

                // set url baru untuk datatable detail sesuai id
                 var url = "{{ route('hris.ga.ajax_get_pemeriksaan_kendaraan_detail', ':id') }}";
                url = url.replace(':id', id);

                // destroy dulu biar ga conflict
                $('#datatable_ajax_pemeriksaan_kendaraan_detail').DataTable().clear().destroy();

                // init ulang
                var table_detail = $('#datatable_ajax_pemeriksaan_kendaraan_detail').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: url,
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'tanggal_pemeriksaan', name: 'tanggal_pemeriksaan' },
                        { data: 'kendaraan', name: 'kendaraan' },
                        { data: 'diajukan_oleh', name: 'diajukan_oleh' },
                        { data: 'komponen', name: 'komponen' },
                        { data: 'status', name: 'status', orderable: false, searchable: false },
                        { data: 'catatan', name: 'catatan' },
                        { data: 'foto_path', name: 'foto_path', orderable: false, searchable: false },
                    ]
                });

                // tampilkan modal
                $('#modalDetailPemeriksaan').modal('show');
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

        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $.get("{{ route('hris.ga.ajax_edit_pemeriksaan_kendaraan', '') }}/" + id, function(data) {
                console.log(data);
                // isi form modal dengan data
                $('#PilihKendaraanID').val(data.kendaraan_id).trigger('change');
                $('#diajukanOlehID').val(data.enroll_id).trigger('change');
                $('#oddometer').val(data.oddometer);
                $('#tanggal_pemeriksaan').val(data.tanggal_pemeriksaan);

                // isi komponen pemeriksaan
                $.each(data.detail, function(index, item) {
                    if (item.status === 'baik') {
                        $('#baik-' + item.komponen_id).prop('checked', true);
                        $('#detail-' + item.komponen_id).hide();
                    } else {
                        $('#tidak-' + item.komponen_id).prop('checked', true);
                        $('textarea[name="catatan['+item.komponen_id+']"]').val(item.catatan);
                         $('#detail-' + item.komponen_id).show();
                    }
                     if(item.foto_path){
                            let fileListContainer = $('#detail-' + item.komponen_id).find('.file-list');
                            fileListContainer.empty();
                            let fileUrl = "{{ asset('storage') }}/" + item.foto_path;

                            let fileType = item.foto_path.match(/\.(jpg|jpeg|png|gif)$/i)
                                ? `<img src="${fileUrl}" width="50" class="me-2">`
                                : `<i class="fa fa-file me-2"></i>`;

                            let fileItem = `
                                <div class="file-item d-flex align-items-center mb-2">
                                    ${fileType}
                                    <span class="me-2">${item.foto_path.split('/').pop()}</span>
                                    <button type="button" class="btn btn-sm btn-danger remove-btn" data-file="${item.foto_path}">Hapus</button>
                                </div>
                            `;
                            fileListContainer.append(fileItem);

                            // hapus preview lama
                            fileListContainer.find(".remove-btn").on("click", function () {
                                $(this).closest(".file-item").remove();
                                // jika mau, bisa tambahkan ajax untuk hapus file lama dari server
                            });
                        }
                });

                // ubah button simpan agar update
                $('#submit-form').attr('data-mode', 'edit').attr('data-id', data.id);

                // tampilkan modal
                $('#ajax-modal-tambah').modal('show');
            });
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


        function open_modal_buat_dokumen() {
            $("#form_mode").val("create");
            $("#record_id").val("");
            $("#ajax-modal-tambah").modal('show');
            $('#title-modal-list').text('BUAT PERBAIKAN KENDARAAN');
            $("#ajax-modal-tambah").find("input, textarea, select").val("");
            $(".pilihan-pemeriksaan[value='baik']").prop("checked", true);
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
