
@php
    use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>
    <style>
        @page { margin: 20px 45px 0px 45px; }
        #watermark {
            position: fixed;
            top: 30%;
            width: 100%;
            text-align: center;
            opacity: .3;
            transform-origin: 50% 50%;
            z-index: -1000;
        }
    </style>
</head>
@foreach($data as $value)
<?php

    $tanggal_masuk = $value['join_date'];
    $tanggal_resign = $value['tanggal_resign'];
    $selisih_tahun = date_diff(date_create($tanggal_masuk), date_create($tanggal_resign))->y;
?>
@if($value['tipe_surat']=='SK Kerja')
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
        <?php
        $tanggal_akhir='';
        if($value['status_aktif']=='AKTIF'){
            $tanggal_akhir='SEKARANG';
        }else{
            $tanggal_akhir=Carbon\Carbon::parse($value['tanggal_resign'])->translatedFormat('d F Y');
        }
        ?>
        <tr>
            <td colspan="6" style="height: 10"></td>
        </tr>
        <tr>
            <td colspan="6" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top" align="center"><b><u>SURAT KETERANGAN KERJA</u></b></td>
        </tr>
        <tr>
            <td colspan="6" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top" align="center"><b>NO. {{$value['no_surat']}}/HRD/NAG-REF/{{$no_form}}{{ $value['catatan'] ? ('/' . Str::substr($value['catatan'], 0, 3)) : '' }}</b></td>
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
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value['employee_name']}}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">NIP</td>
            <td>:</td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value['nik']}}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top">Alamat</td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top">:</td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top">{{$value['alamat_rumah']}}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="6" style="height: 15"></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify" colspan="4">Adalah benar karyawan <b>{{$value['site_nirwana_name']}}</b> dengan jabatan sebagai <b>{{$value['status_jabatan']}} {{$value['department_name']}}</b> yang bekerja sejak <b>{{Carbon\Carbon::parse($value['join_date'])->translatedFormat('d F Y')}}</b> sampai dengan <b>{{$tanggal_akhir}}</b>.</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="6" style="height:2"></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify" colspan="4">Demikian Surat Keterangan ini dibuat untuk pencairan BPJS.</td>
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
    </thead>
    </table>
    <table width="562">
      <thead >
          <tr>
              <td style="border-bottom:1px solid black;" width="80%"></td>
              <img src="data:image/png;base64,{{ \DNS2D::getBarcodePNG(url('hris/identity/card_employee_form_identity') . '?enroll_id=' . $value['enroll_id'] . '&no_form=' . urlencode($no_form) . '&type=SK_KERJA', 'QRCODE') }}" alt="barcode" style="width: 60px; height: 60px;background-color:white" />
          </tr>
          <tr>
              <td style="height:24px"></td>
          </tr>
      </thead>
  </table>
</body>
@else
<body>
    <table width="506" style="border-bottom: 2px solid black">
        <div id="watermark">
            <img src="{{ public_path('assets/images/brand/nirwana logo.jpg') }}" width="460">
        </div>
        <tr>
            <td width="10%"></td>
            <td width="13%" align="right"><img src="{{ public_path('assets/images/brand/nirwana logo.jpg') }}" width="42"></td>
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
            <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;font-weight:bold">Telp. 022-85962081</td>
            <td></td>
        </tr>
    </table>
    <table width="506">
        <thead>
            <?php
            $tanggal_akhir='';
            if($value['status_aktif']=='AKTIF'){
                $tanggal_akhir='SEKARANG';
            }else{
                $tanggal_akhir=Carbon\Carbon::parse($value['tanggal_resign'])->translatedFormat('d F Y');
            }
            ?>
            <tr>
                <td colspan="5" style="height: 9"></td>
            </tr>
            <tr>
                <td colspan="5" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top;font-size:14pt" align="center"><b><u>CERTIFICATE OF EMPLOYMENT</u></b></td>
            </tr>
            <tr>
                <td colspan="5" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;vertical-align:top;font-size:14pt" align="center"><b>NO. {{$value['no_surat']}}/HRD/NAG-PK/{{$no_form}}{{ $value['catatan'] ? ('/' . Str::substr($value['catatan'], 0, 3)) : '' }}</b></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 13"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Dengan ini menerangkan bahwa :</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>This Certificate that</i></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td width="4%"></td>
                <td width="31%" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><u>Nama</u></td>
                <td width="3%">:</td>
                <td width="73%" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value['employee_name']}}</td>
                <td width="4%"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>Name</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 4"></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><u>No. Pegawai</u></td>
                <td>:</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value['nik']}}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>Reg. No.</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 4"></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><u>Bagian</u></td>
                <td>:</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value['sub_dept_name']}}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>Section</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 4"></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><u>Departemen</u></td>
                <td>:</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value['department_name']}}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>Department</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 4"></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><u>Jabatan Terakhir</u></td>
                <td>:</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value['status_jabatan']}}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>Last Position</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 4"></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><u>Lamanya Bekerja</u></td>
                <td>:</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{Carbon\Carbon::parse($value['join_date'])->translatedFormat('d F Y')}} s/d {{$tanggal_akhir}}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>Length of Service</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 4"></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><u>Sebab Berhenti Bekerja</u></td>
                <td>:</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">{{$value['sebab_resign']}}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><i>Reason Of Termination</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 4"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify">Kami mengucapkan terima kasih atas usaha dan kerjasama yang telah saudara sumbangkan kepada perusahaan. </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify"><i>We express our sincere thanks for your efforts and cooperation extended to company.</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify">Semoga keberhasilan dan kesuksesan senantiasa menyertai saudara di waktu mendatang.</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:justify"><i>Wishing you achievement and success always accompanying in the future.</i></td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">Majalaya, {{Carbon\Carbon::parse(date('Y-m-d'))->translatedFormat('d F Y')}}</td>
            </tr>
            <tr>
                <td colspan="5" style="height: 8"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">PT. Nirwana Alabare Garment</td>
            </tr>
            <tr>
                <td colspan="5" style="height: 55"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><b><u>Rudy Aristian Fajar</u></b></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif"><b>HR & GA Manager</b></td>
            </tr>
            <table width="562">
                <thead >
                    <tr>
                        <td style="border-bottom:1px solid black;" width="85%"></td>
                        <td rowspan="2"><img src="data:image/png;base64,{{ \DNS2D::getBarcodePNG(url('hris/identity/card_employee_form_identity') . '?enroll_id=' . $value['enroll_id'] . '&no_form=' . urlencode($value['no_surat'] . "/HRD/NAG-PK/" . $no_form)  . '&type=PAKLARING', 'QRCODE') }}"  alt="barcode" style="width: 60px; height: 60px;background-color:white" /></td>
                    </tr>
                    <tr>
                        <td style="height:24px"></td>
                    </tr>
                </thead>
            </table>
        </thead>
    </table>
</body>
@endif
@endforeach
</html>
