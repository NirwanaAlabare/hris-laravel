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
    <title>FORM PENILAIAN KINERJA KARYAWAN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        .penilaian-radio {
            transform: scale(1); /* Ubah angkanya sesuai ukuran yang diinginkan */
            margin: 0px;
        }
        .penilaian-kompetensi {
            transform: scale(1); /* mengecilkan radio button */
            margin: 0;
        }

    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>
    <div class="purchase-order" id="content">
        <table width="100%">
            <thead>
                <tr>
                    <td width="100px" style="vertical-align: middle; text-align: center;border: 1px solid; padding:5px;" colspan="1" rowspan="4">
                        <img height="60px" src="{{ asset('/assets/images/hrd/nag-logo.png') }}" alt="">
                    </td>
                    <td style="vertical-align: middle; font-size: 10pt; text-align: center; font-weight: 800;border: 1px solid; border-left:none; border-right:none;" width="290px" rowspan="4">FORMULIR PENILAIAN KINERJA KARYAWAN</td>
                    <td colspan="2" class="border-left" style="border: 1px solid; font-weight: 600; font-size:6pt;  text-align: left; padding-left: 4px;">Kode Dok</td>
                    <td colspan="3" class="border-right" style="border: 1px solid; font-weight: 600; font-size:6pt;  text-align: left; padding-left: 4px;  border-left:none;">: F.16.P.HR.P-010.F-014.01.01</td>
                </tr>
                <tr>
                    <td colspan="2" class="border-left" style="border: 1px solid; font-weight: 600; font-size:6pt; text-align: left; padding-left: 4px; border-top:none;">Revisi</td>
                    <td colspan="3" class="border-right" style="border: 1px solid; font-weight: 600; font-size:6pt;  text-align: left; padding-left: 4px; border-top:none; border-left:none;">: </td>
                </tr>
                <tr>
                    <td colspan="2" class="border-left" style="border: 1px solid; font-weight: 600; font-size:6pt;  text-align: left; padding-left: 4px; border-top:none;">Tanggal Revisi</td>
                    <td colspan="3" class="border-right" style="border: 1px solid; font-weight: 600; font-size:6pt;  text-align: left; padding-left: 4px; border-top:none; border-left:none;">: </td>
                </tr>
                <tr>
                    <td colspan="2" class="border-left" style="border: 1px solid;border-bottom: 1px solid; font-weight: 600; font-size:6pt;  text-align: left; padding-left: 4px; border-top:none;">Tanggal Berlaku</td>
                    <td colspan="3" class="border-right" style="border: 1px solid; border-bottom: 1px solid; font-weight: 600; font-size:6pt;  text-align: left; padding-left: 4px; border-top:none; border-left:none;">: </td>
                </tr>
            </thead>
        </table>
        {{-- <div class="line"></div> --}}
        <div class="row" style="border-left: 1px solid #000; border-top: 0px solid #000; border-right: 1px solid #000; margin: 0px; padding: 0px;">
            <div class="col-md-6">
                <div class="size12px d-flex align-items-left">
                    <p class="col-md-4 margin0">ID / NIK</p>
                    <p class="margin0">:</p>
                    <p class="margin0 ms-2">{{$data_karyawan->nik}}</p>
                </div>
                <div class="size12px d-flex align-items-left">
                    <p class="col-md-4 margin0">Nama</p>
                    <p class="margin0">:</p>
                    <p class="margin0 ms-2">
                        {{$data_karyawan->employee_name}}
                    </p>
                </div>
                <div class="size12px d-flex align-items-left">
                    <p class="col-md-4 margin0">Department</p>
                    <p class="margin0">:</p>
                    <p class="margin0 ms-2">{{$data_karyawan->department_name}}</p>
                </div>
                <div class="size12px d-flex align-items-left">
                    <p class="col-md-4 margin0">Tanggal Masuk Kerja</p>
                    <p class="margin0">:</p>
                    <p class="margin0 ms-2">{{ \Carbon\Carbon::parse($data_karyawan->join_date)->translatedFormat('d F Y') }}</p>
                </div>
                <div class="size12px d-flex align-items-left">
                    <p class="col-md-4 margin0">Status Karyawan</p>
                    <p class="margin0">:</p>
                    <p class="margin0 ms-2">{{$data_karyawan->status_aktif}}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="size12px d-flex align-items-left">
                    <p class="col-md-4 margin0">Bagian</p>
                    <p class="margin0">:</p>
                    <p class="margin0 ms-2">{{$data_karyawan->sub_dept_name}}</p>
                </div>
                <div class="size12px d-flex align-items-left">
                    <p class="col-md-4 margin0">Jabatan</p>
                    <p class="margin0">:</p>
                    <p class="margin0 ms-2">{{$data_karyawan->status_jabatan}}</p>
                </div>
                <div class="size12px d-flex align-items-left">
                    <p class="col-md-4 margin0">Dievaluasi Oleh</p>
                    <p class="margin0">:</p>
                    <p class="margin0 ms-2"></p>
                </div>
                <div class="size12px d-flex align-items-left">
                    <p class="col-md-4 margin0">Periode Penilaian</p>
                    <p class="margin0">:</p>
                    @if($data_penilaian && $data_penilaian->tgl_awal_kontrak && $data_penilaian->tgl_akhir_kontrak)
                        <p class="margin0 ms-2">
                            {{ \Carbon\Carbon::parse($data_penilaian->tgl_awal_kontrak)->translatedFormat('d F Y') }} -
                            {{ \Carbon\Carbon::parse($data_penilaian->tgl_akhir_kontrak)->translatedFormat('d F Y') }}
                        </p>
                    @else
                        <p class="margin0 ms-2">-</p>
                    @endif

                </div>
            </div>
        </div>
        <div class="row" style="border-left: 1px solid #000; border-top: 0px solid #000; border-right: 1px solid #000; margin: 0px; padding: 0px;">
            <div class="col-md-12 mt-1">
                <div class="d-flex align-items-left">
                    <p class="col-md-12 margin0" style="font-weight: 800; font-size:6pt;">A. Penilaian Kinerja</p>
                </div>
            </div>
        </div>
        <table class="table m-0 p-0">
            <thead class="thead-dark">
                <tr>
                    <th style="font-weight:700; width:75%; text-align: center; margin: 0px; padding: 3px; background-color:#FFFFFF"><strong>Uraian Tugas & Tanggung Jawaban</strong></th>
                    <th style="font-weight:700; width:25%; text-align: center; margin: 0px; padding: 3px; background-color:#FFFFFF; border-left: none;"><strong>Target Pencapaian Kerja</strong></th>
                </tr>
            </thead>
            <tbody>
                @php
                $no = 1;
                $style = '';
                $total = 1;
                @endphp
                    <tr>
                        <td style="margin: 0px; padding: 3px; height:21px; text-align: left;">{{$data_penilaian ? $data_penilaian->uraian_tugas_1 : ''}}</td>
                        <td style="margin: 0px; padding: 3px; height:21px; border-left: none;">{{$data_penilaian ? $data_penilaian->target_pencapaian_1 : ''}}</td>
                    </tr>
                    <tr>
                        <td style="margin: 0px; padding: 3px; height:21px; text-align: left;">{{$data_penilaian ? $data_penilaian->uraian_tugas_2 : ''}}</td>
                        <td style="margin: 0px; padding: 3px; height:21px; border-left: none;">{{$data_penilaian ? $data_penilaian->target_pencapaian_2 : ''}}</td>
                    </tr>
                    <tr>
                        <td style="margin: 0px; padding: 3px; height:21px; text-align: left;">{{$data_penilaian ? $data_penilaian->uraian_tugas_3 : ''}}</td>
                        <td style="margin: 0px; padding: 3px; height:21px; border-left: none;">{{$data_penilaian ? $data_penilaian->target_pencapaian_3 : ''}}</td>
                    </tr>
                    <tr>
                        <td style="margin: 0px; padding: 3px; height:21px; text-align: left;">{{$data_penilaian ? $data_penilaian->uraian_tugas_4 : ''}}</td>
                        <td style="margin: 0px; padding: 3px; height:21px; border-left: none;">{{$data_penilaian ? $data_penilaian->target_pencapaian_4 : ''}}</td>
                    </tr>
                    <tr>
                        <td style="margin: 0px; padding: 3px; height:21px; text-align: left;">{{$data_penilaian ? $data_penilaian->uraian_tugas_5 : ''}}</td>
                        <td style="margin: 0px; padding: 3px; height:21px; border-left: none;">{{$data_penilaian ? $data_penilaian->target_pencapaian_5 : ''}}</td>
                    </tr>
            </tbody>
        </table>

        <table class="text-center align-middle" style="font-size: 10pt;">
            <tr>
                <td rowspan="2" style="width: 30%; font-weight: 800; vertical-align: middle; font-size:6pt; border-right: 0px solid #000;">
                        Penilaian Kinerja
                </td>
                <td colspan="2" style="width: 20%; border-right: 0px solid #000;">
                    <div style="font-weight: 800; font-size:6pt;">
                        Kinerja dibawah standar, tidak efisien dan efektif, tidak konsisten
                    </div>
                </td>
                <td style="width: 10%; border-right: 0px solid #000;">
                    <div style="font-weight: 800; font-size:6pt;">
                        Kinerja memenuhi standar,<br>efektif dan efisien
                    </div>
                </td>
                <td colspan="2" style="width: 20%;">
                    <div style="font-weight: 800; font-size:6pt;">
                        Kinerja diatas standar, selalu mencari solusi dari setiap masalah
                    </div>
                </td>
            </tr>
            <tr>
                <!-- Skor 10 -->
                <td style="background-color: #f8d7da; font-size: 7pt; font-weight: 700; border-right: 0px solid #000;">
                    10<br>
                    <input type="radio" name="nilai_kinerja" id="nilai_kinerja" value="10" class="penilaian-radio">
                </td>
                <!-- Skor 20 -->
                <td style="background-color: #ffe5b4; font-size: 7pt; font-weight: 700; border-right: 0px solid #000;">
                    20<br>
                    <input type="radio" name="nilai_kinerja" id="nilai_kinerja" value="20" class="penilaian-radio">
                </td>
                <!-- Skor 30 -->
                <td style="background-color: #fff3cd; font-size: 7pt; font-weight: 700; border-right: 0px solid #000;">
                    30<br>
                    <input type="radio" name="nilai_kinerja" id="nilai_kinerja" value="30" class="penilaian-radio">
                </td>
                <!-- Skor 40 -->
                <td style="background-color: #d4edda; font-size: 7pt; font-weight: 700; border-right: 0px solid #000;">
                    40<br>
                    <input type="radio" name="nilai_kinerja" id="nilai_kinerja" value="40" class="penilaian-radio">
                </td>
                <!-- Skor 50 -->
                <td style="background-color: #a6e6a6; font-size: 7pt; font-weight: 700; ">
                    50<br>
                    <input type="radio" name="nilai_kinerja" id="nilai_kinerja" value="50" class="penilaian-radio">
                </td>
            </tr>
        </table>
        <div class="row" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; margin: 0px; padding: 0px;">
                <div class="d-flex align-items-left">
                    <p class="col-md-12 margin0" style="font-weight: 800; font-size:6pt;">B. Penilaian Kompetensi</p>
                </div>
        </div>
        <table class="table table-bordered text-center align-middle m-0 p-0" style="font-size: 10pt;">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 30%; vertical-align: middle; font-weight: 800; font-size:6pt;">Kompetensi</th>
                    <th style="background-color: #f8d7da;font-weight: 800; font-size:6pt; width: 10%; border-left: none; ">Sangat di bawah Standard</th>
                    <th style="background-color: #ffe5b4;font-weight: 800; font-size:6pt; width: 10%; border-left: none; ">Dibawah Standard</th>
                    <th style="background-color: #fff3cd;font-weight: 800; font-size:6pt; width: 10%; border-left: none; ">Standard</th>
                    <th style="background-color: #d4edda;font-weight: 800; font-size:6pt; width: 10%; border-left: none; ">Diatas Standard</th>
                    <th style="background-color: #a6e6a6;font-weight: 800; font-size:6pt; width: 10%; border-left: none; ">Sangat Diatas Standard</th>
                </tr>
                <tr>
                    <th style="background-color: #f8d7da;  border-left: none;">10</th>
                    <th style="background-color: #ffe5b4;  border-left: none;">20</th>
                    <th style="background-color: #fff3cd;  border-left: none;">30</th>
                    <th style="background-color: #d4edda;  border-left: none;">40</th>
                    <th style="background-color: #a6e6a6;  border-left: none;">50</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $kompetensiList = [
                        "tanggung_jawab_tugas" => "Tanggung jawab terhadap tugas yang diberikan",
                        "inisiatif_kerjasama" => "Inisiatif dan Kerjasama",
                        "akurasi_pekerjaan" => "Akurasi dalam pekerjaan",
                        "kemauan_kegigihan" => "Kemauan dan Kegigihan dalam mencapai tujuan",
                        "penyampaian_informasi" => "Penyampaian dan Penerimaan informasi",
                        "attitude_sikap_kerja" => "Attitude / Sikap Kerja"
                    ];
                @endphp

                @foreach ($kompetensiList as $index => $kompetensi)
                <tr>
                    <td class="text-start" style="line-height: 1.2;">{{ $kompetensi }}</td>
                    @foreach ([10, 20, 30, 40, 50] as $nilai)
                        <td style="line-height: 0;  border-left: none;">
                            <input type="radio" class="penilaian-kompetensi" name="kompetensi[{{ $index }}]" value="{{ $nilai }}">
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; margin: 0px; padding: 0px;">
            <div class="d-flex align-items-right">
                <p class="col-md-12 margin0 text-end" style="font-weight: 800; font-size:6pt;">Nilai : Total dari semua nilai dibagi 6</p>
            </div>
        </div>
        <div class="row" style="border-left: 1px solid #000; border-bottom: 0px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; margin: 0px; padding: 0px;">
            <div class="col-12" >
                <p class="margin0 text-left" style="font-weight: 800; font-size: 6pt; margin: 2px;">C. Penilaian Kedisiplinan (Diisi Oleh Bagian HRD)</p>
            </div>
        </div>
        <div class="row">
            <div class="col-8">
                <table class="table table-bordered m-0 p-0" style="width: 100%; font-size: 6pt;">
                    <thead>
                        <tr style="">
                            <th style="width:30%; background-color: #FFFFFF; height:17px; line-height:1">Kategori Pengurangan</th>
                            <th style="width:20%; background-color: #FFFFFF; height:17px; line-height:1;  border-left: none;">Pengurangan</th>
                            <th style="width:20%; background-color: #FFFFFF; height:17px; line-height:1;  border-left: none;">Akumulasi Kejadian</th>
                            <th style="width:20%; background-color: #FFFFFF; height:17px; line-height:1;  border-left: none;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="height:17px; line-height:1; text-align:left; padding-left:6px">Surat Peringatan 3</td>
                            <td style="height:17px; line-height:1;  border-left: none;">6</td>
                            <td style="height:17px; line-height:1;  border-left: none;"><p name="kejadian[sp3_kali]"></p></td>
                            <td style="height:17px; line-height:1;  border-left: none;"><p name="total[sp3_kali]"></p></td>
                        </tr>
                        <tr>
                            <td style="height:17px; line-height:1; text-align:left; padding-left:6px">Surat Peringatan 2</td>
                            <td style="height:17px; line-height:1;  border-left: none;">4</td>
                            <td style="height:17px; line-height:1;  border-left: none;"><p name="kejadian[sp2_kali]"></p></td>
                            <td style="height:17px; line-height:1;  border-left: none;"><p name="total[sp2_kali]"></td>
                        </tr>
                        <tr>
                            <td style="height:17px; line-height:1; text-align:left; padding-left:6px">Surat Peringatan 1</td>
                            <td style="height:17px; line-height:1;  border-left: none;">2</td>
                            <td style="height:17px; line-height:1;  border-left: none;"><p name="kejadian[sp1_kali]"></td>
                            <td style="height:17px; line-height:1;  border-left: none;"><p name="total[sp1_kali]"></td>
                        </tr>
                        <tr>
                            <td style="height:17px; line-height:1; text-align:left; padding-left:6px">Kecelakaan Kerja Karena Kelalaian</td>
                            <td style="height:17px; line-height:1;  border-left: none;">2</td>
                            <td style="height:17px; line-height:1;  border-left: none;"><p name="kejadian[kecelakaan_kali]"></td>
                            <td style="height:17px; line-height:1;  border-left: none;"><p name="total[kecelakaan_kali]"></td>
                        </tr>
                        <tr>
                            <td style="height:17px; line-height:1; text-align:left; padding-left:6px">Mangkir</td>
                            <td style="height:17px; line-height:1;  border-left: none;">1</td>
                            <td style="height:17px; line-height:1;  border-left: none;"><p name="kejadian[mangkir_kali]"></td>
                            <td style="height:17px; line-height:1;  border-left: none;"><p name="total[mangkir_kali]"></td>
                        </tr>
                        <tr>
                            <td style="height:17px; line-height:1; text-align:left; padding-left:6px">Ijin</td>
                            <td style="height:17px; line-height:1;  border-left: none;">0.5</td>
                            <td style="height:17px; line-height:1;  border-left: none;"><p name="kejadian[ijin_kali]"></td>
                            <td style="height:17px; line-height:1;  border-left: none;"><p name="total[ijin_kali]"></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="height:17px; line-height:1; text-align: center; font-weight: bold;">Total Pengurangan</td>
                            <td style="height:17px; line-height:1;  border-left: none;">{{$data_penilaian ? $data_penilaian->total_pengurangan : ''}}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="row" style="border-left: 1px solid #000; border-bottom: 0px solid #000; border-top: 1px solid #000; border-right: 0px solid #000; margin: 0px; padding: 0px;">
                        <p class="margin0 text-left" style="font-weight: 800; font-size: 6pt; margin: 2px;">D. Rekomendasi Tindak Lanjut</p>
                </div>
                <table class="table table-bordered m-0 p-0" style="width: 100%; font-size: 6pt;">
                    <tbody>
                        <tr>
                            <td class="m-0 p-0" colspan="2" style="border: 1px solid #000; border-bottom: 0px solid #000; height: 90px;">
                                <div style="line-height: 0; padding-left: 5px; text-align: left; display: flex; align-items: center; padding-top: 5px;">
                                    <p class="margin0" style="font-weight: 800; font-size: 6pt;"><input class="form-check-input" type="radio" name="rekomendasi" value="perpanjang" id="rekomendasi_perpanjang_kontrak" style="width: 10px; height: 10px;"></p>
                                    <p class="margin0" style="font-weight: 800; font-size: 6pt;">Perpanjangan Kontrak {{ $data_penilaian && ($data_penilaian->perpanjang_bulan ? $data_penilaian->perpanjang_bulan : '___________')}} Bulan</p>
                                </div>
                                <div style="line-height: 0; padding-left: 5px; text-align: left; display: flex; align-items: center; padding-top: 2px;">
                                    <p class="margin0" style="font-weight: 800; font-size: 6pt;"><input class="form-check-input" type="radio" name="rekomendasi" value="phk" id="rekomendasi_phk" style="width: 10px; height: 10px;"></p>
                                    <p class="margin0" style="font-weight: 800; font-size: 6pt;">Tidak Perpanjang Kontrak / PHK</p>
                                </div>
                                <div style="line-height: 0; padding-left: 5px; text-align: left; display: flex; align-items: center; padding-top: 2px;">
                                    <p class="margin0" style="font-weight: 800; font-size: 6pt;"><input class="form-check-input" type="radio"  name="rekomendasi" value="demosi" id="rekomendasi_demosi" style="width: 10px; height: 10px;"></p>
                                    <p class="margin0" style="font-weight: 800; font-size: 6pt;">Demosi</p>
                                </div>
                                <div style="line-height: 0; padding-left: 5px; text-align: left; display: flex; align-items: center; padding-top: 2px;">
                                    <p class="margin0" style="font-weight: 800; font-size: 6pt;"><input class="form-check-input" type="radio" name="rekomendasi" value="promosi" id="rekomendasi_promosi" style="width: 10px; height: 10px;"></p>
                                    <p class="margin0" style="font-weight: 800; font-size: 6pt;">Promosi</p>
                                </div>
                                <div style="line-height: 0; padding-left: 5px; text-align: left; display: flex; align-items: center; padding-top: 2px;">
                                    <p class="margin0" style="font-weight: 800; font-size: 6pt;"><input class="form-check-input" type="radio" name="rekomendasi" value="training" id="rekomendasi_training" style="width: 10px; height: 10px;"></p>
                                    <p class="margin0" style="font-weight: 800; font-size: 6pt;">Training / Pengembangan, Sebutkan Judul / Tujuan {{$data_penilaian && ($data_penilaian->judul_training ? $data_penilaian->judul_training : '...........................................')}}</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-4">
                <table class="table table-bordered m-0 p-0" style="width: 100%; font-size: 6pt;">
                    <tbody>
                        <tr>
                            <td colspan="2" style="border: 1px solid #000; border-bottom: 0px solid #000; text-align: center; height:17px; line-height:1">Nilai Akhir</td>
                        </tr>
                        <tr>
                            <td style="height:18px; line-height:2; width:50%;height:17px; line-height:1; border-right: 0px solid #000;">Penilaian Kinerja</td>
                            <td style="height:18px; line-height:2; width:50%;height:17px; line-height:1">{{$data_penilaian ? $data_penilaian->nilai_kinerja : ''}}</td>
                        </tr>
                        <tr>
                            <td style="height:18px; line-height:2; width:50%;height:17px; line-height:1; border-right: 0px solid #000;">Penilaian Kompeten</td>
                            <td style="height:18px; line-height:2; width:50%;height:17px; line-height:1">{{$data_penilaian ? $data_penilaian->rata_rata_kompetensi : ''}}</td>
                        </tr>
                        <tr>
                            <td style="height:18px; line-height:2; width:50%;height:17px; line-height:1; border-right: 0px solid #000;">Penilaian Kedisiplinan</td>
                            <td style="height:18px; line-height:2; width:50%;height:17px; line-height:1">{{$data_penilaian ? $data_penilaian->total_pengurangan : ''}}</td>
                        </tr>
                        <tr>
                            <td style="height:18px; line-height:2; width:50%;height:17px; line-height:1; border-right: 0px solid #000;">Nilai Akhir</td>
                            <td style="height:18px; line-height:2; width:50%;height:17px; line-height:1">{{$data_penilaian ? $data_penilaian->nilai_akhir : ''}}</td>
                        </tr>
                    </tbody>
                </table>

                <table class=" m-0 p-0" style="width: 100%; font-size: 6pt; border: 0px solid #000;">
                    <tbody>
                        <tr>
                            <td colspan="2" style="border-right: 1px solid #000; border-left: 0px solid #000; height: 20px;"></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered m-0 p-0" style="width: 100%; font-size: 6pt;">
                    <tbody>
                        <tr>
                            <td colspan="2" style="border: 1px solid #000; border-bottom:none; text-align: center; height:13px; line-height:1;">Diketahui</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border: 1px solid #000;border-bottom:none; height: 60px;"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border: 1px solid #000; text-align: center; height:25px; line-height:1;">{{$data_karyawan->employee_name}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class=" m-0 p-0" style="width: 100%; font-size: 6pt;">
                    <tbody>
                        <tr>
                            <td colspan="2" style="border-right: 1px solid #000; border-left: 0px solid #000; border-top: 0px solid #000; height: 17px;"></td>
                        </tr>
                    </tbody>
                </table>
                <table class=" m-0 p-0" style="width: 100%; font-size: 6pt;">
                    <tbody>
                        <tr>
                            <td colspan="2" style="border-right: 1px solid #000; border-left: 0px solid #000; border-top: 0px solid #000; height: 17px;"></td>
                        </tr>
                    </tbody>
                </table>
                <table class=" m-0 p-0" style="width: 100%; font-size: 6pt;">
                    <tbody>
                        <tr>
                            <td colspan="2" style="border-right: 1px solid #000; border-left: 0px solid #000; border-top: 0px solid #000; height: 19px;"></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <table class="table-bottom">
            <tr>
                <th  style="border-left: none; background-color: #FFFFFF;">Penilai</th>
                <th  style="border-left: none; background-color: #FFFFFF;">Diketahui</th>
                <th  style="border-left: none; background-color: #FFFFFF;">Diketahui</th>
                <th  style="border-left: none; border-right: none; background-color: #FFFFFF;">Disetujui</th>
            </tr>
            <tr>
                <td style="border-left: none;">
                        <div class="signature" style="border-left: none;"> </div>
                </td>
                <td style="border-left: none;">
                        <div class="signature" style="border-left: none;"> </div>
                </td>
                <td style="border-left: none;">
                        <div class="signature" style="border-left: none;"> </div>
                </td>
                <td style="border-left: none; border-right: none;">
                        <div class="signature" style="border-left: none;"> </div>
                </td>
            </tr>
            <tr>
                <td style="height:20px; border-top:none; line-height:2;">
                    {{ $data_penilaian ? $data_penilaian->penilai : '' }}
                </td>
                <td style="border-left: none; height:20px; border-top:none; line-height:2;">
                    Chief / Manager
                </td>
                <td style="border-left: none; height:20px; border-top:none; line-height:2;">
                    HRD
                </td>
                <td style="border-left: none; border-right: none; height:20px; border-top:none; line-height:2;">
                    General Manager
                </td>
            </tr>
        </table>
    </div>

    <script type="text/javascript">
       $(document).ready(function () {

            const nilaiKinerja = "{{ $data_penilaian && $data_penilaian->nilai_kinerja ? $data_penilaian->nilai_kinerja : 0 }}";
            const data_penilaian = {!! json_encode($data_penilaian ?? []) !!};


            $('input[name="nilai_kinerja"]').each(function () {
                if ($(this).val() === nilaiKinerja) {
                    $(this).prop('checked', true);
                }
            });

            const radio = $('input[name="rekomendasi"][value="' + data_penilaian.rekomendasi_tindak_lanjut + '"]');
            radio.prop('checked', true);


            const kompetensiFields = [
                    "tanggung_jawab_tugas",
                    "inisiatif_kerjasama",
                    "akurasi_pekerjaan",
                    "kemauan_kegigihan",
                    "penyampaian_informasi",
                    "attitude_sikap_kerja"
                ];
                kompetensiFields.forEach(function(field) {
                    $('input[name="kompetensi[' + field + ']"]').each(function () {
                        if ($(this).val() == data_penilaian[field]) {
                            $(this).prop('checked', true);
                        } else {
                            $(this).prop('checked', false);
                        }
                    });
                });
                if (data_penilaian.kejadian) {
                    Object.entries(data_penilaian.kejadian).forEach(([key, val]) => {
                        $('p[name="kejadian[' + key + ']"]').text(val);
                    });
                }
                if (data_penilaian.total) {
                    Object.entries(data_penilaian.total).forEach(([key, val]) => {
                        $('p[name="total[' + key + ']"]').text(val);
                    });
                }


                setTimeout(function () {
                    const element = document.getElementById('content');

                    var opt = {
                        margin: [0, 0, 0, 0],
                        filename: 'FORM PENILAIAN KINERJA KARYAWAN.pdf',
                        image: { type: 'jpeg', quality: 1 },
                        html2canvas: {
                            dpi: 192,
                            scale: 4,
                            letterRendering: true,
                            useCORS: true
                        },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                    };

                    html2pdf().from(element).set(opt).save().then(function () {
                        window.close();
                    });
                }, 300);
                //      var opt = {
                //         margin: [0, 0, 0, 0],
                //         filename: 'FORM PENILAIAN KINERJA KARYAWAN.pdf',
                //         image: { type: 'jpeg', quality: 1 },
                //         html2canvas: {
                //             dpi: 192,
                //             scale: 4,
                //             letterRendering: true,
                //             useCORS: true
                //         },
                //         jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                //     };

                // const element = document.getElementById('content');
                //   html2pdf()
                //     .from(element)
                //     .set(opt)
                //     .then(function() {
                //         // window.close();
                //     });
        });


        // document.addEventListener("DOMContentLoaded", function() {
        //         const element = document.getElementById('content');

        //         var opt = {
        //             margin: [0, 0, 0, 0],
        //             filename: 'FORM PENILAIAN KINERJA KARYAWAN.pdf',
        //             image: { type: 'jpeg', quality: 1 },
        //             html2canvas: {
        //                 dpi: 192, // Resolusi DPI
        //                 scale: 4, // Skala untuk meningkatkan kualitas
        //                 letterRendering: true,
        //                 useCORS: true // Mengizinkan penggunaan CORS
        //             },
        //             jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        //         };

        //         html2pdf()
        //         .from(element)
        //         .set(opt)
        //         .save()
        //         .then(function() {
        //             window.close();
        //         });

        //     });
    </script>
</body>
</html>
