<!DOCTYPE html>
<html lang="en">

<table class="table">
    <tr>
        <td>PT. NIRWANA ALABARE GARMENT</td>
    </tr>
    <tr>
        <td>Laporan Makan Harian</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td colspan="2" rowspan="2" style="vertical-align: middle">Keterangan</td>
        <td colspan="3" align="center">NON STAFF</td>
        <td colspan="3" align="center">STAFF</td>
        <td colspan="2" align="center">GRAND TOTAL</td>
    </tr>
    <tr>
        <td>JUMLAH KRY NON STAFF</td>
        <td>HARGA</td>
        <td>JUMLAH</td>
        <td>JUMLAH KRY STAFF</td>
        <td>HARGA</td>
        <td>JUMLAH</td>
        <td>JUMLAH KARYAWAN</td>
        <td>TOTAL</td>
    </tr>
    @foreach($data_lembur as $data)
    <tr>
        <td>{{$data->shift}}</td>
        <td>{{$data->department}}</td>
        <td>{{$data->non_staff}}</td>
        <td>{{$data->harga}}</td>
        <td>{{$data->jumlah}}</td>
        <td>{{$data->staff}}</td>
        <td>{{$data->harga2}}</td>
        <td>{{$data->jumlah2}}</td>
        <td>{{$data->jumlah_karyawan}}</td>
        <td>{{$data->total}}</td>
    </tr>
    @endforeach
</table>
</html>