<!DOCTYPE html>
<html lang="en" style="overflow:scroll;">
<head></head>
<style>
    table td, table th {
        vertical-align: top;
    }
</style>

<body>
    <table border="1">
        <tr>
            <td colspan="12"><b>Rekap Pengajuan Perbaikan Kendaraan</b></td>
        </tr>
        <tr>
            <td colspan="12"></td>
        </tr>
        <tr>
            <td><b>TANGGAL PENGAJUAN</b></td>
            <td><b>ODOMETER</b></td>
            <td><b>MERK KENDARAAN</b></td>
            <td><b>NOMOR POLISI</b></td>
            <td><b>DRIVER</b></td>
            <td><b>NIP</b></td>
            <td><b>JENIS PEMELIHARAAN</b></td>
            <td><b>DESKRIPSI PEMELIHARAAN</b></td>
            <td><b>PENYEDIA JASA</b></td>
            <td><b>KETERANGAN</b></td>
            <td><b>TANGGAL DISETUJUI</b></td>
            <td><b>STATUS REALISASI</b></td>
        </tr>

        @foreach ($query as $data_perbaikan)
            @php $rowspan = count($data_perbaikan['details']); @endphp

            @if($rowspan > 0)
                {{-- baris pertama + detail pertama --}}
                <tr>
                    <td style="vertical-align: top;" rowspan="{{ $rowspan }}">
                        {{ $data_perbaikan['tanggal_pengajuan']
                            ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(
                                \Carbon\Carbon::parse($data_perbaikan['tanggal_pengajuan'])
                              )
                            : ''
                        }}
                    </td>
                    <td style="vertical-align: top;" rowspan="{{ $rowspan }}">{{ $data_perbaikan['details'][0]['odometer'] ?? '' }}</td>
                    <td style="vertical-align: top;" rowspan="{{ $rowspan }}">{{ $data_perbaikan['merk'] }}</td>
                    <td style="vertical-align: top;" rowspan="{{ $rowspan }}">{{ $data_perbaikan['plat_no'] }}</td>
                    <td style="vertical-align: top;" rowspan="{{ $rowspan }}">{{ $data_perbaikan['employee_name'] }}</td>
                    <td style="vertical-align: top;" rowspan="{{ $rowspan }}">{{ $data_perbaikan['nip'] }}</td>

                    <td style="vertical-align: top;">{{ $data_perbaikan['details'][0]['detail_input_list']['nama_item_pemeriksaan_detail'] }}</td>
                    <td style="vertical-align: top;">{{ $data_perbaikan['details'][0]['keterangan'] ?? '' }}</td>
                    <td style="vertical-align: top;">{{ $data_perbaikan['details'][0]['penyedia_jasa'] ?? '' }}</td>
                    <td style="vertical-align: top;"></td>
                    <td style="vertical-align: top;" rowspan="{{ $rowspan }}">
                        {{ $data_perbaikan['status_pengajuan'] == 'approved'
                            ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(
                                \Carbon\Carbon::parse($data_perbaikan['tanggal_pengajuan'])
                              )
                            : ''
                        }}
                    </td>
                    <td style="vertical-align: top;" rowspan="{{ $rowspan }}">
                        @foreach($data_perbaikan['images'] as $img)
                            <a href="{{ asset('storage/' . ltrim($img['path'], '/')) }}" target="_blank">
                                {{ asset('storage/' . ltrim($img['path'], '/')) }}
                            </a><br>
                        @endforeach
                    </td>

                </tr>

                {{-- sisanya detail ke-2 dst --}}
                @foreach ($data_perbaikan['details']->skip(1) as $detail)
                <tr>
                    <td style="vertical-align: top;">{{ $detail->detail_input_list['nama_item_pemeriksaan_detail'] }}</td>
                    <td style="vertical-align: top;">{{ $detail->keterangan ?? '' }}</td>
                    <td style="vertical-align: top;">{{ $detail->penyedia_jasa ?? '' }}</td>
                    <td style="vertical-align: top;"></td>
                </tr>
            @endforeach


            @else
                {{-- kalau tidak ada detail --}}
                <tr>
                    <td style="vertical-align: top;">{{ $data_perbaikan['tanggal_pengajuan'] }}</td>
                    <td style="vertical-align: top;">0</td>
                    <td style="vertical-align: top;">{{ $data_perbaikan['merk'] }}</td>
                    <td style="vertical-align: top;">{{ $data_perbaikan['plat_no'] }}</td>
                    <td style="vertical-align: top;">{{ $data_perbaikan['employee_name'] }}</td>
                    <td style="vertical-align: top;">{{ $data_perbaikan['nip'] }}</td>
                    <td style="vertical-align: top;">-</td>
                    <td style="vertical-align: top;">-</td>
                    <td style="vertical-align: top;">-</td>
                    <td style="vertical-align: top;">-</td>
                    <td style="vertical-align: top;">{{ $data_perbaikan['status_pengajuan'] == 'approved'
                            ? $data_perbaikan['tanggal_pengajuan'] : '' }}
                    </td>
                    <td style="vertical-align: top;" rowspan="{{ $rowspan }}">
                        @foreach($data_perbaikan['images'] as $img)
                            <a href="{{ asset('storage/' . ltrim($img['path'], '/')) }}" target="_blank">
                                {{ asset('storage/' . ltrim($img['path'], '/')) }}
                            </a><br>
                        @endforeach
                    </td>
                </tr>
            @endif
        @endforeach
    </table>
</body>
</html>
