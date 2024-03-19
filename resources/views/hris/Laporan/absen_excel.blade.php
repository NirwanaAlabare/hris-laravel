<table>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    @foreach ($dataAbsen as $Kehadiran)
        @php
            $interval = date_diff(date_create(substr($Kehadiran->mulai_jam_kerja, 0, 5)), date_create(substr($Kehadiran->akhir_jam_kerja, 0, 5)));
            $minutes = $interval->days * 24 * 60;
            $minutes += $interval->h * 60;
            $minutes += $interval->i;
            $jumlah_menit_kerja = $minutes;
            if ($Kehadiran->jumlah_menit_istirahat == "") {
                $jumlah_menit_istirahat = 60;
            } else {
                $jumlah_menit_istirahat = $Kehadiran->jumlah_menit_istirahat;
            }
            $kerjalibur = "KERJA";
            if(($Kehadiran->holiday_name <> "") || ($Kehadiran->mulai_jam_kerja == null) || ($Kehadiran->status_absen == "LN" || $Kehadiran->status_absen == "CG" || $Kehadiran->status_absen == "CM" || $Kehadiran->status_absen == "CT" ||$Kehadiran->status_absen == "L") || (($Kehadiran->status_absen == "LP" ) && ($Kehadiran->absen_masuk_kerja==null) && ($Kehadiran->absen_pulang_kerja==null))) {
                $kerjalibur = "LIBUR";
            } else if(($Kehadiran->kode_hari=='6' || $Kehadiran->kode_hari=='5' ) && ($Kehadiran->mulai_jam_kerja!=null)){
                $kerjalibur = "KERJA";
            } else {
                if(($Kehadiran->absen_masuk_kerja <> null) || ($Kehadiran->absen_masuk_kerja <> "") || ($Kehadiran->absen_pulang_kerja <> null) || ($Kehadiran->absen_pulang_kerja <> "")) {
                    $kerjalibur = "KERJA";
                    switch ($Kehadiran->kode_hari) {
                        case '5':
                            $kerjalibur = "LIBUR";
                            $jumlah_menit_istirahat = 30;
                            break;
                        case '6':
                            $kerjalibur = "LIBUR";
                            $jumlah_menit_istirahat = 30;
                            break;
                    }
                }
            }
            if ($Kehadiran->final_jam_istirahat_lembur == 0) {
                $final_jam_istirahat_lembur = "";
            } else {
                $final_jam_istirahat_lembur = $Kehadiran->final_jam_istirahat_lembur;
            }
            if(str_pad($Kehadiran->final_jam_lembur_roundown,2,"0",STR_PAD_LEFT).":".str_pad($Kehadiran->final_menit_lembur_roundown,2,"0",STR_PAD_LEFT)=='00:00'){
                $final_jam_lembur_roundown="";
            }else{
                $final_jam_lembur_roundown=str_pad($Kehadiran->final_jam_lembur_roundown,2,"0",STR_PAD_LEFT).":".str_pad($Kehadiran->final_menit_lembur_roundown,2,"0",STR_PAD_LEFT);
            }
            if ($Kehadiran->jumlah_jam_lembur_approved == 0) {
                $jumlah_jam_lembur_approved = "";
            } else {
                $jumlah_jam_lembur_approved = $Kehadiran->jumlah_jam_lembur_approved;
            }
            if ($Kehadiran->jumlah_jam_istirahat_lembur == 0) {
                $jumlah_jam_istirahat_lembur = "";
            } else {
                $jumlah_jam_istirahat_lembur = $Kehadiran->jumlah_jam_istirahat_lembur;
            }
            if ($Kehadiran->final_total_jam_lembur == 0) {
                $total_jam_lembur = "";
            } else {
                $hms = $Kehadiran->final_total_jam_lembur;
                $final_total_jam_lembur = explode(":", $hms);
                $total_jam_lembur=intval($final_total_jam_lembur[0])+(intval($final_total_jam_lembur[1])/60);
            }
        @endphp
        <tr>
            <td>{{ $Kehadiran->tanggal_berjalan }} </td>
            <td>{{ $Kehadiran->nama_hari }} </td>
            <td>{{ $Kehadiran->nik }} </td>
            <td>{{ $Kehadiran->enroll_id }} </td>
            <td>{{ $Kehadiran->employee_name }} </td>
            <td>{{ $Kehadiran->status_staff }} </td>
            <td>{{ $Kehadiran->department_name }} </td>
            <td>{{ $kerjalibur }} </td>
            <td>{{ substr($Kehadiran->mulai_jam_kerja, 0, 5) }} </td>
            <td>{{ substr($Kehadiran->akhir_jam_kerja, 0, 5) }} </td>
            <td>{{ $jumlah_menit_istirahat }} </td>
            <td>{{ $jumlah_menit_kerja }} </td>
            <td>{{ substr($Kehadiran->absen_masuk_kerja, 0, 5) }} </td>
            <td>{{ substr($Kehadiran->absen_pulang_kerja, 0, 5) }} </td>
            <td>{{ $Kehadiran->jumlah_absen_menit_kerja }} </td>
            <td>{{ substr($Kehadiran->permits_dari_pukul, 0, 5) }} </td>
            <td>{{ substr($Kehadiran->permits_sampai_pukul, 0, 5) }} </td>
            <td>{{ $Kehadiran->total_menit_permits }} </td>
            <td>{{ $Kehadiran->jumlah_menit_absen_dt }} </td>
            <td>{{ $Kehadiran->jumlah_menit_absen_pc }} </td>
            <td>{{ $Kehadiran->jumlah_menit_absen_dtpc }} </td>
            <td>{{ $Kehadiran->status_absen }} </td>
            <td>{{ $Kehadiran->absen_alasan }} </td>
            <td>{{ $Kehadiran->catatan_hrd }} </td>
            <td>{{ "" }} </td>
            <td>{{ $Kehadiran->nomor_form_lembur }} </td>
            <td>{{ $Kehadiran->final_mulai_jam_lembur }} </td>
            <td>{{ $Kehadiran->final_selesai_jam_lembur }} </td>
            <td>{{ $total_jam_lembur }} </td>
            <td>{{ $final_jam_istirahat_lembur }} </td>
            <td>{{ $final_jam_lembur_roundown }} </td>
            <td>{{ $Kehadiran->lembur_1 }} </td>
            <td>{{ $Kehadiran->lembur_2 }} </td>
            <td>{{ $Kehadiran->lembur_3 }} </td>
            <td>{{ $Kehadiran->lembur_4 }} </td>
            <td>{{ $Kehadiran->total_lembur_1234 }} </td>
            <td>{{ $Kehadiran->mulai_jam_lembur }} </td>
            <td>{{ $Kehadiran->akhir_jam_lembur }} </td>
            <td>{{ $jumlah_jam_lembur_approved }} </td>
            <td>{{ $jumlah_jam_istirahat_lembur }} </td>
        </tr>
    @endforeach
</table>
