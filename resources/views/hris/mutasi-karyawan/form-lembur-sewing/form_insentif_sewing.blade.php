<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>

    <style>
        * {
            font-size: 11px;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        .page-break {
            page-break-after: always;
        }

        table {
            border-collapse: collapse;
        }

        table tr th,
        table tr td {
            border: 1px solid;
        }

        table tr th.borderless,
        table tr td.borderless {
            border: none;
        }

        table tbody tr th,
        table tbody tr td {
            height: 24px !important;
            min-height: 24px !important;
            max-height: 24px !important;
            overflow: hidden !important;
        }

        .border-left {
            border-top: none;
            border-left: 1px solid;
            border-bottom: none;
            border-right: none;
        }

        .border-right {
            border-top: none;
            border-right: 1px solid;
            border-bottom: none;
            border-left: none;
        }

        .border-between {
            border-top: none;
            border-right: 1px solid;
            border-bottom: none;
            border-left: 1px solid;
        }

        .text-center {
            text-align: center;
        }

        @page { margin: 45px 45px 45px 45px; }
    </style>
</head>
<body>
    <table width="100%" page-break-inside: auto;>
        <thead>
            <tr>
                <td style="vertical-align: middle; text-align: center; width: 100%;" colspan="2" rowspan="4">
                    <img height="50" src="{{ public_path('/assets/images/hrd/nag-logo.png') }}" alt="">
                </td>
                <td style="vertical-align: middle; font-size: 15px; text-align: center; font-weight: 800;" colspan="4" rowspan="4">FORM INSENTIF</td>
                <td style="border-top: 1px solid;">Kode Dokumen</td>
                <td style="border-top: 1px solid;font-size:5;overflow-wrap: anywhere;">{{$no_form}}</td>
            </tr>
            <tr>
                <td style="border-top: 1px solid;">Revisi</td>
                <td style="border-top: 1px solid;"></td>
            </tr>
            <tr>
                <td style="border-top: 1px solid;">Tanggal Revisi</td>
                <td style="border-top: 1px solid;"></td>
            </tr>
            <tr>
                <td style="border-top: 1px solid;border-bottom: 1px solid;">Tanggal Efektif</td>
                <td style="border-top: 1px solid; border-bottom: 1px solid;">{{$date_now}}</td>
            </tr>
            <tr>
                <td colspan='2' class="border-left" style="height: 22px;vertical-align:bottom;padding-left:3px">TANGGAL</td>
                <td class="borderless" colspan="4" style="height: 22px;vertical-align:bottom">
                    : {{$tgl_lembur}}
                </td>
                <td class="borderless" style="height: 22px;vertical-align:bottom">Target</td>
                <td class="border-right" style="height: 22px;vertical-align:bottom">: </td>
            </tr>
            <tr>
                <td colspan='2' class="border-left" style="padding-left:3px">BAGIAN</td>
                <td colspan='4'class="borderless">
                    : {{ucwords(strtolower($sub_dept))}}
                </td>
                <td class="borderless">Actual</td>
                <td class="border-right">: </td>
            </tr>
            <tr>
                <td colspan='2' class="border-left" style="height: 25px;vertical-align:top;padding-left:3px">DEPARTMENT</td>
                <td colspan='4'class="borderless" style="height: 25px;vertical-align:top">
                    : {{ucwords(strtolower($dept))}}
                </td>
                <td class="borderless"></td>
                <td class="border-right"></td>
            </tr>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>NAMA KARYAWAN</th>
                <th>Jam Awal Realisasi</th>
                <th>Jam Akhir Realisasi</th>
                <th>Keterangan</th>
                <th colspan="2">TANDA TANGAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $style = '';
                $total = 1;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td width="7%" style="text-align: center;">{{ $no++ }}.</td>
                    <td width="7%" style="text-align: center;padding-left:2px">{{ $item->enroll_id }}</td>
                    <td width="21%" style="text-align: left;padding-left:2px">{{ $item->employee_name }}</td>
                    <td width="8%" style="text-align: center;padding-left:2px">{{ substr($item->jam_lembur_awal_rencana,0,5) }}</td>
                    <td width="8%" style="text-align: center;padding-left:2px">{{ substr($item->absen_pulang_kerja,0,5) }}</td>
                    <td width="19%" style="text-align: left;padding-left:2px">{{ $item->ket }}</td>
                    @if ($no % 2 == 0)
                        <td width="15%" style="vertical-align: top;" rowspan="2">
                            <span><small>{{ $total }}</small></span>
                            @for ($i = 0; $i < 30; $i++)
                                &nbsp;
                            @endfor
                            @php
                                $total++
                            @endphp
                        </td>
                        <td width="15%" style="vertical-align: top;" rowspan="2">
                            <span><small>{{ $total }}</small></span>
                            @for ($i = 0; $i < 30; $i++)
                                &nbsp;
                            @endfor
                            @php
                                $total++
                            @endphp
                        </td>
                    @endif
                </tr>
                @if (($no - 1) % 2 != 0 && $no > count($data))
                    <tr>
                        <td style="background-color: {{ $style }}"></td>
                        <td style="background-color: {{ $style }};"></td>
                        <td style="background-color: {{ $style }}"></td>
                        <td style="background-color: {{ $style }}"></td>
                        <td style="background-color: {{ $style }}"></td>
                        <td style="background-color: {{ $style }}"></td>
                    </tr>
                @endif
                @if (($no - 1) % 26 == 0 && count($data)>26)
                <tr style="page-break-before:always">
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    <table width="100%">
        <tbody>
            <tr>
                <td class="borderless"></td>
                <td class="borderless"></td>
                <td class="borderless"></td>
                <td class="borderless"></td>
            </tr>
            <tr>
                <td width="25%" class="borderless" align="center">Diajukan</td>
                <td width="25%" class="borderless" align="center">Diketahui</td>
                <td width="25%" class="borderless" align="center" >Diketahui</td>
                <td width="25%" class="borderless" align="center">Disetujui</td>
            </tr>
            <tr>
                <td class="borderless"></td>
                <td class="borderless"></td>
                <td class="borderless"></td>
                <td class="borderless"></td>
            </tr>
            <tr>
                <td class="borderless"></td>
                <td class="borderless"></td>
                <td class="borderless"></td>
                <td class="borderless"></td>
            </tr>
            <tr>
                <td class="borderless" align="center"><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>Manager</td>
                <td class="borderless" align="center"><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>HRD</td>
                <td class="borderless" align="center"><u>Bobby Tangnga</u><br>General Manager</td>
                <td class="borderless" align="center"><u>Ronald Harsanto</u><br>COO PT. NAG</td>
            </tr>
        </tbody>
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
