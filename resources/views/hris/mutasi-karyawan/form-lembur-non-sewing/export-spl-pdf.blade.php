<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>

    <style>
        * {
            font-size: 8px;
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
            height: 28px !important;
            min-height: 28px !important;
            max-height: 28px !important;
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

        @page { margin: 20px 20px 40px 20px; }
    </style>
</head>

<body>
    <table width="100%" page-break-inside: auto;>
    <thead>
        <tr>
            <td style="vertical-align: middle; text-align: center; width: 100%;" colspan="2" rowspan="4">
                <img height="50" src="{{ public_path('/assets/dist/img/nag-logo.png') }}" alt="">
            </td>
            <td style="vertical-align: middle; font-size: 15px; text-align: center; font-weight: 800;" colspan="7" rowspan="4">FORM PERSETUJUAN LEMBUR</td>
            <td colspan="2" class="border-left" style="border-top: 1px solid;">Kode Dokumen</td>
            <td colspan="4" class="border-right" style="border-top: 1px solid;">: F.16.HR.NAG.P-03.F-01.01</td>
        </tr>
        <tr>
            <td colspan="2" class="border-left" style="border-top: 1px solid;">Revisi</td>
            <td colspan="4" class="border-right" style="border-top: 1px solid;">: 1</td>
        </tr>
        <tr>
            <td colspan="2" class="border-left" style="border-top: 1px solid;">Tanggal Revisi</td>
            <td colspan="4" class="border-right" style="border-top: 1px solid;">: 26 April 2022</td>
        </tr>
        <tr>
            <td colspan="2" class="border-left" style="border-top: 1px solid;border-bottom: 1px solid;">Tanggal Efektif</td>
            <td colspan="4" class="border-right" style="border-top: 1px solid; border-bottom: 1px solid;">: 27 April 2022</td>
        </tr>
        <tr>
            <td colspan='2' class="border-left">TANGGAL</td>
            <td colspan='2' class="borderless">
                {{-- {{ date('d-M-Y', strtotime($from)) }} - {{ date('d-M-Y', strtotime($to)) }} --}}
                {{ Carbon\Carbon::parse($tgl_lembur)->translatedFormat('l, d F Y') }}
            </td>
            <td colspan='5' class="borderless"></td>
            <td colspan='2' class="borderless">Nomor Form</td>
            <td colspan='4' rowspan="2" class="border-right" style="word-break: break-word;vertical-align:top">{{ $no_form }}</td>
        </tr>
        <tr>
            <td colspan='2' class="border-left">BAGIAN</td>
            <td colspan='3'class="borderless">
                {{ $dept }}
            </td>
        </tr>
        <tr>
            <th rowspan="2" width="3%">No</th>
            <th rowspan="2" width="12%">Nama Karyawan</th>
            <th rowspan="2" width="6%">NIP</th>
            <th rowspan="2" width="5%">Jabatan</th>
            <th rowspan="2" width="15%">Keterangan</th>
            <th colspan="3">Rencana Lembur</th>
            <th colspan="2" rowspan="2">Tanda Tangan Rencana</th>
            <th colspan="3">Realisasi Lembur</th>
            <th colspan="2" rowspan="2">Tanda Tangan Realisasi</th>
        </tr>
        <tr>
            <th width="4%">Awal</th>
            <th width="4%">Akhir</th>
            <th width="4%">Jumlah</th>
            <th width="4%">Awal</th>
            <th width="4%">Akhir</th>
            <th width="4%">Jumlah</th>
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
                <td style="text-align: center;">{{ $no++ }}.</td>
                <td style="text-align: left;padding-left:2px;word-wrap:break-all">{{ $item->employee_name }}</td>
                <td style="text-align: center;padding-left:2px">{{ $item->nik }}</td>
                <td style="text-align: center;padding-left:2px">{{ $item->status_jabatan }}</td>
                <td style="text-align: left;padding-left:2px">{{ $item->keterangan }}</td>
                <td style="text-align: center;padding-left:2px">{{ $item->jam_lembur_awal_rencana }}</td>
                <td style="text-align: center;padding-left:2px">{{ $item->jam_lembur_akhir_rencana }}</td>
                <td style="text-align: center;padding-left:2px">{{ $item->total_jam }}</td>
                @if ($no % 2 == 0)
                    <td style="vertical-align: top;" rowspan="2">
                        <span><small>{{ $total }}</small></span>
                        @for ($i = 0; $i < 30; $i++)
                            &nbsp;
                        @endfor
                        @php
                            $total++
                        @endphp
                    </td>
                    <td style="vertical-align: top;" rowspan="2">
                        <span><small>{{ $total }}</small></span>
                        @for ($i = 0; $i < 30; $i++)
                            &nbsp;
                        @endfor
                        @php
                            $total++
                        @endphp
                    </td>
                @endif
                <td style="text-align: center;">{{ $item->jam_lembur_awal_rencana }}</td>
                <td style="text-align: center;"></td>
                <td style="text-align: center"></td>
                @if ($no % 2 == 0)
                    <td style="vertical-align: top;" rowspan="2">
                        <span><small>{{ $total - 2 }}</small></span>
                        @for ($i = 0; $i < 30; $i++)
                            &nbsp;
                        @endfor
                    </td>
                    <td style="vertical-align: top;" rowspan="2">
                        <span><small>{{ $total - 1 }}</small></span>
                        @for ($i = 0; $i < 30; $i++)
                            &nbsp;
                        @endfor
                    </td>
                @endif
                {{-- <td style="text-align: center;">{{ $item->absen_masuk_kerja }}</td>
                <td style="text-align: center;">{{ $item->absen_pulang_kerja }}</td>
                <td style="text-align: center;">{{ $item->realisasi_lembur }}</td> --}}
            </tr>

            @if (($no - 1) % 2 != 0 && $no > count($data))
                <tr>
                    <td style="background-color: {{ $style }}">&nbsp;</td>
                    <td style="background-color: {{ $style }}">&nbsp;</td>
                    <td style="background-color: {{ $style }}">&nbsp;</td>
                    <td style="background-color: {{ $style }}">&nbsp;</td>
                    <td style="background-color: {{ $style }}">&nbsp;</td>
                    <td style="background-color: {{ $style }}">&nbsp;</td>
                    <td style="background-color: {{ $style }}">&nbsp;</td>
                    <td style="background-color: {{ $style }}">&nbsp;</td>
                    <td style="background-color: {{ $style }}">&nbsp;</td>
                    <td style="background-color: {{ $style }}">&nbsp;</td>
                    <td style="background-color: {{ $style }}">&nbsp;</td>
                </tr>
            @endif
            @if (($no - 1) % 26 == 0 && count($data)>26)
            <tr style="page-break-before:always">
            </tr>
            @endif
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="15" class="border-between">
                    <span style="padding-left: 15px;">Catatan : </span> 
                    <ol> 
                        <li>Pengajuan Rencana Lembur dilakukan sebelum pelaksanaan lembur</li>
                        <li>Approval realisasi lembur harus sudah diserahkan kepada HRD
                        paling lambat 1 hari setelah pelaksanaan lembur pukul 09.00</li>
                        <li>Formulir persetujuan lembur harus diisi dengan lengkap dan
                        tidak boleh terdapat coretan</li>
                    </ol>
                </td>
            </tr>
            @if (
                $dept == 'FINANCE, ACCOUNTING & TAX' ||
                $dept == 'EXPORT & IMPORT' ||
                $dept == 'MARKETING' ||
                $dept == 'PURCHASING' ||
                $dept == 'HRD' ||
                $dept == 'GENERAL AFFAIR' ||
                $dept == 'COMPLIANCE' ||
                $dept == 'INFORMATION TECHNOLOGY' ||
                $dept == 'INDUSTRIAL ENGINEERING' ||
                $dept == 'MECHANIC')
            <tr>
                <td colspan="3" class="text-center border-left" style="border-top: 1px solid;">Diajukan Oleh</td>
                <td colspan="3" class="borderless text-center" style="border-top: 1px solid;">Diketahui</td>
                <td colspan="3" class="borderless text-center" style="border-top: 1px solid;">Diketahui</td>
                <td colspan="3" class="borderless text-center" style="border-top: 1px solid;">Disetujui</td>
                <td colspan="3" class="text-center border-right" style="border-top: 1px solid;">Approval Realisasi</td>
            </tr>
            <tr>
                <td colspan="15" class="border-between">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="15" class="border-between">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" class="border-left text-center" style="text-decoration: underline;">
                    @for ($i = 0; $i < 15; $i++)
                        &nbsp;
                    @endfor
                </td>
                <td colspan="3" class="borderless text-center" style="text-decoration: underline;">
                    @for ($i = 0; $i < 15; $i++)
                        &nbsp;
                    @endfor
                </td>
                <td colspan="3" class="borderless text-center" style="text-decoration: underline;">
                    @for ($i = 0; $i < 15; $i++)
                        &nbsp;
                    @endfor    
                </td>
                <td colspan="3" class="borderless text-center" style="text-decoration: underline;">
                    @for ($i = 0; $i < 15; $i++)
                        &nbsp;
                    @endfor    
                </td>
                <td colspan="3" class="border-right text-center" style="text-decoration: underline;">
                    @for ($i = 0; $i < 15; $i++)
                        &nbsp;
                    @endfor    
                </td>
            </tr>
            <tr>
            <tr>
                <td colspan="3" class="border-left text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;"></td>
                <td colspan="3" class="borderless text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;">SPV/ Chief</td>
                <td colspan="3" class="borderless text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;">Manager</td>
                <td colspan="3" class="borderless text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;">General Manager</td>
                <td colspan="3" class="border-right text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;"></td>
            </tr>
            </tr>
        @else
        <tr>
                <td colspan="2" class="text-center border-left" style="border-top: 1px solid;">Diajukan Oleh</td>
                <td colspan="2" class="borderless text-center" style="border-top: 1px solid;">Diketahui</td>
                <td colspan="3" class="borderless text-center" style="border-top: 1px solid;">Diketahui</td>
                <td colspan="3" class="borderless text-center" style="border-top: 1px solid;">Diketahui</td>
                <td colspan="3" class="borderless text-center" style="border-top: 1px solid;">Disetujui</td>
                <td colspan="2" class="text-center border-right" style="border-top: 1px solid;">Approval Realisasi
                </td>
            </tr>
            <tr>
                <td colspan="15" class="border-between">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="15" class="border-between">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left text-center" style="text-decoration: underline;">
                    @for ($i = 0; $i < 15; $i++)
                        &nbsp;
                    @endfor
                </td>
                <td colspan="2" class="borderless text-center" style="text-decoration: underline;">
                    @for ($i = 0; $i < 15; $i++)
                        &nbsp;
                    @endfor
                </td>
                <td colspan="3" class="borderless text-center" style="text-decoration: underline;">
                    @for ($i = 0; $i < 15; $i++)
                        &nbsp;
                    @endfor    
                </td>
                <td colspan="3" class="borderless text-center" style="text-decoration: underline;">
                    @for ($i = 0; $i < 15; $i++)
                        &nbsp;
                    @endfor    
                </td>
                <td colspan="3" class="borderless text-center" style="text-decoration: underline;">
                    @for ($i = 0; $i < 15; $i++)
                        &nbsp;
                    @endfor        
                </td>
                <td colspan="2" class="border-right text-center" style="text-decoration: underline;">
                    @for ($i = 0; $i < 15; $i++)
                        &nbsp;
                    @endfor    
                </td>
            </tr>
            <tr>
            <tr>
                <td colspan="2" class="border-left text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;"></td>
                <td colspan="2" class="borderless text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;">SPV/ Chief</td>
                <td colspan="3" class="borderless text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;">Manager</td>
                <td colspan="3" class="borderless text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;">Manager PPIC</td>
                <td colspan="3" class="borderless text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;">General Manager</td>
                <td colspan="2" class="border-right text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;"></td>
            </tr>
        @endif
        </tfoot>
    </table>
</body>

</html>