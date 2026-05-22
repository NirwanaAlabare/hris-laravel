@extends('admin.adminlayouts.adminlayout-mut-karyawan')

@section('head')
<link href="{{ URL::asset('assets/plugins/datatable/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/js/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/izitoast/dist/css/iziToast.min.css')}}" rel="stylesheet">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.css">
<style>
    .card-tools .btn {
        margin-left: 5px;
    }

    .check-group {
        display: inline-flex;
        align-items: center;
        margin-right: 15px;
        cursor: pointer;
    }

    .check-group input {
        margin-right: 5px;
    }

    .history-select {
        font-size: 12px;
        background-color: #f8f9fa;
        margin-bottom: 10px;
    }

    .detail-table td,
    .detail-table th {
        padding: 8px;
        vertical-align: middle;
    }

    .ui-timepicker-wrapper {
        z-index: 9999 !important;
    }

    .select2-container {
        z-index: 9999 !important;
    }
</style>
@stop

@section('mainarea')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item"><a class="nav-link active"
                        href="{{route('hris.ga.form_pengajuan_transportasi')}}">Formulir</a></li>
                <li class="nav-item">
                    @php $isAdmin = in_array($id_user, [4241,20,8590,6083,17]); @endphp
                    <a class="nav-link" href="{{route('hris.ga.data_pengajuan_transportasi_admin')}}">Data @if($isAdmin
                        && $pengajuan_transportasi > 0) <span
                            class="badge badge-danger">{{$pengajuan_transportasi}}</span> @endif</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <input type="hidden" id="userId" value="{{$id_user}}">

            <!-- Pilih Karyawan -->
            <div class="form-group row mb-4">
                <label class="col-sm-2 col-form-label font-weight-bold">Nama Karyawan <span
                        class="text-danger">*</span></label>
                <div class="col-sm-5">
                    <select id="selectEmployee" class="form-control select2-multiple" multiple>
                        @foreach($selectemployee as $emp)
                        <option value="{{$emp->enroll_id}}">{{$emp->select_employee}}</option>
                        @endforeach
                    </select>
                    <small class="text-danger" id="employeeError"></small>
                </div>
            </div>

            <!-- Lokasi Keberangkatan -->
            <div class="card card-outline card-primary mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fa fa-map-marker-alt mr-2"></i> LOKASI KEBERANGKATAN</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary float-right"
                        onclick="setDefaultOrigin()"><i class="fa fa-building"></i> Set PT. NAG</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2"><label>Provinsi <span class="text-danger">*</span></label><select
                                id="originProvince" class="form-control select2-single"></select></div>
                        <div class="col-md-2"><label>Kab/Kota <span class="text-danger">*</span></label><select
                                id="originCity" class="form-control select2-single"></select></div>
                        <div class="col-md-2"><label>Kecamatan <span class="text-danger">*</span></label><select
                                id="originDistrict" class="form-control select2-single"></select></div>
                        <div class="col-md-2"><label>Desa <span class="text-danger">*</span></label><select
                                id="originSubdistrict" class="form-control select2-single"></select></div>
                        <div class="col-md-2"><label>Instansi</label><input type="text" id="originInstansi"
                                class="form-control" placeholder="Instansi"></div>
                        <div class="col-md-2"><label>Detail Alamat <span class="text-danger">*</span></label><input
                                type="text" id="originDetailAddress" class="form-control" placeholder="Jalan/Gedung">
                        </div>
                        <div class="col-md-3 mt-2"><label>Tanggal <span class="text-danger">*</span></label><input
                                type="text" id="originDate" class="form-control datepicker" placeholder="DD/MM/YYYY">
                        </div>
                        <div class="col-md-3 mt-2"><label>Jam <span class="text-danger">*</span></label><input
                                type="text" id="originTime" class="form-control timepicker" placeholder="--:--"></div>
                    </div>
                </div>
            </div>

            <div id="destinationsContainer"></div>

            <div class="text-center mt-4">
                <button type="button" class="btn btn-success btn-lg px-5" onclick="saveChanges()"><i
                        class="fa fa-send mr-2"></i> SEND REQUEST</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footerjs')
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{URL::asset('assets/plugins/izitoast/dist/js/iziToast.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>

<script>
// ==================== GLOBAL CONFIG & ARRAYS ====================
const config = {
    csrfToken: '{{csrf_token()}}',
    routes: {
        province: "{{route('hris.ga.get_province')}}",
        cities: "{{route('hris.ga.get_cities')}}",
        districts: "{{route('hris.ga.get_districts')}}",
        subdistricts: "{{route('hris.ga.get_subdistricts')}}",
        history: "{{route('hris.ga.get_all_destination_history')}}",
        store: "{{route('hris.ga.store_car_request')}}"
    }
};

let array_provinsi = [], array_kota = [], array_kecamatan = [], array_desa = [];
let array_instansi = [], array_detail_alamat = [], array_tanggal_pemberangkatan = [], array_jam_pemberangkatan = [];
let array_tujuan_pemberangkatan = [], array_jarak_tempuh = [];
let array_jenis_barang = [], array_quantity = [], array_satuan = [], array_nama_penerima = [], array_keterangan_barang = [];
let array_nama_tamu = [], array_nomor_hp_tamu = [], array_karyawan_dinas_luar = [];

// ==================== INIT ====================
$(document).ready(function() {
    initSelect2();
    initDatepicker();
    initTimepicker();
    
    // Load base provinces
    loadProvinces().then(() => {
        let opt = '<option value="">Pilih Provinsi</option>';
        $.each(window.provincesData, (k,v) => opt += `<option value="${v.prov_id}">${v.prov_name}</option>`);
        $('#originProvince').html(opt);
    });

    addArrayItem(); // inisialisasi form tujuan pertama
});

function initSelect2() {
    $('#selectEmployee').select2({ placeholder: "Pilih karyawan", width: '100%' });
    $('.select2-single').select2({ maximumSelectionLength: 1, width: '100%' });
}
function initDatepicker() { $('.datepicker').datepicker({ dateFormat: "dd/mm/yy", altFormat: "yy-mm-dd" }); }
function initTimepicker() { $('.timepicker').timepicker({ timeFormat: "H:i", step: 30 }); }

// ==================== ASYNC DROPDOWN LOADERS ====================
async function loadProvinces() {
    if(window.provincesData) return window.provincesData;
    const res = await $.post(config.routes.province, { _token: config.csrfToken });
    window.provincesData = res; // Cache
    return res;
}

async function loadCities(provinceId, targetId) {
    if (!provinceId) { $(`#${targetId}`).html('<option value="">Pilih Kota</option>').trigger('change.select2'); return; }
    const res = await $.post(config.routes.cities, { provinsi: provinceId, _token: config.csrfToken });
    let opt = '<option value="">Pilih Kota</option>';
    $.each(res, (k,v) => opt += `<option value="${v.city_id}">${v.city_name}</option>`);
    $(`#${targetId}`).html(opt).trigger('change.select2');
}

async function loadDistricts(cityId, targetId) {
    if (!cityId) { $(`#${targetId}`).html('<option value="">Pilih Kecamatan</option>').trigger('change.select2'); return; }
    const res = await $.post(config.routes.districts, { cities: cityId, _token: config.csrfToken });
    let opt = '<option value="">Pilih Kecamatan</option>';
    $.each(res, (k,v) => opt += `<option value="${v.dis_id}">${v.dis_name}</option>`);
    $(`#${targetId}`).html(opt).trigger('change.select2');
}

async function loadSubdistricts(districtId, targetId) {
    if (!districtId) { $(`#${targetId}`).html('<option value="">Pilih Desa</option>').trigger('change.select2'); return; }
    const res = await $.post(config.routes.subdistricts, { districts: districtId, _token: config.csrfToken });
    let opt = '<option value="">Pilih Desa</option>';
    $.each(res, (k,v) => opt += `<option value="${v.subdis_id}">${v.subdis_name}</option>`);
    $(`#${targetId}`).html(opt).trigger('change.select2');
}

// ==================== MANAJEMEN DESTINASI ====================
function addArrayItem() {
    array_provinsi.push(''); array_kota.push(''); array_kecamatan.push(''); array_desa.push('');
    array_instansi.push(''); array_detail_alamat.push(''); array_tanggal_pemberangkatan.push(''); array_jam_pemberangkatan.push('');
    array_tujuan_pemberangkatan.push(''); array_jarak_tempuh.push(0);
    array_jenis_barang.push(''); array_quantity.push(''); array_satuan.push(''); array_nama_penerima.push(''); array_keterangan_barang.push('');
    array_nama_tamu.push(''); array_nomor_hp_tamu.push(''); array_karyawan_dinas_luar.push('');
    renderDestinations();
}

function deleteItem(idx) {
    if (array_provinsi.length === 1) { iziToast.warning({ message: 'Minimal harus ada satu tujuan' }); return; }
    
    // Splice semua array
    const arrays = [
        array_provinsi, array_kota, array_kecamatan, array_desa, array_instansi, array_detail_alamat,
        array_tanggal_pemberangkatan, array_jam_pemberangkatan, array_tujuan_pemberangkatan, array_jarak_tempuh,
        array_jenis_barang, array_quantity, array_satuan, array_nama_penerima, array_keterangan_barang,
        array_nama_tamu, array_nomor_hp_tamu, array_karyawan_dinas_luar
    ];
    arrays.forEach(arr => arr.splice(idx, 1));
    renderDestinations();
}

function duplicateItem(idx) {
    array_provinsi.push(array_provinsi[idx]); array_kota.push(array_kota[idx]); array_kecamatan.push(array_kecamatan[idx]); array_desa.push(array_desa[idx]);
    array_instansi.push(array_instansi[idx]); array_detail_alamat.push(array_detail_alamat[idx]); array_tanggal_pemberangkatan.push(array_tanggal_pemberangkatan[idx]); array_jam_pemberangkatan.push(array_jam_pemberangkatan[idx]);
    array_tujuan_pemberangkatan.push(array_tujuan_pemberangkatan[idx]); array_jarak_tempuh.push(array_jarak_tempuh[idx]);
    array_jenis_barang.push(array_jenis_barang[idx]); array_quantity.push(array_quantity[idx]); array_satuan.push(array_satuan[idx]); array_nama_penerima.push(array_nama_penerima[idx]); array_keterangan_barang.push(array_keterangan_barang[idx]);
    array_nama_tamu.push(array_nama_tamu[idx]); array_nomor_hp_tamu.push(array_nomor_hp_tamu[idx]); array_karyawan_dinas_luar.push(array_karyawan_dinas_luar[idx]);
    
    renderDestinations();
    iziToast.success({ message: 'Tujuan berhasil diduplikat' });
}

function renderDestinations() {
    let container = $('#destinationsContainer');
    container.empty();

    $.each(array_provinsi, function(key) {
        let title = key === 0 ? 'PERTAMA' : `KE ${key+1}`;
        let html = `
        <div class="card card-outline card-secondary mb-4" id="card_${key}">
            <div class="card-header">
                <h5 class="card-title"><i class="fa fa-flag-checkered"></i> TUJUAN ${title}</h5>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-success" onclick="addArrayItem()"><i class="fa fa-plus"></i> TAMBAH</button>
                    <button type="button" class="btn btn-sm btn-info" onclick="duplicateItem(${key})"><i class="fa fa-copy"></i> DUPLIKAT</button>
                    ${array_provinsi.length > 1 ? `<button type="button" class="btn btn-sm btn-danger" onclick="deleteItem(${key})"><i class="fa fa-trash"></i> HAPUS</button>` : ''}
                </div>
            </div>
            <div class="card-body">
                <select id="history_${key}" class="form-control mb-3" onchange="loadHistoryAddress(${key}, this.value)"><option value="">-- Gunakan History Alamat --</option></select>
                <table class="table table-bordered">
                    <thead><tr><th width="16%">Provinsi</th><th width="16%">Kab/Kota</th><th width="16%">Kecamatan</th><th width="16%">Desa</th><th width="16%">Instansi & Detail</th><th width="20%">Waktu</th></tr></thead>
                    <tbody><tr>
                        <td><select id="prov_${key}" class="form-control select2-single" onchange="changeCity(${key}, this.value)"></select><div class="text-danger small mt-1" id="errProv_${key}"></div></td>
                        <td><select id="city_${key}" class="form-control select2-single" onchange="changeDistrict(${key}, this.value)"></select><div class="text-danger small mt-1" id="errCity_${key}"></div></td>
                        <td><select id="dist_${key}" class="form-control select2-single" onchange="changeSubDistrict(${key}, this.value)"></select><div class="text-danger small mt-1" id="errDist_${key}"></div></td>
                        <td><select id="subdist_${key}" class="form-control select2-single" onchange="subdistrictChange(${key}, this.value)"></select><div class="text-danger small mt-1" id="errSubdist_${key}"></div></td>
                        <td>
                            <input type="text" id="instansi_${key}" class="form-control mb-2" placeholder="Nama Instansi" oninput="instansiChange(${key}, this.value)" value="${array_instansi[key] || ''}">
                            <input type="text" id="alamat_${key}" class="form-control" placeholder="Detail Alamat" oninput="detailAlamatChange(${key}, this.value)" value="${array_detail_alamat[key] || ''}">
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-6"><input type="text" id="date_${key}" class="form-control datepicker" placeholder="Tgl" onchange="tanggalPemberangkatanChange(${key}, this.value)" value="${array_tanggal_pemberangkatan[key] || ''}"></div>
                                <div class="col-6"><input type="text" id="time_${key}" class="form-control timepicker" placeholder="Jam" onchange="jamPemberangkatanChange(${key}, this.value)" value="${array_jam_pemberangkatan[key] || ''}"></div>
                            </div>
                        </td>
                    </tr></tbody>
                </table>
                <div class="mt-3"><label>🎯 TUJUAN PERJALANAN:</label> 
                    <div class="d-flex flex-wrap gap-3">
                        <label class="check-group mr-3"><input type="checkbox" class="purpose" data-key="${key}" value="antar_tamu" onchange="checkboxChecked(${key})"> Antar tamu</label>
                        <label class="check-group mr-3"><input type="checkbox" class="purpose" data-key="${key}" value="jemput_tamu" onchange="checkboxChecked(${key})"> Jemput tamu</label>
                        <label class="check-group mr-3"><input type="checkbox" class="purpose" data-key="${key}" value="antar_barang" onchange="checkboxChecked(${key})"> Antar barang</label>
                        <label class="check-group mr-3"><input type="checkbox" class="purpose" data-key="${key}" value="jemput_barang" onchange="checkboxChecked(${key})"> Jemput barang</label>
                        <label class="check-group mr-3"><input type="checkbox" class="purpose" data-key="${key}" value="antar_dinas" onchange="checkboxChecked(${key})"> Antar dinas</label>
                        <label class="check-group mr-3"><input type="checkbox" class="purpose" data-key="${key}" value="jemput_dinas" onchange="checkboxChecked(${key})"> Jemput dinas</label>
                    </div>
                    <div class="text-danger small" id="errTujuan_${key}"></div>
                </div>
                <div id="detail_${key}" class="mt-2"></div>
                <table class="table table-bordered mt-3" style="width:250px;">
                    <tr><th class="bg-light align-middle">Jarak Tempuh</th><td><div class="input-group"><input type="number" id="jarak_${key}" class="form-control" value="${array_jarak_tempuh[key] || 0}" oninput="jarakTempuhChange(${key}, this.value)"><div class="input-group-append"><span class="input-group-text">KM</span></div></div></td></tr>
                </table>
            </div>
        </div>`;
        container.append(html);
    });

    initSelect2();
    initDatepicker();
    initTimepicker();
    restoreUIState();
    loadHistoryForAll();
}

async function restoreUIState() {
    let optProv = '<option value="">Pilih Provinsi</option>';
    if (window.provincesData) {
        $.each(window.provincesData, (k,v) => optProv += `<option value="${v.prov_id}">${v.prov_name}</option>`);
    }

    $.each(array_provinsi, async function(key, val) {
        $(`#prov_${key}`).html(optProv);
        
        // Restore values and load hierarchies asynchronously
        if (val) {
            $(`#prov_${key}`).val(val).trigger('change.select2');
            await loadCities(val, `city_${key}`);
            if (array_kota[key]) {
                $(`#city_${key}`).val(array_kota[key]).trigger('change.select2');
                await loadDistricts(array_kota[key], `dist_${key}`);
                
                if (array_kecamatan[key]) {
                    $(`#dist_${key}`).val(array_kecamatan[key]).trigger('change.select2');
                    await loadSubdistricts(array_kecamatan[key], `subdist_${key}`);
                    
                    if (array_desa[key]) {
                        $(`#subdist_${key}`).val(array_desa[key]).trigger('change.select2');
                    }
                }
            }
        }

        // Restore Checkboxes & Purposes
        let purposes = array_tujuan_pemberangkatan[key] ? array_tujuan_pemberangkatan[key].split(',') : [];
        $(`.purpose[data-key="${key}"]`).each(function() { 
            if(purposes.includes($(this).val())) $(this).prop('checked', true); 
        });
        checkboxChecked(key); // Build dynamic detail tables
    });
}

function loadHistoryForAll() {
    $.post(config.routes.history, { id: $('#userId').val(), _token: config.csrfToken })
        .done(res => {
            $('[id^="history_"]').each(function() {
                let $sel = $(this);
                $sel.html('<option value="">-- Gunakan History Alamat --</option>');
                $.each(res, (k,v) => $sel.append(`<option value="${v.instansi}|${v.detail_alamat}|${v.prov_id}|${v.city_id}|${v.dis_id}|${v.subdistrict}">${v.detail_alamat_tujuan}</option>`));
            });
        });
}

async function loadHistoryAddress(key, val) {
    if (!val) return;
    let parts = val.split('|');
    if (parts.length >= 6) {
        let [instansi, detailAlamat, provId, cityId, districtId, subdistrictId] = parts;
        
        $(`#instansi_${key}`).val(instansi); array_instansi[key] = instansi;
        $(`#alamat_${key}`).val(detailAlamat); array_detail_alamat[key] = detailAlamat;

        array_provinsi[key] = provId;
        $(`#prov_${key}`).val(provId).trigger('change.select2');

        array_kota[key] = cityId;
        await loadCities(provId, `city_${key}`);
        $(`#city_${key}`).val(cityId).trigger('change.select2');

        array_kecamatan[key] = districtId;
        await loadDistricts(cityId, `dist_${key}`);
        $(`#dist_${key}`).val(districtId).trigger('change.select2');

        array_desa[key] = subdistrictId;
        await loadSubdistricts(districtId, `subdist_${key}`);
        $(`#subdist_${key}`).val(subdistrictId).trigger('change.select2');

        $(`#history_${key}`).val(''); // Reset selector
        $(`#errProv_${key}, #errCity_${key}, #errDist_${key}, #errSubdist_${key}`).text(''); // Clear errors
    }
}

// ==================== CASCADING DROPDOWN HANDLERS ====================
async function changeCity(key, val) { 
    if (array_provinsi[key] === val) return; // Mencegah reset saat re-rendering
    array_provinsi[key] = val; 
    array_kota[key] = ''; array_kecamatan[key] = ''; array_desa[key] = '';
    
    $(`#dist_${key}`).html('<option value="">Pilih Kecamatan</option>');
    $(`#subdist_${key}`).html('<option value="">Pilih Desa</option>');
    
    if(val) {
        $(`#errProv_${key}`).text('');
        await loadCities(val, `city_${key}`);
    } else {
        $(`#city_${key}`).html('<option value="">Pilih Kota</option>');
    }
}

async function changeDistrict(key, val) { 
    if (array_kota[key] === val) return; 
    array_kota[key] = val; 
    array_kecamatan[key] = ''; array_desa[key] = '';
    
    $(`#subdist_${key}`).html('<option value="">Pilih Desa</option>');
    if(val) {
        $(`#errCity_${key}`).text('');
        await loadDistricts(val, `dist_${key}`);
    } else {
        $(`#dist_${key}`).html('<option value="">Pilih Kecamatan</option>');
    }
}

async function changeSubDistrict(key, val) { 
    if (array_kecamatan[key] === val) return;
    array_kecamatan[key] = val; 
    array_desa[key] = '';
    
    if(val) {
        $(`#errDist_${key}`).text('');
        await loadSubdistricts(val, `subdist_${key}`);
    } else {
        $(`#subdist_${key}`).html('<option value="">Pilih Desa</option>');
    }
}

function subdistrictChange(key, val) { 
    array_desa[key] = val; 
    if(val) $(`#errSubdist_${key}`).text('');
}

// ==================== EVENT HANDLERS ====================
function instansiChange(key, val) { array_instansi[key] = val; }
function detailAlamatChange(key, val) { array_detail_alamat[key] = val; }
function tanggalPemberangkatanChange(key, val) { array_tanggal_pemberangkatan[key] = val; }
function jamPemberangkatanChange(key, val) { array_jam_pemberangkatan[key] = val; }
function jarakTempuhChange(key, val) { array_jarak_tempuh[key] = val; }

function checkboxChecked(key) {
    let purposes = [];
    $(`.purpose[data-key="${key}"]:checked`).each(function() { purposes.push($(this).val()); });
    array_tujuan_pemberangkatan[key] = purposes.join(',');
    
    if (purposes.length > 0) $(`#errTujuan_${key}`).text('');
    renderDetailTables(key);
}

function renderDetailTables(key) {
    let purposes = array_tujuan_pemberangkatan[key] ? array_tujuan_pemberangkatan[key].split(',') : [];
    let showBarang = purposes.some(p => p.includes('barang'));
    let showTamu = purposes.some(p => p.includes('tamu'));
    let showDinas = purposes.some(p => p.includes('dinas'));
    
    let html = '';
    if (showBarang) {
        html += `<table class="table table-bordered detail-table mt-3"><thead><tr><th colspan="5" class="bg-info">📦 DETAIL BARANG</th></tr><tr class="bg-light"><th>Jenis Barang</th><th>Quantity</th><th>Satuan</th><th>Nama Penerima</th><th>Keterangan</th></tr></thead><tbody><tr>
            <td><input type="text" class="form-control" value="${array_jenis_barang[key] || ''}" oninput="array_jenis_barang[${key}] = this.value"></td>
            <td><input type="number" class="form-control" value="${array_quantity[key] || ''}" oninput="array_quantity[${key}] = this.value"></td>
            <td><input type="text" class="form-control" value="${array_satuan[key] || ''}" oninput="array_satuan[${key}] = this.value"></td>
            <td><input type="text" class="form-control" value="${array_nama_penerima[key] || ''}" oninput="array_nama_penerima[${key}] = this.value"></td>
            <td><input type="text" class="form-control" value="${array_keterangan_barang[key] || ''}" oninput="array_keterangan_barang[${key}] = this.value"></td>
        </tr></tbody></table>`;
    }
    if (showTamu) {
        html += `<table class="table table-bordered detail-table mt-3"><thead><tr><th colspan="2" class="bg-success">👤 DETAIL TAMU</th></tr><tr class="bg-light"><th>Nama Tamu</th><th>Nomor HP</th></tr></thead><tbody><tr>
            <td><input type="text" class="form-control" value="${array_nama_tamu[key] || ''}" oninput="array_nama_tamu[${key}] = this.value"></td>
            <td><input type="text" class="form-control" value="${array_nomor_hp_tamu[key] || ''}" oninput="array_nomor_hp_tamu[${key}] = this.value"></td>
        </tr></tbody></table>`;
    }
    if (showDinas) {
        let karyawanOptions = '';
        @foreach($selectemployee as $emp)
            karyawanOptions += `<option value="{{$emp->enroll_id}}">{{$emp->select_employee}}</option>`;
        @endforeach
        
        let selectedVals = array_karyawan_dinas_luar[key] ? array_karyawan_dinas_luar[key].split(',') : [];
        html += `<table class="table table-bordered detail-table mt-3"><thead><tr><th class="bg-warning">👔 DETAIL KARYAWAN DINAS LUAR</th></tr></thead><tbody><tr>
            <td><select id="dinas_${key}" class="form-control select2-multiple" multiple>${karyawanOptions}</select><div class="text-danger small mt-1" id="errDinas_${key}"></div></td>
        </tr></tbody></table>`;
        
        setTimeout(() => {
            $(`#dinas_${key}`).select2({ width: '100%', placeholder: 'Pilih Karyawan' }).val(selectedVals).trigger('change');
            $(`#dinas_${key}`).on('change', function() { 
                array_karyawan_dinas_luar[key] = $(this).val() ? $(this).val().join(',') : ''; 
                if ($(this).val() && $(this).val().length > 0) $(`#errDinas_${key}`).text('');
            });
        }, 50);
    }
    $(`#detail_${key}`).html(html);
}

// ==================== SET DEFAULT ORIGIN (PT NAG) ====================
async function setDefaultOrigin() {
    $('#originInstansi').val('PT. Nirwana Alabare Garment');
    $('#originDetailAddress').val('Jl. Raya Majalaya - Rancaekek No.289');
    
    await loadProvinces(); // Make sure base provinces are loaded
    $('#originProvince').val(12).trigger('change.select2');
    
    await loadCities(12, 'originCity');
    $('#originCity').val(161).trigger('change.select2');
    
    await loadDistricts(161, 'originDistrict');
    $('#originDistrict').val(2196).trigger('change.select2');
    
    await loadSubdistricts(2196, 'originSubdistrict');
    $('#originSubdistrict').val(30109).trigger('change.select2');
}

// ==================== VALIDASI & SUBMIT ====================
function saveChanges() {
    // Clear old errors
    $('.text-danger').text(''); 

    let valid = true;
    let employee = $('#selectEmployee').val();
    
    if (!employee || employee.length === 0) { 
        $('#employeeError').text('Pilih setidaknya satu karyawan'); 
        valid = false; 
    }

    // Origin Validations
    if (!$('#originProvince').val() || !$('#originCity').val() || !$('#originDistrict').val() || !$('#originSubdistrict').val() || !$('#originDetailAddress').val()) { 
        iziToast.warning({ message: 'Alamat Asal belum lengkap dipilh/diisi' }); 
        valid = false; 
    }
    if (!$('#originDate').val() || !$('#originTime').val()) { 
        iziToast.warning({ message: 'Tanggal dan Jam keberangkatan asal harus diisi' }); 
        valid = false; 
    }

    // Destinations Validations
    $.each(array_provinsi, (i, v) => {
        if (!v) { $(`#errProv_${i}`).text('Pilih provinsi'); valid = false; }
        if (!array_kota[i]) { $(`#errCity_${i}`).text('Pilih kota'); valid = false; }
        if (!array_kecamatan[i]) { $(`#errDist_${i}`).text('Pilih kecamatan'); valid = false; }
        if (!array_desa[i]) { $(`#errSubdist_${i}`).text('Pilih desa'); valid = false; }
        
        if (!array_detail_alamat[i]) valid = false;
        if (!array_tanggal_pemberangkatan[i]) valid = false;
        if (!array_jam_pemberangkatan[i]) valid = false;
        
        if (!array_tujuan_pemberangkatan[i]) { 
            $(`#errTujuan_${i}`).text('Tujuan perjalanan wajib dipilih (minimal 1)'); 
            valid = false; 
        } else {
            let purposes = array_tujuan_pemberangkatan[i].split(',');
            if (purposes.some(p => p.includes('barang')) && (!array_jenis_barang[i] || !array_quantity[i] || !array_satuan[i])) valid = false;
            if (purposes.some(p => p.includes('tamu')) && (!array_nama_tamu[i] || !array_nomor_hp_tamu[i])) valid = false;
            if (purposes.some(p => p.includes('dinas')) && (!array_karyawan_dinas_luar[i])) { 
                $(`#errDinas_${i}`).text('Pilih karyawan dinas luar'); 
                valid = false; 
            }
        }
    });

    if (!valid) { 
        iziToast.error({ message: 'Mohon lengkapi semua baris data berwarna merah / wajib diisi' }); 
        return; 
    }

    let formData = {
        employee: employee.join(','),
        provinsi: $('#originProvince').val(), cities: $('#originCity').val(), districts: $('#originDistrict').val(),
        sub_districts: $('#originSubdistrict').val(), instansi: $('#originInstansi').val(), detail_alamat: $('#originDetailAddress').val(),
        tanggal_pemberangkatan: $('#originDate').val(), jam_pemberangkatan: $('#originTime').val(),
        
        tujuan_pemberangkatan_array: array_tujuan_pemberangkatan, provinsi_array: array_provinsi, city_array: array_kota,
        kecamatan_array: array_kecamatan, desa_array: array_desa, instansi_array: array_instansi, detail_alamat_array: array_detail_alamat,
        tanggal_pemberangkatan_array: array_tanggal_pemberangkatan, jam_pemberangkatan_array: array_jam_pemberangkatan,
        jarak_tempuh_array: array_jarak_tempuh, jenis_barang_array: array_jenis_barang, quantity_array: array_quantity,
        satuan_array: array_satuan, nama_penerima_array: array_nama_penerima, keterangan_barang_array: array_keterangan_barang,
        nama_tamu_array: array_nama_tamu, nomor_hp_tamu_array: array_nomor_hp_tamu, karyawan_dinas_luar_array: array_karyawan_dinas_luar,
        _token: config.csrfToken
    };

    let $btnSubmit = $('button[onclick="saveChanges()"]');
    
    $.ajax({
        type: "POST", 
        url: config.routes.store, 
        data: formData,
        beforeSend: () => { 
            $btnSubmit.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...'); 
        },
        success: () => { 
            iziToast.success({ message: 'Permintaan terkirim!' }); 
            setTimeout(() => window.location.href = 'data_pengajuan_transportasi_admin', 1500); 
        },
        error: () => { 
            iziToast.error({ message: 'Gagal mengirim permintaan' }); 
            $btnSubmit.prop('disabled', false).html('<i class="fa fa-send"></i> KEMBALI SIMPAN'); 
        }
    });
}
</script>
@endsection