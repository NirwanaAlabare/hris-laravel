<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>

    <style>
        table {
            border-collapse: collapse;
        }
        .checkbox {
        display: inline-block;
        width: 15px;
        height: 15px;
        border: 2px solid black;
        text-align: center;
        line-height: 15px;
        font-size: 19px;
        font-weight: bold;
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
                <td style="vertical-align: middle; font-size: 12pt; text-align: center; font-weight: 800;border: 1px solid;" colspan="8" rowspan="4">FORM PERMOHONAN IJIN</td>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Kode Dokumen</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: F.16.HR.NAG.P-04.F-01.01</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: -</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Tanggal Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: </td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid;border-bottom: 1px solid; font-size: 9pt;">Tanggal Efektif</td>
                <td colspan="3" class="border-right" style="border: 1px solid; border-bottom: 1px solid; font-size: 9pt;">: </td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="border:1px solid black; border-top:0px;">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;" width="17%">Nama</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;" width="42%">{{ $data->employee_name }}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;" width="16%"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;" width="1%"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">Nik</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;">{{ $data->nik }}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;width:150px">Bagian</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;">{{ $data->sub_dept_name }}<</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">Department</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;">{{ $data->department_name }}<</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">Nomor Form</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;"> {{ $data->is_verifikasi_pengajuan_admin === 1 ? $data->nomor_form_perizinan : "-" }}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">Jenis Perijinan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;"> {{ $data->nama_absen_ijin }}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">Keterangan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;"> {{ $data->absen_alasan }}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">Mulai</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;"> {{ \Carbon\Carbon::parse($data->tanggal_perizinan)->translatedFormat('d F Y') }} {{ $data->time_mulai_ijin  }}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">Sampai</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;">{{ \Carbon\Carbon::parse($data->tanggal_perizinan)->translatedFormat('d F Y') }} {{ $data->time_akhir_ijin }}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">Tanggal pengajuan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;"> {{ \Carbon\Carbon::parse($data->tanggal_perizinan)->translatedFormat('d F Y') }}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
        </thead>
    </table>
    <table width="100%">
        <thead>
            <tr>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center; border-top:0px;" >Dibuat</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center; border-top:0px;">Disetujui</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center; border-top:0px;">Disetujui</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center; border-top:0px;">Diketahui</td>
            </tr>
            <tr>
                <td style="height: 55px;border:1px solid black;"></td>
                <td style="height: 55px;border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center; text-transform: uppercase;">{{$data->employee_name}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center; text-transform: uppercase;"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center; text-transform: uppercase;"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center; text-transform: uppercase;">HR-GA</td>
            </tr>
        </thead>
    </table>
    <script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            if ($PAGE_COUNT > 1) {
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $size = 6;
                $pageText = "Halaman " . $PAGE_NUM . " dari " . $PAGE_COUNT;
                $y = 5;
                $x = 530;
                $pdf->text($x, $y, $pageText, $font, $size);
            }
        ');
    }
    </script>

</body>

</html>
