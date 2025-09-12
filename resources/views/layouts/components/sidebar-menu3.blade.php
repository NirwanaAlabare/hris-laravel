<!-- Sidebar menu-->
{{--  <div class="app-sidebar__overlay" data-toggle="sidebar"></div>  --}}
<aside class="app-sidebar toggle-sidebar shadow">
    <ul class="side-menu toggle-menu">

        @if (Auth::guard('admin')->user()->email == 'fadli' || Auth::guard('admin')->user()->email == 'ramon' || Auth::guard('admin')->user()->email == 'ronald@ptnag.com' || Auth::guard('admin')->user()->email == 'hadiyoso@nag.nirwanaindonesia.com' || Auth::guard('admin')->user()->email == 'mega@ptnag.com' || Auth::guard('admin')->user()->email == 'rudy@ptnag.com' || Auth::guard('admin')->user()->email == 'HR' || Auth::guard('admin')->user()->email == 'hrd' || Auth::guard('admin')->user()->email == 'ersa@ptnag.com' || Auth::guard('admin')->user()->email == 'tita')
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon fa fa-car"></i><span class="side-menu__label">Transportasi</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{route('hris.ga.data_pengajuan_transportasi')}}" class="slide-item pl-3"><span> Pengajuan Transportasi</span></a></li>
                <li><a href="{{route('hris.ga.pemeliharaan_kendaraan')}}" class="slide-item pl-3"><span> Pemeliharaan Kendaraan</span></a></li>
                <li><a href="{{route('hris.ga.pemeriksaan_kendaraan')}}" class="slide-item pl-3"><span> Pemeriksaan Kendaraan</span></a></li>
                <li><a href="{{route('hris.ga.get_pengajuan_perbaikan_kendaraan')}}" class="slide-item pl-3"><span> Pengajuan Perbaikan</span></a></li>
            </ul>
        </li>
        @endif
        @if (Auth::guard('admin')->user()->email == 'fadli' || Auth::guard('admin')->user()->email == 'ramon' || Auth::guard('admin')->user()->email == 'ronald@ptnag.com' || Auth::guard('admin')->user()->email == 'hadiyoso@nag.nirwanaindonesia.com' || Auth::guard('admin')->user()->email == 'mega@ptnag.com' || Auth::guard('admin')->user()->email == 'rudy@ptnag.com' || Auth::guard('admin')->user()->email == 'HR' || Auth::guard('admin')->user()->email == 'hrd' || Auth::guard('admin')->user()->email == 'ersa@ptnag.com')
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


<script>
document.addEventListener("DOMContentLoaded", function () {
    // lepas semua handler click lama di .slide > a[data-toggle="slide"]
    if (window.jQuery) {
        $('.slide > a[data-toggle="slide"]').off('click');
        $('.sub-slide > a[data-toggle="sub-slide"]').off('click');
    }

    // bikin handler baru (multi expand allowed)
    document.querySelectorAll(".slide > a[data-toggle='slide']").forEach(function(el) {
        el.addEventListener("click", function(e) {
            e.preventDefault();
            el.parentElement.classList.toggle("is-expanded");
        });
    });

    document.querySelectorAll(".sub-slide > a[data-toggle='sub-slide']").forEach(function(el) {
        el.addEventListener("click", function(e) {
            e.preventDefault();
            el.parentElement.classList.toggle("is-expanded");
        });
    });
});
</script>
