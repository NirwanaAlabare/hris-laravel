<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Document</title>
    <style>
        @page { margin: 10px 45px 0px 45px; }
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
@if ($value->status_staff=='NON STAFF')
<body>
   <table class="purchase-order" width="100%" style="margin-top:26px;">
        <thead>
            <tr>
                <td style="vertical-align: middle; text-align: center;">
                    <img height="52" src="{{ public_path('assets/image/header-kop.png') }}" alt="">
                </td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="font-family:Arial, Helvetica, sans-serif; line-height: 12px;" >
        <thead>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center; font-weight:700; font-size: 8.5pt; margin:0; padding:0;">Jl. Raya Rancaekek – Majalaya No. 289</td>
            </tr>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center; font-weight:700; font-size: 8.5pt;  margin:0; padding:0;">Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung 40382</td>
            </tr>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center; font-weight:700; font-size: 8.5pt; margin:0; padding:0;">Telp. 022-85962081</td>
            </tr>
        </thead>
    </table>
     <table width="100%" style="margin-top: -3px;">
        <thead>
            <tr>
                <td style="height:2px; background-color: #000;"></td>
            </tr>
        </thead>
    </table>
    <table width="527" style="line-height: 9px;">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7pt" align="center"><b><u>SURAT PERJANJIAN KERJA WAKTU TERTENTU</u></b></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7pt" align="center"><b>No.{{$value->enroll_id}}/{{$no_form}}</b></td>
            </tr>
        </thead>
    </table>
    <table width="527">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7pt;text-align:justify;padding-top:8px">
                    Perjanjian ini adalah antara :<br>
                    <b>MEGA FITRIANA HARYONO</b> dalam hal ini bertindak atas <b>HR & GA DEPARTMENT  PT. NIRWANA ALABARE GARMENT</b>, sebuah perusahaan berbentuk badan hukum perseroan terbatas yang melakukan kegiatan usaha industri pembuatan pakaian jadi, didirikan berdasarkan Akta Pendirian nomor 68.- dan telah mendapatkan pengesahan dari Menteri Hukum dan Hak Asasi Manusia Republik Indonesia nomor AHU-2465410.AH.01.01 Tahun 2015 dalam hal ini disebut <b>PIHAK PERTAMA</b>.
                </td>
            </tr>
        </thead>
    </table>
    <table width="527" style="padding-top:8px">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="20%">Nama</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;font-weight:bold;vertical-align:top">{{$value->employee_name}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="20%">NIP</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;font-weight:bold;vertical-align:top">{{$value->nik}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top">Tempat, Tanggal lahir</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;font-weight:bold;vertical-align:top">{{$value->tempat_lahir}}, {{Carbon\Carbon::parse($value->tanggal_lahir)->translatedFormat('d F Y')}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top">Alamat</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;font-weight:bold;vertical-align:top;vertical-align:top">{{$value->alamat_rumah}}</td>
            </tr>
        </thead>
    </table>
    <table width="527">
        <tr>
            <td style="font-family:Arial, Helvetica, sans-serif;font-size:7pt;text-align:justify;padding-top:8px">
                Dalam hal ini bertindak atas nama diri sendiri, dan selanjutnya disebut <b>PIHAK KEDUA</b>.<br>
                Pada hari <b>{{Carbon\Carbon::parse($contract2)->translatedFormat('l')}}</b> Tanggal <b>{{Carbon\Carbon::parse($contract2)->translatedFormat('d F Y')}}</b> bertempat di PT. Nirwana Alabare Garment kedua belah pihak sepakat untuk mengadakan Perjanjian Kerja Waktu Tertentu dengan ketentuan-ketentuan sebagai berikut:</b>.
            </td>
        </tr>
        <tr>
            <td>
                <ol style="font-family:Arial, Helvetica, sans-serif;font-size:7pt;text-align:justify;padding-left:20px">
                    <li><b>PIHAK PERTAMA</b> menerima <b>PIHAK KEDUA</b> sebagai Karyawan dengan masa kontrak dimulai sejak tanggal <b>{{Carbon\Carbon::parse($contract2)->translatedFormat('d F Y')}}</b> sampai dengan <b>{{Carbon\Carbon::parse($contract_end2)->translatedFormat('d F Y')}}</b>.</li>
                    <li><b>PIHAK PERTAMA</b> mempekerjakan <b>PIHAK KEDUA</b> sebagai <b>{{$value->status_jabatan}}</b> di bagian <b>{{$value->sub_dept_name}}</b>. Dan <b>PIHAK KEDUA</b> besedia melaksanakan tugas dan tanggung jawabnya yang diberikan Pimpinan/atasannya dengan sebaik-baiknya serta bersedia mengikuti dan patuh terhadap ketentuan-ketentuan dan prosedur yang berlaku didalamnya.</li>
                    <li>Perusahaan dapat dan berhak mengubah serta memindahkan bagian, Jabatan, tempat, dan lokasi kerja karyawan baik diluar kota maupun antar perusahaan di Nirwana Group sesuai dengan kebutuhan.</li>
                    <li>Perjanjian Kerja untuk waktu/pekerjaan tertentu ini diadakan karena tersedianya pekerjaan yang menpurut sifat atau jenis, atau yang kegiatannya akan selesai dalam waktu tertentu atau bersifat tidak tetap.</li>
                    <li>Waktu dan Jam kerja di Perusahaan 5 (lima) hari kerja dalam seminggu, dengan ketentuan 8 (delapan) jam sehari dan/atau 40 (empat puluh) jam seminggu serta hari Sabtu dan Minggu merupakan hari libur/istirahat mingguan.</li>
                    <li><b>PIHAK PERTAMA</b> akan membayar Upah/Gaji pokok kepada <b>PIHAK KEDUA</b> sebesar <b>Rp. {{number_format($umk, 2, ',', '.');}}</b> <i><b>(Tiga Juta Tujuh Ratus Lima Puluh Tujuh Ribu Dua Ratus Delapan Puluh Lima Rupiah)</b></i>, yang perhitungannya dipengaruhi oleh penilaian tingkat kemampuan, kehadiran, serta jenis pekerjaan yang dilakukan. Pembayaran upah / gaji akan dilaksanakan pada tanggal 1 (satu) setiap bulannya.</li>
                    <li><b>PIHAK PERTAMA</b> berhak melakukan perubahan sistem pengupahan dengan metode pengupahan berdasarkan satuan hasil atau pengupahan dengan sistem per jam sesuai dengan kondisi dan situasi perusahaan.</li>
                    <li><b>PIHAK PERTAMA</b> memberikan kompensasi PKWT kepada <b>PIHAK KEDUA</b> dengan besaran, metode dan waktu yang akan diatir dalam prosedur yang berlaku.</li>
                    <li><b>PIHAK KEDUA</b> wajib mematuhi Peraturan Perusahaan yang berlaku dan menjaga ketertiban, kedisiplinan, produktivitas kerja, kerjasama dan kebersihan di lingkungan kerjanya serta merawat peralatan kerja, mesin produksi, dan barang inventaris yang menjadi tanggung jawabnya.</li>
                    <li><b>PIHAK KEDUA</b> wajib memberitahukan melalui telepon, lisan atau tertulis jika berhalangan hadir dan memberikan alasan dengan jelas kepada pimpinan/atasannya, dan <u>bila mana 5 (lima) hari tidak masuk kerja</u> tanpa ada alasan yang sah dan tidak dapat dipertanggungjawabkan, serta sudah mendapatkan 2 Kali panggilan dari HRD maka dianggap mengundurkan diri atas permintaan sendiri.</li>
                    <li><b>PIHAK PERTAMA</b> berhak untuk mengakhiri hubungan kerja sebelum berakhirnya tanggal perjanjian kerja dengan teknis pelaksanaan yang diatur dalam peraturan perusahaan dan atau prosedur yang berlaku.</li>
                    <li>Apabila <b>PIHAK KEDUA</b> akan mengakhiri Perjanjian Kerja Waktu Tertentu dan/atau sebelum berakhirnya masa Perjanjian Kerja yang disepakati dengan <b>PIHAK PERTAMA</b>, maka <b>PIHAK KEDUA</b> wajib memberitahukan kepada <b>PIHAK PERTAMA</b> selambat-lambatnya 2 (dua) minggu sebelumnya.</li>
                    <li>Perjanjian kerja ini dapat diperpanjang apabila <b>PIHAK PERTAMA</b> memerlukan <b>PIHAK KEDUA</b> karena suatu pekerjaan yang belum selesai, dan <b>PIHAK PERTAMA</b> akan memberitahukan kepada <b>PIHAK KEDUA</b> selambat-lambatnya 7 (tujuh) hari sebelum berakhirnya masa berlakunya Perjanjian Kerja ini untuk diperpanjang, dan perpanjangan tersebut atas kepentingan kedua belah pihak.</li>
                    <li>Perjanjian Kerja ini dapat berubah sesuai dengan situasi dan kondisi Perusahaan dan atas kesepakatan kedua belah pihak atau lembaga kerja sama bipartit.</li>
                    <li>Hal-hal yang belum tercantum dalam perjanjian ini, mengenai syarat-syarat kerja, hak dan kewajiban dalam hubungan kerja diatur dalam Peraturan Perusahaan dan atau prosedur yang berlaku.</li>
                    <li>Perjanjian Kerja ini mulai berlaku sejak ditandatangani oleh kedua belah pihak sampai dengan berakhirnya masa Perjanjian Kerja, atau karena meninggalnya <b>PIHAK KEDUA</b>.</li>
                    <li>Apabila terdapat perselisihan antara kedua belah pihak, maka kedua belah pihak setuju untuk menyelesaikannya secara musyawarah untuk mencapai mufakat melalui lembaga kerja sama bipartit.</li>
                    <li>Dalam melakukan Perjanjian kerja ini kedua belah pihak dalam keadaan sadar, tidak mendapatkan paksaan dari pihak manapun serta tidak sedang menjalani/diberikan masa hukuman.</li>
                </ol>
            </td>
        </tr>
        <tr>
            <td style="font-family:Arial, Helvetica, sans-serif;font-size:7pt;text-align:justify;padding-top:8px">
                Demikian Perjanjian Kerja ini dibuat oleh kedua belah pihak dalam keadaan sehat jasmani dan rohani tanpa ada tekanan atau paksaan dari manapun.
            </td>
        </tr>
    </table>
    <table width="527" style="line-height: 8px;padding-top:8px">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7pt">Solokan Jeruk, {{Carbon\Carbon::parse($contract2)->translatedFormat('d F Y')}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7pt;font-weight:bold" width="45%"><b>PIHAK PERTAMA,</b></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7pt;font-weight:bold"><b>PIHAK KEDUA,</b></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7pt;font-weight:bold"><b>PT. Nirwana Alabare Garment</b></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7pt;font-weight:bold"><b>Karyawan</b></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7pt;font-weight:bold;padding-top:75px"><b><u>MEGA FITRIANA HARYONO</u></b></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7pt;font-weight:bold;padding-top:75px"><b><u>{{$value->employee_name}}</u></b></td>
            </tr>
            <tr>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:6pt;color:rgb(2, 2, 99);vertical-align:top">Rangkap Perjanjian (PKWT) ini<br> Sudah di terima oleh karyawan. <br>Pada Tanggal : {{Carbon\Carbon::parse($contract2)->translatedFormat('d F Y')}}</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:6pt;color:rgb(2, 2, 99);vertical-align:top">Terdaftar di<br>Suku Dinas Tenaga Kerja dan Transmigrasi<br>Administrasi Kabupaten Bandung<br>Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{Carbon\Carbon::parse($contract2)->translatedFormat('d F Y')}}<br>Nomor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
            </tr>
        </thead>
    </table>

    <table width="562" style="position: absolute; bottom: 100px;">
        <thead>
            <tr>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:6pt;color:rgb(2, 2, 99);vertical-align:top;" width="88%"><u>{{$value->employee_name}}</u></td>
            </tr>
        </thead>
    </table>
    <table width="562" style="position: absolute; bottom: 75px;">
        <thead>
            <tr>
                <td style="border-bottom:1px solid black;" width="88%"></td>
                <td rowspan="2">
                    <img src="data:image/png;base64,{{ \DNS2D::getBarcodePNG(url('hris/identity/card_employee_form_identity') . '?enroll_id=' . $value->enroll_id . '&no_form=' . urlencode($no_form) . '&type=SK_KERJA', 'QRCODE') }}" alt="barcode" style="width: 60px; height: 60px;background-color:white" />
                </td>
            </tr>
        </thead>
    </table>
</body>
@else
<body>
    <table class="purchase-order" width="100%" style="margin-top:26px;">
        <thead>
            <tr>
                <td style="vertical-align: middle; text-align: center;">
                    <img height="52" src="{{ public_path('assets/image/header-kop.png') }}" alt="">
                </td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="font-family:Arial, Helvetica, sans-serif; line-height: 12px;" >
        <thead>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center; font-weight:700; font-size: 8.5pt; margin:0; padding:0;">Jl. Raya Rancaekek – Majalaya No. 289</td>
            </tr>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center; font-weight:700; font-size: 8.5pt;  margin:0; padding:0;">Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung 40382</td>
            </tr>
            <tr>
                <td width="100%" style="justify-content: center; text-align: center; font-weight:700; font-size: 8.5pt; margin:0; padding:0;">Telp. 022-85962081</td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="margin-top: -3px;">
        <thead>
            <tr>
                <td style="height:2px; background-color: #000;"></td>
            </tr>
        </thead>
    </table>
    <table width="527" style="line-height: 9px;">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7.3pt;padding-top:3px" align="center"><b><u>SURAT PERJANJIAN KERJA WAKTU TERTENTU</u></b></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7.3pt" align="center">No.{{$value->enroll_id}}/{{$no_form}}</td>
            </tr>
        </thead>
    </table>
    <table width="527">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify">
                    Yang bertandatangan dibawah ini :
                </td>
            </tr>
        </thead>
    </table>
    <table width="527">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="20%">Nama</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;font-weight:bold;vertical-align:top">{{$value->status_jabatan == 'MANAGER' ? "BOBBY TANGNGA" : "RUDY ARISTIAN FAJAR"}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top">Jabatan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;font-weight:bold;vertical-align:top">{{$value->status_jabatan == 'MANAGER' ? "GENERAL MANAGER" : "HR & GA DEPARTMENT"}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top">Alamat</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;font-weight:bold;vertical-align:top;vertical-align:top">JALAN RANCAEKEK MAJALAYA NO.289 DESA SOLOKAN JERUK, KECAMATAN SOLOKAN JERUK KABUPATEN BANDUNG.</td>
            </tr>
        </thead>
    </table>
    <table width="527">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify">
                    Dalam perjanjian ini bertindak untuk dan atas nama PT. Nirwana Alabare Garment berkedudukan di Jalan Rancaekek Majalaya No.289 Desa Solokan Jeruk Kecamatan Solokan Jeruk Kabupaten Bandung selanjutnya disebut  sebagai <b>PERUSAHAAN</b> atau <b>PIHAK PERTAMA</b>.
                </td>
            </tr>
        </thead>
    </table>
    <table width="527">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="20%">Nama</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;font-weight:bold;vertical-align:top">{{$value->employee_name}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top">ID / NIP</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;font-weight:bold;vertical-align:top">{{$value->enroll_id}}/ {{$value->nik}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top">Tempat, Tanggal lahir</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;font-weight:bold;vertical-align:top">{{$value->tempat_lahir}}, {{Carbon\Carbon::parse($value->tanggal_lahir)->translatedFormat('d F Y')}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top">Alamat</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;font-weight:bold;vertical-align:top;vertical-align:top">{{$value->alamat_rumah}}</td>
            </tr>
        </thead>
    </table>
    @php
        $departemen_khusus = ['HRD', 'MARKETING', 'FINANCE, ACCOUNTING & TAX', 'EXIM', 'PURCHASHING', 'INFORMATION TECHNOLOGY'];
    @endphp
    <table width="527">
        <tr>
            <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;">
                Dalam perjanjian ini bertindak untuk dan atas nama dirinya sendiri selanjutnya disebut sebagai <b>PEKERJA</b> atau <b>PIHAK KEDUA</b>.<br>Pada hari <b>{{Carbon\Carbon::parse($contract2)->translatedFormat('l')}}</b> Tanggal <b>{{Carbon\Carbon::parse($contract2)->translatedFormat('d F Y')}}</b> bertempat di <b>Jalan Rancaekek Majalaya No.289 Desa Solokan Jeruk Kecamatan Solokan Jeruk Kabupaten Bandung</b>, Kedua belah pihak sepakat untuk mengadakan <b>Perjanjian Kerja Waktu Tertentu</b> dengan ketentuan – ketentuan sebagai berikut :
            </td>
        </tr>
        <tr>
            <td>
                <ol style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;text-align:justify;padding-left:20px;margin-top:0px;margin-bottom:0px">
                    <li><b>PIHAK PERTAMA</b> menerima <b>PIHAK KEDUA</b> sebagai Karyawan dimulai sejak Tanggal <b>{{Carbon\Carbon::parse($contract2)->translatedFormat('d F Y')}}</b> sampai dengan tanggal <b>{{Carbon\Carbon::parse($contract_end2)->translatedFormat('d F Y')}}</b>.</li>
                    <li><b>PIHAK PERTAMA</b> mempekerjakan <b>PIHAK KEDUA</b> sebagai <b>{{$value->status_jabatan}}</b> di bagian <b>{{$value->sub_dept_name}}</b>, dan memiliki kedudukan sebagai karyawan dengan golongan jabatan tertentu, bertanggung jawab sebagai pemikir, perencana, pelaksana dan atau pengendali atau dalam hal ini disebut staff.</li>
                    <li><b>PIHAK KEDUA</b> menyatakan bersedia mengikuti jadwal kerja yang telah ditentukan yaitu Senin s/d Jumat pukul
                    <b>
                        {{-- @if(in_array($value->department_name, $departemen_khusus))
                            07:30 s/d 16:30
                        @else
                            07:00 s/d 16:00
                        @endif --}}
                        {{ date('H:i', strtotime($value->mulai_jam_kerja)) }} s/d {{ date('H:i', strtotime($value->akhir_jam_kerja)) == '07:00' ? '16:00' : '16:30' }}
                    </b>, atau ditentukan lain oleh <b>PIHAK PERTAMA</b>.</li>
                    <li><b>PIHAK KEDUA</b> besedia melaksanakan tugas dan tanggung jawabnya yang diberikan Pimpinan/atasannya dengan sebaik-baiknya serta bersedia mengikuti dan patuh terhadap ketentuan-ketentuan dan prosedur yang berlaku didalamnya.</li>
                    <li><b>PIHAK KEDUA</b> wajib mematuhi Peraturan Perusahaan yang berlaku dan menjaga ketertiban, kedisiplinan, produktivitas kerja, kerjasama dan kebersihan di lingkungan kerjanya serta merawat peralatan kerja, fasilitas perusahaan, dan barang inventaris yang menjadi tanggung jawabnya.</li>
                    <li><b>PIHAK PERTAMA</b> dapat dan berhak mengubah serta memindahkan bagian, Jabatan, tempat, dan lokasi kerja karyawan baik diluar kota maupun antar perusahaan di Nirwana Group sesuai dengan kebutuhan dan kecakapan yang dimilikinya.</li>
                    <li><b>PIHAK KEDUA</b> akan mendapatkan upah pokok sebesar <b>Rp. {{number_format($umk, 2, ',', '.');}}</b> <i><b>(Tiga Juta Tujuh Ratus Lima Puluh Tujuh Ribu Dua Ratus Delapan Puluh Lima Rupiah)</b></i> /bulan atau Upah Minimun yang ditentukan oleh peraturan yang berlaku, atau kesepakatan yang telah dilakukan dan dibayarkan setiap tanggal 1 (satu) pada setiap bulannya, kemudian dihitung berdasarkan pada kehadiran dan pekerjaan yang dilakukan.</li>
                    <li><b>PIHAK KEDUA</b> akan dianggap mengundurkan diri atas permintaan sendiri bilamana 5 (lima) hari tidak masuk kerja tanpa ada pemberitahuan yang sesuai.</li>
                    <li><b>PIHAK PERTAMA</b> berhak untuk mengakhiri hubungan kerja jika <b>PIHAK KEDUA</b>
                        <ul style="padding-left:14px;list-style-type:square;">
                            <li>Gagal untuk menunjukkan pengetahuan, ilmu, dan kemampuan atas pekerjaannya sesuai dengan yang diharapkan, atau tidak dapat melaksanakan pekerjaannya.</li>
                            <li>Secara sadar dan sengaja tidak mematuhi perintah/petunjuk/arahan yang sah dan wajar yang diberikan oleh Perusahaan.</li>
                            <li>Bersalah atas kelalaian tertentu, melakukan pelanggaran, atau segala tindakan tidak jujur dalam melaksanakan tugasnya.</li>
                            <li>Menjadi terdakwa atau didakwa dengan pidana tertentu atau diragukan kemampuan di masa depan untuk melaksanakan tugas-tugasnya.</li>
                        </ul>
                    </li>
                    <li>Apabila <b>PIHAK KEDUA</b> akan mengakhiri Perjanjian Kerja Waktu Tertentu dan/atau sebelum berakhirnya masa Perjanjian Kerja yang disepakati dengan <b>PIHAK PERTAMA</b>, maka <b>PIHAK KEDUA</b> wajib memberitahukan kepada <b>PIHAK PERTAMA</b> selambat-lambatnya 30 Hari sebelumnya.</li>
                    <li><b>PIHAK PERTAMA</b> mengetahui dan menyetujui bahwa seluruh materi informasi yang bersifat non-publik, termasuk namun tidak terbatas pada informasi yang berhubungan dengan pendapatan, volume bisnis, metode bisnis, sistem, rencana-rencana, akun-akun, ketentuan dalam Perjanjian ini, dan hal lain yang bersifat rahasia atau merupakan informasi hak milik yang bernilai komersial yang dimiliki oleh Perusahaan (“Informasi Rahasia”), akan tetap dirahasiakan dan tidak akan diungkapkan atau diberikan kepada pihak ketiga manapun selain untuk keperluan pelaksanaan pekerjaan dan/atau kewajiban profesional yang secara sah diperlukan.
                    Setiap pengungkapan kepada pihak lain yang tidak berkaitan langsung dengan pekerjaan atau yang berada di luar lingkup kewajiban profesional, wajib mendapatkan persetujuan tertulis terlebih dahulu dari <b>PIHAK PERTAMA</b>.
                    Ketentuan dalam pasal ini akan tetap berlaku meskipun setelah putus dan/atau berakhirnya Perjanjian ini, tanpa batasan waktu.</li>
                    <li><b>PIHAK KEDUA</b> dengan ini mengakui dan menyetujui bahwa seluruh merk dagang, nama dagang, logo, hak cipta dan hak milik lainnya, termasuk namun tidak terbatas pada penciptaan, paten, rahasia dagang, penemuan, teknik, proses, alat, penyempurnaan, know-how, perbaikan, sistem, kurikulum, perubahan yang terkandung, gambar, tulisan, susunan desain, model, hasil karya seni, hasil pekerjaan pengarang dan benda berwujud dan benda tidak berwujud lainnya (<b>“Hak Kekayaan Intelektual”</b>) yang dibuat dalam hubungannya dalam Perjanjian ini baik terdaftar maupun tidak, akan tetap dan merupakan hak milik eksklusif dari Perusahaan (atau pemilik yang sesuai) adalah pemilik dari seluruh hak, title, dan kepentingan atas Hak Kekayaan Intelektual baik yang berada dalam wilayahnya atau di tempat lain di seluruh dunia. <b>PIHAK KEDUA</b> setuju untuk tidak mengambil tindakan apapun yang mungkin merugikan atau mempengaruhi validas dari Hak Kekayaan Intelektual atau kepemilikan Perusahaan (atau pemilik yang sesuai) atau lisensi daripadanya dan akan berhenti menggunakan Hak Kekayaan Intelektual setelah putusnya Perjanjian ini.</li>
                    <li>Perjanjian ini diatur dan ditafsirkan berdasarkan hukum Republik Indonesia. Masing-masing Pihak setuju bahwa segala sengketa yang muncul sehubungan dengan Perjanjian ini akan diselesaikan secara musyawarah. Jika penyelesaian secara musyawarah tidak dapat dicapai oleh Para Pihak, maka Para Pihak setuju bahwa segala tindakan atau proses yang muncul atau yang berhubungan dengan Perjanjian ini akan diserahkan dan menjadi kewenangan yurisdiksi Pengadilan Indonesia.</li>
                </ol>

            </td>
        </tr>
    </table>
    <table width="527" style="margin-top:-5px;">
        <thead>
            <tr>
                <td width='20px'></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;font-weight:bold"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;font-weight:bold;"><b>Solokan Jeruk, <u>{{Carbon\Carbon::parse($contract2)->translatedFormat('d F Y')}}</u></b></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;font-weight:bold"><b>PIHAK PERTAMA,</b></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;font-weight:bold"><b>PIHAK KEDUA,</b></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;font-weight:bold"><b>PT. Nirwana Alabare Garment</b></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;font-weight:bold"><b>Karyawan</b></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;font-weight:bold;padding-top:55px"><b><u>{{$value->status_jabatan == 'MANAGER' ? "BOBBY TANGNGA" : "RUDY ARISTIAN FAJAR"}}</u></b></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.3pt;font-weight:bold;padding-top:55px"><b><u>{{$value->employee_name}}</u></b></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:6pt;color:rgb(2, 2, 99);vertical-align:top">Rangkap Perjanjian (PKWT) ini<br> Sudah di terima oleh karyawan. <br>Pada Tanggal : {{Carbon\Carbon::parse($contract2)->translatedFormat('d F Y')}}</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:6pt;color:rgb(2, 2, 99);vertical-align:top">Terdaftar di<br>Suku Dinas Tenaga Kerja dan Transmigrasi<br>Administrasi Kabupaten Bandung<br>Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{Carbon\Carbon::parse($contract2)->translatedFormat('d F Y')}}<br>Nomor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
            </tr>
        </thead>
    </table>
    <table width="562" style="position: absolute; bottom: 57px;">
        <thead>
            <tr>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:6pt;color:rgb(2, 2, 99);vertical-align:top; padding-left:25px;" width="88%"><u>{{$value->employee_name}}</u></td>
            </tr>
        </thead>
    </table>
    <table width="562" style="position: absolute; bottom: 70px;">
        <thead>
            <tr>
                <td style="border-bottom:1px solid black;" width="88%"></td>
                <td rowspan="2">
                    <img src="data:image/png;base64,{{ \DNS2D::getBarcodePNG(url('hris/identity/card_employee_form_identity') . '?enroll_id=' . $value->enroll_id . '&no_form=' . urlencode($no_form) . '&type=SK_KERJA', 'QRCODE') }}" alt="barcode" style="width: 60px; height: 60px;background-color:white" />
                </td>
            </tr>
        </thead>
    </table>
</body>
@endif
@endforeach
</html>
