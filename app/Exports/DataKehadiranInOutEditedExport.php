<?php

namespace App\Exports;

use App\Models\DataKehadiranInOutEdited;
use App\Models\MasterDataAbsenKehadiran;
use App\Http\Controllers\Hris\DataKehadiranInOutEditedController;

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

class DataKehadiranInOutEditedExport implements FromQuery, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell, WithTitle
{
    use Exportable;

    public function exportParams()
    {
        return $this;
    }

    public function query()
    {
        $q =  DataKehadiranInOutEdited::query()
                ->join('department_all', 'employee_atribut.sub_dept_id', '=', 'department_all.sub_dept_id')
                ->groupBy('employee_atribut.enroll_id')
                ->orderBy('employee_atribut.employee_name','asc')
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
        $employee_id = $Data->employee_id;
        $employee_name = $Data->employee_name;
        $jenis_kelamin = $Data->jenis_kelamin;
        $tempat_lahir = $Data->tempat_lahir;
        $tanggal_lahir = $Data->tanggal_lahir;
        $golongan_darah = $Data->golongan_darah;
        $email = $Data->email;
        $nomor_tlpn = $Data->nomor_tlpn;
        $agama = $Data->agama;
        $status_kawin = $Data->status_kawin;
        $npwp = $Data->npwp;
        $nomor_ktp = $Data->nomor_ktp;
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
        $site_nirwana_id_new = $Data->site_nirwana_id_new;
        $site_nirwana_name = $Data->site_nirwana_name;
        $site_nirwana_name_new = $Data->site_nirwana_name_new;
        $department_id = $Data->department_id;
        $department_id_new = $Data->department_id_new;
        $department_name = $Data->department_name;
        $department_name_new = $Data->department_name_new;
        $sub_dept_id = $Data->sub_dept_id;
        $sub_dept_id_new = $Data->sub_dept_id_new;
        $sub_dept_name = $Data->sub_dept_name;
        $sub_dept_name_new = $Data->sub_dept_name_new;
        $enroll_id = $Data->enroll_id;
        $join_date = $Data->join_date;
        $nik = $Data->nik;
        $nik_new = $Data->nik_new;
        $status_aktif = $Data->status_aktif;
        $status_jabatan = $Data->status_jabatan;
        $status_kontrak_tetap = $Data->status_kontrak_tetap;
        $status_staff = $Data->status_staff;
        $tanggal_resign = $Data->tanggal_resign;
        $tunjangan = $Data->tunjangan;
        $kode_grade = $Data->kode_grade;
        $referensi = $Data->referensi;
        $employee_name_atasan = $Data->employee_name_atasan;
        $status_aktif_bpjs_tk = $Data->status_aktif_bpjs_tk;
        $tanggal_bpjs_ketenagakerjaan = $Data->tanggal_bpjs_ketenagakerjaan;
        $nomor_bpjs_ketenagakerjaan = $Data->nomor_bpjs_ketenagakerjaan;
        $status_aktif_bpjs_ks = $Data->status_aktif_bpjs_ks;
        $tanggal_bpjs_kesehatan = $Data->tanggal_bpjs_kesehatan;
        $nomor_bpjs_kesehatan = $Data->nomor_bpjs_kesehatan;
        $pengalaman_bekerja = $Data->pengalaman_bekerja;
        $lokasi_file_cv = $Data->lokasi_file_cv;
        $nama_kerabat = $Data->nama_kerabat;
        $nomor_tlpn_kerabat = $Data->nomor_tlpn_kerabat;
        $hubungan_kerabat = $Data->hubungan_kerabat;
        $alamat_kerabat = $Data->alamat_kerabat;
        $tanggal_vaccine1 = $Data->tanggal_vaccine1;
        $nama_vaksin1 = $Data->nama_vaksin1;
        $tanggal_vaccine2 = $Data->tanggal_vaccine2;
        $nama_vaksin2 = $Data->nama_vaksin2;
        $tanggal_vaccine3 = $Data->tanggal_vaccine3;
        $nama_vaksin3 = $Data->nama_vaksin3;
        $golongan_sim = $Data->golongan_sim;
        $nomor_sim = $Data->nomor_sim;
        $tanggal_expire_sim = $Data->tanggal_expire_sim;
        $catatan = $Data->catatan;
        $lokasi_foto = $Data->lokasi_foto;
        $operator = $Data->operator;
        $tanggal_mulai_kontrak = $Data->tanggal_mulai_kontrak;
        $tanggal_akhir_kontrak = $Data->tanggal_akhir_kontrak;
        $catatan_kontrak = $Data->catatan_kontrak;
        $created_at = $Data->created_at;
        $updated_at = $Data->updated_at;

        return [
            $employee_id,
            $employee_name,
            $jenis_kelamin,
            $tempat_lahir,
            $tanggal_lahir,
            $golongan_darah,
            $email,
            $nomor_tlpn,
            $agama,
            $status_kawin,
            $npwp,
            $nomor_ktp,
            $nomor_kk,
            $ptkp,
            $pendidikan_terakhir,
            $jurusan_pendidikan,
            $nama_bank,
            $nomor_rekening_bank,
            $ibu_kandung,
            $propinsi,
            $kota_kab,
            $kecamatan,
            $kelurahan_desa,
            $alamat_rumah,
            $alamat_sementara,
            $site_nirwana_id,
            $site_nirwana_id_new,
            $site_nirwana_name,
            $site_nirwana_name_new,
            $department_id,
            $department_id_new,
            $department_name,
            $department_name_new,
            $sub_dept_id,
            $sub_dept_id_new,
            $sub_dept_name,
            $sub_dept_name_new,
            $enroll_id,
            $join_date,
            $nik,
            $nik_new,
            $status_aktif,
            $status_jabatan,
            $status_kontrak_tetap,
            $status_staff,
            $tanggal_resign,
            $tunjangan,
            $kode_grade,
            $referensi,
            $employee_name_atasan,
            $status_aktif_bpjs_tk,
            $tanggal_bpjs_ketenagakerjaan,
            $nomor_bpjs_ketenagakerjaan,
            $status_aktif_bpjs_ks,
            $tanggal_bpjs_kesehatan,
            $nomor_bpjs_kesehatan,
            $pengalaman_bekerja,
            $nama_kerabat,
            $nomor_tlpn_kerabat,
            $hubungan_kerabat,
            $alamat_kerabat,
            $tanggal_vaccine1,
            $nama_vaksin1,
            $tanggal_vaccine2,
            $nama_vaksin2,
            $tanggal_vaccine3,
            $nama_vaksin3,
            $golongan_sim,
            $nomor_sim,
            $tanggal_expire_sim,
            $catatan,
            $tanggal_mulai_kontrak,
            $tanggal_akhir_kontrak,
            $catatan_kontrak,
            $created_at,
            $updated_at,
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
                $sheet->setCellValue('A3', 'Periode Tahun dan Bulan : ' . substr(now(), 0, 4) . '-' . substr(now(), 5, 2));
                $sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:D3');

                $sheet->setCellValue('A5', 'EMPLOYEE ID');
                $sheet->setCellValue('B5', 'NAMA KARYAWAN');
                $sheet->setCellValue('C5', 'JENIS KELAMIN');
                $sheet->setCellValue('D5', 'TEMPAT LAHIR');
                $sheet->setCellValue('E5', 'TANGGAL LAHIR');
                $sheet->setCellValue('F5', 'GOLONGAN DARAH');
                $sheet->setCellValue('G5', 'EMAIL');
                $sheet->setCellValue('H5', 'NOMOR TELEPON');
                $sheet->setCellValue('I5', 'AGAMA');
                $sheet->setCellValue('J5', 'STATUS KAWIN');
                $sheet->setCellValue('K5', 'NPWP');
                $sheet->setCellValue('L5', 'NOMOR KTP');
                $sheet->setCellValue('M5', 'NOMOR KK');
                $sheet->setCellValue('N5', 'PTKP');
                $sheet->setCellValue('O5', 'PENDIDIKAN TERAKHIR');
                $sheet->setCellValue('P5', 'JURUSAN PENDIDIKAN TERAKHIR');
                $sheet->setCellValue('Q5', 'NAMA BANK');
                $sheet->setCellValue('R5', 'NOMOR REKENING');
                $sheet->setCellValue('S5', 'IBU KANDUNG');
                $sheet->setCellValue('T5', 'PROPINSI');
                $sheet->setCellValue('U5', 'KOTA / KAB');
                $sheet->setCellValue('V5', 'KECAMATAN');
                $sheet->setCellValue('W5', 'KELURAHAN / DESA');
                $sheet->setCellValue('X5', 'ALAMAT RUMAH');
                $sheet->setCellValue('Y5', 'ALAMAT SEMENTARA');
                $sheet->setCellValue('Z5', 'KODE DIVISI');
                $sheet->setCellValue('AA5', 'KODE BARU DIVISI');
                $sheet->setCellValue('AB5', 'NAMA DIVISI');
                $sheet->setCellValue('AC5', 'NAMA BARU DIVISI');
                $sheet->setCellValue('AD5', 'KODE DEPARTMENT');
                $sheet->setCellValue('AE5', 'KODE BARU DEPARTMENT');
                $sheet->setCellValue('AF5', 'NAMA DEPARTMENT');
                $sheet->setCellValue('AG5', 'NAMA BARU DEPARTMENT');
                $sheet->setCellValue('AH5', 'KODE BAGIAN');
                $sheet->setCellValue('AI5', 'KODE BARU BAGIAN');
                $sheet->setCellValue('AJ5', 'NAMA BAGIAN');
                $sheet->setCellValue('AK5', 'NAMA BARU BAGIAN');
                $sheet->setCellValue('AL5', 'NOMOR ABSEN');
                $sheet->setCellValue('AM5', 'TANGGAL MASUK');
                $sheet->setCellValue('AN5', 'NIK');
                $sheet->setCellValue('AO5', 'NIK BARU');
                $sheet->setCellValue('AP5', 'AKTIF / TIDAK AKTIF');
                $sheet->setCellValue('AQ5', 'JABATAN');
                $sheet->setCellValue('AR5', 'KONTRAK / TETAP');
                $sheet->setCellValue('AS5', 'STAFF / NON STAFF');
                $sheet->setCellValue('AT5', 'TANGGAL RESIGN');
                $sheet->setCellValue('AU5', 'TUNJANGAN');
                $sheet->setCellValue('AV5', 'KODE GRADE');
                $sheet->setCellValue('AW5', 'REFERENSI');
                $sheet->setCellValue('AX5', 'NAMA ATASAN');
                $sheet->setCellValue('AY5', 'STATUS AKTIF BPJS TK');
                $sheet->setCellValue('AZ5', 'TANGGAL BPJS TK');
                $sheet->setCellValue('BA5', 'NOMOR BPJS TK');
                $sheet->setCellValue('BB5', 'STATUS AKTIF BPJS KS');
                $sheet->setCellValue('BC5', 'TANGGAL BPJS KS');
                $sheet->setCellValue('BD5', 'NOMOR BPJS KS');
                $sheet->setCellValue('BE5', 'PENGALAMAN KERJA');
                $sheet->setCellValue('BF5', 'NAMA KERABAT');
                $sheet->setCellValue('BG5', 'NOMOR TLPN KERABAT');
                $sheet->setCellValue('BH5', 'HUBUNGAN KERABAT');
                $sheet->setCellValue('BI5', 'ALAMAT KERABAT');
                $sheet->setCellValue('BJ5', 'TANGGAL VAKSIN 1');
                $sheet->setCellValue('BK5', 'NAMA VAKSIN 1');
                $sheet->setCellValue('BL5', 'TANGGAL VAKSIN 2');
                $sheet->setCellValue('BM5', 'NAMA VAKSIN 2');
                $sheet->setCellValue('BN5', 'TANGGAL VAKSIN 3');
                $sheet->setCellValue('BO5', 'NAMA VAKSIN 3');
                $sheet->setCellValue('BP5', 'GOLONGAN SIM');
                $sheet->setCellValue('BQ5', 'NOMOR SIM');
                $sheet->setCellValue('BR5', 'TANGGAL EXPIRE SIM');
                $sheet->setCellValue('BS5', 'CATATAN');
                $sheet->setCellValue('BT5', 'TANGGAL MULAI KONTRAK');
                $sheet->setCellValue('BU5', 'TANGGAL AKHIR KONTRAK');
                $sheet->setCellValue('BV5', 'CATATAN KONTRAK');
                $sheet->setCellValue('BW5', 'TERAKHIR DI BUAT');
                $sheet->setCellValue('BX5', 'TERAKHIR DI UBAH');

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
