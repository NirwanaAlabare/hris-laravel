<?php
function formatHarga($harga) {
    return 'Rp ' . number_format($harga, 0, ',', '.');
}

function formatNama($name) {
    $parts = explode(' ', $name);
    $formatted = ucfirst(strtolower($parts[0])); // Nama depan tetap normal
    if (count($parts) > 1) {
        $initials = array_map(fn($word) => strtoupper($word[0]), array_slice($parts, 1));
        $formatted .= ' ' . implode('', $initials);
    }
    return $formatted;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORM REALISASI KAS BON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        p {
            font-weight: 700;
            margin-bottom: 0;
        }
        body {
            font-family: Arial, sans-serif; /* Menggunakan font yang umum */
            font-size: 10px;
        }
        .purchase-order {
            width: 210mm;
            height: auto;
            margin: 0;
            padding-left: 10mm;
            padding-right: 10mm;
            padding-top: 0px;
        }
        .info {
            display: flex;
            justify-content: start;
            margin-left: 10px;
            margin-right: 30px;
            margin-top: 0px;
            margin-bottom: 0px;
            border: 0.1px solid #000000;
            border-bottom: none;
            margin: 5px 0px 0px 0px;
            background-color: #E7E6E6;
        }
        .line {
            border-top: 1px solid #000;
            margin: 5px 0;
        }
        .alamat p {
            margin: 5px 0;
        }
        .totals p {
            margin: 5px 0;
        }
        .separator {
            height: 1px;
            background-color: #000;
            margin: 5px 0;
        }
        .footer p {
            margin: 3px 0;
        }
        .footer {
            margin-top: 10px;
        }
        .table td, .table th {
            border: 0.1px solid #000000;
            border-bottom: none;
            font-size: 9px;
        }
        .size12px {
            font-size: 8px;
        }
        .margin0 {
            margin: 2px;
        }
    </style>
      <style>
        body {
            font-family: Arial, sans-serif;
        }
        .table-bottom {
            width: 100%;
            border-collapse: collapse;
            border: 0.1px solid #000;
            table-layout: fixed; /* This ensures all columns have equal width */
            border-top: none;
        }
        th, td {
            border: 0.1px solid #000;
            border-bottom: none;
            padding: 0px;
            margin: 0px;
            text-align: center;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .payment-header {
            background-color: #FFEB9C;
        }
        .approval-header {
            background-color: #f2f2f2;
        }
        .name-date-container {
            text-align: left;
            height: 80px;
            margin: 0px 0px 0px 0px;
        }
        .label {
            display: inline-block;
            width: 40px;
            text-align: left;
        }
        .colon {
            display: inline-block;
            width: 10px;
            text-align: center;
        }
        .value {
            display: inline-block;
            text-align: left;
        }
        .signature {
            height: 50px;
            border-bottom: 1px solid #000;
            margin: 0px 0px 0px 0px;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>
    <div class="purchase-order" id="content">
        <div class="info">
            <div class="d-flex align-items-center m-1" style="margin:0px">
                <img src="{{ asset('/assets/images/hrd/nag-logo.png') }}" width="100px" height="50px" alt="Nirwana Image">
            </div>
            <div class="d-flex justify-content-center w-100 align-items-center" style="margin:0px">
                <p class="ms-2" style="text-align:center; font-size:18px"><strong>FORM REALISASI KAS BON</strong></p>
            </div>
        </div>
        {{-- <div class="line"></div> --}}
        <div class="row" style="border-left: 1px solid #000; border-top: 0px solid #000; border-right: 1px solid #000; margin: 0px; padding: 0px;">
            <div class="col-md-6">
                <div class="size12px d-flex align-items-left">
                    <p class="col-md-4 margin0">No Form</p>
                    <p class="margin0">:</p>
                    <p class="margin0 ms-2">RKK/NAG/</p>
                </div>
                <div class="size12px d-flex align-items-left">
                    <p class="col-md-4 margin0">No Ref Kas Bon</p>
                    <p class="margin0">:</p>
                    <p class="margin0 ms-2">FPK/NAG/</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="size12px d-flex align-items-left">
                    <p class="col-md-4 margin0">Department</p>
                    <p class="margin0">:</p>
                    <p class="margin0 ms-2">{{$department->department_name}}</p>
                </div>
                <div class="size12px d-flex align-items-left">
                    <p class="col-md-4 margin0">Tanggal Realisasi</p>
                    <p class="margin0">:</p>
                    <p class="margin0 ms-2 d-flex align-items-center">

                    </p>
                </div>
            </div>
        </div>
        <table class="table m-0 p-0">
            <thead class="thead-dark">
                <tr>
                    <th style="font-weight: bold; width:70%; text-align: center; margin: 0px; padding: 3px; background-color:#E7E6E6"><strong>KETERANGAN</strong></th>
                    <th style="font-weight: bold; width:30%; text-align: center; margin: 0px; padding: 3px; background-color:#E7E6E6; border-left: none;"><strong>JUMLAH</strong></th>
                </tr>
            </thead>
            <tbody>
                @php
                $no = 1;
                $style = '';
                $total = 1;
                @endphp
                @foreach ($pengajuan->keterangan as $keterangan)
                    <tr>
                        <td style="margin: 0px; padding: 3px; text-align: left;">{{$keterangan->keterangan}}</td>
                        <td style="margin: 0px; padding: 3px; border-left: none;">{{formatHarga($keterangan->jumlah)}}</td>
                    </tr>
                @endforeach
                <tr>
                    <th style="font-weight: bold; width:70%; text-align: left; margin: 0px; padding: 3px; background-color:#E7E6E6; border-bottom:none;"><strong>TOTAL REALISASI</strong></th>
                    <th style="font-weight: bold; width:30%; text-align: center; margin: 0px; padding: 3px; background-color:#E7E6E6; border-bottom:none; border-left: none;"><strong>{{formatHarga($total_jumlah)}}</strong></th>
                </tr>
                <tr>
                    <th style="font-weight: bold; width:70%; text-align: left; margin: 0px; padding: 3px; background-color:#E7E6E6; border-bottom:none;"><strong>NILAI KAS BON (ADVANCE)</strong></th>
                    <th style="font-weight: bold; width:30%; text-align: center; margin: 0px; padding: 3px; background-color:#E7E6E6; border-bottom:none; border-left: none;"><strong>{{formatHarga($total_jumlah)}}</strong></th>
                </tr>
                <tr>
                    <th style="font-weight: bold; width:70%; text-align: left; margin: 0px; padding: 3px; background-color:#E7E6E6; border-bottom:none;"><strong>KELEBIHAN / (KEKURANGAN) DANA</strong></th>
                    <th style="font-weight: bold; width:30%; text-align: center; margin: 0px; padding: 3px; background-color:#E7E6E6; border-bottom:none; border-left: none;"><strong>{{formatHarga($total_jumlah)}}</strong></th>
                </tr>
            </tbody>
        </table>
        <table class="table-bottom">
            <tr>
                <th colspan="3" class="approval-header" style="border-left: none;">PERSETUJUAN REALISASI</th>
                <th colspan="3" class="payment-header" style="border-left: none; border-right: none;">

                    <p class="margin0 ms-2 d-flex align-items-center">
                        <input type="checkbox"/>
                        <span class="ms-2"></span>
                        PEMBAYARAN DANA
                        <span class="ms-5"></span>
                        <span class="ms-4"></span>
                        <input type="checkbox"/>
                        <span class="ms-2"></span>
                        PENGEMBALIAN DANA
                    </p>
                </th>
            </tr>
            <tr>
                <th  style="border-left: none;">PEMOHON</th>
                <th colspan="2"  style="border-left: none;">DISETUJUI</th>
                <th  style="border-left: none; background-color: #FFEB9C;">KASIR</th>
                <th  style="border-left: none; background-color: #FFEB9C;">FIN & ACC MANAGER</th>
                <th  style="border-left: none; border-right: none; background-color: #FFEB9C;">PENERIMA</th>
            </tr>
            <tr>
                <td style="border-left: none;">
                    <div class="name-date-container" style="border-left: none;">
                        <div class="signature" style="border-left: none;"> </div>
                        <div><span class="label">Nama</span><span class="colon">:</span><span class="value">{{formatNama($employee->employee_name)}}</span></div>
                        <div><span class="label">Tanggal</span><span class="colon">:</span><span class="value">{{ \Carbon\Carbon::parse($pengajuan->tanggal_kedatangan_tamu)->format('d/m/y') }}</span></div>
                    </div>
                </td>
                <td style="border-left: none;">
                    <div class="name-date-container" style="border-left: none;">
                        <div class="signature" style="border-left: none;"> </div>
                        <div><span class="label">Nama</span><span class="colon">:</span><span class="value">{{formatNama($employee_manager->employee_name)}}</span></div>
                        <div><span class="label">Tanggal</span><span class="colon">:</span><span class="value">{{ \Carbon\Carbon::parse($pengajuan->tanggal_kedatangan_tamu)->format('d/m/y') }}</span></div>
                    </div>
                </td>
                <td style="border-left: none;">
                    <div class="name-date-container" style="border-left: none;">
                        <div class="signature" style="border-left: none;"> </div>
                        <div><span class="label">Nama</span><span class="colon">:</span><span class="value">Bobby</span></div>
                        <div><span class="label">Tanggal</span><span class="colon">:</span><span class="value">{{ \Carbon\Carbon::parse($pengajuan->tanggal_kedatangan_tamu)->format('d/m/y') }}</span></div>
                    </div>
                </td>
                <td style="border-left: none;">
                    <div class="name-date-container" style="border-left: none;">
                        <div class="signature" style="border-left: none;"> </div>
                        <div><span class="label">Nama</span><span class="colon">:</span><span class="value"></span></div>
                        <div><span class="label">Tanggal</span><span class="colon">:</span><span class="value"></span></div>
                    </div>
                </td>
                <td style="border-left: none;">
                    <div class="name-date-container" style="border-left: none;">
                        <div class="signature" style="border-left: none;"> </div>
                        <div><span class="label">Nama</span><span class="colon">:</span><span class="value"></span></div>
                        <div><span class="label">Tanggal</span><span class="colon">:</span><span class="value"></span></div>
                    </div>
                </td>
                <td style="border-left: none; border-right: none;">
                    <div class="name-date-container" style="border-left: none;">
                        <div class="signature" style="border-left: none;"> </div>
                        <div><span class="label">Nama</span><span class="colon">:</span><span class="value"></span></div>
                        <div><span class="label">Tanggal</span><span class="colon">:</span><span class="value"></span></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <script type="text/javascript">
   document.addEventListener("DOMContentLoaded", function() {
        const element = document.getElementById('content');

        // Opsi untuk meningkatkan kualitas PDF
        var opt = {
            margin: [15, 0, 15, 0],
            filename: 'FORM REALISASI KAS BON.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: {
                dpi: 192, // Resolusi DPI
                scale: 4, // Skala untuk meningkatkan kualitas
                letterRendering: true,
                useCORS: true // Mengizinkan penggunaan CORS
            },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        html2pdf()
            .from(element)
            .set(opt) // Mengatur opsi
            .save()
            .then(function() {
                window.close();
            });
    });
</script>
</body>
</html>
