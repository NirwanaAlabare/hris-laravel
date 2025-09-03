<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Document</title>
    <style>
         body {
            font-family: Arial, sans-serif; /* Menggunakan font yang umum */
            font-size: 10px;
        }
        table {
            border-collapse: collapse;
        }
        .penilaian-radio {
        appearance: none;
        -webkit-appearance: none;
        width: 13px;
        height: 13px;
        border: 2px solid #555;
        border-radius: 50%;
        position: relative;
        }

        .penilaian-radio:checked::before {
        content: "";
        position: absolute;
        top: 1px;
        left: 1px;
        width: 7px;
        height: 7px;
        background-color: #555;
        border-radius: 50%;
        }

        .penilaian_radio_rekomendasi {
        appearance: none;
        -webkit-appearance: none;
        width: 9px;
        height: 9px;
        border: 2px solid #555;
        border-radius: 50%;
        position: relative;
        vertical-align: bottom; /* 👈 penting */
        margin-right: 6px;
        }

        .penilaian_radio_rekomendasi:checked::before {
        content: "";
        position: absolute;
        top: -1px;
        left: -1px;
        width: 7px;
        height: 7px;
        background-color: #555;
        border-radius: 50%;
        }

    .container {
      position: relative;
      width: 100%;
      height: 500px;
      border: 1px solid #000;
      padding: 0px;
      margin: 0px;
    }
    .custom-table {
    border-collapse: collapse;
    }

.custom-table th,
.custom-table td {
  border: 1px solid black;
  padding: 6px 8px;
  text-align: center;
}

    .discipline {
      position: absolute;
      top: 0;
      left: 0;
      width: 580px;
      border: 1px solid #000;
    }
    .discipline th:first-child,
    .discipline td:first-child {
      text-align: left;
    }
    .discipline caption {
      caption-side: top;
      text-align: left;
      font-weight: bold;
      height: 20px;
    }
    .score {
      position: absolute;
      top: 0;
      right: 0px;
      width: 270px;
    }
    .score table {
      width: 100%;
    }
    .score td {
      text-align: left;
    }
    .score td:last-child {
      text-align: center;
    }
    .known {
      position: absolute;
      top: 180px;
      right: -1px;
      width: 270px;
      height: 80px;
      border: 1px solid black;
      text-align: center;
    }
    .known p {
      margin-top: 40px;
    }
    .recommendation {
      position: absolute;
      bottom: 155px;
      left: 0;
      width: 48.9%;
      border-right: 1px solid black;
      border-bottom: 1px solid black;
      padding-left:9px;
      height: 115px;
    }

    .recommendation label {
        display: block;
        align-items: center;
        margin: 4px 0;
    }

    @page { margin: 20px 20px 40px 20px; }
    </style>
