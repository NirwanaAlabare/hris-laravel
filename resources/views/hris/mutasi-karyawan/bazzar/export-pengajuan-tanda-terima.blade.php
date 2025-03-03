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
    <table width="100%"  page-break-inside: auto; >
        @if($data->count() == 0)
            <tr>
                <td colspan="8" style="border:red 1px solid; color:red" class="text-center">Tidak ada data yang memerlukan tanda tangan.</td>
            </tr>
        @else
        <thead>
            <tr>
                <td style="vertical-align: middle; text-align: center; width: 100%;" colspan="2" rowspan="4">
                    <img height="50" src="{{ public_path('/assets/images/hrd/nag-logo.png') }}" alt="">
                </td>
                <td style="vertical-align: middle; font-size: 15px; text-align: center; font-weight: 800;" colspan="4" rowspan="4">TANDA TERIMA VOUCHER BAZZAR</td>
                <td colspan="1" class="border-left" style="border-top: 1px solid;">Kode Dokumen</td>
                <td colspan="1" class="border-right" style="border-top: 1px solid;">:</td>
            </tr>
            <tr>
                <td colspan="1" class="border-left" style="border-top: 1px solid;">Revisi</td>
                <td colspan="1" class="border-right" style="border-top: 1px solid;">: </td>
            </tr>
            <tr>
                <td colspan="1" class="border-left" style="border-top: 1px solid;">Tanggal Revisi</td>
                <td colspan="1" class="border-right" style="border-top: 1px solid;">: </td>
            </tr>
            <tr>
                <td colspan="1" class="border-left" style="border-top: 1px solid;border-bottom: 1px solid;">Tanggal Efektif</td>
                <td colspan="1" class="border-right" style="border-top: 1px solid; border-bottom: 1px solid;">: </td>
            </tr>
            <tr>
                <td colspan="1" class="border-left text-center"
                    style="vertical-align: top; height: 15px; border-bottom: 1px solid;"></td>
                <td colspan="1" class="borderless text-center"
                    style="vertical-align: top; height: 15px; border-bottom: 1px solid;"></td>
                <td colspan="1" class="borderless text-center"
                    style="vertical-align: top; height: 15px; border-bottom: 1px solid;"></td>
                <td colspan="1" class="borderless text-center"
                    style="vertical-align: top; height: 15px; border-bottom: 1px solid;"></td>
                <td colspan="2" class="borderless text-center"
                    style="vertical-align: top; height: 15px; border-bottom: 1px solid;"></td>
                <td colspan="2" class="border-right text-center"
                    style="vertical-align: top; height: 15px; border-bottom: 1px solid;"></td>
            </tr>
            <tr>
                <th>NO</th>
                <th>ID</th>
                <th>NAMA KARAYWAN</th>
                <th>BAGIAN</th>
                <th>STATUS</th>
                <th>JUMLAH</th>
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
                    <td style="text-align: center;">{{ $no++ }}.</td>
                    <td style="text-align: left;padding-left:3px">{{ $item->enroll_id }}</td>
                    <td style="text-align: left;padding-left:3px">{{ $item->employee_name }}</td>
                    <td style="text-align: left;padding-left:3px">{{ $item->sub_dept_name }}</td>
                    <td style="text-align: left;padding-left:3px">{{ $item->status_staff }}</td>
                    <td style="text-align: center;padding-left:3px">{{ $item->jumlah == 0 ? 'Rp 0' : 'Rp ' . number_format($item->jumlah, 0, ',', '.') }}</td>
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
                </tr>

                @if (($no - 1) % 2 != 0 && $no > count($data))
                    <tr>
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
                <td colspan="1" class="border-left text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;"></td>
                <td colspan="1" class="borderless text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;"></td>
                <td colspan="1" class="borderless text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;"></td>
                <td colspan="1" class="borderless text-left"
                    style="height: 20px; border-bottom: 1px solid;">
                </td>
                <td colspan="1" class="borderless text-left"
                    style="height: 20px; border-bottom: 1px solid;">
                </td>
                <td colspan="1" class="border-right text-center"
                    style="vertical-align: middle; height: 20px; border-bottom: 1px solid;">
            <b>{{ $total_jumlah == 0 ? 'Rp 0' : 'Rp ' . number_format($total_jumlah, 0, ',', '.') }}</b>
            </td>

                <td colspan="2" class="border-right text-center"
                    style="vertical-align: top; height: 20px; border-bottom: 1px solid;"></td>
            </tr>
            <tr>
                <td colspan="8" class="border-between">
                    <span style="padding-left: 15px;">Pernyataan : </span>
                    <ol>
                        <li>Dengan ini saya mengajukan pengambilan Voucher Bazzar PT. Nirwana Alabare Garment</li>
                        <li>Saya bersedia  untuk melakukan pembayaran dengan cara pemotongan gaji pada periode yang telah di sepakati</li>
                        <li>Apabila terjadi kekurangan pemotongan pada nominal yang tidak seharusnya,Saya bersedia membayar kekurangan tersebut secara cash / sesuai kesepakatan</li>
                    </ol>
                </td>
            </tr>

            <tr>
                    <td colspan="1" class="border-left text-center"
                        style="vertical-align: top; height: 0px; border-bottom: 1px solid;"></td>
                    <td colspan="1" class="borderless text-center"
                        style="vertical-align: top; height: 0px; border-bottom: 1px solid;"></td>
                    <td colspan="1" class="borderless text-center"
                        style="vertical-align: top; height: 0px; border-bottom: 1px solid;"></td>
                    <td colspan="1" class="borderless text-center"
                        style="vertical-align: top; height: 0px; border-bottom: 1px solid;"></td>
                    <td colspan="2" class="borderless text-center"
                        style="vertical-align: top; height: 0px; border-bottom: 1px solid;"></td>
                    <td colspan="2" class="border-right text-center"
                        style="vertical-align: top; height: 0px; border-bottom: 1px solid;"></td>
            </tr>
        </tfoot>
        @endif
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
