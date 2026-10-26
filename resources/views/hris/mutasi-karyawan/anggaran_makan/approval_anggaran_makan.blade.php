@php
    /* ====== FORMAT ANGKA ====== */
    $fmt = fn($v) => str_replace(',', '.', number_format($v));
    // kosongkan sel jika nilainya 0
    $fmtBlank = fn($v) => round((float) $v) == 0 ? '' : str_replace(',', '.', number_format($v));

    /* ====== KUMPULKAN SEMUA BARIS KE SATU ARRAY ====== */
    $rows = [];

    $add = function ($type, $payload = null) use (&$rows) {
        $rows[] = ['type' => $type, 'v' => $payload];
    };

    $addBlock = function ($title, $items, $subtot) use ($add) {
        if (count($items)) {
            $add('sub', $title);
            foreach ($items as $v) { $add('item', $v); }
            foreach ($subtot as $v) { $add('subtotal', $v); }
        }
    };

    // ---------- ALABARE ----------
    if (count($data) || count($data2)) { $add('section', 'ALABARE'); }
    $addBlock('LEMBUR',      $data,  $data3);
    $addBlock('SHIFT MALAM', $data2, $data4);
    $addBlock('TAKJIL',      $data6, $data7);
    foreach ($data5 as $v) { $add('grand', $v); }

    // ---------- KNITTING ----------
    if (count($dataknitting) || count($dataknitting2)) { $add('section', 'KNITTING'); }
    $addBlock('LEMBUR',      $dataknitting,  $dataknitting3);
    $addBlock('SHIFT MALAM', $dataknitting2, $dataknitting4);
    $addBlock('TAKJIL',      $dataknitting6, $dataknitting7);
    foreach ($dataknitting5 as $v) { $add('grand', $v); }

    // ---------- GRAND TOTAL ----------
    foreach ($datatotal as $v) { $add('grand', $v); }

    /* ====== FONT OTOMATIS ====== */
    $totalRows = count($rows);
    $availPt   = 530;  // tinggi area data per halaman (pt) -> sesuaikan kalau perlu
    $minFont   = 7;    // font terkecil
    $maxFont   = 9;   // font terbesar
    $rowH      = fn($f) => $f * 1.15 + 3;  // perkiraan tinggi 1 baris (pt)

    // font ideal supaya SEMUA baris muat di 1 halaman
    $fontPt = ($availPt / max($totalRows, 1) - 3) / 1.15;
    $fontPt = max($minFont, min($maxFont, floor($fontPt * 2) / 2)); // kelipatan 0.5
    $fontPt = number_format($fontPt, 1, '.', '');

    // kalau sudah di font minimum tapi masih banyak -> baru pecah halaman
    $perPage = max(1, (int) floor($availPt / $rowH((float) $fontPt)));
    $padPx   = $fontPt >= 9 ? 2 : 1; // padding atas-bawah ikut menyesuaikan

    /* ====== BAGI KE HALAMAN ====== */
    $pages = [];
    $cur   = [];
    foreach ($rows as $r) {
        if (count($cur) >= $perPage) {
            // jangan biarkan judul (ALABARE / LEMBUR / dst) tertinggal sendirian di akhir halaman
            $carry = [];
            while (count($cur) && in_array(end($cur)['type'], ['section', 'sub'])) {
                array_unshift($carry, array_pop($cur));
            }
            $pages[] = $cur;
            $cur = $carry;
        }
        $cur[] = $r;
    }
    if (count($cur) || !count($pages)) { $pages[] = $cur; }
    $totalPages = count($pages);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>
    <style>
        table tr th,
        table tr td {
            border: 1px solid;
            font-size: {{ $fontPt }}pt;
            padding: {{ $padPx }}px 3px;
            font-family: Arial, Helvetica, sans-serif
        }
        table tr td.textkecil { }
        table tr td.boldtext,
        table tr td.boldtextKecil,
        table tr td.boldtextKecil2 {
            font-weight: bold;
        }
        table tr td.boldtextyellowBG {
            background-color: yellow;
            font-weight: bold;
        }
        table tr td.boldtextblueBGKecil {
            background-color: rgb(118, 214, 255);
            font-weight: bold;
        }
        table tr td.boldtextgreenBG {
            background-color: rgb(248, 124, 16);
            font-weight: bold;
        }
        table tr td.borderless {
            border: none;
            font-size: 11pt;
            height: 1;
            font-weight: bold;
        }
        table tr td.borderless2 {
            border: none;
            font-size: 9pt;
            font-weight: bold;
        }
        table tr td.borderless3 {
            border: none;
            font-size: 10pt;
            padding-bottom: 5px;
            font-weight: bold;
        }
        table {
            border-collapse: collapse;
        }
        tr {
            page-break-inside: avoid;
        }
        .signature-table {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .page-break {
            page-break-after: always;
            break-after: page;
        }
    </style>
</head>

<body>

@foreach ($pages as $pageIndex => $pageRows)
    <table width="700px">
        {{-- Judul + header kolom: DIULANG di setiap halaman --}}
        <thead>
            <tr>
                <td colspan="9" class="borderless">PT. NIRWANA ALABARE GARMENT</td>
            </tr>
            <tr>
                <td colspan="9" class="borderless2">Laporan Makan Harian</td>
            </tr>
            <tr>
                <td colspan="9" class="borderless3">
                    Hari / Tanggal :
                    {{ Carbon\Carbon::parse($tanggal)->translatedFormat('l, j F Y') }}
                </td>
            </tr>
            <tr>
                <td rowspan="2" width="29%" style="vertical-align: middle" class="boldtextblueBGKecil">Bagian</td>
                <td colspan="3" align="center" class="boldtextblueBGKecil">NON STAFF</td>
                <td colspan="3" align="center" class="boldtextblueBGKecil">STAFF</td>
                <td colspan="2" align="center" class="boldtextblueBGKecil">GRAND TOTAL</td>
            </tr>
            <tr>
                <td width="7%" align="center" class="boldtextblueBGKecil">Jumlah Karyawan</td>
                <td width="8%" align="center" class="boldtextblueBGKecil">Harga</td>
                <td width="8%" align="center" class="boldtextblueBGKecil">Jumlah</td>
                <td width="7%" align="center" class="boldtextblueBGKecil">Jumlah Karyawan</td>
                <td width="8%" align="center" class="boldtextblueBGKecil">Harga</td>
                <td width="8%" align="center" class="boldtextblueBGKecil">Jumlah</td>
                <td width="7%" align="center" class="boldtextblueBGKecil">Jumlah Karyawan</td>
                <td width="8%" align="center" class="boldtextblueBGKecil">Total</td>
            </tr>
        </thead>

        @foreach ($pageRows as $r)
            @php $value = $r['v']; @endphp

            @if ($r['type'] === 'section')
                <tr>
                    <td colspan="9" class="boldtextgreenBG">{{ $value }}</td>
                </tr>

            @elseif ($r['type'] === 'sub')
                <tr>
                    <td colspan="9" class="boldtextyellowBG">{{ $value }}</td>
                </tr>

            @elseif ($r['type'] === 'item')
                <tr>
                    <td class="textkecil">{{ $value->sub_dept_name }}</td>
                    <td class="textkecil" align="right">{{ $fmtBlank($value->non_staff) }}</td>
                    <td class="textkecil" align="right">{{ $fmtBlank($value->harga) }}</td>
                    <td class="textkecil" align="right">{{ $fmtBlank($value->jumlah) }}</td>
                    <td class="textkecil" align="right">{{ $fmtBlank($value->staff) }}</td>
                    <td class="textkecil" align="right">{{ $fmtBlank($value->harga2) }}</td>
                    <td class="textkecil" align="right">{{ $fmtBlank($value->jumlah2) }}</td>
                    <td class="textkecil" align="right">{{ $value->jumlah_karyawan }}</td>
                    <td class="textkecil" align="right">{{ $fmt($value->total) }}</td>
                </tr>

            @else {{-- subtotal (bold) & grand (oranye) --}}
                @php $cls = $r['type'] === 'grand' ? 'boldtextgreenBG' : 'boldtext'; @endphp
                <tr>
                    <td class="{{ $cls }}">{{ $value->shift }}</td>
                    <td class="{{ $cls }}" align="right">{{ $value->non_staff }}</td>
                    <td class="{{ $cls }}" align="right">{{ $fmt($value->harga) }}</td>
                    <td class="{{ $cls }}" align="right">{{ $fmt($value->jumlah) }}</td>
                    <td class="{{ $cls }}" align="right">{{ $value->staff }}</td>
                    <td class="{{ $cls }}" align="right">{{ $fmt($value->harga2) }}</td>
                    <td class="{{ $cls }}" align="right">{{ $fmt($value->jumlah2) }}</td>
                    <td class="{{ $cls }}" align="right">{{ $value->jumlah_karyawan }}</td>
                    <td class="{{ $cls }}" align="right">{{ $fmt($value->total) }}</td>
                </tr>
            @endif
        @endforeach
    </table>

    @if ($pageIndex === $totalPages - 1)
        {{-- Tanda tangan: hanya di halaman TERAKHIR --}}
        <table width="700px" class="signature-table" style="margin-top:20px;">
            <tr>
                <td colspan="5" class="boldtextKecil2" align="center">PERSETUJUAN PERMINTAAN</td>
                <td colspan="3" class="boldtextKecil2" align="center">PEMBAYARAN</td>
            </tr>
            <tr>
                <td width="12%" class="boldtext" align="center">PEMOHON</td>
                <td width="26%" class="boldtext" colspan="2" align="center">DIKETAHUI</td>
                <td width="26%" class="boldtext" colspan="2" align="center">DISETUJUI</td>
                <td width="12%" class="boldtext" align="center">KASIR</td>
                <td width="12%" class="boldtext" align="center">FIN & ACC</td>
                <td width="12%" class="boldtext" align="center">PENERIMA</td>
            </tr>
            <tr>
                <td style="height: 50px"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="font-size: 7pt; text-align: center; font-weight: bold;"><span>Mega Fitriana</span> <br> <span style="font-size: 6pt;">Chief HR</span></td>
                <td style="font-size: 7pt; text-align: center; font-weight: bold;"><span>Rudy Aristian</span> <br><span style="font-size: 6pt;">HR & GA Manager</span></td>
                <td style="font-size: 7pt; text-align: center; font-weight: bold;"><span>Tedi Nova</span> <br><span style="font-size: 6pt;">Production Manager</span></td>
                <td style="font-size: 7pt; text-align: center; font-weight: bold;"><span>Bobby Tangnga</span> <br><span style="font-size: 6pt;">General Manager</span></td>
                <td style="font-size: 7pt; text-align: center; font-weight: bold;"><span>Ronald Harsanto</span> <br><span style="font-size: 6pt;">COO PTNAG</span></td>
                <td style="font-size: 7pt; text-align: center;"><br></td>
                <td style="font-size: 7pt; text-align: center; font-weight: bold;"><span>Willy Fernandez</span> <br><span style="font-size: 6pt;">Fin&Acc Manager</span></td>
                <td style="font-size: 7pt; text-align: center;"><br></td>
            </tr>
        </table>
    @else
        {{-- Pindah ke halaman berikutnya --}}
        <div class="page-break"></div>
    @endif
@endforeach

</body>
</html>
