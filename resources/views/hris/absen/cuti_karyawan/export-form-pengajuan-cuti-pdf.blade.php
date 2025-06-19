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
            display: inline-flex;       /* agar dia inline bersama teks */
            width: 15px;
            height: 15px;
            border: 2px solid black;
            text-align: center;
            font-size: 23px;             /* kecilkan sedikit agar muat */
            font-weight: bold;
            vertical-align: top;      /* sangat penting! */
            position: relative;
        }


        @page { margin: 20px 20px 40px 20px; }
    </style>
</head>

<body>
    <table width="93%" style="justify-content: center; margin: 0 auto;">
        <thead>
            <tr>
                <td width="100px" style="vertical-align: middle; text-align: center;border: 1px solid;" colspan="2" rowspan="4">
                    <img height="60" src="{{ public_path('assets/images/brand/nirwana logo.jpg') }}" alt="">
                </td>
                <td style="vertical-align: middle; font-size: 12pt; text-align: center; font-weight: 800;border: 1px solid;" colspan="8" rowspan="4">FORM PERMOHONAN {{ strpos(strtolower($data->nama_absen_ijin), 'cuti') !== false ? 'CUTI' : 'PERIJINAN' }}</td>
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
                <td colspan="3" class="border-right" style="border: 1px solid; border-bottom: 1px solid; font-size: 9pt;">: 30 Oktober 2016 </td>
            </tr>
        </thead>
    </table>
    <table width="93%" style="border:1px solid black; border-top:0px; justify-content: center; margin: 0 auto;">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;" width="17%">Nama</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top;" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;" width="42%">{{ $data->employee_name }}  <div style="border-bottom: 1px solid #000; width: 100%; margin-top: 0px;">
                    <span style="display: block; height: 1px;"></span>
                </div></td>
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
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;">{{ $data->nik }}  <div style="border-bottom: 1px solid #000; width: 100%; margin-top: 0px;">
                    <span style="display: block; height: 1px;"></span>
                </div></td>
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
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;">{{ $data->sub_dept_name }}  <div style="border-bottom: 1px solid #000; width: 100%; margin-top: 0px;">
                    <span style="display: block; height: 1px;"></span>
                </div></td>
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
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;">{{ $data->department_name }}  <div style="border-bottom: 1px solid #000; width: 100%; margin-top: 0px;">
                    <span style="display: block; height: 1px;"></span>
                </div></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">Tanggal pengajuan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;"> {{ \Carbon\Carbon::parse($data->tanggal_perizinan)->translatedFormat('d F Y') }}  <div style="border-bottom: 1px solid #000; width: 100%; margin-top: 0px;">
                    <span style="display: block; height: 1px;"></span>
                </div></td>
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
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top" colspan="6">Dengan ini bermaksud untuk mengajukan {{ strpos(strtolower($data->nama_absen_ijin), 'cuti') !== false ? 'CUTI' : 'IJIN' }} :</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td colspan="3"
                    style="
                      padding-left: 60px !important;
                      font-family: DejaVu Sans, Helvetica, sans-serif;
                      font-size: 9pt;
                      vertical-align: bottom;
                    ">
                  <span class="checkbox"><span style="position: absolute; top: -9; left: -2px;">✔</span></span>
                  <span style="vertical-align: middle; margin-left: 6px;font-size:8pt;">{{$data->nama_absen_ijin}} </span>
                </td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
              </tr>

            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:9px;" colspan="6"></td>
            </tr>
              <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">Keterengan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; text-transform: uppercase;"> {{ $data->absen_alasan}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top"></td>
            </tr>

            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:9px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top" colspan="6">
                    {{ strpos(strtolower($data->nama_absen_ijin), 'cuti') !== false ? 'CUTI' : 'IJIN' }} tersebut dilakukan mulai dari <span style="width: 50px !important;"></span>
                    <b><u>{{ \Carbon\Carbon::parse($data->tanggal_mulai_ijin)->translatedFormat('d F Y') }}</u></b> <span style="width: 50px !important;"></span>
                    sampai dengan
                    <span style="width: 50px !important;"></span>
                    <b><u>{{ \Carbon\Carbon::parse($data->tanggal_akhir_ijin)->translatedFormat('d F Y') }}</u></b>
                    <span style="width: 50px !important;"></span>
                </td>

            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top" colspan="6">Selama pelaksanaan {{ strpos(strtolower($data->nama_absen_ijin), 'cuti') !== false ? 'CUTI' : 'IJIN' }} tersebut maka seluruh pekerjaan akan ditangani oleh sdr/I :</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px !important; font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top" colspan="6">Nama <u>{{$data->didelegasikan_employee_name ? $data->didelegasikan_employee_name : '________________'}}</u> NIP <u>{{$data->didelegasikan_nik ? $data->didelegasikan_nik : '________________'}}</u>.</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:8pt;vertical-align:top; height:10px;" colspan="6"></td>
            </tr>
        </thead>
    </table>
    <table width="93%" style="justify-content: center; margin: 0 auto;">
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
