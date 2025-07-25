<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Document</title>
    <style>
         body {
            font-family: 'Times New Roman', Times, serif; /* Menggunakan font yang umum */
            font-size: 10px;
            letter-spacing: 1px;
            margin-left: 20px;
            margin-right: 20px;
        }
        table {
            border-collapse: collapse;
        }
        .purchase-order {
            height: auto;
            margin: 0;
            padding-left: 5mm;
            padding-right: 5mm;
            padding-top: 0px;
        }
    </style>
</head>
@foreach ($data as $key=>$value)
<body>
    <div style="width: 100%; height: 1250px; position: relative; background-color: rgb(255, 255, 255)">
    <table class="purchase-order" width="100%" style="">
        <thead>
            <tr>
                {{-- <td width="100%" style="vertical-align: middle; text-align: center;" colspan="2" rowspan="4"> --}}
                    {{-- <img height="50" src="{{ public_path('assets/images/brand/nirwana logo.jpg') }}" alt=""> --}}
                {{-- </td> --}}
                <td style="vertical-align: middle; text-align: center;">
                    <img height="65" src="{{ public_path('assets/image/header-kop.png') }}" alt="">
                </td>
                {{-- <td style="vertical-align: middle; font-size: 30pt; text-align: center; font-weight: 800; line-height: 0px; font-family: 'Arial', sans-serif; letter-spacing: 0.5px;" colspan="8" rowspan="4">PT NIRWANA ALABARE GARMENT</td> --}}
            </tr>
        </thead>
    </table>
    <table width="100%" style="font-family: 'Arial', sans-serif; ">
        <thead>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center;  font-size: 10pt; font-weight: 800;">Jl. Raya Rancaekek – Majalaya No. 289</td>
            </tr>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center;  font-size: 10pt; font-weight: 800;">Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung 40382</td>
            </tr>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center;  font-size: 10pt; font-weight: 800;">Telp. 022-85962081</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:3px; background-color: #000;"></td>
            </tr>
        </thead>
    </table>
      <table width="100%" style="">
        <thead>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center; height:10px;"></td>
            </tr>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center;  font-size: 20pt; font-weight: 800;">SURAT PERINGATAN</td>
            </tr>
            <tr>
                @php
                    $bulan = \Carbon\Carbon::parse($value->tanggal_mulai)->month;
                    $bulanRomawi = [
                        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
                        5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
                        9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
                    ];
                    $tahun = \Carbon\Carbon::parse($value->tanggal_mulai)->year;
                @endphp

                <td width="100%" style="justify-content: center; text-align: center; font-size: 15pt; text-decoration: underline;">
                    No.{{$value->no_form}}/HRD-NAG/SP/{{strtolower($value->surat_peringatan) == 'sp_1' ? 1 : (strtolower($value->surat_peringatan) == 'sp_2' ? 2 : (strtolower($value->surat_peringatan) == 'sp_3' ? 3 : ''))}}/{{ $bulanRomawi[$bulan] }}/{{ $tahun }}
                </td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="width: 90%; margin-left:30px;">
        <thead>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center; height:16px;"></td>
            </tr>
            <tr>
                <td style="vertical-align: middle; font-size: 14pt; text-align: justify;">
                    Dengan memperhatikan peraturan perusahaan mengenai tindakan pendisiplinan karyawan
                    yang bekerja di PT. Nirwana Alabare Garment, maka dengan ini dipandang perlu diberikan
                    surat peringatan kepada karyawan / ti berikut :
                </td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:20px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="width: 90%; margin-left:30px;">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; width:10%;">Nama</td>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;">:</td>
                <td style="vertical-align: middle; font-size: 14pt; width:50%;">{{$value->employee_name}}</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="width: 90%; margin-left:30px;">
        <thead>
           <tr>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; width:10%;">NPP</td>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;">:</td>
                <td style="vertical-align: middle; font-size: 14pt; width:50%;">{{$value->nik}}</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="width: 90%; margin-left:30px;">
        <thead>
          <tr>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; width:10%;">Jabatan</td>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;">:</td>
                <td style="vertical-align: middle; font-size: 14pt; width:50%;">{{$value->status_jabatan}}</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="width: 90%; margin-left:30px;">
        <thead>
          <tr>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; width:10%;">Bagian</td>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;">:</td>
                <td style="vertical-align: middle; font-size: 14pt; width:50%;">{{$value->sub_dept_name}}</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="width: 90%; margin-left:30px;">
        <thead>
          <tr>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; width:6.5%;">Departmen</td>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;">:</td>
                <td style="vertical-align: middle; font-size: 14pt; width:50%;">{{$value->department_name}}</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:30px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="width: 90%; margin-left:30px;">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 14pt; width:25%;">Sehubungan dengan hal tersebut yang bersangkutan diberikan Surat Peringatan :</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:20px;"></td>
            </tr>
        </thead>
    </table>
   <table width="100%" style="width: 90%; margin-left:30px;">
    <thead>
        <tr>
            <td width="100%" style="justify-content: center; text-align: center;  font-size: 15pt; font-weight: 800;">
                Ke -
                @if(strtolower($value->surat_peringatan) == strtolower('sp_1'))
                    1 Satu
                @endif
                @if(strtolower($value->surat_peringatan) == strtolower('sp_2'))
                    2 Dua
                @endif
                @if(strtolower($value->surat_peringatan) == strtolower('sp_3'))
                    3 Tiga
                @endif
            </td>
        </tr>
    </thead>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:20px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="width: 90%; margin-left:30px;">
        <thead>
            <tr>
                <td style=" font-size: 14pt; text-align: justify;">
                    Karyawan tersebut telah melakukan pelanggaran Peraturan Perusahaan pasal {{substr($value->kode_pasal, 0,2)}} ayat {{substr($value->pasal, 2,2)}}. “{{ str_replace('.', '', $value->desc_surat_peringatan) }}"{{ $value->alasan_pelanggaran ? ', '.$value->alasan_pelanggaran : '' }}
                </td>
            </tr>
        </thead>
    </table>
     <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:30px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="width: 90%; margin-left:30px;">
        <thead>
            <tr>
                <td style="font-size: 14pt;  text-align: justify;">
                   Sanksi yang diberikan kepada <b>{{$value->employee_name}}</b> berlaku mulai tanggal {{" "}} <b>{{Carbon\Carbon::parse($value->tanggal_mulai)->translatedFormat('d F Y')}}</b> sampai dengan <b>{{Carbon\Carbon::parse($value->tanggal_sampai)->translatedFormat('d F Y')}}</b>.
                </td>
            </tr>
        </thead>
    </table>
      <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:30px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="width: 90%; margin-left:30px;">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 14pt; text-align: justify;">
                    Perusahaan mengharapkan atas pelanggaran yang telah dilakukan agar dapat diperbaiki. Jika
                    Saudara masih melakukan pelanggaran yang sama atau pelanggaran lainnya selama masa
                    peringatan ini, maka kami akan memberikan sanksi berupa surat peringatan 2 atau surat
                    peringatan 3 (tiga) dan atau pemutusan hubungan kerja Saudara
                </td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:30px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="width: 90%; margin-left:30px;">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 12pt; width:25%;">Bandung, <span style="text-decoration: underline"> {{Carbon\Carbon::parse(Date('Y-m-d'))->translatedFormat('d F Y')}} </span></td>
            </tr>
            <tr>
                <td style="vertical-align: middle; font-size: 12pt; width:25%;">Diberikan Oleh</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:30px;"></td>
            </tr>
        </thead>
    </table>
    @if($value->status_jabatan == 'OPERATOR' || $value->status_jabatan == 'ADMINISTRASI')
    <table width="100%" style="width: 90%; margin-left:30px; font-family: 'Times New Roman', Times, serif; font-size: 11pt;">
        <thead>
            <tr style="height: 65px; vertical-align: bottom;">
                <!-- Nama + Garis kiri -->
                <td style="text-align: left;">
                    <div style="width:200px; border-bottom: 1px solid #000; text-align: center;">
                        RUDY ARISTIAN FAJAR
                    </div>
                </td>

                <!-- Garis Leader -->
                <td style="text-align: center;">
                    <div style="width: 100px; border-bottom: 1px solid #000; margin: 0 auto;">&nbsp;</div>
                </td>

                <!-- Garis Chief -->
                <td style="text-align: center;">
                    <div style="width: 100px; border-bottom: 1px solid #000; margin: 0 auto;">&nbsp;</div>
                </td>

                <!-- Nama + Garis kanan -->
                <td style="text-align: center;">
                    <div style="width:200px; border-bottom: 1px solid #000; margin: 0 auto;">
                        {{ $value->employee_name }}
                    </div>
                </td>
            </tr>

            <!-- Jabatan -->
            <tr style="height: 25px;">
                <td style="text-align: left;">Manager HRGA-Compliance</td>
                <td style="text-align: center;">Leader</td>
                <td style="text-align: center;">Chief</td>
                <td style="text-align: center;">Karyawan</td>
            </tr>
        </thead>
    </table>
    @endif
    @if($value->status_jabatan == 'STAFF')
    <table width="100%" style="width: 90%; margin-left:30px; font-family: 'Times New Roman', Times, serif; font-size: 11pt;">
        <thead>
            <tr style="height: 65px; vertical-align: bottom;">
                <!-- Nama + Garis kiri -->
                <td style="text-align: left;">
                    <div style="width:200px; border-bottom: 1px solid #000; text-align: center;">
                        RUDY ARISTIAN FAJAR
                    </div>
                </td>

                <!-- Garis Leader -->
                <td style="text-align: center;">
                    <div style="width: 100px; border-bottom: 1px solid #000; margin: 0 auto;">&nbsp;</div>
                </td>

                <!-- Garis Chief -->
                <td style="text-align: center;">
                    <div style="width: 100px; border-bottom: 1px solid #000; margin: 0 auto;">&nbsp;</div>
                </td>


                <!-- Nama + Garis kanan -->
                <td style="text-align: center;">
                    <div style="width:200px; border-bottom: 1px solid #000; margin: 0 auto;">
                        {{ $value->employee_name }}
                    </div>
                </td>
            </tr>

            <!-- Jabatan -->
            <tr style="height: 25px;">
                <td style="text-align: left;">Manager HRGA-Compliance</td>
                <td style="text-align: center;">SPV</td>
                <td style="text-align: center;">Chief</td>
                <td style="text-align: center;">Karyawan</td>
            </tr>
        </thead>
    </table>
    @endif
    @if($value->status_jabatan == 'SPV' || $value->status_jabatan == 'LEADER')
     <table width="100%" style="width: 90%; margin-left:30px; font-family: 'Times New Roman', Times, serif; font-size: 11pt;">
        <thead>
            <tr style="height: 65px; vertical-align: bottom;">
                <!-- Nama + Garis kiri -->
                <td style="text-align: left;">
                    <div style="width:200px; border-bottom: 1px solid #000; text-align: center;">
                        RUDY ARISTIAN FAJAR
                    </div>
                </td>

                <!-- Garis Chief -->
                <td style="text-align: center;">
                    <div style="width: 100px; border-bottom: 1px solid #000; margin: 0 auto;">&nbsp;</div>
                </td>

                <!-- Garis Manager -->
                <td style="text-align: center;">
                    <div style="width: 100px; border-bottom: 1px solid #000; margin: 0 auto;">&nbsp;</div>
                </td>

                <!-- Nama + Garis kanan -->
                <td style="text-align: center;">
                    <div style="width:200px; border-bottom: 1px solid #000; margin: 0 auto;">
                        {{ $value->employee_name }}
                    </div>
                </td>
            </tr>

            <!-- Jabatan -->
            <tr style="height: 25px;">
                <td style="text-align: left;">Manager HRGA-Compliance</td>
                <td style="text-align: center;">Chief</td>
                <td style="text-align: center;">Manager</td>
                <td style="text-align: center;">Karyawan</td>
            </tr>
        </thead>
    </table>
    @endif
    @if($value->status_jabatan == 'CHIEF')
    <table width="100%" style="width: 90%; margin-left:30px; font-family: 'Times New Roman', Times, serif; font-size: 11pt;">
        <thead>
            <tr style="height: 65px; vertical-align: bottom;">
                <!-- Nama + Garis kiri -->
                <td style="text-align: left;">
                    <div style="width:200px; border-bottom: 1px solid #000; text-align: center;">
                        RUDY ARISTIAN FAJAR
                    </div>
                </td>

                <!-- Garis Manager -->
                <td style="text-align: center;">
                    <div style="width: 100px; border-bottom: 1px solid #000; margin: 0 auto;">&nbsp;</div>
                </td>

                 <!-- Garis General Manager -->
                <td style="text-align: center;">
                    <div style="width: 100px; border-bottom: 1px solid #000; margin: 0 auto;">&nbsp;</div>
                </td>

                <!-- Nama + Garis kanan -->
                <td style="text-align: center;">
                    <div style="width:200px; border-bottom: 1px solid #000; margin: 0 auto;">
                        {{ $value->employee_name }}
                    </div>
                </td>
            </tr>

            <!-- Jabatan -->
            <tr style="height: 25px;">
                <td style="text-align: left;">Manager HRGA-Compliance</td>
                <td style="text-align: center;">Manager</td>
                <td style="text-align: center;">General Manager</td>
                <td style="text-align: center;">Karyawan</td>
            </tr>
        </thead>
    </table>
    @endif
    @if($value->status_jabatan == 'MANAGER')
     <table width="100%" style="width: 90%; margin-left:30px; font-family: 'Times New Roman', Times, serif; font-size: 11pt;">
        <thead>
            <tr style="height: 65px; vertical-align: bottom;">
                <!-- Nama + Garis kiri -->
                <td style="text-align: left;">
                    <div style="width:200px; border-bottom: 1px solid #000; text-align: center;">
                        RUDY ARISTIAN FAJAR
                    </div>
                </td>

                <!-- Garis General Manager -->
                <td style="text-align: center;">
                    <div style="width: 100px; border-bottom: 1px solid #000; margin: 0 auto;">&nbsp;</div>
                </td>

                <!-- Nama + Garis kanan -->
                <td style="text-align: center;">
                    <div style="width:200px; border-bottom: 1px solid #000; margin: 0 auto;">
                        {{ $value->employee_name }}
                    </div>
                </td>
            </tr>

            <!-- Jabatan -->
            <tr style="height: 25px;">
                <td style="text-align: left;">Manager HRGA-Compliance</td>
                <td style="text-align: center;">General Manager</td>
                <td style="text-align: center;">Karyawan</td>
            </tr>
        </thead>
    </table>
    @endif
    {{-- <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:100px;"></td>
            </tr>
        </thead>
    </table> --}}
        <table width="40%" style="border: 2px solid #747474; position: absolute; left: 0; bottom: 10px;">
            <thead>
                <tr>
                    <td style="vertical-align: middle; font-size: 12pt; width:18%; color: #494949;">No Doc</td>
                    <td style="vertical-align: middle; font-size: 12pt; width:1%; color: #494949;">:</td>
                    <td style="vertical-align: middle; font-size: 12pt; width:50%; color: #494949;">S.17.P.HR.P-06.S-01</td>
                </tr>
                <tr>
                    <td style="vertical-align: middle; font-size: 12pt; width:18%; color: #494949;">Rev</td>
                    <td style="vertical-align: middle; font-size: 12pt; width:1%; color: #494949;">:</td>
                    <td style="vertical-align: middle; font-size: 12pt; width:50%; color: #494949;">2</td>
                </tr>
            </thead>
        </table>
        <table width="27%" style="position: absolute; right: 0; bottom: 10px; font-style: italic;">
            <thead>
                <tr>
                    <td style="vertical-align: middle; font-size: 12pt; width:18%; color: #494949;">Tembusan</td>
                    <td style="vertical-align: middle; font-size: 12pt; width:1%; color: #494949;">:</td>
                    <td style="vertical-align: middle; font-size: 12pt; width:50%; color: #494949;">Serikat Pekerja</td>
                </tr>
            </thead>
        </table>