</head>
@foreach ($data as $key=>$value)
<body>
    <table width="100%" style="border-top: 1px solid; border-left: 1px solid;">
        <thead>
            <tr>
                <td width="100px" style="vertical-align: middle; text-align: center;border-bottom: 1px solid;" colspan="2" rowspan="4">
                    <img height="60" src="{{ public_path('assets/images/brand/nirwana logo.jpg') }}" alt="">
                </td>
                <td style="vertical-align: middle; font-size: 12pt; text-align: center; font-weight: 800;border-bottom: 1px solid; border-left: 1px solid;" colspan="8" rowspan="4">FORMULIR PENILAIAN KINERJA
                    KARYAWAN</td>
                <td colspan="2" class="border-left" style="border-left: 1px solid; font-size: 7.5pt; height: 18px;">Kode Dokumen</td>
                <td colspan="3" class="border-right" style="border-left: 1px solid; font-size: 7.5pt; border-right: 1px solid;">: F.16.HR.NAG.P-03.F-01.01</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 7.5pt; height: 18px;">Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 7.5pt;">: 1</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 7.5pt; height: 18px;">Tanggal Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 7.5pt;">: 14 Desember 2023</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid;border-bottom: 1px solid; font-size: 7.5pt; height: 18px;">Tanggal Berlaku</td>
                <td colspan="3" class="border-right" style="border: 1px solid; border-bottom: 1px solid; font-size: 7.5pt;">: 18 Desember 2023</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="border-bottom:1px solid black; border-top:0px; letter-spacing: 1px; border-left: 1px solid; border-right: 1px solid;">
        <thead>
            <tr>
                <td style="height:2px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="16%">ID / NIK</td>
                <td style="font-size:8pt;vertical-align:top;text-align:left;" width="2%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%">{{ $value->nik }}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%">Bagian</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%">{{ $value->sub_dept_name }}</td>
            </tr>
            <tr>
                <td style="height:2px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="16%">Nama</td>
                <td style="font-size:8pt;vertical-align:top;text-align:left;" width="2%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%">{{ $value->employee_name }}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%">Jabatan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%">{{ $value->status_jabatan }}</td>
            </tr>
            <tr>
                <td style="height:2px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="16%">Department</td>
                <td style="font-size:8pt;vertical-align:top;text-align:left;" width="2%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%">{{ $value->department_name }}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="10%">Dievaluasi Oleh</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%"></td>
            </tr>
            <tr>
                <td style="height:2px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="16%">Tanggal Masuk Kerja</td>
                <td style="font-size:8pt;vertical-align:top;text-align:left;" width="2%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%">{{ \Carbon\Carbon::parse($value->join_date)->translatedFormat('d F Y') }}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="11%">Periode Penilaian</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%">
                    @if(isset($value->tgl_penilaian_akhir) && $value->tgl_penilaian_akhir)
                    {{ \Carbon\Carbon::parse($value->contract ?? $value->contract)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($value->tgl_penilaian_akhir)->translatedFormat('d F Y') }}
                    @endif
                </td>
            </tr>
            <tr>
            <tr>
                <td style="height:2px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;">Status Karyawan</td>
                <td style="font-size:8pt;vertical-align:top">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;">{{$value->status_aktif}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left; font-style: italic;" width="17%">
                    @if(isset($value->penilaian->tgl_akhir_kontrak) && $value->penilaian->tgl_akhir_kontrak)
                    Perpanjangan PKS {{ \Carbon\Carbon::parse($value->penilaian->tgl_akhir_kontrak)->translatedFormat('d F Y') }}
                    @else
                        Perpanjangan PKS {{ \Carbon\Carbon::parse($value->contract_end)->translatedFormat('d F Y') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="height:2px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="height:2px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left; font-weight:bold">A. Penilaian Kinerja</td>
                <td style="font-size:8pt;vertical-align:top"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;"></td>
                <td style="font-size:8pt;vertical-align:top"></td>
                <td style="font-size:8pt;vertical-align:top"></td>
                <td style="font-size:8pt;vertical-align:top"></td>
            </tr>
        </thead>
    </table>
    <table style="width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 7.5pt; border-left: 1px solid; border-right: 1px solid;">
        <thead>
          <tr>
            <th style=" padding: 5px; text-align: center; width: 69%;">
              Uraian Tugas & Tanggung Jawaban
            </th>
            <th style=" padding: 5px; text-align: center; width: 25%;">
              Target Pencapaian Kerja
            </th>
          </tr>
        </thead>
        <tbody style="font-family: sans-serif; font-size: 7.5pt;">
          <tr>
            <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px;">{{$value->penilaian->uraian_tugas_1 ?? ''}}</td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px;">{{$value->penilaian->target_pencapaian_1 ?? ''}}</td>
          </tr>
          <tr>
            <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px;">{{$value->penilaian->uraian_tugas_2 ?? ''}}</td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px;">{{$value->penilaian->target_pencapaian_2 ?? ''}}</td>
          </tr>
          <tr>
            <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px;">{{$value->penilaian->uraian_tugas_3 ?? ''}}</td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px;">{{$value->penilaian->target_pencapaian_3 ?? ''}}</td>
          </tr>
          <tr>
            <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px;">{{$value->penilaian->uraian_tugas_4 ?? ''}}</td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px;">{{$value->penilaian->target_pencapaian_4 ?? ''}}</td>
          </tr>
          <tr>
            <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px; border-bottom: 1px solid #000;">{{$value->penilaian->uraian_tugas_5 ?? ''}}</td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px;  border-bottom: 1px solid #000;">{{$value->penilaian->target_pencapaian_5 ?? ''}}</td>
          </tr>
        </tbody>
    </table>
    <table style="width: 100%; border-collapse: collapse; font-size: 12px; text-align: center; font-family: sans-serif; font-size: 7.5pt; font-weight: bold;">
        <tr>
            <td rowspan="2" style="width: 25%; font-weight: 800; vertical-align: middle; font-size:7.5pt; border-right: 1px solid #000; border-bottom: 1px solid #000; border-left: 1px solid;">
                    Penilaian Kinerja
            </td>
            <td colspan="2" style="width: 20%; border-right: 1px solid #000; height:35px;">
                <div style="font-weight: 800; font-size:7.5pt;">
                    Kinerja dibawah standar, tidak efisien dan efektif, tidak konsisten
                </div>
            </td>
            <td style="width: 10%; border-right: 1px solid #000;">
                <div style="font-weight: 800; font-size:7.5pt;">
                    Kinerja memenuhi standar,<br>efektif dan efisien
                </div>
            </td>
            <td colspan="2" style="width: 20%; border-right: 1px solid #000;">
                <div style="font-weight: 800; font-size:7.5pt;">
                    Kinerja diatas standar, selalu mencari solusi dari setiap masalah
                </div>
            </td>
        </tr>
        <tr>
          <td style="border: 1px solid #000; background-color: #f8d7da; padding: 5px;">
            10<br>
            {{-- <input type="radio" name="nilai_kinerja" value="10" class="penilaian-radio" {{ ($value->penilaian->nilai_kinerja ?? null) == 10 ? 'checked' : '' }} > --}}
            <span style="font-size: 20px; color: #555;">
                {!! ($value->penilaian->nilai_kinerja ?? null) == 10 ? '&#9679;' : '&#9675;' !!}
            </span>
          </td>
          <td style="border: 1px solid #000; background-color: #ffe5b4; padding: 5px;">
            20<br>
            {{-- <input type="radio" name="nilai_kinerja" id="nilai_kinerja" value="20" class="penilaian-radio" {{ ($value->penilaian->nilai_kinerja ?? null) == 20 ? 'checked' : '' }}> --}}
            <span style="font-size: 20px; color: #555;">
                {!! ($value->penilaian->nilai_kinerja ?? null) == 20 ? '&#9679;' : '&#9675;' !!}
            </span>
          </td>
          <td style="border: 1px solid #000; background-color: #fff3cd; padding: 5px;">
            30<br>
            {{-- <input type="radio" name="nilai_kinerja" id="nilai_kinerja" value="30" class="penilaian-radio" {{ ($value->penilaian->nilai_kinerja ?? null) == 30 ? 'checked' : '' }}> --}}
            <span style="font-size: 20px; color: #555;">
                {!! ($value->penilaian->nilai_kinerja ?? null) == 30 ? '&#9679;' : '&#9675;' !!}
            </span>
          </td>
          <td style="border: 1px solid #000; background-color: #d4edda; padding: 5px;">
            40<br>
            {{-- <input type="radio" name="nilai_kinerja" id="nilai_kinerja" value="40" class="penilaian-radio" {{ ($value->penilaian->nilai_kinerja ?? null) == 40 ? 'checked' : '' }}> --}}
            <span style="font-size: 20px; color: #555;">
                {!! ($value->penilaian->nilai_kinerja ?? null) == 40 ? '&#9679;' : '&#9675;' !!}
            </span>
          </td>
          <td style="border: 1px solid #000; background-color: #c3e6cb; padding: 5px;">
            50<br>
            {{-- <input type="radio" name="nilai_kinerja" id="nilai_kinerja" value="50" class="penilaian-radio" {{ ($value->penilaian->nilai_kinerja ?? null) == 50 ? 'checked' : '' }}> --}}
            <span style="font-size: 20px; color: #555;">
                {!! ($value->penilaian->nilai_kinerja ?? null) == 50 ? '&#9679;' : '&#9675;' !!}
            </span>
          </td>
        </tr>
      </table>

    <table width="100%" style="border-bottom:1px solid black; border-top:0px; border-left: 1px solid; border-right: 1px solid;">
        <thead>
            <tr>
                <td style="padding-left: 10px; padding-top:3px; font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left; font-weight:bold; height:16px;  border-right: 1px solid #000;" colspan="6">B. Penilaian Kompetensi</td>
            </tr>
        </thead>
    </table>
    <table style="width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 7.5pt;border-right: 1px solid #000;border-left: 1px solid #000;">
        <thead>
          <tr>
                <th rowspan="2" style="border-right: 1px solid black; padding: 8px; text-align: left; width:33.5%; vertical-align: middle; text-align: center;">Kompetensi</th>
                <th style="border-bottom: 1px solid #000; border-right: 1px solid #000; width:13.5%;">
                    Sangat di bawah Standard
                </th>
                <th style="border-bottom: 1px solid #000; border-right: 1px solid #000; width:13.5%;">
                    Dibawah Standard
                </th>
                <th style="border-bottom: 1px solid #000; border-right: 1px solid #000; width:13.5%;">
                    Standard
                </th>
                <th style="border-bottom: 1px solid #000; border-right: 1px solid #000; width:13.5%;">
                    Diatas Standard
                </th>
                <th style="border-bottom: 1px solid #000; width:13.5%;">
                    Sangat Diatas Standard
                </th>
          </tr>
          <tr>
            <th style="border: 1px solid black; height:20px; background-color: #f8d7da;">10</th>
            <th style="border: 1px solid black; height:20px; background-color: #ffe5b4;">20</th>
            <th style="border: 1px solid black; height:20px; background-color: #fff3cd;">30</th>
            <th style="border: 1px solid black; height:20px; background-color: #d4edda;">40</th>
            <th style="border: 1px solid black; height:20px; background-color: #c3e6cb;">50</th>
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

        @foreach ($kompetensiList as $index => $label)
        <tr>
            <td style="text-align: left; padding: 6px; border: 1px solid black;">
                {{ $label }}
            </td>

            @foreach ([10, 20, 30, 40, 50] as $nilai)
                <td style="text-align: center; border: 1px solid black; font-size: 20px; color: #555;">
                    {!! isset($value->penilaian[$index]) && $value->penilaian[$index] == $nilai ? '&#9679;' : '&#9675;' !!}
                </td>
            @endforeach
        </tr>
        @endforeach

        {{-- Debug tampilkan semua index yg tersedia --}}
        {{-- @php
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
            <td class="text-start"
                style="line-height: 1.2; padding: 6px; black;border: 1px solid black; border-left:none;">
                {{ $kompetensi }}
            </td>
            @foreach ([10, 20, 30, 40, 50] as $nilai)
                <td style="text-align: center; border: 1px solid black;">
                    <input type="radio" class="penilaian-radio" name="kompetensi[{{ $index }}]" value="{{ $nilai }}" {{ isset($value->penilaian[$index]) && $value->penilaian[$index] == $nilai ? 'checked' : '' }}>
                </td>
            @endforeach
        </tr>
        @endforeach --}}
        </tbody>
    </table>
    <table width="100%" style="border-bottom:1px solid black; border-top:0px; border-left: 1px solid; border-right: 1px solid;">
        <thead>
            <tr>
                <td style="padding-right: 10px; padding-top:3px; font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:right; font-weight:bold; height:16px;border-right: 1px solid;" colspan="6">Nilai : Total dari semua nilai dibagi 6</td>
            </tr>
        </thead>
    </table>



    <div class="container custom-table" style="border:none; padding:0px; margin:0px;">
        <!-- Penilaian Kedisiplinan -->
        <table class="discipline" style="border-right: 1px solid;">
          <caption style="padding-left: 10px; padding-top:3px; border-left: 1px solid;">C. Penilaian Kedisiplinan (Diisi Oleh Bagian HRD)</caption>
          <div style="height: 30px; border-right: 1px solid;">
          </div>
          <thead>
            <tr>
              <th style="border-left:none;">Kategori Pengurangan</th>
              <th style="border-left:none;">Pengurangan</th>
              <th style="border-left:none;">Akumulasi Kejadian</th>
              <th style="border-left:none;">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr>
                <td style="border-left:none;">Surat Peringatan 3</td>
                <td style="border-left: none;">6</td>
                <td style="border-left: none;">{{$value->penilaian['kejadian']['sp3_kali'] ?? ''}}</td>
                <td style="border-left: none;">{{$value->penilaian['total']['sp3_kali'] ?? ''}}</td>
            </tr>
            <tr>
                <td style="border-left:none;">Surat Peringatan 2</td>
                <td style="border-left: none;">4</td>
                <td style="border-left: none;">{{$value->penilaian['kejadian']['sp2_kali'] ?? ''}}</td>
                <td style="border-left: none;">{{$value->penilaian['total']['sp2_kali'] ?? ''}}</td>
            </tr>
            <tr>
                <td style="border-left:none;">Surat Peringatan 1</td>
                <td style="border-left: none;">2</td>
                <td style="border-left: none;">{{$value->penilaian['kejadian']['sp1_kali'] ?? ''}}</td>
                <td style="border-left: none;">{{$value->penilaian['total']['sp1_kali'] ?? ''}}</td>
            </tr>
            <tr>
                <td style="border-left:none;">Kecelakaan Kerja Karena Kelalaian</td>
                <td style="border-left: none;">2</td>
                <td style="border-left: none;">{{$value->penilaian['kejadian']['kecelakaan_kali'] ?? ''}}</td>
                <td style="border-left: none;">{{$value->penilaian['total']['kecelakaan_kali'] ?? ''}}</td>
            </tr>
            <tr>
                <td style="border-left:none;">Mangkir</td>
                <td style="border-left: none;">1</td>
                <td style="border-left: none;">{{$value->penilaian['kejadian']['mangkir_kali'] ?? ''}}</td>
                <td style="border-left: none;">{{$value->penilaian['total']['mangkir_kali'] ?? ''}}</td>
            </tr>
            <tr>
                <td style="border-left:none;">Ijin</td>
                <td style="border-left: none;">0.5</td>
                <td style="border-left: none;">{{$value->penilaian['kejadian']['ijin_kali'] ?? ''}}</td>
                <td style="border-left: none;">{{$value->penilaian['total']['ijin_kali'] ?? ''}}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; font-weight: bold; border-left:none;">Total Pengurangan</td>
                <td style="border-left: none;">{{$value->penilaian['total_pengurangan'] ?? ''}}</td>
            </tr>

          </tbody>
        </table>
        {{-- <pre>{{ dd($value->penilaian) }}</pre> --}}

        <!-- Nilai Akhir -->
        <div class="score" style="padding-top: 20px;">
          <table>
            <tr>
                <td colspan="2" style="border: 1px solid #000; border-bottom: 0px solid #000; border-right: 1px solid #000; text-align: center; height:17px; line-height:1">Nilai Akhir</td>
            </tr>
            <tr><td style="width:50%">Penilaian Kinerja</td><td>{{$value->penilaian->nilai_kinerja ?? 0}}</td></tr>
            <tr><td>Penilaian Kompeten</td><td>{{$value->penilaian->rata_rata_kompetensi ?? 0}}</td></tr>
            <tr><td>Penilaian Kedisiplinan</td><td>{{$value->penilaian['total_pengurangan'] ?? 0}}</td></tr>
            <tr><td style="font-weight: bold;">Nilai Akhir</td><td style="font-weight: bold;">{{$value->penilaian->nilai_akhir ?? 0}}</td></tr>
          </table>
        </div>

        <div style="height: 30px; border-right: 1px solid; position: absolute; right: 0px; top:150px">
        </div>
        <!-- Diketahui -->
        <table class="table table-bordered m-0 p-0 known" style="border-right: 1px solid;">
            <tbody>
                <tr>
                    <td colspan="2" style=" border-bottom:none; text-align: center; height:13px; line-height:1;border-right: none;">Diketahui</td>
                </tr>
                <tr>
                    <td colspan="2" style="border-bottom:none; height: 60px;border-right:none;"></td>
                </tr>
                <tr>
                    <td colspan="2" style=" text-align: center; height:25px; line-height:1;border-right:none; letter-spacing: 1px;">{{$value->employee_name}}</td>
                </tr>
            </tbody>
        </table>
        <div style="height: 30px; border-right: 1px solid; position: absolute; right: 0px; top:315px;">
        </div>
        <!-- Rekomendasi -->
        <div class="recommendation" style="border-left:1px solid;">
            <div style="height: 3px;"></div>
          <div ><strong>D. Rekomendasi Tindak Lanjut</strong></div>
          <label><input type="radio" class="penilaian_radio_rekomendasi" {{ $value->penilaian ? $value->rekomendasi_perpanjang_kontrak == 'perpanjang' ? 'checked' : '' : ''}} name="rekomendasi_perpanjang_kontrak" value="perpanjang" id="rekomendasi_perpanjang_kontrak"> Perpanjangan Kontrak {{ $value ? $value->perpanjang_bulan == 0 ? '___________' : $value->perpanjang_bulan : '___________'}} Bulan</label>
          <label><input type="radio" class="penilaian_radio_rekomendasi" {{ $value->penilaian ? $value->rekomendasi_phk == 'phk' ? 'checked' : '' : ''}} name="rekomendasi_phk" value="phk" id="rekomendasi_phk"> Tidak Perpanjang Kontrak / PHK</label>
          <label><input type="radio" class="penilaian_radio_rekomendasi" {{ $value->penilaian ? $value->rekomendasi_demosi == 'demosi' ? 'checked' : '' : ''}} name="rekomendasi_demosi" value="demosi" id="rekomendasi_demosi"> Demosi</label>
          <label><input type="radio" class="penilaian_radio_rekomendasi" {{ $value->penilaian ? $value->rekomendasi_promosi == 'promosi' ? 'checked' : '' : ''}} name="rekomendasi_promosi" value="promosi" id="rekomendasi_promosi"> Promosi</label>
          <label><input type="radio" class="penilaian_radio_rekomendasi" {{ $value->penilaian ? $value->rekomendasi_training == 'training' ? 'checked' : '' : ''}} name="rekomendasi_training" value="training" id="rekomendasi_training"> Training / Pengembangan, Sebutkan Judul / Tujuan {{$value ? $value->judul_training ? $value->judul_training : '___________'  : '___________'}}</label>
         </div>
        <table width="100%" style="position: absolute; bottom: -20px;">
            <thead>
                <tr>
                    <td width="25%" style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align: center; height:20px; padding-top:8px;" >Penilai</td>
                    <td width="25%" style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align: center; height:20px; padding-top:8px;" >Diketahui</td>
                    <td width="25%" style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align: center; height:20px; padding-top:8px;">Diketahui</td>
                    <td width="25%" style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align: center; height:20px; padding-top:8px;border-right:1px solid;">Disetujui</td>
                </tr>
                <tr>
                    <td style="height: 85px;border:1px solid black;border-right:none;"></td>
                    <td style="height: 85px;border:1px solid black;"></td>
                    <td style="border:1px solid black;"></td>
                    <td style="border:1px solid black;border-right:1px solid;"></td>
                </tr>
                <tr>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black; text-align: center; height:25px; line-height:1; padding-top:10px;"> {{ $value->penilaian->penilai ?? '' }}</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black; text-align: center; height:25px; line-height:1; padding-top:10px;">Chief / Manager</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black; text-align: center; height:25px; line-height:1; padding-top:10px;">HRD</td>
                    <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black; text-align: center; height:25px; line-height:1; padding-top:10px;">General Manager</td>
                </tr>
            </thead>
        </table>
    </div>
    <div style="height: 100px;">
    </div>
</body>
@endforeach
</html>
