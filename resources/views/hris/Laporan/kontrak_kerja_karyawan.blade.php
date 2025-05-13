<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>
    <style>
        @page { margin: 20px 45px 0px 45px; }
    </style>
</head>

@foreach ($data as $key=>$value)
@if ($value->status_staff=='NON STAFF')
<body>
    <table width="527" style="border-bottom: 2px solid black;line-height: 8px;">
        <tr>
            <td width="10%"></td>
            <td align="center" width="25%" align="right"><img src="{{ public_path('assets/images/brand/logo.jpg') }}" width="30"></td>
            <td style="font-family:Arial, Helvetica, sans-serif;vertical-align:middle;font-size:10pt;font-weight:bold;padding-top:6px">PT NIRWANA ALABARE GARMENT</td>
            <td width="10%"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7pt;font-weight:bold" align="center">Jl. Raya Rancaekek – Majalaya No. 289</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7pt;font-weight:bold" align="center">Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung 40382</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7pt;font-weight:bold" align="center">Telp. +62 22 8596 2076 / +62 22 8596 2081</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7pt;text-decoration:underline;font-style:italic;padding-bottom:7px" align="center">https://nirwanagroup.co.id</td>
            <td></td>
        </tr>
    </table>
    <table width="527" style="line-height: 8px;">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7pt" align="center"><b><u>SURAT PERJANJIAN KERJA WAKTU TERTENTU</u></b></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7pt" align="center"><b>No.{{$value->no_surat}}/{{$no_form}}</b></td>
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
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="20%">Nama</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;font-weight:bold;vertical-align:top">{{$value->employee_name}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="20%">NIP</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;font-weight:bold;vertical-align:top">{{$value->nik}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top">Tempat, Tanggal lahir</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;font-weight:bold;vertical-align:top">{{$value->tempat_lahir}}, {{Carbon\Carbon::parse($value->tanggal_lahir)->translatedFormat('d F Y')}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top">Alamat</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;font-weight:bold;vertical-align:top;vertical-align:top">{{$value->alamat_rumah}}</td>
            </tr>
        </thead>
    </table>
    <table width="527">
        <tr>
            <td style="font-family:Arial, Helvetica, sans-serif;font-size:7pt;text-align:justify;padding-top:8px">
                Dalam hal ini bertindak atas nama diri sendiri, dan selanjutnya disebut <b>PIHAK KEDUA</b>.<br>
                Pada hari <b>{{Carbon\Carbon::parse($value->contract)->translatedFormat('l')}}</b> Tanggal <b>{{Carbon\Carbon::parse($value->contract)->translatedFormat('d F Y')}}</b> bertempat di PT. Nirwana Alabare Garment Kedua belah pihak sepakat untuk mengadakan Perjanjian Kerja Waktu Tertentu dengan ketentuan-ketentuan sebagai berikut:</b>.
            </td>
        </tr>
        <tr>
            <td>
                <ol style="font-family:Arial, Helvetica, sans-serif;font-size:7pt;text-align:justify;padding-left:20px">
                    <li>Pihak Pertama menerima Pihak Kedua sebagai Karyawan dengan masa kontrak dimulai sejak tanggal <b>{{Carbon\Carbon::parse($value->contract)->translatedFormat('d F Y')}}</b> sampai dengan <b>{{Carbon\Carbon::parse($value->contract_end)->translatedFormat('d F Y')}}</b>.</li>
                    <li>Pihak Pertama mempekerjakan Pihak Kedua sebagai  di bagian . Dan Pihak Kedua besedia melaksanakan tugas dan tanggung jawabnya yang diberikan Pimpinan/atasannya dengan sebaik-baiknya serta bersedia mengikuti dan patuh terhadap ketentuan-ketentuan dan prosedur yang berlaku didalamnya.</li>
                    <li>Perusahaan dapat dan berhak mengubah serta memindahkan bagian, Jabatan, tempat, dan lokasi kerja karyawan baik diluar kota maupun antar perusahaan di Nirwana Group sesuai dengan kebutuhan.</li>
                    <li>Perjanjian Kerja untuk waktu/pekerjaan tertentu ini diadakan karena tersedianya pekerjaan yang menpurut sifat atau jenis, atau yang kegiatannya akan selesai dalam waktu tertentu atau bersifat tidak tetap.</li>
                    <li>Waktu dan Jam kerja di Perusahaan 5 (lima) hari kerja dalam seminggu, dengan ketentuan 8 (delapan) jam sehari dan/atau 40 (empat puluh) jam seminggu serta hari Sabtu dan Minggu merupakan hari libur/istirahat mingguan.</li>
                    <li>Pihak Pertama akan membayar Upah/Gaji pokok kepada Pihak Kedua sebesar <b>Rp. {{number_format($umk, 2, ',', '.');}}</b> <i><b>(Tiga Juta Tujuh Ratus Lima Puluh Tujuh Ribu Dua Ratus Delapan Puluh Lima Rupiah)</b></i>, yang perhitungannya dipengaruhi oleh penilaian tingkat kemampuan, kehadiran, serta jenis pekerjaan yang dilakukan. Pembayaran upah / gaji akan dilaksanakan pada tanggal 1 (satu) setiap bulannya.</li>
                    <li>Pihak Pertama berhak melakukan perubahan sistem pengupahan dengan metode pengupahan berdasarkan satuan hasil atau pengupahan dengan sistem per jam sesuai dengan kondisi dan situasi perusahaan.</li>
                    <li>Pihak Pertama memberikan kompensasi PKWT kepada Pihak Kedua dengan besaran, metode dan waktu yang akan diatir dalam prosedur yang berlaku.</li>
                    <li>Pihak Kedua wajib mematuhi Peraturan Perusahaan yang berlaku dan menjaga ketertiban, kedisiplinan, produktivitas kerja, kerjasama dan kebersihan di lingkungan kerjanya serta merawat peralatan kerja, mesin produksi, dan barang inventaris yang menjadi tanggung jawabnya.</li>
                    <li>Pihak Kedua wajib memberitahukan melalui telepon, lisan atau tertulis jika berhalangan hadir dan memberikan alasan dengan jelas kepada pimpinan/atasannya, dan <u>bila mana 5 (lima) hari tidak masuk kerja</u> tanpa ada alasan yang sah dan tidak dapat dipertanggungjawabkan, serta sudah mendapatkan 2 Kali panggilan dari HRD maka dianggap mengundurkan diri atas permintaan sendiri.</li>
                    <li>Pihak Pertama berhak untuk mengakhiri hubungan kerja sebelum berakhirnya tanggal perjanjian kerja dengan teknis pelaksanaan yang diatur dalam peraturan perusahaan dan atau prosedur yang berlaku.</li>
                    <li>Apabila Pihak Kedua akan mengakhiri Perjanjian Kerja Waktu Tertentu dan/atau sebelum berakhirnya masa Perjanjian Kerja yang disepakati dengan Pihak Pertama, maka Pihak Kedua wajib memberitahukan kepada Pihak Pertama selambat-lambatnya 2 (dua) minggu sebelumnya.</li>
                    <li>Perjanjian kerja ini dapat diperpanjang apabila Pihak Pertama memerlukan Pihak Kedua karena suatu pekerjaan yang belum selesai, dan Pihak Pertama akan memberitahukan kepada Pihak Kedua selambat-lambatnya 7 (tujuh) hari sebelum berakhirnya masa berlakunya Perjanjian Kerja ini untuk diperpanjang, dan perpanjangan tersebut atas kepentingan kedua belah pihak.</li>
                    <li>Perjanjian Kerja ini dapat berubah sesuai dengan situasi dan kondisi Perusahaan dan atas kesepakatan kedua belah pihak atau lembaga kerja sama bipartit.</li>
                    <li>Hal-hal yang belum tercantum dalam perjanjian ini, mengenai syarat-syarat kerja, hak dan kewajiban dalam hubungan kerja diatur dalam Peraturan Perusahaan dan atau prosedur yang berlaku.</li>
                    <li>Perjanjian Kerja ini mulai berlaku sejak ditandatangani oleh kedua belah pihak sampai dengan berakhirnya masa Perjanjian Kerja, atau karena meninggalnya Pihak Kedua.</li>
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
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7pt">Solokan Jeruk,</td>
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
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:6pt;color:rgb(2, 2, 99);vertical-align:top">Rangkap Perjanjian (PKWT) ini<br> Sudah di terima oleh karyawan. <br>Pada Tanggal : {{Carbon\Carbon::parse($value->contract)->translatedFormat('d F Y')}}</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:6pt;color:rgb(2, 2, 99);vertical-align:top">Terdaftar di<br>Suku Dinas Tenaga Kerja dan Transmigrasi<br>Administrasi Kabupaten Bandung<br>Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{Carbon\Carbon::parse($value->contract)->translatedFormat('d F Y')}}<br>Nomor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
            </tr>
        </thead>
    </table>
    <table width="562" style="padding-top: 100px">
        <thead>
            <tr>
                <td style="border-bottom:1px solid black;height:24px" width="93%"></td>
                <td rowspan="2"><img src="data:image/png;base64,{{\DNS2D::getBarcodePNG(''.$value->enroll_id, 'QRCODE',)}}" alt="barcode" style="width: 42px; height: 42px;background-color:white" /></td>
            </tr>
            <tr>
                <td style="height:24px"></td>
            </tr>
        </thead>
    </table>
</body>
@else
<body>
    <table width="527" style="border-bottom: 2px solid black;line-height: 8px;">
        <tr>
            <td width="10%"></td>
            <td align="center" width="25%" align="right"><img src="{{ public_path('assets/images/brand/logo.jpg') }}" width="30"></td>
            <td style="font-family:Arial, Helvetica, sans-serif;vertical-align:middle;font-size:10pt;font-weight:bold;padding-top:6px">PT NIRWANA ALABARE GARMENT</td>
            <td width="10%"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7pt;font-weight:bold" align="center">Jl. Raya Rancaekek – Majalaya No. 289</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7pt;font-weight:bold" align="center">Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung 40382</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7pt;font-weight:bold" align="center">Telp. +62 22 8596 2076 / +62 22 8596 2081</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7pt;text-decoration:underline;font-style:italic;padding-bottom:5px" align="center">https://nirwanagroup.co.id</td>
            <td></td>
        </tr>
    </table>
    <table width="527" style="line-height: 9px;">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7.5pt;padding-top:5px" align="center"><b><u>SURAT PERJANJIAN KERJA WAKTU TERTENTU</u></b></td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;vertical-align:top;font-size:7.5pt" align="center">No.{{$value->no_surat}}/{{$no_form}}</td>
            </tr>
        </thead>
    </table>
    <table width="527">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify">
                    Yang bertandatangan dibawah ini :
                </td>
            </tr>
        </thead>
    </table>
    <table width="527">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="20%">Nama</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;font-weight:bold;vertical-align:top">RUDY ARISTIAN FAJAR</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top">Jabatan</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;font-weight:bold;vertical-align:top">HR & GA DEPARTMENT</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top">Alamat</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;font-weight:bold;vertical-align:top;vertical-align:top">JALAN RANCAEKEK MAJALAYA NO.289 DESA SOLOKAN JERUK, KECAMATAN SOLOKAN JERUK KABUPATEN BANDUNG.</td>
            </tr>
        </thead>
    </table>
    <table width="527">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify">
                    Dalam perjanjian ini bertindak untuk dan atas nama PT. Nirwana Alabare Garment berkedudukan di Jalan Rancaekek Majalaya No.289 Desa Solokan Jeruk Kecamatan Solokan Jeruk Kabupaten Bandung selanjutnya disebut  sebagai <b>PERUSAHAAN</b> atau <b>PIHAK PERTAMA</b>.
                </td>
            </tr>
        </thead>
    </table>
    <table width="527">
        <thead>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="20%">Nama</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;font-weight:bold;vertical-align:top">{{$value->employee_name}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top">ID / NIP</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;font-weight:bold;vertical-align:top">{{$value->enroll_id}}/ {{$value->nik}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top">Tempat, Tanggal lahir</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;font-weight:bold;vertical-align:top">{{$value->tempat_lahir}}, {{Carbon\Carbon::parse($value->tanggal_lahir)->translatedFormat('d F Y')}}</td>
            </tr>
            <tr>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top">Alamat</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top" width="1%">:</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;font-weight:bold;vertical-align:top;vertical-align:top">{{$value->alamat_rumah}}</td>
            </tr>
        </thead>
    </table>
    <table width="527">
        <tr>
            <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;">
                Dalam perjanjian ini bertindak untuk dan atas nama dirinya sendiri selanjutnya disebut sebagai <b>PEKERJA</b> atau <b>PIHAK KEDUA</b>.<br>Pada hari <b>{{Carbon\Carbon::parse($value->contract)->translatedFormat('l')}}</b> Tanggal <b>{{Carbon\Carbon::parse($value->contract)->translatedFormat('d F Y')}}</b> bertempat di <b>Jalan Rancaekek Majalaya No.289 Desa Solokan Jeruk Kecamatan Solokan Jeruk Kabupaten Bandung</b>, Kedua belah pihak sepakat untuk mengadakan <b>Perjanjian Kerja Waktu Tertentu</b> dengan ketentuan – ketentuan sebagai berikut :
            </td>
        </tr>
        <tr>
            <td>
                <ol style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;text-align:justify;padding-left:20px;margin-top:0px;margin-bottom:0px">
                    <li><b>PIHAK  PERTAMA</b> menerima <b>PIHAK  KEDUA</b> sebagai  Karyawan dimulai sejak Tanggal <b>{{Carbon\Carbon::parse($value->contract)->translatedFormat('d F Y')}}</b> sampai dengan tanggal <b>{{Carbon\Carbon::parse($value->contract_end)->translatedFormat('d F Y')}}</b>.</li>
                    <li><b>PIHAK  PERTAMA</b> mempekerjakan <b>PIHAK  KEDUA</b> sebagai <b>{{$value->status_staff}}</b> di bagian <b>{{$value->department_name}}</b>, dan memiliki kedudukan sebagai karyawan dengan golongan jabatan tertentu, bertanggung jawab sebagai pemikir, perencana, pelaksana dan atau pengendali atau dalam hal ini disebut staff.</li>
                    <li><b>PIHAK KEDUA</b> menyatakan bersedia mengikuti jadwal kerja yang telah ditentukan yaitu Senin s/d Jumat pukul <b>07:00 s/d 16:00</b>, atau ditentukan lain oleh <b>PIHAK PERTAMA</b>.</li>
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
                    <li>Apabila <b>PIHAK KEDUA</b> akan mengakhiri Perjanjian Kerja Waktu Tertentu dan/atau sebelum berakhirnya masa Perjanjian Kerja yang disepakati dengan PIHAK PERTAMA, maka Pihak Kedua wajib memberitahukan kepada PIHAK PERTAMA selambat-lambatnya 30 Hari sebelumnya.</li>
                    <li><b>PIHAK PERTAMA</b> mengetahui dan menyetujui bahwa seluruh materi informasi yang bersifat non-publik, termasuk namun tidak terbatas pada informasi yang berhubungan dengan pendapatan, volume bisnis, metode bisnis, sistem, rencana-rencana, akun-akun, ketentuan dalam Perjanjian ini, dan hal lain yang bersifat rahasia atau informasi hak milik yang bernilai komersil yang dimiliki oleh Perusahaan (<b>“Informasi Rahasia”</b>) akan tetap dirahasiakan dan tidak akan diungkapkan atau diberikan kepada pihak ketiga manapun tanpa persetujuan tertulis dari <b>PIHAK PERTAMA.</b><br>Ketentuan dalam pasal ini akan terus berlaku meskipun setelah putus dan/atau berakhirnya Perjanjian ini, tanpa batasan waktu.</li>
                    <li><b>PIHAK KEDUA</b> dengan ini mengakui dan menyetujui bahwa seluruh merk dagang, nama dagang, logo, hak cipta dan hak milik lainnya, termasuk namun tidak terbatas pada penciptaan, paten, rahasia dagang, penemuan, teknik, proses, alat, penyempurnaan, know-how, perbaikan, sistem, kurikulum, perubahan yang terkandung, gambar, tulisan, susunan desain, model, hasil karya seni, hasil pekerjaan pengarang dan benda berwujud dan benda tidak berwujud lainnya (<b>“Hak Kekayaan Intelektual”</b>) yang dibuat dalam hubungannya dalam Perjanjian ini baik terdaftar maupun tidak, kan tetap dan merupakan hak milik eksklusif dari Perusahaan (atau pemilik yang sesuai) adalah pemilik dari seluruh hak, title, dan kepentingan atas Hak Kekayaan Intelektual baik yang berada dalam wilayahnya atau di tempat lain di seluruh dunia. Karyawati menjamin dan setuju untuk tidak mengambil tindakan apapun yang mungkin merugikan atau mempengaruhi validas dari Hak Kekayaan Intelektual atau kepemilikan Perusahaan (atau pemilik yang sesuai) atau lisensi daripadanya dan akan berhenti menggunakan Hak Kekayaan Intelektual setelah putusnya Perjanjian ini.</li>
                    <li>Perjanjian ini diatur dan ditafsirkan berdasarkan hukum Republik Indonesia. Masing-masing Pihak setuju bahwa segala sengketa yang muncul sehubungan dengan Perjanjian ini akan diselesaikan secara musyawarah. Jika penyelesaian secara musyawarah tidak dapat dicapai oleh Para Pihak, maka Para Pihak setuju bahwa segala tindakan atau proses yang muncul atau yang berhubungan dengan Perjanjian ini akan diserahkan dan menjadi kewenangan yurisdiksi Pengadilan Indonesia.</li>
                </ol>

            </td>
        </tr>
    </table>
    <table width="527">
        <thead>
            <tr>
                <td width='20px'></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;font-weight:bold"></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;font-weight:bold;"><b>Solokan Jeruk, <u>{{Carbon\Carbon::parse(date('Y-m-d'))->translatedFormat('d F Y')}}</u></b></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;font-weight:bold"><b>PIHAK PERTAMA,</b></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;font-weight:bold"><b>PIHAK KEDUA,</b></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;font-weight:bold"><b>PT. Nirwana Alabare Garment</b></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;font-weight:bold"><b>Karyawan</b></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;font-weight:bold;padding-top:60px"><b><u>RUDY ARISTIAN FAJAR</u></b></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:7.5pt;font-weight:bold;padding-top:60px"><b><u>{{$value->employee_name}}</u></b></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:6pt;color:rgb(2, 2, 99);vertical-align:top">Rangkap Perjanjian (PKWT) ini<br> Sudah di terima oleh karyawan. <br>Pada Tanggal : {{Carbon\Carbon::parse($value->contract)->translatedFormat('d F Y')}}</td>
                <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:6pt;color:rgb(2, 2, 99);vertical-align:top">Terdaftar di<br>Suku Dinas Tenaga Kerja dan Transmigrasi<br>Administrasi Kabupaten Bandung<br>Tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{Carbon\Carbon::parse($value->contract)->translatedFormat('d F Y')}}<br>Nomor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
            </tr>
        </thead>
    </table>
    <table width="562">
        <thead>
            <tr>
                <td style="border-bottom:1px solid black;height:24px" width="93%"></td>
                <td rowspan="2"><img src="data:image/png;base64,{{\DNS2D::getBarcodePNG(''.$value->enroll_id, 'QRCODE',)}}" alt="barcode" style="width: 42px; height: 42px;background-color:white" /></td>
            </tr>
            <tr>
                <td style="height:24px"></td>
            </tr>
        </thead>
    </table>
</body>
@endif
@endforeach
</html>
