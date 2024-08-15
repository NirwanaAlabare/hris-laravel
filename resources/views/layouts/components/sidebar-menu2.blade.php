<!-- Sidebar menu-->
{{--  <div class="app-sidebar__overlay" data-toggle="sidebar"></div>  --}}
<aside class="app-sidebar toggle-sidebar shadow">
    <ul class="side-menu toggle-menu">
        @php
        if (($loggedAdmin->role_user == "admin") || ($loggedAdmin->role_user == "superadmin")|| ($loggedAdmin->role_user == "absensi")) {
        @endphp

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#"><i class="side-menu__icon typcn typcn-clipboard"></i><span class="side-menu__label">Master Data</span><i class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{route('hris.hrd.index')}}" class="slide-item"><span> Karyawan</span></a></li>
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
<!--sidemenu end-->