</div>
    <div style="page-break-after: always;"></div>
     <table class="purchase-order" width="100%" style="">
        <thead>
            <tr>
                {{-- <td width="100%" style="vertical-align: middle; text-align: center;" colspan="2" rowspan="4"> --}}
                    {{-- <img height="50" src="{{ public_path('assets/images/brand/nirwana logo.jpg') }}" alt=""> --}}
                {{-- </td> --}}
                <td style="vertical-align: middle; text-align: center;">
                    <img height="65" src="{{ public_path('assets/image/header-kop.png') }}" alt="">
                </td>
                {{-- <td style="vertical-align: middle; font-size: 30pt; text-align: center; font-weight: 800; line-height: 0px; font-family: 'Arial', sans-serif; letter-spacing: 0.5px;" colspan="8" rowspan="4">PT NIRWANA ALABARE GARMENT</td> --}}
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center;  font-size: 9pt; font-weight: 800;">Jl. Raya Rancaekek – Majalaya No. 289</td>
            </tr>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center;  font-size: 9pt; font-weight: 800;">Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung 40382</td>
            </tr>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center;  font-size: 9pt; font-weight: 800;">Telp. 022-85962081</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td style="height:3px; background-color: #000;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="width: 90%; margin-left:30px;">
        <thead>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center; height:10px;"></td>
            </tr>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center;  font-size: 20pt; font-weight: 800; letter-spacing: 0.5px;">TANGGAPAN KARYAWAN</td>
            </tr>
        </thead>
    </table>
     <table width="100%" style="">
        <thead>
            <tr>
                <td width="100%" style=" height:40px;"></td>
            </tr>
        </thead>
    </table>
     <table width="100%" style="width: 90%; margin-left:30px;">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;">1.</td>
                <td style="vertical-align: middle; font-size: 14pt; width:80%;">Tanggapan terhadap tindakan pendisiplinan yang diberikan:</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td width="100%" style=" height:10px;"></td>
            </tr>
        </thead>
    </table>
     <table width="100%" style="width: 90%; margin-left:40px;">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;"><div style="border: 1px solid #000; height:20px; width:20px;"></div></td>
                <td style="vertical-align: middle; font-size: 14pt; width:19%;">Ada</td>
                <td style="vertical-align: middle; font-size: 14pt; width:10%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;"><div style="border: 1px solid #000; height:20px; width:20px;"></div></td>
                <td style="vertical-align: middle; font-size: 14pt; width:19%;">Tidak</td>
            </tr>
        </thead>
    </table>
     <table width="100%" style="">
        <thead>
            <tr>
                <td width="100%" style=" height:30px;"></td>
            </tr>
        </thead>
    </table>
     <table width="100%" style="width: 90%; margin-left:30px;">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;"></td>
                <td style="vertical-align: middle; font-size: 14pt; width:1%;">2.</td>
                <td style="vertical-align: middle; font-size: 14pt; width:80%;">Penjelasan Jika ada Tanggapan:</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td width="100%" style=" height:10px;"></td>
            </tr>
        </thead>
    </table>
     <table width="100%" style="border: 1px solid #000; width: 85%; margin-left:60px;">
        <thead>
            <tr>
                <td style="vertical-align: middle; font-size: 14pt; height: 500px;"></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td width="100%" style=" height:30px;"></td>
            </tr>
        </thead>
    </table>
    <table width="90%" style="text-align: center;">
        <thead>
            <tr>
                <td width="65%" style=" height:10px; font-size: 14pt;"></td>
                <td width="35%" style=" height:10px; font-size: 14pt;">Tanda Tangan</td>
            </tr>
        </thead>
    </table>
     <table width="100%" style="">
        <thead>
            <tr>
                <td width="100%" style=" height:90px;"></td>
            </tr>
        </thead>
    </table>
     <table width="90%" style="text-align: center;">
        <thead>
            <tr>
                <td width="65%" style=" height:10px; font-size: 14pt;"></td>
                <td width="35%" style=" height:10px; font-size: 14pt; text-decoration: underline; letter-spacing: 0.5px;">{{$value->employee_name}}</td>
            </tr>
            <tr>
                <td width="65%" style=" height:10px; font-size: 14pt;"></td>
                <td width="35%" style=" height:10px; font-size: 14pt;">Karyawan</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="">
        <thead>
            <tr>
                <td width="100%" style=" height:200px;"></td>
            </tr>
        </thead>
    </table>
    <table width="40%" style="border: 2px solid #747474; position: absolute; left: 0; bottom: 10px;">
            <thead>
                <tr>
                    <td style="vertical-align: middle; font-size: 12pt; width:18%; color: #494949;">No Doc</td>
                    <td style="vertical-align: middle; font-size: 12pt; width:1%; color: #494949;">:</td>
                    <td style="vertical-align: middle; font-size: 12pt; width:50%; color: #494949;">S.17.P.HR.P-06.S-01</td>
                </tr>
                <tr>
                    <td style="vertical-align: middle; font-size: 12pt; width:18%; color: #494949;">Rev</td>
                    <td style="vertical-align: middle; font-size: 12pt; width:1%; color: #494949;">:</td>
                    <td style="vertical-align: middle; font-size: 12pt; width:50%; color: #494949;">2</td>
                </tr>
            </thead>
        </table>
</body>
@endforeach
</html>
