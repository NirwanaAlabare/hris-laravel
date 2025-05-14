<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>
    <style>
        @page { margin: 70px 100px 0px 100px; }
    </style>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; font-size: 10pt; display: flex; flex-direction: column; justify-content: center; align-items: center;">

    <table width="100%" style=" line-height: 8px;">
        <tr>
            <td align="center" width="20%">
                <img src="{{ public_path('assets/images/brand/nirwana logo.jpg') }}" width="100" height="65">
            </td>
            <td style="vertical-align:middle; line-height: 0px;">
                <p style="font-size: 15pt; font-weight: bold;">PT. NIRWANA ALABARE GARMENT</p>
                <p style="font-size: 10pt; font-weight: 700;">Kompensasi PKWT</p>
            </td>
        </tr>
    </table>
    <table width="50%" style=" margin-top: 10px; font-style: italic;">
        <tr>
            <td align="left">Keterangan</td>
            <td align="center">Rincian</td>
        </tr>
    </table>
    <table width="100%" style="border-bottom: 2px solid black;">
    </table>
    <table width="100%" style="border-bottom: 2px solid black;">
    </table>


    <table width="100%" style="margin-top: 10px;">
        <tr>
            <td>{{$data->employee_name}}</td>
        </tr>
        <tr>
            <td>{{$data->nik}}</td>
        </tr>
        <tr>
            <td>{{$data->department_name}}</td>
        </tr>
        <tr>
            <td>Ket : Perpanjangan Kontrak</td>
        </tr>

        </tr>
    </table>
    <div style=" display: flex; flex-direction: column; justify-content: center; align-items: center; margin-left:200px;">
        <table width="100%" style="margin-top: 0px;">
            <tr>
                <td align="left">Awal Kontrak</td>
                <td align="right">{{Carbon\Carbon::parse($data->contract)->translatedFormat('d F Y')}}</td>
            </tr>
            <tr>
                <td align="left">Akhir Kontrak</td>
                <td align="right">{{Carbon\Carbon::parse($data->contract_end)->translatedFormat('d F Y')}}</td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td align="left">Gaji Pokok</td>
                <td align="right">{{number_format($umk, 0, '.', '.');}}</td>
            </tr>
            <tr>
                <td align="left">TMK</td>
                <td align="right">{{number_format($tunjangan, 0, '.', '.');}}</td>
            </tr>
            <tr>
                <td><hr></td>
            </tr>
            <tr>
                <td>Perhitungan Kompensasi :</td>
            </tr>
            <tr>
                <td>Masa Kerja (Bulan)</td>
                <td align="right">{{$jumlah_bulan}}</td>
            </tr>
            <tr>
                <td>Rp. Kompensasi</td>
                <td align="right">{{number_format($total_kompensasi, 0, '.', '.');}}</td>
            </tr>
            <tr>
                <td><br><br><br><br><br><br><br><br><br></td>
            </tr>
            <tr>
                <td>Tanggal diterima :</td>
                <td align="right">{{Carbon\Carbon::parse($data->contract_end)->translatedFormat('d F Y')}}</td>
            </tr>
            <tr>
                <td>Tanda Tangan</td>
            </tr>
            <tr>
                <td><br><br><br><br><br><br></td>
            </tr>
            <tr>
                <td style="font-weight: bold; font-size: 9pt;">{{$data->employee_name}}</td>
              </tr>
              <tr>
                <td style="border-bottom: 1px solid black; width: 200px; margin: 0 auto;"></td>
              </tr>
        </table>
    </div>
</body>
</html>
