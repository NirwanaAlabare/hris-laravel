
<link href="{{URL::asset('assets/plugins/notify-growl/css/jquery.growl.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/notify-growl/css/notifIt.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
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
.notifyimg {
    width: 40px; /* Sesuaikan ukuran lingkaran */
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notifyimg i {
    font-size: 18px; /* Sesuaikan ukuran ikon */
    color: white;
}

.toast-custom {
    background-color: white !important; /* Background putih */
    border-left: 5px solid blue !important; /* Border biru di kiri */
    color: black !important; /* Warna teks */
    box-shadow: 0px 0px 10px rgba(0, 0, 255, 0.5); /* Efek glow biru */
}

.toast-custom .toast-title::before {
    content: none !important;
}



/* Sidebar umum */
.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    width: 250px;
    overflow-y: auto;
    z-index: 1000;
    background-color: #f8f9fa;
    transition: transform 0.3s ease-in-out;
}

/* Sidebar notifikasi */
.notify-sidebar {
    width: 400px;
    right: -250px; /* Sidebar disembunyikan di luar layar */
}

.notify-sidebar.sidebar-open {
    right: 0; /* Sidebar ditampilkan */
}

/* Sidebar profil */
.profile-sidebar {
    right: -250px; /* Sidebar disembunyikan di luar layar */
}

.profile-sidebar.sidebar-open {
    right: 0; /* Sidebar ditampilkan */
}

</style>

