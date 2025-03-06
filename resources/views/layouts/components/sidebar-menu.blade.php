<!-- Sidebar menu-->
{{--  <div class="app-sidebar__overlay" data-toggle="sidebar"></div>  --}}
<aside class="app-sidebar toggle-sidebar shadow">
    <ul class="side-menu toggle-menu">
        <li class="slide">
            <a class="side-menu__item"  data-toggle="slide" href="#">
                <!-- <i class="side-menu__icon typcn typcn-th-large-outline"></i> -->
                <i class="side-menu__icon fa fa-calendar-check-o" aria-hidden="true"></i>
                <span class="side-menu__label">Absensi Karyawan</span><i class="angle fa fa-angle-right"></i></a>

            <ul class="slide-menu">
                <li><a class="slide-item" href="{{route('hris.dashboard.index')}}"><span> Dashboard</span></a></li>
                <li><a class="slide-item" href="{{route('hris.mdabsenhadir.datahadir')}}"><span> Data Kehadiran</span></a></li>
                <li><a class="slide-item" href="{{route('hris.gagalabsen.index')}}"><span> Gagal Absen</span></a></li>
                <!-- <li><a class="slide-item" href="{{route('hris.dataabsenperijinan.index')}}"><span> Absen Perizinan</span></a></li> -->
                <li class="sub-slide">
                    <a href="#" data-toggle="sub-slide" class="sub-slide-item"><span class="sub-side-menu__label">Absen Perizinan</span><i class="sub-angle fa fa-angle-right"></i></a>
                    <ul class="sub-slide-menu">
                        <li><a class="sub-slide-item" href="{{route('hris.dataabsenperijinan.index')}}"><span> Data Perizinan</span></a></li>
                        <li><a class="sub-slide-item" href="{{route('hris.dataabsenperijinan.verifikasi')}}"><span> Verifikasi Perizinan</span></a></li>
                    </ul>
                </li>
                <li class="sub-slide">
                    <a href="#" data-toggle="sub-slide" class="sub-slide-item"><span class="sub-side-menu__label">Lembur Karyawan</span><i class="sub-angle fa fa-angle-right"></i></a>
                    <ul class="sub-slide-menu">
                        <li><a class="sub-slide-item" href="{{route('hris.datalembur.index')}}"><span> Data Lembur</span></a></li>
                        <li><a class="sub-slide-item" href="{{route('hris.verifikasi_insentif_lembur.index')}}"><span> Verifikasi Insentif Lembur</span></a></li>
                        <li><a class="sub-slide-item" href="{{route('hris.datalembur.add_datalembur')}}"><span> Tambah Data Lembur</span></a></li>
                    </ul>
                </li>
                <li class="sub-slide">
                    <a href="#" data-toggle="sub-slide" class="sub-slide-item"><span class="sub-side-menu__label">Koreksi</span><i class="sub-angle fa fa-angle-right"></i></a>
                    <ul class="sub-slide-menu">
                        <li><a class="sub-slide-item" href="{{route('hris.koreksiupah.index')}}"><span>Penambah Upah</span></a></li>
                        <li><a class="sub-slide-item" href="{{route('hris.koreksipotongan.index')}}"><span>Pengurang Upah</span></a></li>
                    </ul>
                </li>
                @php
                    if (($loggedAdmin->role_user == "admin") || ($loggedAdmin->role_user == "superadmin")|| ($loggedAdmin->role_user == "absensi")) {
                @endphp
                 <!--<li><a class="slide-item" href="{{route('hris.mdabsenhadir.proses')}}"><span> Proses Kehadiran</span></a></li>-->
                <li><a class="slide-item" href="{{route('hris.rekapkehadirankaryawan.index')}}"><span> Laporan Kehadiran</span></a></li>
                @php
                    }
                @endphp
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <!-- <i class="side-menu__icon typcn typcn-th-large-outline"></i> -->
                <i class="side-menu__icon fa fa-calculator" aria-hidden="true"></i>
                <span class="side-menu__label">Perhitungan</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a class="slide-item" href="{{route('hris.rekapperhitunganlembur.index')}}"><span> Lembur Karyawan</span></a></li>
                <li><a class="slide-item"  href="{{route('hris.rekapperhitungandtpc.index')}}"><span> DTPC Karyawan</span></a></li>
                <li><a class="slide-item"  href="{{route('hris.rekapperhitunganiks.index')}}"><span> IKS Karyawan</span></a></li>
                <li><a class="slide-item" href="{{route('hris.estimasinilaipayroll.index')}}"><span> Estimasi Nilai Payroll</span></a></li>
                @if($loggedAdmin->email === 'willy@ptnag.com' || $loggedAdmin->role_user === 'superadmin' )
                <li><a class="slide-item" href="{{route('hris.nilaipayrollperhari.index')}}"><span> Nilai Payroll Per Hari</span></a></li>
                @endif

                <li><a class="slide-item" href="{{route('hris.jurnal.index')}}"><span> Jurnal</span></a></li>

            </ul>

        </li>

        @php
        if (($loggedAdmin->role_user == "admin") || ($loggedAdmin->role_user == "superadmin")|| ($loggedAdmin->role_user == "absensi")|| ($loggedAdmin->email=='alex.herdian@ptnag.com')) {
        @endphp

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <!-- <i class="side-menu__icon typcn typcn-th-large-outline"></i> -->
                <i class="side-menu__icon fa fa-credit-card" aria-hidden="true"></i>
                <span class="side-menu__label">Payroll</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a class="slide-item" href="{{route('hris.gradingsalary.index')}}"><span> Grading Salary</span></a></li>
                <li><a class="slide-item" href="{{route('hris.employeegrading.index')}}"><span> Salary Karyawan</span></a></li>
                <li><a class="slide-item" href="{{route('hris.tunjangankaryawan.index')}}"><span> Tunjangan Karyawan</span></a></li>
                <li><a class="slide-item" href="{{route('hris.rekapperhitunganpayroll.index')}}"><span> Laporan Payroll</span></a></li>
                <li><a class="slide-item" href="{{route('hris.dataclosingpayroll.index')}}"><span> Closing Payroll</span></a></li>
                @php
                    if (($loggedAdmin->email == "reza") || ($loggedAdmin->email == "fadli")) {
                    @endphp
                        <li><a class="slide-item" href="{{route('hris.aktifitasperubahan.index')}}"><span> Aktifitas Perubahan</span></a></li>
                    @php
                    }
                @endphp
            </ul>

        </li>
        @php
            }
        @endphp

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <!-- <i class="side-menu__icon typcn typcn-th-large-outline"> -->
                <i class="side-menu__icon fa fa-address-card-o" aria-hidden="true"></i>

                </i><span class="side-menu__label">BPJS Karyawan</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{route('hris.dasarpotbpjs.index')}}" class="slide-item"><span> Dasar Pot BPJS</span></a></li>
                <li><a href="{{route('hris.bpjssetting.index')}}" class="slide-item"><span> BPJS TK/KS Tarif</span></a></li>
                <li><a href="{{route('hris.employeebpjs.index')}}" class="slide-item"><span> BPJS TK/KS Karyawan</span></a></li>
            </ul>

        </li>

        @php
        if (($loggedAdmin->role_user == "admin") || ($loggedAdmin->role_user == "superadmin")|| ($loggedAdmin->role_user == "absensi")) {
        @endphp

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <!-- <i class="side-menu__icon typcn typcn-clipboard"></i> -->
                <i class="side-menu__icon fa fa-users" aria-hidden="true"></i>
                <span class="side-menu__label">Master Data</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{route('hris.refabsenijin.index')}}" class="slide-item"><span> Absen Ijin</span></a></li>
                <li><a href="{{route('hris.refharilibur.index')}}"class="slide-item"><span> Hari Libur</span></a></li>
                <li><a href="{{route('hris.datajadwalkerjalog.index')}}"class="slide-item"><span> Jadwal Kerja</span></a></li>
                <li><a href="{{route('hris.departmentall.index')}}" class="slide-item"><span> Department</span></a></li>
                <li><a href="{{route('hris.employeeatr.index')}}" class="slide-item"><span> Karyawan</span></a></li>
                @php
                    if ($loggedAdmin->role_user == "superadmin") {
                @endphp
                <li><a href="{{route('admin.datakehadiraninoutedited.abseninout')}}" class="slide-item"><span> Absen IN/OUT</span></a></li>
                @php
                    }
                @endphp
            </ul>
        </li>
        @php
            }
        @endphp

        <li class="slide">
            <a class="side-menu__item"  data-toggle="slide" href="#">
                <!-- <i class="side-menu__icon typcn typcn-th-large-outline"></i> -->
                <i class="side-menu__icon fa fa-cog" aria-hidden="true"></i>
                <span class="side-menu__label">Setting</span><i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a class="slide-item" href="{{route('admin.admin.editprofile')}}"><span> Profile</span></a></li>
                @php
                    if (($loggedAdmin->role_user == "admin") || ($loggedAdmin->role_user == "superadmin")) {
                @endphp
                <li><a class="slide-item" href="{{route('admin.admin.index')}}"><span> Admin User</span></a></li>
                @php
                    }
                @endphp
            </ul>

        </li>

    </ul>
</aside>
<!--sidemenu end-->
