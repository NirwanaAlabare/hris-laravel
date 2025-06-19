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
                <td style="vertical-align: middle; font-size: 12pt; text-align: center; font-weight: 800;border: 1px solid;" colspan="8" rowspan="4">FORM PERMOHONAN PERIJINAN</td>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Kode Dokumen</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: F.16.HR.NAG.P-04.F-01.01</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: Rev 1</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Tanggal Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: 05 Maret 2024</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid;border-bottom: 1px solid; font-size: 9pt;">Tanggal Efektif</td>
                <td colspan="3" class="border-right" style="border: 1px solid; border-bottom: 1px solid; font-size: 9pt;">: 05 Maret 2024</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="border-left:1px solid black; border-top:0px; border-right:1px solid black;">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;" width="17%">Nama</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;" width="1%">:</td>
            <td style="font-family: Arial, Helvetica, sans-serif; font-size: 8pt; vertical-align: top; text-transform: uppercase; line-height: normal;" width="100%">
                {{ $data->employee_name }}
                <div style="border-bottom: 1px solid #000; width: 100%; margin-top: 0px;">
                    <span style="display: block; height: 1px;"></span>
                </div>
            </td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;" width="16%"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;" width="1%"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">NIP</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase; line-height: normal;">{{ $data->nik }}
                    <div style="border-bottom: 1px solid #000; width: 100%; margin-top: 0px;">
                    <span style="display: block; height: 1px;"></span>
                </div>
                </td>
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
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase; line-height: normal;">{{ $data->sub_dept_name }}
                    <div style="border-bottom: 1px solid #000; width: 100%; margin-top: 0px;">
                    <span style="display: block; height: 1px;"></span>
                </div>
                </td>
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
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase; line-height: normal;">{{ $data->department_name }}
                    <div style="border-bottom: 1px solid #000; width: 100%; margin-top: 0px;">
                    <span style="display: block; height: 1px;"></span>
                </td>
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
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;">   {{$data->nama_absen_ijin
                    ? $data->nama_absen_ijin
                    : ($data->kode_absen_ijin == 'PC'
                        ? 'PULANG CEPAT'
                        : ($data->kode_absen_ijin == 'DT'
                            ? 'DATANG TERLAMBAT'
                            : '-')),}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
            </tr>

            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
        </thead>
    </table>
  @php
    function checkboxRow($label, $kode, $data) {
        $realKode = $data->kode_absen_ijin;
        $isChecked = $realKode === $kode;

        $icon = $isChecked
            ? '<div style="width:15px; height:15px; border:1px solid #000; text-align:center;"><img src="' . public_path('assets/images/brand/check-mark-icon-5374.jpg') . '" style="width:12px; height:12px;" /></div>'
            : '<div style="width:15px; height:15px; border:1px solid #000;"></div>';

        return '
            <table style="margin-bottom: 3px;">
                <tr>
                    <td style="vertical-align: middle !important; padding: 0; padding-right: 5px;">
                        ' . $icon . '
                    </td>
                    <td style="vertical-align: middle !important; font-size: 8pt; font-family: Arial, sans-serif;">
                        ' . $label . '
                    </td>
                </tr>
            </table>
        ';
    }
@endphp

    <table style="width: 100%; border-left: 1px solid #000; border-right: 1px solid #000; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 8pt;">
        <tr>
        <td style="width: 50%; vertical-align: top; padding-left: 20px;">
            {!! checkboxRow('Datang terlambat', 'DT', $data) !!}
            {!! checkboxRow('Pulang lebih awal', 'PC', $data) !!}
            {!! checkboxRow('Ijin keluar perusahaan sementara', 'IK', $data) !!}
        </td>
        <td style="width: 50%; vertical-align: top;">
             {!! checkboxRow('Dinas Luar', 'DL', $data) !!}
            {!! checkboxRow('Tidak masuk kerja', 'TMK', $data) !!}
        </td>
        </tr>
    </table>



    <table width="100%" style="border-left:1px solid black; border-top:0px; border-right:1px solid black; margin: none; padding:none;">
        <thead>
             <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
             <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; width:21.5%">Keterangan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; width: 1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase; border: 1px solid #000; height:30px; padding: 5px"> {{ $data->absen_alasan }}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            @php
                $tanggal = \Carbon\Carbon::parse($data->tanggal_mulai_ijin ?? $data->tanggal_perizinan);
                $waktu = $data->time_mulai_ijin;

                $tanggal_sampai = \Carbon\Carbon::parse($data->tanggal_akhir_ijin ?? $data->tanggal_perizinan);
                $waktu_sampai = $data->time_akhir_ijin;
            @endphp
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">Mulai</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; width: 50%; ">Hari <b>{{ $tanggal->translatedFormat('l') }}</b> Tanggal <b>{{ $tanggal->translatedFormat('d') }}</b> Bulan <b>{{ $tanggal->translatedFormat('F') }}</b> Tahun <b>{{ $tanggal->translatedFormat('Y') }}</b> Jam <b>{{ $waktu }}</b></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">Sampai</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;  width: 50%;">Hari <b>{{ $tanggal_sampai->translatedFormat('l') }}</b> Tanggal <b>{{ $tanggal_sampai->translatedFormat('d') }}</b> Bulan <b>{{ $tanggal_sampai->translatedFormat('F') }}</b> Tahun <b>{{ $tanggal_sampai->translatedFormat('Y') }}</b> Jam <b>{{ $waktu_sampai }}</td>
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
    <table width="100%" style="border-top:1px solid black;">
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
