<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>
    <style>
        table {
            border-collapse: collapse;
        }
        @page { margin: 20px 20px 40px 20px; }
    </style>
</head>

<body>
    <table width="100%">
        <thead>
            <tr>
                <td width="100px" style="vertical-align: middle; text-align: center;border: 1px solid;" colspan="2" rowspan="4">
                    <img height="60" src="{{ public_path('assets/images/brand/nirwana logo.jpg') }}" alt="">
                </td>
                <td style="vertical-align: middle; font-size: 12pt; text-align: center; font-weight: 800;border: 1px solid;" colspan="8" rowspan="4">FORM PENGAJUAN PERBAIKAN KENDARAAN</td>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Kode Dokumen</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: F.16.HR.NAG.P-03.F-01.01</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: 1</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Tanggal Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: 26 April 2022</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid;border-bottom: 1px solid; font-size: 9pt;">Tanggal Efektif</td>
                <td colspan="3" class="border-right" style="border: 1px solid; border-bottom: 1px solid; font-size: 9pt;">: 27 April 2022</td>
            </tr>
        </thead>
    </table>
    @foreach ($data as $key=>$value)
    <table width="100%" style="padding-top:8px">
        <thead>
            <tr>
                <td style="height:10px"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top" width="17%">Tanggal Pengajuan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top" width="42%">{{Carbon\Carbon::parse($value->tanggal)->translatedFormat('d F Y')}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top" width="16%"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top" width="1%"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="height:10px"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">Kendaraan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top">{{$value->vehicle_merk}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;vertical-align:top"></td>
            </tr>
        </thead>
    </table>
    <table>
        
        <tr>
            <td style="height:10px"></td>
        </tr>
    </table>
    @endforeach
    <table width="100%" style="padding-top:8px" border="1">
        <thead>
            <tr>
                <td width="50px">No</td>
                <td>Nama Barang</td>
                <td>Quantity</td>
                <td>Harga</td>
                <td>Total Harga Satuan</td>
            </tr>
            @foreach ($data2 as $key=>$value)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$value->nama_barang}}</td>
                <td>{{$value->quantity}}</td>
                <td>{{$value->price}}</td>
                <td>{{$value->price*$value->quantity}}</td>
            </tr>
            @endforeach
            @foreach ($data3 as $key=>$value)
            <tr>
                <td colspan="4">Total Harga</td>
                <td>{{$value->price_unit}}</td>
            </tr>
            @endforeach
        </thead>
    </table>
    <table width="100%" style="padding-top:8px;">
        <thead>
            <tr>
                <td style="height:20px"></td>
            </tr>
            <tr>
                <td width="37%"></td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center" >Dibuat</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">Disetujui</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">Diketahui</td>
            </tr>
            <tr>
                <td></td>
                <td style="height: 55px;border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
            </tr>
            <tr>
                <td></td>
                <td style="border:1px solid black;text-align:center" ></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">Manager</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">General Affair</td>
            </tr>
        </thead>
    </table>
</body>
</html>