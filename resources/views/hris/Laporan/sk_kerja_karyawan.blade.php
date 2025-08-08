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
    <table class="purchase-order" width="100%" style="margin-top:11px;">
        <thead>
            <tr>
                <td style="vertical-align: middle; text-align: center;">
                    <img height="52" src="{{ public_path('assets/image/header-kop.png') }}" alt="">
                </td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="font-family:Arial, Helvetica, sans-serif; line-height: 12px;" >
        <thead>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center; font-weight:700; font-size: 8.5pt; margin:0; padding:0;">Jl. Raya Rancaekek – Majalaya No. 289</td>
            </tr>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center; font-weight:700; font-size: 8.5pt;  margin:0; padding:0;">Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung 40382</td>
            </tr>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center; font-weight:700; font-size: 8.5pt; margin:0; padding:0;">Telp. 022-85962081</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="margin-top: -3px;">
        <thead>
            <tr>
                <td style="height:2px; background-color: #000;"></td>
            </tr>
        </thead>
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
            <td colspan="6" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top" align="center"><b>NO. {{$no_surat ? $no_surat.'/' : $value->no_surat.'/'}}{{$no_form}}</b></td>
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
            <td width="56%" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Mega Fitriana Haryono</td>
            <td width="10%"></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;"></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top">Jabatan</td>
            <td style="vertical-align:top">:</td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top">Chief HRD</td>
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
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">NIP</td>
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
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify" colspan="4"><b><u>Mega Fitriana Haryono</u></b></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify" colspan="4"><b>Chief HRD</b></td>
            <td></td>
        </tr>
        <table width="562">
            <thead >
                <tr>
                    <td style="border-bottom:1px solid black;" width="80%"></td>
                    <img src="data:image/png;base64,{{ \DNS2D::getBarcodePNG(url('hris/identity/card_employee_form_identity') . '?enroll_id=' . $value->enroll_id . '&no_form=' . urlencode($no_form) . '&type=SK_KERJA', 'QRCODE') }}" alt="barcode" style="width: 60px; height: 60px;background-color:white" />
                </tr>
                <tr>
                    <td style="height:24px"></td>
                </tr>
            </thead>
        </table>
        @endforeach
    </thead>
    </table>
</body>
</html>
