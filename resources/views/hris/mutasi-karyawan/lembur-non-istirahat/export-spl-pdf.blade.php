<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Form Persetujuan Lembur</title>

    <style>
        * {
            font-size: 9px;
            font-family: Arial, Helvetica, sans-serif;
        }

        @page {
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            /* Table-layout fixed mencegah kolom bergeser otomatis */
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        th {
            text-align: center;
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }

        .no-border-bottom { border-bottom: none; }
        .no-border-top { border-top: none; }
    </style>
</head>

<body>

<table>
    <thead>
        {{-- <tr>
            <td colspan="2" rowspan="2" class="text-center">
                <img height="40" src="{{ public_path('/assets/images/hrd/nag-logo.png') }}" alt="LOGO">
            </td>
            <td colspan="11" rowspan="2" class="text-center" style="font-size:14px; font-weight:800;">
                FORM PERSETUJUAN LEMBUR
            </td>
        </tr>
        <tr></tr> --}}
        <tr>
            <td colspan="2" class="text-center"><img height="40" src="{{ public_path('/assets/images/hrd/nag-logo.png') }}" alt="LOGO"></td>
            <td colspan="12"class="text-center text-bold" >FORM PERGESERAN WAKTU ISTIRAHAT</td>
        </tr>
        <thead>
        @php
            use Carbon\Carbon;
            $firstRow = $data->first();
        @endphp
        <tr>
            <td colspan="2" class="text-bold">TANGGAL</td>
           <td colspan="12">  : {{ $firstRow ? Carbon::parse($firstRow->tgl_lembur) ->locale('id')->translatedFormat('d F Y') : '-' }} </td> </tr>
            {{-- <td colspan="5">: </td> --}}
        </tr>

        <tr>
            <td colspan="2" class="text-bold">TUJUAN </td>
            <td colspan="12" text-bold class="bolder-cell">: HRD </td>
        </tr>
        {{-- <tr>
            <td colspan="14" class="text-bold tab-content" >TUJUAN  : {{ $firstRow ? $firstRow->nomor_form_lembur : '-'}} </td>
            <td colspan="12" text-bold>: {{ $firstRow ? $firstRow->tanggal_berjalan : '-'}} </td>
        </tr> --}}
        </thead>

        <tr>
            <th style="width: 30px;">No</th>
            <th colspan="2">No Form</th>
            <th colspan="2">NIK</th>
            <th colspan="3">Nama Karyawan</th>
            <th colspan="2">Tanggal Lembur</th>
            <th colspan="2">Bagian</th>
             <th colspan="2">Tanda Tangan Realisasi</th>
        </tr>
        {{-- <tr>
            <th style="width: 30px;">No</th>
            <th colspan="5">Nama Karyawan</th>
            <th colspan="4">NIK</th>
            <th colspan="4">Bagian</th> --}}
            {{-- <th colspan="3">Tanggal Lembur</th>
            <th colspan="2">Bagian</th> --}}
        {{-- </tr> --}}

    </thead>

    <tbody>
        @php
            $no = 1;
            $style = '';
            $total = 1;
            $rowCount = count($data);
        @endphp

        @forelse($data as $index => $row)
        <tr>
            <td class="text-center">{{ $no }}</td>
            <td colspan="2">{{ $row->nomor_form_lembur }}</td>
            <td colspan="2">{{ $row->nik }}</td>
            <td colspan="3">{{ $row->employee_name }}</td>
            <td colspan="2" class="text-center">
                {{ \Carbon\Carbon::parse($row->tgl_lembur)->format('d-m-Y') }}
            </td>
            <td colspan="2">{{ $row->sub_dept_name }}</td>

            {{-- Tampilkan kolom tanda tangan hanya pada baris ganjil (baris 1, 3, 5, dst) --}}
            @if ($no % 2 == 1)
                <td style="vertical-align: top;" rowspan="2">
                    <span><small>{{ $total }}</small></span>
                    @for ($i = 0; $i < 30; $i++)
                        &nbsp;
                    @endfor
                </td>
                <td style="vertical-align: top;" rowspan="2">
                    <span><small>{{ $total + 1 }}</small></span>
                    @for ($i = 0; $i < 30; $i++)
                        &nbsp;
                    @endfor
                    @php
                        $total += 2;
                    @endphp
                </td>
            @endif

            {{-- Increment no di akhir --}}
            @php $no++; @endphp
        </tr>

        {{-- Jika ini adalah baris terakhir dan ganjil, tambahkan baris kosong untuk rowspan --}}
        @if ($no > $rowCount && $no % 2 == 0)
            <tr>
                <td class="text-center">{{ $no }}</td>
                <td colspan="2"></td>
                <td colspan="2"></td>
                <td colspan="3"></td>
                <td colspan="2" class="text-center"></td>
                <td colspan="2"></td>
            </tr>
        @endif

        @empty
        <tr>
            <td colspan="14" class="text-center">Data tidak ditemukan</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
                <td colspan="14" class="border-between">
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
        <tr>
            <td colspan="4" class="text-center text-bold">Diajukan Oleh</td>
            <td colspan="3" class="text-center text-bold">Diketahui</td>
            <td colspan="3" class="text-center text-bold">Diketahui</td>
            <td colspan="4" class="text-center text-bold">Disetujui</td>
        </tr>

        <tr>
            <td colspan="4" style="height: 60px;"></td>
            <td colspan="3"></td>
            <td colspan="3"></td>
            <td colspan="4"></td>
        </tr>

        <tr>
            <td colspan="4" class="text-center" style="text-decoration: underline;">&nbsp;</td>
            <td colspan="3" class="text-center" style="text-decoration: underline;"></td>
            <td colspan="3" class="text-center" style="text-decoration: underline;"></td>
            <td colspan="4" class="text-center" style="text-decoration: underline;"></td>
        </tr>

        <tr>
            <td colspan="4" class="text-center">SPV / Chief</td>
            <td colspan="3" class="text-center">Manager</td>
            <td colspan="3" class="text-center">General Manager</td>
            <td colspan="4" class="text-center">HRD</td>
        </tr>
    </tfoot>
</table>

<script type="text/php">
if (isset($pdf)) {
    $pdf->page_script('
        $font = $fontMetrics->get_font("Arial", "normal");
        $pdf->text(520, 820, "Halaman $PAGE_NUM dari $PAGE_COUNT", $font, 7);
    ');
}
</script>

</body>
</html>
