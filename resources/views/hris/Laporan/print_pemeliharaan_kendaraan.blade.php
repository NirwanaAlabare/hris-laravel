<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Document</title>
    <style>
        table {
            border-collapse: collapse;
        }
        @page { margin: 20px 20px 40px 20px; }
    </style>
</head>

<body>
    <table width="100%">
        <thead>
            <tr>
                <td width="100px" style="vertical-align: middle; text-align: center;border: 1px solid;" colspan="2" rowspan="4">
                    <img height="60" src="{{ public_path('assets/images/brand/nirwana logo.jpg') }}" alt="">
                </td>
                <td style="vertical-align: middle; font-size: 12pt; text-align: center; font-weight: 800;border: 1px solid;" colspan="8" rowspan="4">FORM PENGAJUAN PEMELIHARAAN KENDARAAN</td>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Kode Dokumen</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: : F.21.P.CM.P-07.F-02.01</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: -</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid; font-size: 9pt;">Tanggal Revisi</td>
                <td colspan="3" class="border-right" style="border: 1px solid; font-size: 9pt;">: -</td>
            </tr>
            <tr>
                <td colspan="2" class="border-left" style="border: 1px solid;border-bottom: 1px solid; font-size: 9pt;">Tanggal Efektif</td>
                <td colspan="3" class="border-right" style="border: 1px solid; border-bottom: 1px solid; font-size: 9pt;">: 31 Agustus 2021</td>
            </tr>
        </thead>
    </table>

     <table width="100%" style="border-bottom:1px solid black; border-top:0px; border-left:1px solid black; border-right:1px solid black; letter-spacing: 1px;">
        <thead>
            <tr>
                <td style="height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left; font-weight: bold;" width="16%">TANGGAL PENGAJUAN</td>
                <td style="font-size:8pt;vertical-align:top;text-align:left; font-weight: bold;" width="2%">:</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left; font-weight: bold;" width="22%">{{ Carbon\Carbon::parse($data->tanggal_pengajuan)->translatedFormat('d F Y') }}</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%"></td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%"></td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%"></td>
            </tr>
            <tr>
                <td style="height:10px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="16%">MERK KENDARAAN</td>
                <td style="font-size:8pt;vertical-align:top;text-align:left;" width="2%">:</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%">{{$kendaraan->merk}}</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%">DRIVER</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%"></td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%"></td>
            </tr>
            <tr>
                <td style="height:2px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="16%">NOMOR POLISI</td>
                <td style="font-size:8pt;vertical-align:top;text-align:left;" width="2%">:</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%">{{$kendaraan->plat_no}}</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%">NAMA</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%">:</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%">{{$data->employee->employee_name}}</td>
            </tr>
            <tr>
                <td style="height:2px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="16%">WARNA</td>
                <td style="font-size:8pt;vertical-align:top;text-align:left;" width="2%">:</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%">{{$kendaraan->warna}}</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="10%">NIP</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%">:</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%">{{$data->employee->nik}}</td>
            </tr>
            <tr>
                <td style="height:2px;" colspan="6"></td>
            </tr>
            <tr>
                <td style="padding-left: 10px;font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="16%">ODDOMETER</td>
                <td style="font-size:8pt;vertical-align:top;text-align:left;" width="2%">:</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%">{{$data->oddometer}}</td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="10%"></td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="2%"></td>
                <td style="font-family:sans-serif;font-size:7.5pt;text-align:justify;vertical-align:top;text-align:left;" width="22%"></td>
            </tr>
            <tr>
                <td style="height:2px;" colspan="6"></td>
            </tr>
        </thead>
    </table>
    <table style="width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 7.5pt;">
        <thead>
          <tr>
            <th style="padding: 5px; text-align: center; width: 5%; border-left: 1px solid #000; border-right: 1px solid #000;">
              NO
            </th>
            <th style="padding: 5px; text-align: center; width: 30%;  border-right: 1px solid #000;">
              JENIS PEMELIHARAAN
            </th>
            <th style="padding: 5px; text-align: center; width: 25%;  border-right: 1px solid #000;">
              PENYEDIA JASA
            </th>
            <th style="padding: 5px; text-align: center; width: 15%;  border-right: 1px solid #000;">
              PEMELIHARAAN SEBELUMNYA (ODDOMETER)
            </th>
            <th style="padding: 5px; text-align: center; width: 25%;  border-right: 1px solid #000;">
              KETERANGAN
            </th>
          </tr>
        </thead>
        <tbody style="font-family: sans-serif; font-size: 7.5pt;">
            @foreach ($data->details as $index => $detail)
                <tr>
                    <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px; border-left: 1px solid #000;border-bottom: 1px solid #000;">{{$index+1}}</td>
                    <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000; border-bottom: 1px solid #000;">{{$detail->detail_input_list->nama_item_pemeriksaan_detail}}</td>
                    <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000; border-bottom: 1px solid #000;">{{$detail->penyedia_jasa}}</td>
                    <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000; border-bottom: 1px solid #000;">{{$detail->odometer}}</td>
                    <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000; border-bottom: 1px solid #000;">{{$detail->keterangan}}</td>
                </tr>
            @endforeach
          {{-- <tr>
            <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px; border-left: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
          </tr>
          <tr>
            <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px; border-left: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
          </tr>
          <tr>
            <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px; border-left: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
          </tr>
          <tr>
            <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px; border-bottom: 1px solid #000; border-left: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px;  border-bottom: 1px solid #000; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px;  border-bottom: 1px solid #000; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px;  border-bottom: 1px solid #000; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px;  border-bottom: 1px solid #000; border-right: 1px solid #000;"></td>
          </tr> --}}
        </tbody>
        {{-- <tbody style="font-family: sans-serif; font-size: 7.5pt;">
          <tr>
            <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px; border-left: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
          </tr>
          <tr>
            <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px; border-left: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
          </tr>
          <tr>
            <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px; border-left: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
          </tr>
          <tr>
            <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px; border-left: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px; border-right: 1px solid #000;"></td>
          </tr>
          <tr>
            <td style="border-top: 1px solid #000; height: 21px; padding-left: 10px; border-bottom: 1px solid #000; border-left: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px;  border-bottom: 1px solid #000; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px;  border-bottom: 1px solid #000; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px;  border-bottom: 1px solid #000; border-right: 1px solid #000;"></td>
            <td style="border-top: 1px solid #000; border-left: 1px solid #000; padding-left: 10px;  border-bottom: 1px solid #000; border-right: 1px solid #000;"></td>
          </tr>
        </tbody> --}}
    </table>
    <table width="100%" style="padding-top:8px;">
        <thead>
            <tr>
                <td style="height:20px"></td>
            </tr>
            <tr>
                <td width="37%"></td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center" >Dibuat</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">Disetujui</td>
                <td width="21%" style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">Diketahui</td>
            </tr>
            <tr>
                <td></td>
                <td style="height: 55px;border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
                <td style="border:1px solid black;"></td>
            </tr>
            <tr>
                <td></td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center" >{{$data->employee->employee_name}}</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">Manager</td>
                <td style="font-family:Arial, Helvetica, sans-serif;font-size:9pt;text-align:justify;vertical-align:top;border:1px solid black;text-align:center">General Manager</td>
            </tr>
        </thead>
    </table>
</body>
</html>
