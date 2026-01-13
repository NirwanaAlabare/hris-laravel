{{-- <!DOCTYPE html>
<html lang="en">

<table class="table">
    <tr>
        <td>PT. NIRWANA ALABARE GARMENT</td>
    </tr>
    <tr>
        <td>BIAYA MAKAN  KARYAWAN</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>Periode</td>
        <td>Hari</td>
        <td width="15">Tanggal Lembur</td>
        <td>Department</td>
        <td>Staff / Non Staff</td>
        <td>Jumlah Karyawan</td>
        <td>Harga</td>
        <td>Jumlah</td>
        <td>Keterangan</td>
    </tr>
    @foreach($data_lembur as $data)
    <tr>
        <td>{{$data->periode}}</td>
        <td>{{Carbon\Carbon::parse($data->tanggal)->translatedFormat('l')}}</td>
        <td>{{PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($data->tanggal)}}</td>
        <td>{{$data->department}}</td>
        <td>{{$data->staff_non_staff}}</td>
        <td>{{$data->jumlah_karyawan}}</td>
        <td>{{$data->harga}}</td>
        <td>{{$data->jumlah}}</td>
        <td>{{$data->shift}}</td>
    </tr>
    @endforeach
</table>
</html> --}}

<!DOCTYPE html>
<html lang="en">

<table class="table">
    <tr>
        <td>PT. NIRWANA ALABARE GARMENT</td>
    </tr>
    <tr>
        <td>BIAYA MAKAN  KARYAWAN</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>Periode</td>
        <td>Hari</td>
        <td width="15">Tanggal Lembur</td>
        <td>Department</td>
        <td>Bagian</td>
        <td>Staff / Non Staff</td>
        <td>Jumlah Karyawan</td>
        <td>Harga</td>
        <td>Jumlah</td>
        <td>Keterangan</td>
    </tr>
    @foreach($data_lembur as $data)
    <tr>
        <td>{{$data->periode}}</td>
        <td>{{Carbon\Carbon::parse($data->tanggal)->translatedFormat('l')}}</td>
        <td>{{PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($data->tanggal)}}</td>
        <td>{{$data->department}}</td>
        <td>{{$data->sub_dept_name}}</td>
        <td>{{$data->staff_non_staff}}</td>
        <td>{{$data->jumlah_karyawan}}</td>
        <td>{{$data->harga}}</td>
        <td>{{$data->jumlah}}</td>
        <td>{{$data->shift}}</td>
    </tr>
    @endforeach
</table>
</html>
