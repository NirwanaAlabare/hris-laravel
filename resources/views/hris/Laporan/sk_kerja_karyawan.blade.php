<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>
    <style>
        @page { margin: 25px 60px 60px 60px; }
    </style>
</head>

<body>
    <table width="506" style="border-bottom: 2px solid black">
        <tr>
            <td width="10%"></td>
            <td width="13%" align="right"><img src="{{ public_path('assets/images/brand/logo.jpg') }}" width="42"></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:18pt;font-weight:bold">PT NIRWANA ALABARE GARMENT</td>
            <td width="10%"></td>
        </tr>
        <tr>
            <td></td>
            <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;font-weight:bold">Jl. Raya Rancaekek – Majalaya No. 289</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;font-weight:bold">Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung 40382</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;font-weight:bold">Telp. +62 22 8596 2076 / +62 22 8596 2081</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;color:blue"><i>https://nirwanagroup.co.id</i></td>
            <td></td>
        </tr>
    </table>
    <table width="506">
    <thead>
        @foreach ($data as $key=>$value)
        <?php 
        $tanggal_akhir='';
        if($value->status_aktif=='AKTIF'){
            $tanggal_akhir='SEKARANG';
        }else{
            $tanggal_akhir=Carbon\Carbon::parse($value->tanggal_resign)->translatedFormat('d F Y');
        }
        ?>
        <tr>
            <td colspan="6" style="height: 10"></td>
        </tr>
        <tr>
            <td colspan="6" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top" align="center"><b><u>SURAT KETERANGAN KERJA</u></b></td>
        </tr>
        <tr>
            <td colspan="6" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top" align="center"><b>{{$no_form}}</b></td>
        </tr>
        <tr>
            <td colspan="6" style="height: 10"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Yang bertanda tangan di bawah ini :</td>
            <td></td>
        </tr>
        <tr>
            <td width="10%"></td>
            <td width="5%"></td>
            <td width="31%" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Nama</td>
            <td width="3%">:</td>
            <td width="56%" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Rudy Aristian Fajar</td>
            <td width="10%"></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;"></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top">Jabatan</td>
            <td style="vertical-align:top">:</td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top">HR - GA Department</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="6" style="height: 10"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Menerangkan dengan sesungguhnya bahwa :</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Nama</td>
            <td>:</td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value->employee_name}}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">NIK</td>
            <td>:</td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value->nik}}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top">Alamat</td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top">:</td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top">{{$value->alamat_rumah}}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="6" style="height: 15"></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify" colspan="4">Adalah benar karyawan <b>{{$value->site_nirwana_name}}</b> dengan jabatan sebagai <b>{{$value->status_jabatan}} {{$value->department_name}}</b> yang bekerja sejak <b>{{Carbon\Carbon::parse($value->join_date)->translatedFormat('d F Y')}}</b> sampai dengan <b>{{$tanggal_akhir}}</b>.</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="6" style="height:2"></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify" colspan="4">Demikian Surat Keterangan ini dibuat untuk {{$reason}}.</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="6" style="height:100"></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify" colspan="4">Bandung, {{Carbon\Carbon::parse(date('Y-m-d'))->translatedFormat('d F Y')}}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify" colspan="4">PT. Nirwana Alabare Garment</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="6" style="height:80"></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify" colspan="4"><b><u>Rudy Aristian Fajar</u></b></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify" colspan="4"><b>HRGA - Compliance Manager</b></td>
            <td></td>
        </tr>
        @endforeach
    </thead>
    </table>
</body>
</html>