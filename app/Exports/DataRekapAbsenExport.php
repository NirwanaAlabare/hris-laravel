<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DataRekapAbsenExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($date_now,$periode_payroll,$tanggal_absensi,$jumlah_tanggal_absensi,$bulan_pertama,$bulan_kedua,$non_sewing_staff,$non_sewing_nonstaff,$non_sewing_total,$sewing_total,$sewing_staff,$sewing_nonstaff,$grand_total,$persentase_non_sewing,$persentase_sewing,$non_sewing_nonstaff_present,$non_sewing_staff_present,$non_sewing_present,$sewing_nonstaff_present,$sewing_staff_present,$sewing_present,$grand_total_present,$persentase_non_sewing_present,$persentase_sewing_present,$non_sewing_nonstaff_absent,$non_sewing_staff_absent,$non_sewing_absent,$sewing_nonstaff_absent,$sewing_staff_absent,$sewing_absent,$grand_total_absent,$persentase_non_sewing_absent,$persentase_sewing_absent,$karyawan_sakit,$karyawan_izin,$karyawan_mangkir,$karyawan_libur,$karyawan_cuti,$karyawan_dinas_luar,$karyawan_resign,$recruitment_sewing,$recruitment_nonsewing,$date_string,$all_dept,$all_dept_direct,$total_direct_employee,$all_dept_indirect,$total_indirect_employee,$grand_total_direct_employee)
    {
        $this->date_now=$date_now;
        $this->periode_payroll=$periode_payroll;
        $this->tanggal_absensi=$tanggal_absensi;
        $this->jumlah_tanggal_absensi=$jumlah_tanggal_absensi;
        $this->bulan_pertama=$bulan_pertama;
        $this->bulan_kedua=$bulan_kedua;
        $this->non_sewing_staff=$non_sewing_staff;
        $this->non_sewing_nonstaff=$non_sewing_nonstaff;
        $this->non_sewing_total=$non_sewing_total;
        $this->sewing_total=$sewing_total;
        $this->sewing_staff=$sewing_staff;
        $this->sewing_nonstaff=$sewing_nonstaff;
        $this->grand_total=$grand_total;
        $this->persentase_non_sewing=$persentase_non_sewing;
        $this->persentase_sewing=$persentase_sewing;
        $this->non_sewing_nonstaff_present=$non_sewing_nonstaff_present;
        $this->non_sewing_staff_present=$non_sewing_staff_present;
        $this->non_sewing_present=$non_sewing_present;
        $this->sewing_nonstaff_present=$sewing_nonstaff_present;
        $this->sewing_staff_present=$sewing_staff_present;
        $this->sewing_present=$sewing_present;
        $this->grand_total_present=$grand_total_present;
        $this->persentase_non_sewing_present=$persentase_non_sewing_present;
        $this->persentase_sewing_present=$persentase_sewing_present;
        $this->non_sewing_nonstaff_absent=$non_sewing_nonstaff_absent;
        $this->non_sewing_staff_absent=$non_sewing_staff_absent;
        $this->non_sewing_absent=$non_sewing_absent;
        $this->sewing_nonstaff_absent=$sewing_nonstaff_absent;
        $this->sewing_staff_absent=$sewing_staff_absent;
        $this->sewing_absent=$sewing_absent;
        $this->grand_total_absent=$grand_total_absent;
        $this->persentase_non_sewing_absent=$persentase_non_sewing_absent;
        $this->persentase_sewing_absent=$persentase_sewing_absent;
        $this->karyawan_sakit=$karyawan_sakit;
        $this->karyawan_izin=$karyawan_izin;
        $this->karyawan_mangkir=$karyawan_mangkir;
        $this->karyawan_libur=$karyawan_libur;
        $this->karyawan_cuti=$karyawan_cuti;
        $this->karyawan_dinas_luar=$karyawan_dinas_luar;
        $this->karyawan_resign=$karyawan_resign;
        $this->recruitment_sewing=$recruitment_sewing;
        $this->recruitment_nonsewing=$recruitment_nonsewing;
        $this->date_string=$date_string;
        $this->all_dept=$all_dept;
        $this->all_dept_direct=$all_dept_direct;
        $this->total_direct_employee=$total_direct_employee;
        $this->all_dept_indirect=$all_dept_indirect;
        $this->total_indirect_employee=$total_indirect_employee;
        $this->grand_total_direct_employee=$grand_total_direct_employee;
    }
    public function view(): View
    {
        return view('hris.Laporan.rekap_absen',[
            'date_now'=>$this->date_now,
            'periode_payroll'=>$this->periode_payroll,
            'tanggal_absensi'=>$this->tanggal_absensi,
            'jumlah_tanggal_absensi'=>$this->jumlah_tanggal_absensi,
            'bulan_pertama'=>$this->bulan_pertama,
            'bulan_kedua'=>$this->bulan_kedua,
            'non_sewing_staff'=>$this->non_sewing_staff,
            'non_sewing_nonstaff'=>$this->non_sewing_nonstaff,
            'non_sewing_total'=>$this->non_sewing_total,
            'sewing_total'=>$this->sewing_total,
            'sewing_staff'=>$this->sewing_staff,
            'sewing_nonstaff'=>$this->sewing_nonstaff,
            'grand_total'=>$this->grand_total,
            'persentase_non_sewing' =>$this->persentase_non_sewing,
            'persentase_sewing'=>$this->persentase_sewing,
            'non_sewing_nonstaff_present'=>$this->non_sewing_nonstaff_present,
            'non_sewing_staff_present'=>$this->non_sewing_staff_present,
            'non_sewing_present'=>$this->non_sewing_present,
            'sewing_nonstaff_present'=>$this->sewing_nonstaff_present,
            'sewing_staff_present'=>$this->sewing_staff_present,
            'sewing_present'=>$this->sewing_present,
            'grand_total_present'=>$this->grand_total_present,
            'persentase_non_sewing_present'=>$this->persentase_non_sewing_present,
            'persentase_sewing_present'=>$this->persentase_sewing_present,
            'non_sewing_nonstaff_absent'=>$this->non_sewing_nonstaff_absent,
            'non_sewing_staff_absent'=>$this->non_sewing_staff_absent,
            'non_sewing_absent'=>$this->non_sewing_absent,
            'sewing_nonstaff_absent'=>$this->sewing_nonstaff_absent,
            'sewing_staff_absent'=>$this->sewing_staff_absent,
            'sewing_absent'=>$this->sewing_absent,
            'grand_total_absent'=>$this->grand_total_absent,
            'persentase_non_sewing_absent'=>$this->persentase_non_sewing_absent,
            'persentase_sewing_absent'=>$this->persentase_sewing_absent,
            'karyawan_sakit'=>$this->karyawan_sakit,
            'karyawan_izin'=>$this->karyawan_izin,
            'karyawan_mangkir'=>$this->karyawan_mangkir,
            'karyawan_libur'=>$this->karyawan_libur,
            'karyawan_cuti'=>$this->karyawan_cuti,
            'karyawan_dinas_luar'=>$this->karyawan_dinas_luar,
            'karyawan_resign'=>$this->karyawan_resign,
            'recruitment_sewing'=>$this->recruitment_sewing,
            'recruitment_nonsewing'=>$this->recruitment_nonsewing,
            'date_string'=>$this->date_string,
            'all_dept'=>$this->all_dept,
            'all_dept_direct'=>$this->all_dept_direct,
            'total_direct_employee'=>$this->total_direct_employee,
            'all_dept_indirect'=>$this->all_dept_indirect,
            'total_indirect_employee'=>$this->total_indirect_employee,
            'grand_total_direct_employee'=>$this->grand_total_direct_employee,
        ]);
    }
}
