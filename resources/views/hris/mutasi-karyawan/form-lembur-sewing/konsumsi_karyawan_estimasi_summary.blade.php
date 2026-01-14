<!DOCTYPE html>
<html lang="en">
    <table class="table">
        <tr>
            <td style="font-size: 14pt;font-weight:bold">PT. NIRWANA ALABARE GARMENT</td>
        </tr>
        <tr>
            <td>Laporan Makan Harian</td>
        </tr>
        <tr>
            <td>Periode {{Carbon\Carbon::parse($from)->translatedFormat('l, j F Y')}} - {{Carbon\Carbon::parse($to)->translatedFormat('l, j F Y')}}</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td colspan="1" rowspan="2" style="vertical-align: middle;font-weight:bold;background-color:#b8cce4;border:1px solid black;">Keterangan</td>
            <td colspan="1" rowspan="2" style="vertical-align: middle;font-weight:bold;background-color:#b8cce4;border:1px solid black;">Departemen</td>
            <td colspan="1" rowspan="2" style="vertical-align: middle;font-weight:bold;background-color:#b8cce4;border:1px solid black;">Bagian</td>
            <td colspan="3" align="center" style="vertical-align: middle;font-weight:bold;background-color:#b8cce4;border:1px solid black;">NON STAFF</td>
            <td colspan="3" align="center" style="vertical-align: middle;font-weight:bold;background-color:#b8cce4;border:1px solid black;">STAFF</td>
            <td colspan="2" align="center" style="vertical-align: middle;font-weight:bold;background-color:#b8cce4;border:1px solid black;">GRAND TOTAL</td>
        </tr>
        <tr>
            <td style="vertical-align: middle;text-align:center;font-weight:bold;background-color:#b8cce4;border:1px solid black;word-wrap:break-word;">JUMLAH KRY NON STAFF</td>
            <td style="vertical-align: middle;text-align:center;font-weight:bold;background-color:#b8cce4;border:1px solid black;word-wrap:break-word;">HARGA</td>
            <td style="vertical-align: middle;text-align:center;font-weight:bold;background-color:#b8cce4;border:1px solid black;word-wrap:break-word;">JUMLAH</td>
            <td style="vertical-align: middle;text-align:center;font-weight:bold;background-color:#b8cce4;border:1px solid black;word-wrap:break-word;">JUMLAH KRY STAFF</td>
            <td style="vertical-align: middle;text-align:center;font-weight:bold;background-color:#b8cce4;border:1px solid black;word-wrap:break-word;">HARGA</td>
            <td style="vertical-align: middle;text-align:center;font-weight:bold;background-color:#b8cce4;border:1px solid black;word-wrap:break-word;">JUMLAH</td>
            <td style="vertical-align: middle;text-align:center;font-weight:bold;background-color:#b8cce4;border:1px solid black;word-wrap:break-word;">JUMLAH KARYAWAN</td>
            <td style="vertical-align: middle;text-align:center;font-weight:bold;background-color:#b8cce4;border:1px solid black;word-wrap:break-word;">TOTAL</td>
        </tr>
        @foreach($data_lembur1 as $data)
        <tr>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;" width="14">{{$data->shift}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;" width="23">{{$data->department}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;" width="23">{{$data->sub_dept_name}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;" width="13">{{$data->non_staff}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;" width="13">{{$data->harga}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;" width="13">{{$data->jumlah}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;" width="13">{{$data->staff}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;" width="13">{{$data->harga2}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;" width="13">{{$data->jumlah2}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;" width="13">{{$data->jumlah_karyawan}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;" width="13">{{$data->total}}</td>
        </tr>
        @endforeach
        @foreach($data_lembur3 as $data)
        <tr>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;" colspan="3">{{$data->shift}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->non_staff}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->harga}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->jumlah}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->staff}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->harga2}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->jumlah2}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->jumlah_karyawan}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->total}}</td>
        </tr>
        @endforeach
        @foreach($data_lembur2 as $data)
        <tr>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;">{{$data->shift}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;">{{$data->department}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;">{{$data->sub_dept_name}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;">{{$data->non_staff}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;">{{$data->harga}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;">{{$data->jumlah}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;">{{$data->staff}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;">{{$data->harga2}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;">{{$data->jumlah2}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;">{{$data->jumlah_karyawan}}</td>
            <td style="vertical-align: middle;border:1px solid black;word-wrap:break-word;">{{$data->total}}</td>
        </tr>
        @endforeach
        @foreach($data_lembur4 as $data)
        <tr>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;" colspan="3">{{$data->shift}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->non_staff}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->harga}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->jumlah}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->staff}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->harga2}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->jumlah2}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->jumlah_karyawan}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#fffc04;border:1px solid black;">{{$data->total}}</td>
        </tr>
        @endforeach
        @foreach($data_lembur5 as $data)
        <tr>
            <td style="vertical-align: middle;font-weight:bold;background-color:#ffc404;border:1px solid black;" colspan="3">{{$data->shift}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#ffc404;border:1px solid black;">{{$data->non_staff}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#ffc404;border:1px solid black;">{{$data->harga}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#ffc404;border:1px solid black;">{{$data->jumlah}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#ffc404;border:1px solid black;">{{$data->staff}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#ffc404;border:1px solid black;">{{$data->harga2}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#ffc404;border:1px solid black;">{{$data->jumlah2}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#ffc404;border:1px solid black;">{{$data->jumlah_karyawan}}</td>
            <td style="vertical-align: middle;font-weight:bold;background-color:#ffc404;border:1px solid black;">{{$data->total}}</td>
        </tr>
        @endforeach
    </table>
</html>
