<?php

namespace App\Exports;

use App\Models\EmployeeAtribut;
use App\Http\Controllers\Hris\EmployeeAtrController;

use App\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;

use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Illuminate\Support\Facades\DB;

use Auth;

class EmployeeAtrExport implements FromQuery, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle, WithColumnFormatting
{
    use Exportable;

    public function exportParams()
    {
        return $this;
    }

    public function query()
    {
        $q =  EmployeeAtribut::query()
                ->selectRaw('
                employee_atribut.employee_id,
                employee_atribut.employee_name,
                employee_atribut.jenis_kelamin,
                employee_atribut.tempat_lahir,
                employee_atribut.tanggal_lahir,
                employee_atribut.golongan_darah,
                employee_atribut.email,
                employee_atribut.nomor_tlpn,
                employee_atribut.agama,
                employee_atribut.status_kawin,
                employee_atribut.npwp,
                concat("\'",employee_atribut.nomor_ktp) nomor_ktp,
                concat(employee_atribut.nomor_kk," ") nomor_kk,
                employee_atribut.ptkp,
                employee_atribut.nama_sekolah_terakhir,
                employee_atribut.pendidikan_terakhir,
                employee_atribut.jurusan_pendidikan,
                employee_atribut.nama_bank,
                CONCAT(employee_atribut.nomor_rekening_bank," ") nomor_rekening_bank,
                employee_atribut.ibu_kandung,
                employee_atribut.propinsi,
                employee_atribut.kota_kab,
                employee_atribut.kecamatan,
                employee_atribut.kelurahan_desa,
                employee_atribut.alamat_rumah,
                employee_atribut.alamat_sementara,
                employee_atribut.site_nirwana_id,
                employee_atribut.site_nirwana_name,
                employee_atribut.department_id,
                employee_atribut.department_name,
                employee_atribut.sub_dept_id,
                employee_atribut.sub_dept_name,
                employee_atribut.enroll_id,
                employee_atribut.join_date,
                employee_atribut.nik,
                employee_atribut.sewing_nonsewing,
                employee_atribut.direct_indirect,
                employee_atribut.status_aktif,
                employee_atribut.status_jabatan,
                employee_atribut.status_kontrak_tetap,
                employee_atribut.status_staff,
                employee_atribut.tanggal_resign,
                employee_atribut.tunjangan,
                employee_atribut.kode_grade,
                employee_atribut.referensi,
                employee_atribut.employee_name_atasan,
                employee_atribut.status_aktif_bpjs_tk,
                employee_atribut.tanggal_bpjs_ketenagakerjaan,
                employee_atribut.nomor_bpjs_ketenagakerjaan,
                employee_atribut.status_aktif_bpjs_ks,
                employee_atribut.tanggal_bpjs_kesehatan,
                employee_atribut.nomor_bpjs_kesehatan,
                employee_atribut.pengalaman_bekerja,
                employee_atribut.lokasi_file_cv,
                employee_atribut.nama_kerabat,
                employee_atribut.nomor_tlpn_kerabat,
                employee_atribut.hubungan_kerabat,
                employee_atribut.alamat_kerabat,
                employee_atribut.tanggal_vaccine1,
                employee_atribut.nama_vaksin1,
                employee_atribut.tanggal_vaccine2,
                employee_atribut.nama_vaksin2,
                employee_atribut.tanggal_vaccine3,
                employee_atribut.nama_vaksin3,
                employee_atribut.golongan_sim,
                concat(" ", employee_atribut.nomor_sim) nomor_sim,
                employee_atribut.tanggal_expire_sim,
                employee_atribut.catatan,
                employee_atribut.lokasi_foto,
                employee_atribut.operator,
                employee_atribut.tanggal_mulai_kontrak,
                employee_atribut.tanggal_akhir_kontrak,
                employee_atribut.catatan_kontrak,
                employee_atribut.created_at,
                employee_atribut.updated_at
                ')
                ->groupBy('employee_atribut.enroll_id')
                ->orderByRaw('CAST(employee_atribut.enroll_id AS SIGNED) ASC')
                ->orderBy('employee_atribut.tanggal_resign','asc')
                ->limit(1);

        return $q;
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function map($Data): array
    {
        // cara cepat tanpa kondisi if else
        // dd($Data->tanggal_lahir!=null?date('d-m-Y', strtotime($Data->tanggal_lahir)):$Data->tanggal_lahir);

        if($Data->tanggal_lahir!=null){
            $tanggal_lahirf=date('d-m-Y', strtotime($Data->tanggal_lahir));
        }else{
            $tanggal_lahirf=$Data->tanggal_lahir;
        }

        if($Data->join_date!=null){
            $join_datef=date('d-m-Y', strtotime($Data->join_date));
        }else{
            $join_datef=$Data->join_date;
        } 

        if($Data->tanggal_resign!=null){
            $tanggal_resignf=date('d-m-Y', strtotime($Data->tanggal_resign));
        }else{
            $tanggal_resignf=$Data->tanggal_resign;
        } 

        if($Data->tanggal_bpjs_ketenagakerjaan!=null){
            $tanggal_bpjs_ketenagakerjaanf=date('d-m-Y', strtotime($Data->tanggal_bpjs_ketenagakerjaan));
        }else{
            $tanggal_bpjs_ketenagakerjaanf=$Data->tanggal_bpjs_ketenagakerjaan;
        } 

        if($Data->tanggal_bpjs_kesehatan!=null){
            $tanggal_bpjs_kesehatanf=date('d-m-Y', strtotime($Data->tanggal_bpjs_kesehatan));
        }else{
            $tanggal_bpjs_kesehatanf=$Data->tanggal_bpjs_kesehatan;
        } 

        if($Data->tanggal_vaccine1!=null){
            $tanggal_vaccine1f=date('d-m-Y', strtotime($Data->tanggal_vaccine1));
        }else{
            $tanggal_vaccine1f=$Data->tanggal_vaccine1;
        } 

        if($Data->tanggal_vaccine2!=null){
            $tanggal_vaccine2f=date('d-m-Y', strtotime($Data->tanggal_vaccine2));
        }else{
            $tanggal_vaccine2f=$Data->tanggal_vaccine2;
        } 
        
        if($Data->tanggal_vaccine3!=null){
            $tanggal_vaccine3f=date('d-m-Y', strtotime($Data->tanggal_vaccine3));
        }else{
            $tanggal_vaccine3f=$Data->tanggal_vaccine3;
        } 
        
        if($Data->tanggal_expire_sim!=null){
            $tanggal_expire_simf=date('d-m-Y', strtotime($Data->tanggal_expire_sim));
        }else{
            $tanggal_expire_simf=$Data->tanggal_expire_sim;
        }
        
        if($Data->tanggal_mulai_kontrak!=null){
            $tanggal_mulai_kontrakf=date('d-m-Y', strtotime($Data->tanggal_mulai_kontrak));
        }else{
            $tanggal_mulai_kontrakf=$Data->tanggal_mulai_kontrak;
        }
        
        if($Data->tanggal_akhir_kontrak!=null){
            $tanggal_akhir_kontrakf=date('d-m-Y', strtotime($Data->tanggal_akhir_kontrak));
        }else{
            $tanggal_akhir_kontrakf=$Data->tanggal_akhir_kontrak;
        }

        $employee_id = $Data->employee_id;
        $employee_name = $Data->employee_name;
        $jenis_kelamin = $Data->jenis_kelamin;
        $tempat_lahir = $Data->tempat_lahir;
        $tanggal_lahir =$tanggal_lahirf;
        $golongan_darah = $Data->golongan_darah;
        $email = $Data->email;
        $nomor_tlpn = $Data->nomor_tlpn;
        $agama = $Data->agama;
        $status_kawin = $Data->status_kawin;
        $npwp = $Data->npwp;
        $nomor_ktp =$Data->nomor_ktp;
        $nomor_kk = $Data->nomor_kk;
        $ptkp = $Data->ptkp;
        $nama_sekolah_terakhir = $Data->nama_sekolah_terakhir;
        $pendidikan_terakhir = $Data->pendidikan_terakhir;
        $jurusan_pendidikan = $Data->jurusan_pendidikan;
        $nama_bank = $Data->nama_bank;
        $nomor_rekening_bank = $Data->nomor_rekening_bank;
        $ibu_kandung = $Data->ibu_kandung;
        $propinsi = $Data->propinsi;
        $kota_kab = $Data->kota_kab;
        $kecamatan = $Data->kecamatan;
        $kelurahan_desa = $Data->kelurahan_desa;
        $alamat_rumah = $Data->alamat_rumah;
        $alamat_sementara = $Data->alamat_sementara;
        $site_nirwana_id = $Data->site_nirwana_id;
        $site_nirwana_name = $Data->site_nirwana_name;
        $department_id = $Data->department_id;
        $department_name = $Data->department_name;
        $sub_dept_id = $Data->sub_dept_id;
        $sub_dept_name = $Data->sub_dept_name;
        $enroll_id = $Data->enroll_id;
        $join_date = $join_datef;
        $nik = $Data->nik;
        $status_aktif = $Data->status_aktif;
        $status_jabatan = $Data->status_jabatan;
        $status_kontrak_tetap = $Data->status_kontrak_tetap;
        $status_staff = $Data->status_staff;
        $tanggal_resign = $tanggal_resignf;
        $tunjangan = $Data->tunjangan;
        $kode_grade = $Data->kode_grade;
        $referensi = $Data->referensi;
        $employee_name_atasan = $Data->employee_name_atasan;
        $status_aktif_bpjs_tk = $Data->status_aktif_bpjs_tk;
        $tanggal_bpjs_ketenagakerjaan = $tanggal_bpjs_ketenagakerjaanf;
        $nomor_bpjs_ketenagakerjaan = $Data->nomor_bpjs_ketenagakerjaan;
        $status_aktif_bpjs_ks = $Data->status_aktif_bpjs_ks;
        $tanggal_bpjs_kesehatan =  $tanggal_bpjs_kesehatanf;
        $nomor_bpjs_kesehatan = $Data->nomor_bpjs_kesehatan;
        $pengalaman_bekerja = $Data->pengalaman_bekerja;
        $lokasi_file_cv = $Data->lokasi_file_cv;
        $nama_kerabat = $Data->nama_kerabat;
        $nomor_tlpn_kerabat = $Data->nomor_tlpn_kerabat;
        $hubungan_kerabat = $Data->hubungan_kerabat;
        $alamat_kerabat = $Data->alamat_kerabat;
        $tanggal_vaccine1 = $tanggal_vaccine1f;
        $nama_vaksin1 = $Data->nama_vaksin1;
        $tanggal_vaccine2 = $tanggal_vaccine2f;
        $nama_vaksin2 = $Data->nama_vaksin2;
        $tanggal_vaccine3 = $tanggal_vaccine3f;
        $nama_vaksin3 = $Data->nama_vaksin3;
        $golongan_sim = $Data->golongan_sim;
        $nomor_sim = $Data->nomor_sim;
        $tanggal_expire_sim = $tanggal_expire_simf;
        $catatan = $Data->catatan;
        $lokasi_foto = $Data->lokasi_foto;
        $operator = $Data->operator;
        $tanggal_mulai_kontrak =$tanggal_mulai_kontrakf;
        $tanggal_akhir_kontrak = $tanggal_akhir_kontrakf;
        $catatan_kontrak = $Data->catatan_kontrak;
        $created_at = $Data->created_at;
        $updated_at = $Data->updated_at;
        $direct_indirect=$Data->direct_indirect;
        $sewing_nonsewing=$Data->sewing_nonsewing;

        return [
            $employee_id,
            $enroll_id,
            $nik,
            $employee_name,
            $jenis_kelamin,
            $status_kontrak_tetap,
            $status_staff,
            $status_jabatan,
            $department_id,
            $department_name,
            $sub_dept_id,
            $sub_dept_name,
            $sewing_nonsewing,
            $direct_indirect,
            $status_aktif,
            Date::stringToExcel( $join_date),
            Date::stringToExcel($tanggal_resign),
            $tempat_lahir,
            Date::stringToExcel($tanggal_lahirf),
            $agama,
            $ibu_kandung,
            $status_kawin,
            $ptkp,
            $npwp,
            $nomor_ktp,
            $nomor_kk,
            $golongan_darah,
            $nomor_tlpn,
            $email,
            $pendidikan_terakhir,
            $jurusan_pendidikan,
            $nama_bank,
            $nomor_rekening_bank,
            $alamat_rumah,
            $propinsi,
            $kota_kab,
            $kecamatan,
            $kelurahan_desa,
            $alamat_sementara,
            $tunjangan,
            $kode_grade,
            $referensi,
            $employee_name_atasan,
            $status_aktif_bpjs_tk,
            Date::stringToExcel($tanggal_bpjs_ketenagakerjaan),
            $nomor_bpjs_ketenagakerjaan,
            $status_aktif_bpjs_ks,
            Date::stringToExcel($tanggal_bpjs_kesehatan),
            $nomor_bpjs_kesehatan,
            $pengalaman_bekerja,
            $nama_kerabat,
            $nomor_tlpn_kerabat,
            $hubungan_kerabat,
            $alamat_kerabat,
            Date::stringToExcel($tanggal_vaccine1),
            $nama_vaksin1,
            Date::stringToExcel($tanggal_vaccine2),
            $nama_vaksin2,
            Date::stringToExcel($tanggal_vaccine3),
            $nama_vaksin3,
            $golongan_sim,
            $nomor_sim,
            Date::stringToExcel($tanggal_expire_sim),
            $catatan,
            Date::stringToExcel($tanggal_mulai_kontrak),
            Date::stringToExcel($tanggal_akhir_kontrak),
            $catatan_kontrak,
            $created_at,
            $updated_at,
        ];
    }
    public function columnFormats(): array
    {
        return [
            'P' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'Q' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'S' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'Y' => NumberFormat::FORMAT_NUMBER,
            'Z' => NumberFormat::FORMAT_TEXT,

            'AG' => NumberFormat::FORMAT_TEXT,
            'AS' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'AV' => NumberFormat::FORMAT_DATE_DDMMYYYY,

            'BC' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'BE' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'BG' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'BI' => NumberFormat::FORMAT_DATE_DDMMYYYY,

            'BM' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'BN' => NumberFormat::FORMAT_DATE_DDMMYYYY,



            // 'N' => NumberFormat::FORMAT_DATE_DMYMINUS
        ];
    }

    public function title(): string
    {
        return 'DATAKARYAWAN';
    }

    public function registerEvents() : array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet;
                $sheet->setCellValue('A1', 'PT NIRWANA ALABARE GARMENT');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(18);
                $sheet->setCellValue('A2', 'DATA KARYAWAN');
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(16);
                $sheet->setCellValue('A3', 'PERIODE TAHUN DAN BULAN : ' . substr(now(), 0, 4) . '-' . substr(now(), 5, 2));
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');

                $sheet->setCellValue('A5', 'EMPLOYEE ID');
                $sheet->setCellValue('B5', 'NO. ABSEN');
                $sheet->setCellValue('C5', 'NIP');
                $sheet->setCellValue('D5', 'NAMA KARYAWAN');
                $sheet->setCellValue('E5', 'JENIS KELAMIN');
                $sheet->setCellValue('F5', 'KONTRAK / TETAP');
                $sheet->setCellValue('G5', 'STAFF / NON STAFF');
                $sheet->setCellValue('H5', 'JABATAN');
                $sheet->setCellValue('I5', 'ID DEPARTMENT');
                $sheet->setCellValue('J5', 'DEPARTMENT');
                $sheet->setCellValue('K5', 'ID BAGIAN');
                $sheet->setCellValue('L5', 'BAGIAN');
                $sheet->setCellValue('M5', 'SEWING / NON SEWING');
                $sheet->setCellValue('N5', 'DIRECT / INDIRECT');
                $sheet->setCellValue('O5', 'AKTIF / TIDAK AKTIF');
                $sheet->setCellValue('P5', 'TANGGAL MASUK');
                $sheet->setCellValue('Q5', 'TANGGAL RESIGN');
                $sheet->setCellValue('R5', 'TEMPAT LAHIR');
                $sheet->setCellValue('S5', 'TANGGAL LAHIR');
                $sheet->setCellValue('T5', 'AGAMA');
                $sheet->setCellValue('U5', 'IBU KANDUNG');
                $sheet->setCellValue('V5', 'STATUS KAWIN');
                $sheet->setCellValue('W5', 'PTKP');
                $sheet->setCellValue('X5', 'NPWP');
                $sheet->setCellValue('Y5', 'NOMOR KTP');
                $sheet->setCellValue('Z5', 'NOMOR KK');
                $sheet->setCellValue('AA5', 'GOLONGAN DARAH');
                $sheet->setCellValue('AB5', 'NOMOR TELEPON');
                $sheet->setCellValue('AC5', 'EMAIL');
                $sheet->setCellValue('AD5', 'PENDIDIKAN TERAKHIR');
                $sheet->setCellValue('AE5', 'JURUSAN PENDIDIKAN TERAKHIR');
                $sheet->setCellValue('AF5', 'NAMA BANK');
                $sheet->setCellValue('AG5', 'NOMOR REKENING');
                $sheet->setCellValue('AH5', 'ALAMAT RUMAH');
                $sheet->setCellValue('AI5', 'PROPINSI');
                $sheet->setCellValue('AJ5', 'KOTA / KAB');
                $sheet->setCellValue('AK5', 'KECAMATAN');
                $sheet->setCellValue('AL5', 'KELURAHAN / DESA');
                $sheet->setCellValue('AM5', 'ALAMAT SEMENTARA');
                $sheet->setCellValue('AN5', 'TUNJANGAN');
                $sheet->setCellValue('AO5', 'KODE GRADE');
                $sheet->setCellValue('AP5', 'REFERENSI');
                $sheet->setCellValue('AQ5', 'NAMA ATASAN');
                $sheet->setCellValue('AR5', 'STATUS AKTIF BPJS TK');
                $sheet->setCellValue('AS5', 'TANGGAL BPJS TK');
                $sheet->setCellValue('AT5', 'NOMOR BPJS TK');
                $sheet->setCellValue('AU5', 'STATUS AKTIF BPJS KS');
                $sheet->setCellValue('AV5', 'TANGGAL BPJS KS');
                $sheet->setCellValue('AW5', 'NOMOR BPJS KS');
                $sheet->setCellValue('AX5', 'PENGALAMAN KERJA');
                $sheet->setCellValue('AY5', 'NAMA KERABAT');
                $sheet->setCellValue('AZ5', 'NOMOR TLPN KERABAT');
                $sheet->setCellValue('BA5', 'HUBUNGAN KERABAT');
                $sheet->setCellValue('BB5', 'ALAMAT KERABAT');
                $sheet->setCellValue('BC5', 'TANGGAL VAKSIN 1');
                $sheet->setCellValue('BD5', 'NAMA VAKSIN 1');
                $sheet->setCellValue('BE5', 'TANGGAL VAKSIN 2');
                $sheet->setCellValue('BF5', 'NAMA VAKSIN 2');
                $sheet->setCellValue('BG5', 'TANGGAL VAKSIN 3');
                $sheet->setCellValue('BH5', 'NAMA VAKSIN 3');
                $sheet->setCellValue('BI5', 'GOLONGAN SIM');
                $sheet->setCellValue('BJ5', 'NOMOR SIM');
                $sheet->setCellValue('BK5', 'TANGGAL EXPIRE SIM');
                $sheet->setCellValue('BL5', 'CATATAN');
                $sheet->setCellValue('BM5', 'TANGGAL MULAI KONTRAK');
                $sheet->setCellValue('BN5', 'TANGGAL AKHIR KONTRAK');
                $sheet->setCellValue('BO5', 'CATATAN KONTRAK');
                $sheet->setCellValue('BP5', 'TERAKHIR DI BUAT');
                $sheet->setCellValue('BQ5', 'TERAKHIR DI UBAH');

            },
        ];
    }

    public function properties(): array
    {
        return [
            'creator'        => 'PT NAG - HRIS',
            'lastModifiedBy' => 'HRIS',
            'title'          => 'DATA KARYAWAN',
            'description'    => 'DATA KARYAWAN',
            'subject'        => 'DATA KARYAWAN',
            'keywords'       => 'hr,hris,hrm,daily,report,karyawan,data',
            'category'       => 'DATA',
            'manager'        => 'HRIS',
            'company'        => 'PT Nirwana Alabare Garment',
        ];
    }
}
