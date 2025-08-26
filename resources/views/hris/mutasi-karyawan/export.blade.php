
<table class="table">
    <tr>
        <td colspan='6'>Laporan Karyawan</td>
    </tr>
    <tr>
        <td colspan='6'>{{ date('d-M-Y', strtotime($from)) }} - {{ date('d-M-Y', strtotime($to)) }}
        </td>
    </tr>
    <thead>
        <tr>
            <th style="background-color: yellow">No</th>
            <th style="background-color: yellow">Tanggal</th>
            <th style="background-color: yellow">Enroll ID</th>
            <th style="background-color: yellow">NIP</th>
            <th style="background-color: yellow">Nama Karyawan</th>
            <th style="background-color: yellow">Kategori</th>
            <th style="background-color: yellow">Line Sekarang</th>
            <th style="background-color: yellow">Line Asal</th>
            <th style="background-color: yellow">Tgl. Pindah</th>
            <th style="background-color: yellow">Jam Absen</th>
            <th style="background-color: yellow">Status Absen</th>
            <th style="background-color: yellow">Status Scan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
            $style = '';
        @endphp
        @foreach ($data as $item)

            @if ($item->absen_masuk_kerja == null)
                @php
                    $style = 'red';
                @endphp
            @else
                @php
                    $style = 'white';
                @endphp
            @endif
            <tr>
                <td style="background-color: {{ $style }}">{{ $no++ }}.</td>
                <td style="background-color: {{ $style }}">{{ date('d-M-Y', strtotime($item->tanggal_berjalan)) }}</td>
                <td style="background-color: {{ $style }}">{{ preg_replace('/[^\x20-\x7E]/', '', $item->enroll_id) ?? '-' }}</td>
                <td style="background-color: {{ $style }}">{{ $item->nik }}</td>
                <td style="background-color: {{ $style }}">{{ $item->nm_karyawan }}</td>
                <td style="background-color: {{ $style }}">{{ $item->sewing_nonsewing }}</td>
                <td style="background-color: {{ $style }}">{{ $item->line }}</td>
                <td style="background-color: {{ $style }}">{{ $item->line_asal }}</td>
                <td style="background-color: {{ $style }}">{{ date('d-M-Y', strtotime($item->tgl_pindah)) }}</td>
                <td style="background-color: {{ $style }}">{{ $item->absen_masuk_kerja }}</td>
                <td style="background-color: {{ $style }}">{{ $item->status_absen }}</td>
                <td style="background-color: {{ $style }}">{{ $item->status_scan }}</td>

            </tr>
        @endforeach
    </tbody>

</table>

