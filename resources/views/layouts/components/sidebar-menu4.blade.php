
    <!-- Sidebar menu-->
{{--  <div class="app-sidebar__overlay" data-toggle="sidebar"></div>  --}}
<aside class="app-sidebar toggle-sidebar shadow">
    <ul class="side-menu toggle-menu">
    <li class="slide">
        <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-clipboard"></i><span class="side-menu__label">Mutasi Karyawan</span><i class="angle fa fa-angle-right"></i></a>
        <ul class="slide-menu">
            <li><a href="{{route('hris.mutasi-karyawan.dashboard')}}" class="slide-item"><span> Dashboard</span></a></li>
            <li><a href="{{route('hris.mutasi-karyawan')}}" class="slide-item"><span> Mutasi Karyawan</span></a></li>
        </ul>
    </li>

    <li class="slide">
        <a class="side-menu__item"  data-toggle="slide" href="#"><i class="side-menu__icon fa fa-file-text"></i><span class="side-menu__label">Form Lembur</span><i class="angle fa fa-angle-right"></i></a>
        <ul class="slide-menu">
            <li><a href="{{route('fls.index')}}" class="slide-item"><span> Form Sewing</span></a></li>
            <li><a href="{{route('flns.index')}}" class="slide-item"><span> Form Non Sewing</span></a></li>
        </ul>

    </li>
    <li class="slide">
        <a class="side-menu__item"  data-toggle="slide" href="#"><i class="side-menu__icon fa fa-cutlery"></i><span class="side-menu__label">Anggaran Makan</span><i class="angle fa fa-angle-right"></i></a>
        <ul class="slide-menu">
            <li><a href="{{route('anggaran_makan.index')}}" class="slide-item"><span> Anggaran Makan</span></a></li>
    </ul>
    <li class="slide">
        <a class="side-menu__item"  data-toggle="slide" href="#"><i class="side-menu__icon fa fa-cart-arrow-down"></i><span class="side-menu__label">Bazzar</span><i class="angle fa fa-angle-right"></i></a>
        <ul class="slide-menu">
            <li><a href="{{route('bazzar.index')}}" class="slide-item"><span>Pengajuan Kupon</span></a></li>
    </ul>
    <li class="slide">
        <a class="side-menu__item"  data-toggle="slide" href="#"><i class="side-menu__icon fa fa-vcard"></i><span class="side-menu__label">Perizinan Karyawan</span><i class="angle fa fa-angle-right"></i></a>
        <ul class="slide-menu">
            <li><a href="{{route('cuti_karyawan.pengajuan_perizinan_admin')}}" class="slide-item"><span>Pengajuan Perizinan</span></a></li>
    </ul>
    @php
    if (($loggedAdmin->name == "HR") || ($loggedAdmin->name == "HRD")|| ($loggedAdmin->name == "GA") || ($loggedAdmin->email == 'mega@ptnag.com') || ($loggedAdmin->email == 'rudy@ptnag.com') || ($loggedAdmin->email == 'fadli') || ($loggedAdmin->email == 'indri@nag.nirwanaindonesia.com') || ($loggedAdmin->email == 'ersa@ptnag.com')) {
        @endphp
    <li class="mt-4">
        <a class="btn btn-app w-100" style="background-color: #16a34a" data-toggle="tooltip" title="Export Rekap Overtime" href="{{ route('anggaran_makan.export_excel_overtime_recap2') }}"
            class="dropdown-item">
            Export Overtime
            <i class="ml-2 fa fa-file-excel-o fa-sm"></i>
        </a>
    </li>
    @php
    }
    @endphp
       </ul>
    </aside>
