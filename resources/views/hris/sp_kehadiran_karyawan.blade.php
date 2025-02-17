
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Panggilan</title>
    <style>
         @page { margin: 5px 40px 0px 40px; }

        .container {
            background: white;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header img {
            height: 50px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
        }

        .header p {
            font-size: 14px;
            margin: 0;
        }

        .content {
            margin: 0px 0px 0px 45px
        }

        .content p {
            margin: 1px 0 0 0;
            font-size: 11px;

        }
        .signature {
            text-align: left;
            margin:5px 0 0 45px
        }

        .signature p {
            margin: 5px 0;
            font-size: 11px;
        }

        .divider {
            border-top: 2px dashed black;
            margin: 15px 0;
        }

        .footer {
            text-align: left;
            margin-top: 5px;
        }

        .footer img {
            height: 50px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 5px;
            vertical-align: top;
        }
        .thead.td {
            vertical-align: top;
        }

        .no-padding td {
            padding: 0;
        }

        .bold {
            font-weight: bold;
        }

        .content table {
            border-collapse: collapse;
            margin-top: 20px;
        }
        .content td {
            padding: 1px 1px;
            margin: 5px 0;
            font-size: 11px;
        }
        .content tr {
            margin: 0;
        }
        .footer table {
            border-collapse: collapse;
            margin-top: 20px;
        }
        .footer td {
            padding: 1px 1px;
            margin: 5px 0;
            font-size: 11px;
        }
        .footer tr {
            margin: 0;
        }
        .footer {
            margin:15px 0 0 45px;
            position: relative;
        }

        .footer p {
            margin: 1px 0 0 0;
            font-size: 11px;

        }

        .image1 {
            position: absolute;
            top: 80;
            left: 5;
    }
    </style>
</head>
<body>
        <div class="container">
            <div class="thead">
            <table class="no-padding" width="506" style="border-bottom: 2px solid black;">
                <tr>
                    <td width="10%"></td>
                    <td width="13%" align="right"><img src="{{ public_path('assets/images/brand/logo.jpg') }}" width="42" style="margin-right: 10px"></td>
                    <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:18pt;font-weight:bold">PT NIRWANA ALABARE GARMENT</td>
                    <td width="10%"></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;font-weight:bold">Jl. Raya Rancaekek – Majalaya No. 289</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;font-weight:bold">Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung 40382</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;font-weight:bold">Telp. +62 22 8596 2076 / +62 22 8596 2081</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;color:blue;"><i>https://nirwanagroup.co.id</i></td>
                    <td></td>
                </tr>
            </table>
            </div>
            <div class="content">
                <table>
                    <tr>
                        <td width="100px">Nomor</td>
                        <td>:</td>
                        <td>3872/HRD-NAC/EXT/XII/2024</td>
                    </tr>
                    <tr>
                        <td width="100px">Lampiran</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td width="100px">Perihal</td>
                        <td>:</td>
                        <td>Surat Panggilan Ke-II</td>
                    </tr>
                </table>

                <p style="margin-top:10px">Kepada</p>
                <p style="margin-top:10px">Yth.</p>
                <p>Di</p>
                <p>Tempat</p>

                <p style="margin-top:15px">Sehubungan dengan data yang ada pada kami mengenai absensi atas nama :</p>

                <table style="margin-top:5px">
                    <tr>
                        <td width="100px" class="">Nama</td>
                        <td>:</td>
                        <td>{{$data['employee_name']}}</td>
                    </tr>
                    <tr>
                        <td width="100px">NIP</td>
                        <td>:</td>
                        <td>{{$data['nik']}}</td>
                    </tr>
                    <tr>
                        <td width="100px">Bagian</td>
                        <td>:</td>
                        <td>{{$data['department_name']}}</td>
                    </tr>
                    <tr>
                        <td width="100px">Jabatan</td>
                        <td>:</td>
                        <td>{{$data['status_jabatan']}}</td>
                    </tr>
                    <tr>
                        <td width="100px">Alamat</td>
                        <td>:</td>
                        <td style="font-size:10px;">{{$data['alamat_rumah']}}</td>
                    </tr>
                </table>
                @php
                    $formatter = new NumberFormatter('id', NumberFormatter::SPELLOUT);
                    $jumlahHariMangkir = $formatter->format($data['jumlah_hari_mangkir']);
                @endphp
                <p style="margin-top:20px">Dengan ini kami beritahukan bahwa Sdra/i telah melakukan pelanggaran tata tertib yang ada di PT. Nirwana Alabare Garment yaitu tidak masuk
                kerja dengan TANPA KETERANGAN berturut-turut selama <b>{{$data['jumlah_hari_mangkir']}}</b> ({{ ucfirst($jumlahHariMangkir) }}) hari pada tanggal <b>{{Carbon\Carbon::parse($data['mulai'])->translatedFormat('d F')}}</b> hingga <b>{{Carbon\Carbon::parse($data['selesai'])->translatedFormat('d F Y')}}</b></p>
                <p style="margin-top:20px">Maka dengan ini kami memanggil Sdra/i untuk hadir dan kembali bekerja dengan membawa bukti alasan pelanggaran absensi tersebut.
                    Demikian surat panggilan ini kami kirimkan, atas kerjasamanya kami ucapkan terima kasih.
                </p>
            </div>

            <div class="signature">
                <p>Bandung, {{Carbon\Carbon::parse(date('Y-m-d'))->translatedFormat('d F Y')}}</p>
                <p>PT. Nirwana Alabare Garment</p>
                <img class="" src="{{ public_path('assets/images/hrd/ttd-hr.png') }}" width="114">
                <p style="margin-left:18px; margin-top:0px">Rudy Aristian Fajar</p>
                <p>HRGA - Compliance Manager</p>
            </div>

            <div class="divider"></div>

            <div class="thead">
                <table class="no-padding" width="506" style="border-bottom: 2px solid black;">
                    <tr>
                        <td width="10%"></td>
                        <td width="13%" align="right"><img src="{{ public_path('assets/images/brand/logo.jpg') }}" width="42" style="margin-right: 10px"></td>
                        <td style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:18pt;font-weight:bold">PT NIRWANA ALABARE GARMENT</td>
                        <td width="10%"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;font-weight:bold">Jl. Raya Rancaekek – Majalaya No. 289</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;font-weight:bold">Desa Solokan Jeruk Kecamatan Solokan Jeruk, Kabupaten Bandung 40382</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;font-weight:bold">Telp. +62 22 8596 2076 / +62 22 8596 2081</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td align="center" colspan="2" style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-size:10pt;color:blue;"><i>https://nirwanagroup.co.id</i></td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class="footer">
                <p style="font-weight:bold">TANDA TERIMA SURAT PANGGILAN</p>
                <table>
                    <tr>
                        <td width="100px">Nomor</td>
                        <td>:</td>
                        <td>3872/HRD-NAC/EXT/XII/2024</td>
                    </tr>
                </table>
                <p align="left" style="margin-top:10px;">Panggilan ke -, atas nama :</p>
                <table style="margin-top:1px;">
                <tr>
                        <td width="100px" class="">Nama</td>
                        <td>:</td>
                        <td>{{$data['employee_name']}}</td>
                    </tr>
                    <tr>
                        <td width="100px">NIP</td>
                        <td>:</td>
                        <td>{{$data['nik']}}</td>
                    </tr>
                    <tr>
                        <td width="100px">Bagian</td>
                        <td>:</td>
                        <td>{{$data['department_name']}}</td>
                    </tr>
                    <tr>
                        <td width="100px">Jabatan</td>
                        <td>:</td>
                        <td>{{$data['status_jabatan']}}</td>
                    </tr>
                    <tr>
                        <td width="100px">Alamat</td>
                        <td>:</td>
                        <td style="font-size:10px;">{{$data['alamat_rumah']}}</td>
                    </tr>
                </table>
                <p style="margin-top:10px">Tidak masuk kerja dengan TANPA KETERANGAN berturut-turut selama <b>{{$data['jumlah_hari_mangkir']}}</b> ({{ ucfirst($jumlahHariMangkir) }}) hari pada tanggal <b>{{Carbon\Carbon::parse($data['mulai'])->translatedFormat('d F')}}</b> hingga <b>{{Carbon\Carbon::parse($data['selesai'])->translatedFormat('d F Y')}}</b> .</p>
                <p style="margin-top:10px">Diterima pada tanggal :</p>
                <p>Diterima Oleh :</p>
                <p>Hubungan dengan nama diatas :</p>
                <br><br>
                <div>
                    <div>
                            <p>___________________</p>
                    </div>
                </div>
            </div>
            <div class="barcode" style="display: flex; margin-top: 1px;  position: absolute; bottom: 20px; right: 20px;">
                <div align="right">
                    <td rowspan="2"><img src="data:image/png;base64,{{ \DNS2D::getBarcodePNG(url('hris/identity/card_employee_form_identity') . '?enroll_id=' . $data['enroll_id'] . '&no_form=3872/HRD-NAC/EXT/XII/2024', 'QRCODE', 'QRCODE') }}"  alt="barcode" style="width: 60px; height: 60px;background-color:white" /></td>
                </div>
            </div>
        </div>
</body>
</html>
