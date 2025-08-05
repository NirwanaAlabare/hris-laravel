<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Document</title>
    <style>
         body {
            font-family: Arial, sans-serif; /* Menggunakan font yang umum */
            font-size: 10px;
            letter-spacing: 1px;
        }
        table {
            border-collapse: collapse;
        }
        .purchase-order {
            height: auto;
            margin: 0;
            padding-left: 5mm;
            padding-right: 5mm;
            padding-top: 0px;
        }
    </style>
</head>
@foreach ($data as $key=>$value)
<body>
    <table class="purchase-order" width="100%" style="border-top: 1px solid; border-left: 1px solid;">
        <thead>
            <tr>
                <td width="100px" style="vertical-align: middle; text-align: center;border-bottom: 1px solid;" colspan="2" rowspan="4">
                    <img height="60" src="{{ public_path('assets/images/brand/nirwana logo.jpg') }}" alt="">
                </td>
                <td style="vertical-align: middle; font-size: 12pt; text-align: center; font-weight: 800;border-bottom: 1px solid; border-left: 1px solid;" colspan="8" rowspan="4">FORM PENGAJUAN TINDAKAN PENDISIPLINAN</td>
                <td colspan="2" class="border-left" style="border-left: 1px solid; font-size: 7.5pt; height: 18px;">Kode Dokumen</td>
                <td colspan="3" class="border-right" style="border-left: 1px solid; font-size: 7.5pt; border-right: 1px solid;">: F.16.HR.NAG.P-06.F-01.01</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 7.5pt; height: 18px;">Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 7.5pt;">: 02</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 7.5pt; height: 18px;">Tanggal Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 7.5pt;">: 25 Maret 2022</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid;border-bottom: 1px solid; font-size: 7.5pt; height: 18px;">Tanggal Berlaku</td>
                <td colspan="3" class="border-right" style="border: 1px solid; border-bottom: 1px solid; font-size: 7.5pt;">: 28 Maret 2022</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:10px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 9pt; width:20%;">Tanggal Pengajuan</td>
                <td style="vertical-align: middle; font-size: 9pt; width:1%;">:</td>
                <td style="vertical-align: middle; font-size: 11pt; padding-left:5px;"> {{Carbon\Carbon::parse($value->tanggal_pengajuan)->translatedFormat('d F Y')}} </td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:20px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 11pt; width:25%;">Dengan ini mengajukan karyawan :</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:10px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:20px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 11pt; width:1%;"></td>
                <td style="vertical-align: middle; font-size: 11pt; width:10%;">Nama</td>
                <td style="vertical-align: middle; font-size: 11pt; width:1%;">:</td>
                <td style="vertical-align: middle; font-size: 11pt; width:50%;">{{$value->employee_name}}</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:10px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
           <tr>
                <td style="vertical-align: middle; font-size: 11pt; width:1%;"></td>
                <td style="vertical-align: middle; font-size: 11pt; width:10%;">NIK</td>
                <td style="vertical-align: middle; font-size: 11pt; width:1%;">:</td>
                <td style="vertical-align: middle; font-size: 11pt; width:50%;">{{$value->nik}}</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:10px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
          <tr>
                <td style="vertical-align: middle; font-size: 11pt; width:1%;"></td>
                <td style="vertical-align: middle; font-size: 11pt; width:10%;">Bagian</td>
                <td style="vertical-align: middle; font-size: 11pt; width:1%;">:</td>
                <td style="vertical-align: middle; font-size: 11pt; width:50%;">{{$value->sub_dept_name}}</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:10px;"></td>
            </tr>
        </thead>
    </table>
     <table width="100%" style="">
        <thead>
          <tr>
                <td style="vertical-align: middle; font-size: 11pt; width:1%;"></td>
                <td style="vertical-align: middle; font-size: 11pt; width:10%;">Department</td>
                <td style="vertical-align: middle; font-size: 11pt; width:1%;">:</td>
                <td style="vertical-align: middle; font-size: 11pt; width:50%;">{{$value->department_name}}</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:10px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
          <tr>
                <td style="vertical-align: middle; font-size: 11pt; width:1%;"></td>
                <td style="vertical-align: middle; font-size: 11pt; width:10%;">Jabatan</td>
                <td style="vertical-align: middle; font-size: 11pt; width:1%;">:</td>
                <td style="vertical-align: middle; font-size: 11pt; width:50%;">{{$value->status_jabatan}}</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:30px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 11pt; width:25%;">Untuk diberikan tindakan pendisiplinan dalam bentuk :</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:20px;"></td>
            </tr>
        </thead>
    </table>

   <table width="100%" style="">
    <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 14pt; border: 2px solid; width:4%; text-align: center;">
                    @if(strtolower($value->tindakan_pendisiplinan) == strtolower('counseling'))
                    ✔
                    @endif
                </td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:4%; padding-left:5px;">Counseling</td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:5%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; border: 2px solid; width:4%; text-align: center;">
                    @if(strtolower($value->tindakan_pendisiplinan) == strtolower('surat_peringatan'))
                    ✔
                    @endif
                </td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:10%; padding-left:5px;">Surat Peringatan</td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:3%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; border: 2px solid; width:4%; text-align: center;">
                    @if(strtolower($value->tindakan_pendisiplinan) == strtolower('coaching'))
                        ✔
                    @endif
                </td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:4%; padding-left:5px;">Coaching</td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:5%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; border: 2px solid; width:4%; text-align: center;">
                    @if(strtolower($value->tindakan_pendisiplinan) == strtolower('mutasi_demosi'))
                        ✔
                    @endif
                </td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:4%; padding-left:5px;">Mutasi/Demosi</td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:5%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; border: 2px solid; width:4%; text-align: center;">
                    @if(strtolower($value->tindakan_pendisiplinan) == strtolower('phk'))
                        ✔
                    @endif
                </td>
                <td style="vertical-align: middle; font-size: 8pt; border: none; width:4%; padding-left:5px;">PHK</td>

            </tr>
    </thead>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:30px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 11pt; width:25%;">Dikarenakan telah melakukan pelanggaran/ kesalahan :</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:20px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 11pt; width:25%;">{{$value->pelanggaran}}<br>
                    <div style="border-bottom: 1px solid #000; width: 100%;">&nbsp;</div><br><br></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 11pt; width:25%;">Akibat dari pelanggaran tersebut :</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:20px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 11pt; width:25%;">{{$value->sumber_permasalahan}}<br>
                    <div style="border-bottom: 1px solid #000; width: 100%;">&nbsp;</div><br><br></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 11pt; width:25%;">Dengan faktor tersebut :</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:20px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" border="0.5px" cellspacing="0" cellpadding="6" style="font-size: 10pt; border-collapse: collapse;">
        <thead style="background-color: #f0f0f0;">
            <tr>
                <th style="width: 30%; text-align: center;">Faktor</th>
                <th style="text-align: center;">Uraian</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($value->faktor_list as $faktor)
                <tr>
                    <td style="vertical-align: top;">{{ $faktor->faktor }}</td>
                    <td style="vertical-align: top;">{{ $faktor->uraian }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center; padding: 10px;">Tidak ada faktor yang diisi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:30px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%">
        <thead>
            <tr>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:center;border:1px solid black;text-align:center; height: 23px;">Diajukan Oleh</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:center;border:1px solid black;text-align:center;">Diketahui</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:center;border:1px solid black;text-align:center;">Diketahui</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:center;border:1px solid black;text-align:center;">Disetujui</td>
            </tr>
            <tr>
                <td style="height: 65px;border:1px solid black;"></td>
                <td style="height: 65px;border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:center;border:1px solid black;text-align:center; text-transform: uppercase;"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:center;border:1px solid black;text-align:center;  height: 23px;">Kary. Terkait</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:center;border:1px solid black;text-align:center;">HR-GA</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:center;border:1px solid black;text-align:center;"></td>
            </tr>
              <tr>
                <td style="height: 23px;border:1px solid black;"></td>
                <td style="height: 23px;border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
            </tr>
        </thead>
</table>
</body>
@endforeach
</html>