<style>
    .container-notif {
        width: 100%;
        max-width: 450px;
        padding: 10px 15px;
    }
    .notification-card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;

    }

    .notification-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }


    .notification-card.unread {
        border-left: 2px solid var(--primary); /* Garis biru di sebelah kiri */
        background-color: #c2dbff;  /* Warna latar belakang berbeda */
    }

    /* Card sudah dibaca */
    .notification-card.read {
        border-left: 4px solid #e0e0e0; /* Garis abu-abu di sebelah kiri */
        opacity: 0.8; /* Sedikit transparan untuk menunjukkan sudah dibaca */
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eaedf0;
        padding-bottom: 10px;
        padding-top: 10px;
    }

    .source {
        display: flex;
        align-items: center;
        line-height: 5px;
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .card-body {
        padding: 10px;
    }

    .source-value {
        color: #111827;
        font-weight: 600;
        font-size: 12px;
    }

    .notification-type {
        display: inline-block;
        color: rgb(0, 0, 0);
        font-size: 9px;
        border-radius: 4px;
        padding: 5px 5px;
        text-transform: uppercase;
    }

    .message.read {
        font-size: 12px;
        line-height: 1.2;
        color: #3f4757;
        font-weight: 400;
        padding: 0px;
        margin-bottom: 5px;
    }
    .message.unread {
        font-size: 12px;
        line-height: 1.2;
        color: #1d212a;
        font-weight: 400;
        padding: 0px;
        margin-bottom: 5px;
        font-weight: 500;
    }
    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        padding-bottom: 0px;
    }
    .meta-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .badge {
        display: inline-block;
        /* background-color: var(--primary); */
        color: rgb(0, 0, 0);
        font-size: 9px;
        border-radius: 4px;
        border: 1px solid var(--primary);
        padding: 5px 5px;
        text-transform: uppercase;
        width: 100%;
    }

    .timestamp-waktu {
        color: #6b7280;
        font-size: 11px;
    }

    .view-button {
        display: flex;
        align-items: center;
        gap: 3px;
        background-color: #ffffff;
        color: rgb(0, 0, 0);
        font-size: 13px;
        padding: 5px 6px;
        border-radius: 6px;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }
    .view-delete-btn {
        display: flex;
        align-items: center;
        gap: 3px;
        background-color: #ffffff;
        color: rgb(0, 0, 0);
        font-size: 13px;
        padding: 5px 6px;
        border-radius: 6px;
        text-decoration: none;
        transition: background-color 0.2s ease;
        margin-left: 5px;
    }
    .view-delete-btn:hover {
        background-color: #e9e9e9;
        color: rgb(0, 0, 0);
    }

    .view-button:hover {
        background-color: #e9e9e9;
        color: rgb(0, 0, 0);
    }


    .arrow-icon {
        width: 14px;
        height: 14px;
        margin-left: 4px;
    }

    @media (max-width: 480px) {
        .container {
            padding: 15px;
        }

        .card-header {
            padding: 14px 16px;
        }

        .card-body {
            padding: 16px;
        }

        .message {
            font-size: 15px;
        }
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

        <div class="dropdown    ">
            <a href="#" class="nav-link icon siderbar-link" id="toggle-notify-sidebar">
                <i class="fe fe-bell"></i>
                <span class="pulse bg-primary h-4 w-4" style="font-size: 11px; display: flex; align-items: center; justify-content: center"></span>
            </a>
        </div>
        <div class="dropdown">
            <a  class="nav-link icon full-screen-link" id="fullscreen-button">
                <i class="fe fe-maximize-2"></i>
            </a>
        </div>


        <!-- Sidebar Notifikasi -->
        <div class="sidebar sidebar-right notify-sidebar sidebar-animate" id="notify-sidebar">
            <div class="card-header bg-primary p-3">
                <div class="card-title w-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Notifikasi</div>
                        <div>
                            <button id="btn-refresh-data-notifikasi" name="btn-refresh-data-notifikasi" class="btn btn-icon btn-secondary p-0 m-0">
                                <span>
                                    <i class="fa fa-refresh"></i>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-3" id="notification-list">
                <!-- Notifikasi akan dimuat di sini melalui AJAX -->
            </div>
        </div>

        <!-- Tombol untuk membuka sidebar profil -->
        <div class="dropdown">
            <a class="nav-link leading-none siderbar-link" id="toggle-profile-sidebar">
                <span class="mr-3 d-none d-lg-block">
                    <span class="text-gray-white"><span class="ml-2">{{ $loggedAdmin->name }}</span></span>
                </span>
                <span class="avatar avatar-md brround">
                    <img src="{{URL::asset('assets/images/users/avatars/avatar4.png')}}" alt="Profile-img" class="avatar avatar-md brround">
                </span>
            </a>
        </div>

     <!-- Right-sidebar-->
        <div class="sidebar sidebar-right sidebar-animate" id="profile-sidebar">
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
    </div>
</div>





<!-- Notifications js -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<!-- Bootstrap -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- Bootstrap Notify -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/3.1.3/bootstrap-notify.min.js"></script>

<script src="{{URL::asset('assets/plugins/notify-growl/js/rainbow.js')}}"></script>
<script src="{{URL::asset('assets/plugins/notify-growl/js/sample.js')}}"></script>
<script src="{{URL::asset('assets/plugins/notify-growl/js/jquery.growl.js')}}"></script>
<script src="{{URL::asset('assets/plugins/notify-growl/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets/plugins/growl-notification-bootstrap-alert/bootstrap-notify.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/growl-notification-bootstrap-alert/bootstrap-notify.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/3.1.3/bootstrap-notify.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    function getCurrentTime() {
        const now = new Date();
        return now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace(":", "-");
    }

    $(document).ready(function() {
        const enroll_id = @json($loggedAdmin);
        function loadNotifications() {
            $.ajax({
                url: "{{ route('admin.admin.get_notifications') }}",
                method: 'GET',
                success: function(response) {
                    $('#notification-list').empty();
                    if(response.notifications.length === 0) {
                        $('#notification-list').append('<div class="text-center">Tidak ada notifikasi</div>');
                    } else {
                        response.notifications.forEach(function(notification) {
                            let cardClass = notification.is_read ? 'read' : 'unread';
                            let iconClass = notification.is_read ? 'fa-envelope-open-o' : 'fa-envelope-o';
                            let badgeClass;
                            switch(notification.type) {
                                case 'REKAP PAYROLL':
                                    badgeClass = 'badge-success';
                                    break;
                                case 'REKAP LEMBUR':
                                    badgeClass = 'badge-primary';
                                    break;
                                case 'KONTRAK':
                                    badgeClass = 'badge-default';
                                    break;
                                default:
                                    badgeClass = 'badge-default';
                            }

                            let cardHtml = `
                            <div class="container-notif">
                                <div class="notification-card ${cardClass}" data-id="${notification.id}" data-href="${notification.href_menu}?enroll_ids=${notification.enroll_ids.join(',')}">
                                    <div class="card-header">
                                        <div>
                                            <div class="source">
                                                <span class="source-value">${notification.sender.name}</span>
                                            </div>
                                            <span class="timestamp-waktu">${moment(notification.created_at).format('DD MMMM YYYY HH:mm')}</span>
                                        </div>
                                        <div class="notification-type ${badgeClass}">${notification.type}</div>
                                    </div>
                                    <div class="card-body">
                                        <p class="message ${cardClass}">${notification.message}</p>
                                        <div class="card-footer">
                                            <div class="meta-info">
                                                ${notification.status_staff ? `<span class="badge">${notification.status_staff}</span>` : ''}
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="view-button btn-mark-read" data-id="${notification.id}" title="Tandai Baca">
                                                    <i class="fa ${iconClass}" aria-hidden="true"></i>
                                                </div>
                                                <div class="view-delete-btn" data-id="${notification.id}" title="Hapus">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                            $('#notification-list').append(cardHtml);
                        });
                    }
                    updateUnreadCount(response.unread_count);
                },
                error: function(xhr) {
                    console.error('Gagal memuat notifikasi.');
                }
            });
        }

                // Klik tombol Mark as Read
        $('#notification-list').on('click', '.btn-mark-read', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let notificationId = $(this).data('id');
            let $card = $(this).closest('.notification-card');
            markAsRead(notificationId, $card);
        });

        // Klik tombol Delete
        $('#notification-list').on('click', '.view-delete-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            let notificationId = $(this).data('id');
            let $card = $(this).closest('.notification-card');
            markAsDelete(notificationId, $card);
        });

        // Klik Card (buka halaman)
        $('#notification-list').on('click', '.notification-card', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if ($(e.target).closest('.btn-mark-read').length > 0 || $(e.target).closest('.view-delete-btn').length > 0) {
                return;
            }
            let notificationId = $(this).data('id');
            let href = $(this).data('href');
            let $card = $(this);

            markAsRead(notificationId, $card);

            if (href) {
                window.open(href, '_blank');
            }
        });


        $('#btn-refresh-data-notifikasi').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            loadNotifications();
        });



        loadNotifications();

        setInterval(function() {
            loadNotifications();
        }, 60000);

        function markAsRead(notificationId, cardElement) {
            $.ajax({
                url: `{{ route('admin.admin.markAsRead', '') }}/${notificationId}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
                },
                success: function(response) {
                    if (response.success) {
                        var $cardElement = $(cardElement);
                        // Update tampilan card
                        $cardElement.removeClass('unread').addClass('read');
                        $cardElement.css('opacity', '0.8');
                        console.log('response',response)
                        // Update jumlah notifikasi yang belum dibaca

                        $cardElement.removeClass('unread').addClass('read');
                        let $icon = $cardElement.find('.btn-mark-read i');
                        $icon.removeClass('fa-envelope-o').addClass('fa-envelope-open-o');

                        updateUnreadCount(response.unread_count);

                    }
                },
                error: function(xhr) {
                    console.error('Gagal mengupdate notifikasi.');
                }
            });
        }
        function markAsDelete(notificationId, cardElement) {
            $.ajax({
                url: `{{ route('admin.admin.markAsDelete', '') }}/${notificationId}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
                },
                success: function(response) {
                    if (response.success) {
                        loadNotifications();
                    }
                },
                error: function(xhr) {
                    console.error('Gagal mengupdate notifikasi.');
                }
            });
        }

        function updateUnreadCount(unreadCount) {
            const $pulse = $('.pulse');
            if (unreadCount > 0) {
                $pulse.text(unreadCount).show(); // Tampilkan jumlah notifikasi yang belum dibaca
            } else {
                $pulse.hide(); // Sembunyikan pulse jika tidak ada notifikasi yang belum dibaca
            }
        }

        $('#toggle-notify-sidebar').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Mencegah event klik menyebar ke document
            loadNotifications();
            $('#notify-sidebar').toggleClass('sidebar-open');
            $('#profile-sidebar').removeClass('sidebar-open'); // Tutup sidebar profil jika terbuka
        });

        // Toggle sidebar profil
        $('#toggle-profile-sidebar').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Mencegah event klik menyebar ke document
            $('#profile-sidebar').toggleClass('sidebar-open');
            $('#notify-sidebar').removeClass('sidebar-open'); // Tutup sidebar notifikasi jika terbuka
        });

        // Tutup sidebar ketika klik di luar sidebar
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.sidebar').length && !$(e.target).closest('#toggle-notify-sidebar, #toggle-profile-sidebar').length) {
                $('#notify-sidebar').removeClass('sidebar-open');
                $('#profile-sidebar').removeClass('sidebar-open');
            }
        });

        // Mencegah sidebar menutup ketika klik di dalam sidebar
        $('.sidebar').on('click', function(e) {
            e.stopPropagation();
        });

    });
</script>

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
