@extends('admin.adminlayouts.adminlayout4')

@section('head')
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<!---Sweetalert Css-->
<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<style>
    #datatable tr td {
        height: 4px;
        line-height: 0.3;
    }
</style>

@stop
@section('mainarea')

<div class="card">
    <div class="card-header mt-7 pt-1 pb-0">
        <ul class="nav nav-tabs pl-5">
            <li class="nav-item">
                <a class="btn btn-white" href="{{route('hris.ga.pemeliharaan_kendaraan')}}">Formulir</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-primary text-white">Monitoring</a>
            </li>
        </ul>
    </div>
    <div class="container-fluid content-row">
        <div class="card-body" style="border: 1px solid #d8d4dc">
            <table class="table table-bordered w-100" id="datatable">
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th>Merk Mobil</th>
                        <th>Sparepart</th>
                        <th>Last Date Maintained</th>
                        <th>Next Date maintained</th>
                    </tr>
                </thead>
            </table>
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
<!-- Sweet alert js-->
<script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
<script>
    $(document).ready(function() {
        
    });
    let datatable = $("#datatable").DataTable({
        ordering: true,
        processing: true,
        serverSide: true,
        paging: true,
        searching: true,
        destroy: true,
        scrollX: true,
        ajax: {
            url: '{{ route('hris.ga.get_vehicle_item_monitoring') }}',
        },
        columns: [
            {
                data: null, // Kolom untuk nomor urut
                render: function (data, type, row, meta) {
                    return meta.row + 1; // Menampilkan urutan angka
                }
            }, {
                data: 'vehicle_merk'
            }, {
                data: 'nama_barang'
            }, {
                data: 'tanggal'
            }, {
                data: 'next_maintain_plan'
            }
        ],
    });
</script>
@endsection