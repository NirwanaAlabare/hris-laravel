
        
<style>
.dropdown-theme {
    display: none;
    position: absolute;
    height: 50px;
    justify-content: center;
    align-items: center;    
    top: 40px;
    left: 0;
    background-color:rgb(255, 255, 255);
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    z-index: 10;
    width: auto;
}

.color-option {
    display: inline-block;
    width: 35px;
    height: 35px;
    margin: 5px;
    border-radius: 50%;
    cursor: pointer;
    margin-top: 20px

}

.primarySub {
    background-color: #15435A;
}

.pinkSub {
    background-color: #FF72C6;
}

.secondSub {
    background-color: #920559;
}

.theme-switcher {
    height:30px;
    width:30px;
    background-color: var(--primary);
    color: white;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-right: 18px;
}
button.theme-switcher:hover {
            opacity: 0.8;
        }
        button.theme-switcher{
            transition: all 0.7s ease;
            box-sizing: border-box;
        } 

</style>
<div class="d-flex">
    <a class="header-brand" href="{{url('hris/dashboard')}}">
        <img src="{{URL::asset('assets/images/brand/hris.png')}}" class="header-brand-img main-logo" alt="Sparic logo">
        <img src="{{URL::asset('assets/images/brand/icon.png')}}" class="header-brand-img icon-logo" alt="Sparic logo">
    </a><!-- logo-->
    <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-toggle="sidebar" href="#"></a>
    <div class="d-flex order-lg-2 ml-auto header-rightmenu">
        <div class="dropdown text-center mt-4 pb-4">
            <a  class="">
                <button class="theme-switcher" id="themeSwitcher">T</button>
            </a>
            <div class="dropdown-theme text-center mt-4 pb-4" id="colorPicker">
                <div class="color-option primarySub" data-color="primary"></div>
                <div class="color-option secondSub" data-color="second"></div>
                <div class="color-option pinkSub" data-color="pink"></div>
            </div>
        </div>

        
        <div class="dropdown">
            <a  class="nav-link icon full-screen-link" id="fullscreen-button">
                <i class="fe fe-maximize-2"></i>
            </a>
        </div><!-- full-screen -->
        
        <!-- notifications -->
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
        </div>
        <!-- profile -->
        <div class="dropdown">
            <a class="nav-link leading-none siderbar-link" data-toggle="sidebar-right" data-target=".sidebar-right">
                <span class="mr-3 d-none d-lg-block ">
                    <span class="text-gray-white"><span class="ml-2">{{ $loggedAdmin->name }}</span></span>
                </span>
                <span class="avatar avatar-md brround"><img src="{{URL::asset('assets/images/users/avatars/avatar4.png')}}" alt="Profile-img" class="avatar avatar-md brround"></span>
            </a>
        </div>
        <!-- Right-siebar-->
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



        <!-- <script>
           document.addEventListener('DOMContentLoaded', function () {
            const themeSwitcher = document.getElementById('themeSwitcher');
            const body = document.body;

            // Load theme from localStorage
            if (localStorage.getItem('theme') === 'light') {
                body.classList.add('light-theme');
            }

            themeSwitcher.addEventListener('click', function () {
                colorPicker.style.display = colorPicker.style.display === 'block' ? 'none' : 'block';
                // Toggle theme
                if (body.classList.contains('light-theme')) {
                    body.classList.remove('light-theme');
                    localStorage.setItem('theme', 'dark');
                } else {
                    body.classList.add('light-theme');
                    localStorage.setItem('theme', 'light');
                }
            });
        });

        </script> -->

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const themeSwitcher = document.getElementById('themeSwitcher');
                const body = document.body;
                const colorPicker = document.getElementById('colorPicker');
                const colorOptions = document.querySelectorAll('.color-option');

                // Load theme from localStorage
                const savedTheme = localStorage.getItem('theme') || 'light'; // Default to light if not set
                body.classList.add(savedTheme + '-theme');

                themeSwitcher.addEventListener('click', function () {
                    colorPicker.style.display = colorPicker.style.display === 'flex' ? 'none' : 'flex';
                });

                // Theme switching logic
                colorOptions.forEach(option => {
                    option.addEventListener('click', function () {
                        const colorType = this.getAttribute('data-color');

                        if (colorType === 'primary') {
                            body.classList.remove('second-theme', 'pink-theme');
                            body.classList.add('light-theme'); // Default light theme
                            localStorage.setItem('theme', 'light');
                        } else if (colorType === 'pink') {
                            body.classList.remove('second-theme', 'light-theme');
                            body.classList.add('pink-theme');
                            localStorage.setItem('theme', 'pink');
                        } else if (colorType === 'second') {
                            body.classList.remove('pink-theme', 'light-theme');
                            body.classList.add('second-theme');
                            localStorage.setItem('theme', 'second');
                        }

                        colorPicker.style.display = 'none';
                    });
                });
            });

        </script>