@extends('admin.adminlayouts.adminlayout-mut-karyawan')

@section('head')
    <link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
@stop
@section('mainarea')
<?php ini_set('date.timezone', 'Asia/Jakarta'); ?>
    <div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
        <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
            <li><a href="{{route('flns.index')}}">Bazzar</a></li>
            <li class="active"><span>Pengajuan kupon bazzar</span></li>
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

        <div class="card card-sb">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">CARI DATA : </label>
                            <select id="selectEmployeeID" name="selectEmployeeID[]" data-placeholder="Pilih karyawan" class="form-control select2 EmployeeID" onchange="cek_filter_modal();">
                                @foreach ($selectemployee as $r_empl)
                                    <option value="{{$r_empl->enroll_id}}">{{$r_empl->select_employee}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label><small><b>Nama</b></small></label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control " name="txt_name" id="txt_name" disabled>
                            <input type="hidden" class="form-control " name="txt_enroll_id" id="txt_enroll_id" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label><small><b>Department</b></small></label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control " name="txt_department" id="txt_department" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label><small><b>Bagian</b></small></label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control " name="txt_bagian" id="txt_bagian" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label><small><b>Status Staff</b></small></label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control " name="txt_staff" id="txt_staff" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label><small><b>NIK</b></small></label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control " name="txt_nik" id="txt_nik" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label><small><b>Jumlah</b></small></label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control " name="txt_jumlah" id="txt_jumlah" placeholder="Rp.">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group mt-5">
                            <button class="btn btn-primary w-100" onclick="onSave()">Simpan</button>
                        </div>
                    </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-list"></i> List Karyawan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered table-sm w-100 table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>No.</th>
                                        <th> <input type="checkbox" onclick="toggle(this);"></th>
                                        <th>Enroll id</th>
                                        <th>Nama</th>
                                        <th>Department</th>
                                        <th>Bagian</th>
                                        <th>Status</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Dibuat</th>
                                        <th>Act</th>
                                    </tr>
                                </thead>
                            </table>
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <style>
    .checkbox-xl .form-check-input {
        scale: 1.5;
    }
    </style>

<script>
    function toggle(source) {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
                ceklis(checkboxes[i]);
        }
    }
    function ceklis(checkeds) {
            //get id..and check if checked
            var tgl_lembur=$("#tgl_lembur").val();
            var id=checkeds.value;
            if(checkeds.checked==true){
                $.ajax({
                    type: "post",
                    url: '{{ route('fls.cek_data_lembur') }}',
                    data: {
                        id: id,
                        tgl_lembur: tgl_lembur
                    },
                    success: function(res) {
                        if(res>0){
                            iziToast.error({
                                message: 'Karyawan sudah ada',
                                position: 'center'
                            });
                            $('.checklist_'+id).prop( "checked", false );
                            const element = $('#keterangan_'+id);  // Get the DIV element
                            element.classList.remove("form-control keterangan"); // Remove mystyle class from DIV
                            element.classList.add("form-control");
                        }else{
                            $('#keterangan_'+id).attr('class', 'form-control keterangan');
                        }
                    }
                });
            }else{
            }

        }
</script>


    <script>
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        $('.select2').select2();
    </script>
    <script>


        function undo() {
            location.reload();
        }


        function cek_filter_modal() {
            const data= @json($selectemployee);
            var employee = $("select[name='selectEmployeeID[]']").map(function(){return $(this).val();}).get();
            const employe_data = data.filter(x => x.enroll_id == employee)[0];
            console.log('employee',employee)
            console.log(data.filter(x => x.enroll_id == employee));
            if (employe_data == '') {
                iziToast.error({
                    message: 'Terjadi kesalahan',
                    position: 'topCenter'
                });
            } else {
                document.getElementById("txt_name").value = employe_data.employee_name;
                document.getElementById("txt_enroll_id").value = employe_data.enroll_id;
                document.getElementById("txt_department").value = employe_data.department_name;
                document.getElementById("txt_bagian").value = employe_data.sub_dept_name;
                document.getElementById("txt_staff").value = employe_data.status_staff;
                document.getElementById("txt_nik").value = employe_data.nik;
            }
        }

        function onSave() {
            let txt_jumlah =  document.getElementById("txt_jumlah").value;
            let enroll_id =  document.getElementById("txt_enroll_id").value;
            let html = $.ajax({
                type: "post",
                url: '{{ route('bazzar.store') }}',
                data: {
                    enroll_id: enroll_id,
                    jumlah: txt_jumlah,
                },
                success: function(response) {
                    console.log('response',response);
                },
                error: function(request, status, error) {
                    console.log('error',error);
                },
            });
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
            dataTableReload();
        })


        function dataTableReload() {
            let datatable = $("#datatable").DataTable({
                processing: true,
                paging: false,
                searching: true,
                ordering: false,
                destroy: true,
                ajax: {
                    url: '{{ route('bazzar.get_bazzar') }}',
                },
                columns: [
                    {
                        data: null, // Kolom nomor urut
                        render: function(data, type, row, meta) {
                            return meta.row + 1; // Menghasilkan nomor urut
                        }
                    },
                    {
                        data: 'id'
                    },
                    {
                        data: 'enroll_id'
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
                        data: 'status_staff'
                    },
                    {
                        data: 'jumlah'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'created_at'
                    },

                    {
                        data: 'id'
                    },
                ],
                columnDefs: [
                    {
                    targets: [1],
                    render: (data, type, row, meta) => {
                        return `
                    <div
                        class="form-check checkbox-xl" style="text-align:center">
                        <input class="form-check-input" type="checkbox"
                        value="` + row.enroll_id + `" id="cek_data" onchange="ceklis(this)"
                        name="cek_data[` + row.enroll_id + `] "/>
                    </div>
                    <div>
                            <input type="hidden" size="10" id="enroll_id"
                            name="enroll_id[` + row.enroll_id + `]" value = "` + row.enroll_id + `"/>
                    </div>
                    <div>
                            <input type="hidden" size="10" id="stat"
                            name="stat[` + row.enroll_id + `]" value = "` + row.stat + `"/>
                    </div>
                    `;
                    }
                },
                {
                    targets: [10],
                    render: (data, type, row, meta) => {
                        return `
                    <div>
                        <a style="text-align:center" class='btn btn-success btn-sm' onclick="hapus('` + row.id + `');">
                         <i class='fa fa-edit'></i>
                        </a>
                        <a style="text-align:center" class='btn btn-danger btn-sm' onclick="hapus('` + row.id + `');">
                         <i class='fa fa-trash'></i>
                        </a>
                    </div>
                    `;
                    }
                }, ]
            });
        }

        function hapus(a) {
            let id_tmp = a;
            console.log(id_tmp);
            $.ajax({
                type: "post",
                url: '{{ route('bazzar.hapus') }}',
                data: {
                    id_bazzar: id_tmp
                },
                success: async function(res) {
                    iziToast.success({
                        message: 'Data Berhasil Dihapus',
                        position: 'topCenter'
                    });
                    dataTableReload();
                }
            });

        }

    </script>
@endsection
