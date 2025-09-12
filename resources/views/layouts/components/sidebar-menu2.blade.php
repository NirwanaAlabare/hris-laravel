<!-- Sidebar menu-->
{{--  <div class="app-sidebar__overlay" data-toggle="sidebar"></div>  --}}
<style>
    .slide-menu .slide-item span {
        display: block;
        text-align: left;
        padding-left: 10px;
    }
</style>

<aside class="app-sidebar toggle-sidebar shadow">
    <ul class="side-menu toggle-menu">
        @php
        if (($loggedAdmin->role_user == "admin") || ($loggedAdmin->role_user == "superadmin")|| ($loggedAdmin->role_user == "absensi")) {
        @endphp

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-clipboard"></i><span class="side-menu__label">Master Data</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{route('hris.hrd.index')}}" class="slide-item"><span> Surat Keterangan Kerja</span></a></li>
                <li><a href="{{route('hris.hrd.kontrak_kerja')}}" class="slide-item"><span> PKS</span></a></li>
                <li><a href="{{route('hris.hrd.layoff_termination')}}" class="slide-item"><span> Layoff & Termination</span></a></li>
                <li><a href="{{route('permintaan_tenaga_kerja_hr.permintaan_tenaga_kerja_hr')}}" class="slide-item"><span> Permintaan Tenaga Kerja</span></a></li>
                <li><a href="{{route('tindakan_kedisiplinan.surat_peringatan_hr')}}" class="slide-item"><span> Pendisiplinan</span></a></li>
            </ul>
        </li>
        @php
            }
        @endphp

        <li class="slide">
            <a class="side-menu__item"  data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-th-large-outline"></i><span class="side-menu__label">Setting</span><i class="angle fa fa-angle-right"></i></a>
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
<!--sidemenu end-->
