<!DOCTYPE html>
<html lang="en">

<table class="table">
    <tr>
        <td colspan="5" style="font-weight: bold;font-size:13pt;">PT. NIRWANA ALABARE GARMENT</td>
    </tr>
    <tr>
        <td colspan="5" style="font-weight: bold;font-size:11pt;">Rekap Karyawan Lembur</td>
    </tr>
    <tr>
        <td colspan="5">{{Carbon\Carbon::parse($date_from)->translatedFormat('l, d F Y')}}</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td style="vertical-align:middle;font-weight:bold;background-color:#b8cce4;border:1px solid black;">Keterangan</td>
        <td style="vertical-align:middle;font-weight:bold;background-color:#b8cce4;border:1px solid black;">Department</td>
        <td align="center" style="vertical-align:middle;font-weight:bold;background-color:#b8cce4;border:1px solid black;" width="12">NON STAFF</td>
        <td align="center" style="vertical-align:middle;font-weight:bold;background-color:#b8cce4;border:1px solid black;" width="12">STAFF</td>
        <td style="text-align:center;font-weight:bold;background-color:#b8cce4;border:1px solid black;word-wrap:break-word;" width="13">JUMLAH KARYAWAN</td>
    </tr>
    @foreach ($overtime as $data)
    <tr>
        <td style="border:1px solid black;" width="13">{{$data->shift}}</td>
        <td style="border:1px solid black;" width="27">{{$data->department}}</td>
        <td align="center" style="border:1px solid black;">{{$data->non_staff}}</td>
        <td align="center" style="border:1px solid black;">{{$data->staff}}</td>
        <td align="center" style="border:1px solid black;">{{$data->jumlah}}</td>
    </tr>
    @endforeach
    @foreach ($overtime2 as $data)
    <tr>
        <td style="border:1px solid black;background-color:yellow;font-weight:bold" colspan="2">LEMBUR TOTAL</td>
        <td align="center" style="border:1px solid black;background-color:yellow;font-weight:bold">{{$data->non_staff}}</td>
        <td align="center" style="border:1px solid black;background-color:yellow;font-weight:bold">{{$data->staff}}</td>
        <td align="center" style="border:1px solid black;background-color:yellow;font-weight:bold">{{$data->jumlah}}</td>
    </tr>
    @endforeach
    @foreach ($overtime3 as $data)
    <tr>
        <td style="border:1px solid black;">{{$data->shift}}</td>
        <td style="border:1px solid black;">{{$data->department}}</td>
        <td align="center" style="border:1px solid black;">{{$data->non_staff}}</td>
        <td align="center" style="border:1px solid black;">{{$data->staff}}</td>
        <td align="center" style="border:1px solid black;">{{$data->jumlah}}</td>
    </tr>
    @endforeach
    @foreach ($overtime4 as $data)
    <tr>
        <td style="border:1px solid black;background-color:yellow;font-weight:bold" colspan="2">SHIFT MALAM TOTAL</td>
        <td align="center" style="border:1px solid black;background-color:yellow;font-weight:bold">{{$data->non_staff}}</td>
        <td align="center" style="border:1px solid black;background-color:yellow;font-weight:bold">{{$data->staff}}</td>
        <td align="center" style="border:1px solid black;background-color:yellow;font-weight:bold">{{$data->jumlah}}</td>
    </tr>
    @endforeach
    @foreach ($overtime5 as $data)
    <tr>
        <td style="border:1px solid black;background-color:#ffbb00;font-weight:bold" colspan="2">GRAND TOTAL</td>
        <td align="center" style="border:1px solid black;background-color:#ffbb00;font-weight:bold">{{$data->non_staff}}</td>
        <td align="center" style="border:1px solid black;background-color:#ffbb00;font-weight:bold">{{$data->staff}}</td>
        <td align="center" style="border:1px solid black;background-color:#ffbb00;font-weight:bold">{{$data->jumlah}}</td>
    </tr>
    @endforeach
    <tr>
        <td></td>
    </tr>
</table>
</html>