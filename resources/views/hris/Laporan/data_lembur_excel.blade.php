<table>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    @foreach ($dataLembur as $lembur)
        @php
            $kode_hari = $lembur->kode_hari;
            $liburnasional = $lembur->holiday_name;

            $absenIN = $lembur->absen_masuk_kerja;
            $absenOUT = $lembur->absen_pulang_kerja;

            $kerjalibur = "KERJA";
            switch ($kode_hari) {
                case '5':
                    $kerjalibur = "LIBUR";
                    break;
                case '6':
                    $kerjalibur = "LIBUR";
                    break;
            }

            if(($absenIN <> null) || ($absenIN <> "") || ($absenOUT <> null) || ($absenOUT <> "")) {
                $kerjalibur = "KERJA";
                switch ($kode_hari) {
                    case '5':
                        $kerjalibur = "LIBUR";
                        break;
                    case '6':
                        $kerjalibur = "LIBUR";
                        break;
                }
            }

            if($liburnasional <> "") {
                $kerjalibur = "LIBUR";
            }
            if($lembur->is_verifikasi==0){
                $is_verifikasi='UNVERIFIED';
            }else if($lembur->is_verifikasi==1){
                $is_verifikasi='VERIFIED';
            }
        @endphp
        <tr>
            <td>{{ $lembur->tanggal_berjalan }} </td>
            <td>{{ $lembur->nama_hari }} </td>
            <td>{{ $lembur->nomor_form_lembur }} </td>
            <td>{{ $lembur->nik }} </td>
            <td>{{ $lembur->enroll_id }} </td>
            <td>{{ $lembur->employee_name }} </td>
            <td>{{ $kerjalibur }} </td>
            <td>{{ $lembur->status_staff }} </td>
            <td>{{ $lembur->department_name }} </td>
            <td>{{ $lembur->sub_dept_name }} </td>
            <td>{{ substr($lembur->mulai_jam_kerja, 0, 5) }} </td>
            <td>{{ substr($lembur->akhir_jam_kerja, 0, 5) }} </td>
            <td>{{ substr($lembur->absen_masuk_kerja, 0, 5) }} </td>
            <td>{{ substr($lembur->absen_pulang_kerja, 0, 5) }} </td>
            <td>{{ substr($lembur->mulai_jam_lembur, 0, 16) }} </td>
            <td>{{ substr($lembur->akhir_jam_lembur, 0, 16) }} </td>
            <td>{{ $lembur->jumlah_jam_lembur_approved }} </td>
            <td>{{ $lembur->catatan_hrd }} </td>
            <td>{{ $lembur->lembur_1 }} </td>
            <td>{{ $lembur->lembur_2 }} </td>
            <td>{{ $lembur->lembur_3 }} </td>
            <td>{{ $lembur->lembur_4 }} </td>
            <td>{{ $lembur->total_lembur_1234 }} </td>
            <td>{{ $is_verifikasi }} </td>
        </tr>
    @endforeach
</table>