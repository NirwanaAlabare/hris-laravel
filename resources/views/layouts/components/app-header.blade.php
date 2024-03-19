
        <div class="d-flex">
            <a class="header-brand" href="{{url('hris/dashboard/index')}}">
                <img src="{{URL::asset('assets/images/brand/logo2.png')}}" class="header-brand-img main-logo" alt="Sparic logo">
                <img src="{{URL::asset('assets/images/brand/icon.png')}}" class="header-brand-img icon-logo" alt="Sparic logo">
            </a><!-- logo-->
            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-toggle="sidebar" href="#"></a>
            <div class="d-flex order-lg-2 ml-auto header-rightmenu">

                <div class="dropdown">
                    <a  class="nav-link icon full-screen-link" id="fullscreen-button">
                        <i class="fe fe-maximize-2"></i>
                    </a>
                </div><!-- full-screen -->
                <div class="dropdown header-notify">
                    <a href="#" class="nav-link icon" data-toggle="dropdown" aria-expanded="false">
                        <i class="fe fe-bell"></i>
                        <span class="pulse bg-success"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow ">
                        <a href="#" class="dropdown-item text-center">4 New Notifications</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item d-flex pb-3">
                            <div class="notifyimg bg-green">
                                <i class="fe fe-mail"></i>
                            </div>
                            <div>
                                <strong>Message Sent.</strong>
                                <div class="small text-muted">12 mins ago</div>
                            </div>
                        </a>
                        <a href="#" class="dropdown-item d-flex pb-3">
                            <div class="notifyimg bg-pink">
                                <i class="fe fe-shopping-cart"></i>
                            </div>
                            <div>
                                <strong>Order Placed</strong>
                                <div class="small text-muted">2  hour ago</div>
                            </div>
                        </a>
                        <a href="#" class="dropdown-item d-flex pb-3">
                            <div class="notifyimg bg-blue">
                                <i class="fe fe-calendar"></i>
                            </div>
                            <div>
                                <strong> Event Started</strong>
                                <div class="small text-muted">1  hour ago</div>
                            </div>
                        </a>
                        <a href="#" class="dropdown-item d-flex pb-3">
                            <div class="notifyimg bg-orange">
                                <i class="fe fe-monitor"></i>
                            </div>
                            <div>
                                <strong>Your Admin Lanuch</strong>
                                <div class="small text-muted">2  days ago</div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item text-center">View all Notifications</a>
                    </div>
                </div><!-- notifications -->
                <div class="dropdown header-user">
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        <div class="header-user text-center mt-4 pb-4">
                            <span class="avatar avatar-xxl brround"><img src="{{URL::asset('assets/images/users/avatars/19.png')}}" alt="Profile-img" class="avatar avatar-xxl brround"></span>
                            <a href="#" class="dropdown-item text-center font-weight-semibold user h3 mb-0">Alison</a>
                            <small>Web Designer</small>
                        </div>
                        <a class="dropdown-item" href="#">
                            <i class="dropdown-icon mdi mdi-account-outline "></i> Spruko technologies
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="dropdown-icon  mdi mdi-account-plus"></i> Add another Account
                        </a>
                        <div class="card-body border-top">
                            <div class="row">
                                <div class="col-6 text-center">
                                    <a class="" href=""><i class="dropdown-icon mdi  mdi-message-outline fs-30 m-0 leading-tight"></i></a>
                                    <div>Inbox</div>
                                </div>
                                <div class="col-6 text-center">
                                    <a class="" href=""><i class="dropdown-icon mdi mdi-logout-variant fs-30 m-0 leading-tight"></i></a>
                                    <div>Sign out</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- profile -->
                <div class="dropdown">
                    <a class="nav-link leading-none siderbar-link" data-toggle="sidebar-right" data-target=".sidebar-right">
                        <span class="mr-3 d-none d-lg-block ">
                            <span class="text-gray-white"><span class="ml-2">{{ $loggedAdmin->name }}</span></span>
                        </span>
                        <span class="avatar avatar-md brround"><img src="{{URL::asset('assets/images/users/avatars/avatar4.png')}}" alt="Profile-img" class="avatar avatar-md brround"></span>
                    </a>
                </div><!-- Right-siebar-->
            </div>
        </div>
        <!-- Right-sidebar-->
        <div class="sidebar sidebar-right sidebar-animate">
            <div class="card-header bg-primary p-3">
                <div class="card-title">Profile User</div>
            </div>
            <div class="card-body p-0">
                <div class="header-user text-center mt-4 pb-4">
                    <span class="avatar avatar-xxl brround"><img src="{{URL::asset('assets/images/users/avatars/avatar4.png')}}" alt="Profile-img" class="avatar avatar-xxl brround"></span>
                    <div class="dropdown-item text-center font-weight-semibold user h3 mb-0">{{ $loggedAdmin->name }}</div>
                    @php
                    $role_user = "";
                    switch ($loggedAdmin->role_user) {
                        case 'guest':
                            $role_user = "Guest";
                            break;
                        case 'absensi':
                            $role_user = "Absensi";
                            break;
                        case 'payroll':
                            $role_user = "Payroll";
                            break;
                        case 'admin':
                            $role_user = "Administrator";
                            break;
                        case 'superadmin':
                            $role_user = "Super Admin";
                            break;
                    }
                    $nestedData['role_user'] = $role_user;
                    $level = "";
                    switch ($loggedAdmin->level) {
                        case 'read':
                            $level = "Lihat";
                            break;
                        case 'cread':
                            $level = "Input dan Lihat";
                            break;
                        case 'updel':
                            $level = "Update dan Delete";
                            break;
                        case 'crud':
                            $level = "CRUD";
                            break;
                    }
                    @endphp
                    <small>{{ $role_user }} | {{ $level }}</small>
                    <div class="card-body">
                        <div class="form-group ">
                            <label class="form-label  text-left">Offline/Online</label>
                            <select class="form-control select2 " data-placeholder="Choose one">
                                <option label="Choose one">
                                </option>
                                <option value="1">Online</option>
                                <option value="2">Offline</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body border-top">
                        <div class="row">
                            <div class="col-4 text-center">
                                <a href="{{ url('screenlock') }}"><i class="dropdown-icon fa fa-lock fs-30 m-0 leading-tight"></i>
                                <div>Lock App</div></a>
                            </div>
                            <div class="col-4 text-center">
                                <a class="" href="{{ route('admin.admin.editprofile') }}"><i class="dropdown-icon fa fa-address-card-o fs-30 m-0 leading-tight"></i>
                                <div>Edit Profile</div></a>
                            </div>
                            <div class="col-4 text-center">
                                <a class="" href="{{ route('admin.logout') }}"><i class="dropdown-icon mdi mdi-logout-variant fs-30 m-0 leading-tight"></i>
                                <div>Sign Out</div></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

