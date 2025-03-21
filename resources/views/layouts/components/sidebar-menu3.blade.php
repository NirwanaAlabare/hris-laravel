<!-- Sidebar menu-->
{{--  <div class="app-sidebar__overlay" data-toggle="sidebar"></div>  --}}
<aside class="app-sidebar toggle-sidebar shadow">
    <ul class="side-menu toggle-menu">

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon fa fa-car"></i><span class="side-menu__label">Transportasi</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{route('hris.ga.form_pengajuan_transportasi')}}" class="slide-item pl-3"><span> Pengajuan Transportasi</span></a></li>
                <li><a href="{{route('hris.ga.pemeliharaan_kendaraan')}}" class="slide-item pl-3"><span> Pemeliharaan Kendaraan</span></a></li>
            </ul>
        </li>
        @if (Auth::guard('admin')->user()->email == 'fadli' || Auth::guard('admin')->user()->email == 'mega@ptnag.com' || Auth::guard('admin')->user()->email == 'rudy@patnag.com' )
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-clipboard"></i><span class="side-menu__label">License & Permit</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{route('dokumen_legal.index')}}" class="slide-item pl-3"><span>Dokumen Legal</span></a></li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon fa fa-universal-access"></i><span class="side-menu__label">Entertaint</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{route('entertaint_tamu.index')}}" class="slide-item pl-3"><span>Entertaint Tamu</span></a></li>
            </ul>
        </li>
        @endif
    </ul>
</aside>
<!--sidemenu end-->
