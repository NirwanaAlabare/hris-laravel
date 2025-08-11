@extends('admin.adminlayouts.adminlayout')

@section('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Owl Theme css-->
<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet">

<!-- Morris  Charts css-->
<link href="{{URL::asset('assets/plugins/morris/morris.css')}}" rel="stylesheet" />

<style>
    :root {
      --color-primary: #0ea5e9;
      --color-primary-light: #e0f2fe;
      --color-emerald: #10b981;
      --color-emerald-light: #d1fae5;
      --color-amber: #f59e0b;
      --color-amber-light: #fef3c7;
      --color-rose: #f43f5e;
      --color-rose-light: #ffe4e6;
      --color-violet: #8b5cf6;
      --color-violet-light: #ede9fe;
      --color-blue: #3b82f6;
      --color-blue-light: #dbeafe;
      --color-teal: #14b8a6;
      --color-teal-light: #ccfbf1;
      --color-white: #ffffff;
      --color-gray-50: #f9fafb;
      --color-gray-100: #f3f4f6;
      --color-gray-200: #e5e7eb;
      --color-gray-300: #d1d5db;
      --color-gray-400: #9ca3af;
      --color-gray-500: #6b7280;
      --color-gray-600: #4b5563;
      --color-gray-700: #374151;
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
      --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      --radius-md: 0.375rem;
      --radius-lg: 0.5rem;
    }



    .container-app {
      max-width: 1400px;
      margin: 0 auto;
      padding: 2rem 1rem;
    }

    header {
      margin-bottom: 2rem;
    }

    .header-content {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    @media (min-width: 768px) {
      .header-content {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
      }
    }

    h1 {
      font-size: 1.875rem;
      font-weight: 700;
      line-height: 1.2;
      color: var(--primary);
    }

    .last-update {
      display: flex;
      align-items: center;
      font-size: 0.875rem;
      color: var(--gray-dark);
      margin-top: 0.5rem;
    }

    .last-update i {
      margin-right: 0.25rem;
      color: var(--primary);
    }

    .date-badge-card-jabatan {
      display: inline-flex;
      align-items: center;
      background-color: var(--color-emerald);
      padding: 0.25rem 0.75rem;
      border-radius: var(--radius-lg);
      font-size: 0.875rem;
      font-weight: 500;
      color: white;
      margin-bottom: 10px;
      margin-top: 6px;
    }
    .date-badge-card {
      display: inline-flex;
      align-items: center;
      background-color: var(--blue);
      padding: 0.25rem 0.75rem;
      border-radius: var(--radius-lg);
      font-size: 0.875rem;
      font-weight: 500;
      color: white;
      margin-bottom: 10px;
      margin-top: 6px;
    }
    .date-badge {
      display: inline-flex;
      align-items: center;
      background-color: var(--blue);
      padding: 0.25rem 0.75rem;
      border-radius: var(--radius-lg);
      font-size: 0.875rem;
      font-weight: 500;
      color: white;
    }

    .date-badge i {
      margin-right: 0.5rem;
    }

    .dashboard-grid {
      display: grid;
      gap: 1.5rem;
      grid-template-columns: 1fr;
    }

    @media (min-width: 768px) {
      .dashboard-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (min-width: 1024px) {
      .dashboard-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    .card-app {
      background-color: var(--color-white);
      border-radius: var(--radius-lg);
      overflow: hidden;
      box-shadow: var(--shadow-lg);
    }

    .card-header-app {
        padding: 1.25rem 1.5rem 0.5rem;
        display: flex;
        flex-direction: column;
        text-align: left;          /* supaya teks rata kiri */
        justify-content: flex-start; /* agar item mulai dari atas */
        align-items: flex-start;   /* agar item rata kiri */
    }
    .card-header-app.emerald {
      background-color: var(--color-emerald-light);
    }

    .card-header-app.sky {
      background-color: var(--color-primary-light);
    }

    .card-header-app.amber {
      background-color: var(--color-amber-light);
    }
    .card-title, .card-description {
        width: 100%;  /* supaya teks mengambil lebar penuh */
        text-align: left; /* pastikan juga ini */
        margin: 0; /* kalau ada margin tengah yang besar */
        padding: 0; /* cek padding juga */
        font-size: 0.875rem;
      color: var(--color-gray-500);
      margin-top: 0.25rem;
      text-align: left;
        }

    .card-title {
      font-size: 1.125rem;
      font-weight: 500;
      color: var(--color-gray-700);
      width: 100%;  /* supaya teks mengambil lebar penuh */
      text-align: left;
    }

    .card-content-app {
      padding: 1.5rem;
    }

    /* Attendance Chart */
    .attendance-chart {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .chart-container {
      position: relative;
      width: 12rem;
      height: 12rem;
      margin-bottom: 1rem;
    }

    .chart-circle {
      transform: rotate(-90deg);
      transform-origin: center;
      height: 200px;
      width: 200px;
    }

    .chart-bg {
      fill: none;
      stroke: var(--color-gray-200);
      stroke-width: 10;
    }

    .chart-progress {
      fill: none;
      stroke: var(--color-emerald);
      stroke-width: 10;
      stroke-linecap: round;
      stroke-dasharray: 251.2;
      stroke-dashoffset: 17.58; /* 251.2 * (1 - 0.9303) */
      transition: stroke-dashoffset 1s ease;
    }

    .chart-text {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }

    .chart-percentage {
      font-size: 1.875rem;
      font-weight: 700;
      color: var(--color-emerald);
    }

    .chart-label {
      font-size: 0.875rem;
      color: var(--color-gray-500);
    }

    .attendance-stats {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 0.5rem;
      width: 100%;
    }

    .stat-box {
      background-color: var(--color-gray-50);
      border-radius: var(--radius-md);
      padding: 0.75rem;
      text-align: center;
      border: 1px solid var(--color-gray-200);
    }

    .stat-label {
      font-size: 0.875rem;
      color: var(--color-gray-500);
    }

    .stat-value {
      font-size: 1.25rem;
      font-weight: 700;
      margin-top: 0.25rem;
    }

    .stat-value.present {
      color: var(--color-emerald);
    }

    .stat-value.absent {
      color: var(--color-rose);
    }

    /* Employee Stats */
    .employee-count {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.8rem;
    }

    .count-value {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--color-blue);
      line-height: 1;
    }

    .count-label {
      font-size: 0.875rem;
      color: var(--color-gray-500);
    }

    .count-icon {
      background-color: var(--color-primary-light);
      color: var(--color-primary);
      width: 3rem;
      height: 3rem;
      border-radius: 9999px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 40px;
    }

    .count-icon-2 {
      background-color: var(--color-primary-light);
      color: var(--color-primary);
      width: 3rem;
      height: 3rem;
      border-radius: 9999px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .progress-container {
      margin-bottom: 1rem;
    }

    .progress-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.25rem;
    }

    .progress-label {
      font-size: 0.875rem;
      color: var(--color-gray-500);
    }

    .progress-value {
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--color-gray-700);
    }

    .progress-bar {
      height: 0.5rem;
      border-radius: 9999px;
      overflow: hidden;
    }
    .progress-bar.gray {
      background-color: #d5d5d5;
    }

    .progress-indicator {
      height: 100%;
      border-radius: 9999px;
      transition: width 0.5s ease;
    }

    .progress-indicator.staff {
      background-color: var(--primary);
      /* width: 8.9%; */
    }

    .progress-indicator.non-staff {
      background-color: var(--primary);
      /* width: 91.1%; */
    }

    .progress-indicator.staff-gs {
      background-color: var(--primary);
      /* width: 8.9%; */
    }

    .progress-indicator.non-staff-gs {
      background-color: var(--primary);
      /* width: 91.1%; */
    }
    .progress-indicator.staff-nagd {
      background-color: var(--primary);
      /* width: 8.9%; */
    }

    .progress-indicator.non-staff-nagd {
      background-color: var(--primary);
      /* width: 91.1%; */
    }
    .progress-indicator.staff-nak {
      background-color: var(--primary);
      /* width: 8.9%; */
    }

    .progress-indicator.non-staff-nak {
      background-color: var(--primary);
      /* width: 91.1%; */
    }
    .progress-indicator.staff-sa {
      background-color: var(--primary);
      /* width: 8.9%; */
    }

    .progress-indicator.non-staff-sa {
      background-color: var(--primary);
      /* width: 91.1%; */
    }
    .progress-indicator.indicator-jabatan-tidak-aktif {
      background-color: var(--primary);
      /* width: 91.1%; */
    }
    .progress-indicator.indicator-jabatan-aktif {
      background-color: var(--primary);
      /* width: 91.1%; */
    }

    /* Attendance Stats */
    .jml-karyawan-card {
      align-items: center;
      background-color: var(--color-gray-50);
      border-radius: var(--radius-lg);
      margin: 0.7rem;
      border: 1px solid var(--color-gray-200);
    }

    .jml-karyawan-card:last-child {
      margin-bottom: 0;
    }
    .stat-card {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: var(--color-gray-50);
      padding: 1rem;
      border-radius: var(--radius-lg);
      margin-bottom: 1rem;
      border: 1px solid var(--color-gray-200);
    }

    .stat-card:last-child {
      margin-bottom: 0;
    }

    .stat-info {
      display: flex;
      align-items: center;
    }

    .stat-icon {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 9999px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
    }

    .today-icon {
      background-color: var(--color-amber-light);
      color: var(--color-amber);
    }

    .yesterday-icon {
      background-color: var(--color-rose-light);
      color: var(--color-rose);
    }

    .lastweek-icon {
      background-color: var(--color-violet-light);
      color: var(--color-violet);
    }

    .stat-details {
      display: flex;
      flex-direction: column;
    }

    .stat-period {
      font-size: 0.875rem;
      color: var(--color-gray-500);
    }

    .stat-count {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--color-gray-700);
    }

    .stat-code {
      font-size: 1.5rem;
      font-weight: 700;
    }

    .stat-code.today {
      color: var(--color-amber);
    }

    .stat-code.yesterday {
      color: var(--color-rose);
    }

    .stat-code.lastweek {
      color: var(--color-violet);
    }
  </style>
  <style>
    #chartdiv {
      width: 100%;
      height: 300px;
    }
</style>
@stop
@section('mainarea')

<!-- page-header -->
<div class="page-header shadow pr-2 m-0 pt-0 pb-0 pl-2">
    <ol class="breadcrumb breadcrumb-arrow m-0 p-0">
        <li><a href="{{route('hris.dashboard.tes')}}">DASHBOARD</a></li>
        <li class="active"><span>KEHADIRAN</span></li>
    </ol>
    <div class="ml-auto">
        <div class="input-group">
            <a href="#" id="btn-refresh-data" class="btn btn-icon btn-secondary p-0 m-0" data-toggle="tooltip"
                title="" data-placement="bottom" data-original-title="Refresh Halaman">
                <span>
                    <i class="fa fa-refresh"></i>
                </span>
            </a>
        </div>
    </div>
</div>
<!-- End page-header -->

<div class="container-app">
    <header>
      <div class="header-content">
        <div>
          <h1>MONITORING ABSENSI KARYAWAN</h1>
          <div class="last-update">
            <span id="tanggal_kehadiran_sekarang"></span>
          </div>
        </div>
        <div class="date-badge">
          <i class="fa fa-calendar"></i>
          <span>20 Mei 2025</span>
        </div>
      </div>
    </header>

    <div class="dashboard-grid">
      <!-- Attendance Card -->
      <div class="card-app">
        <div class="card-header-app emerald">
          <div class="card-title">Tingkat Kehadiran</div>
          <div class="card-description">Persentase kehadiran karyawan</div>
        </div>
        <div class="card-content-app">
          <div class="attendance-chart">
            <div id="chartdiv"></div>
            <div class="attendance-stats">
              <div class="stat-box">
                <div class="stat-label">Hadir</div>
                <div class="stat-value present" id="jumlah_karyawan_masuk"></div>
              </div>
              <div class="stat-box">
                <div class="stat-label">Tidak Hadir</div>
                <div class="stat-value absent" id="jumlah_karyawan_tidak_masuk">170</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Employee Card -->
      <div class="card-app ">
        <div class="card-header-app sky">
          <div class="card-title">Karyawan</div>
          <div class="card-description">Jumlah staff dan non-staff</div>
        </div>
        <div class="jml-karyawan-card" style="margin: 1.5rem;">
            <div class="px-4 py-2" >
            <div class="employee-count">
                <div>
                    <div class="date-badge-card">
                        <div style="">Nirwana Alabare Garment</div>
                    </div>
                    <div class="count-value" id="total_karyawan_aktif"></div>
                    <div class="count-label">Jumlah Karyawan Aktif</div>
                </div>
                <div class="count-icon">
                    <i class="fa fa-users fa-lg"></i>
                </div>
            </div>

            <div class="progress-container">
                <div class="progress-header">
                <div class="progress-label">Staff</div>
                <div class="progress-value" id="jumlah_staff"></div>
                </div>
                <div class="progress-bar gray">
                <div class="progress-indicator staff"></div>
                </div>
            </div>

            <div class="progress-container">
                <div class="progress-header">
                <div class="progress-label">Non Staff</div>
                <div class="progress-value" id="jumlah_nonstaff"></div>
                </div>
                <div class="progress-bar gray">
                <div class="progress-indicator non-staff"></div>
                </div>
            </div>
            </div>
        </div>
      </div>

      <!-- Statistics Card -->
        <div class="card-app">
            <div class="card-header-app amber">
            <div class="card-title">Statistik Kehadiran</div>
            <div class="card-description">Perbandingan periode waktu</div>
            </div>
            <div class="card-content-app">
            <div class="stat-card">
                <div class="stat-info">
                <div class="stat-icon today-icon">
                    <i class="fa fa-line-chart"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-period">Hari Ini</div>
                    <div class="stat-count" id="absen_m_hari_ini"></div>
                </div>
                </div>
                <div class="stat-code today">M</div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                <div class="stat-icon yesterday-icon">
                    <i class="fa fa-user"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-period">Hari Kemarin</div>
                    <div class="stat-count" id="absen_tl_hari_kemarin"></div>
                </div>
                </div>
                <div class="stat-code yesterday">TL</div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                <div class="stat-icon lastweek-icon">
                    <i class="fa fa-bar-chart"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-period">Minggu Kemarin</div>
                    <div class="stat-count" id="absen_m_weekly"></div>
                </div>
                </div>
                <div class="stat-code lastweek">M</div>
            </div>
            </div>
        </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-3 gap-0 m-0 p-0">
                <div class="jml-karyawan-card">
                    <div class="px-4 py-2" >
                        <div class="employee-count">
                            <div>
                                <div class="date-badge-card">
                                <div style="">Gunajaya Santosa</div>
                                </div>
                                <div class="count-value" id="total_karyawan_aktif_gs"></div>
                                <div class="count-label">Jumlah Karyawan Aktif</div>
                            </div>
                            <div class="count-icon">
                                <i class="fa fa-users fa-lg"></i>
                            </div>
                        </div>

                        <div class="progress-container">
                            <div class="progress-header">
                            <div class="progress-label">Staff</div>
                            <div class="progress-value" id="jumlah_staff_gs"></div>
                            </div>
                            <div class="progress-bar gray">
                            <div class="progress-indicator staff-gs"></div>
                            </div>
                        </div>

                        <div class="progress-container">
                            <div class="progress-header">
                            <div class="progress-label">Non Staff</div>
                            <div class="progress-value" id="jumlah_nonstaff_gs"></div>
                            </div>
                            <div class="progress-bar gray">
                            <div class="progress-indicator non-staff-gs"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 gap-0 m-0 p-0">
                <div class="jml-karyawan-card">
                    <div class="px-4 py-2" >
                    <div class="employee-count">
                        <div>
                            <div class="date-badge-card">
                            <div style="">Nirwana Alabare Garment - Dago</div>
                            </div>
                            <div class="count-value" id="total_karyawan_aktif_nagd"></div>
                            <div class="count-label">Jumlah Karyawan Aktif</div>
                        </div>
                        <div class="count-icon">
                            <i class="fa fa-users fa-lg"></i>
                        </div>
                    </div>

                    <div class="progress-container">
                        <div class="progress-header">
                        <div class="progress-label">Staff</div>
                        <div class="progress-value" id="jumlah_staff_nagd"></div>
                        </div>
                        <div class="progress-bar gray">
                        <div class="progress-indicator staff-nagd"></div>
                        </div>
                    </div>

                    <div class="progress-container">
                        <div class="progress-header">
                        <div class="progress-label">Non Staff</div>
                        <div class="progress-value" id="jumlah_nonstaff_nagd"></div>
                        </div>
                        <div class="progress-bar gray">
                        <div class="progress-indicator non-staff-nagd"></div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 gap-0 m-0 p-0">
                <div class="jml-karyawan-card">
                    <div class="px-4 py-2" >
                    <div class="employee-count">
                        <div>
                            <div class="date-badge-card">
                            <div style="">Nirwana Alabare Knitting</div>
                            </div>
                            <div class="count-value" id="total_karyawan_aktif_nak"></div>
                            <div class="count-label">Jumlah Karyawan Aktif</div>
                        </div>
                        <div class="count-icon">
                            <i class="fa fa-users fa-lg"></i>
                        </div>
                    </div>

                    <div class="progress-container">
                        <div class="progress-header">
                        <div class="progress-label">Staff</div>
                        <div class="progress-value" id="jumlah_staff_nak"></div>
                        </div>
                        <div class="progress-bar gray">
                        <div class="progress-indicator staff-nak"></div>
                        </div>
                    </div>

                    <div class="progress-container">
                        <div class="progress-header">
                        <div class="progress-label">Non Staff</div>
                        <div class="progress-value" id="jumlah_nonstaff_nak"></div>
                        </div>
                        <div class="progress-bar gray">
                        <div class="progress-indicator non-staff-nak"></div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 gap-0 m-0 p-0">
                <div class="jml-karyawan-card">
                    <div class="px-4 py-2" >
                    <div class="employee-count">
                        <div>
                            <div class="date-badge-card">
                            <div style="">Soljer Abadi</div>
                            </div>
                            <div class="count-value" id="total_karyawan_aktif_sa"></div>
                            <div class="count-label">Jumlah Karyawan Aktif</div>
                        </div>
                        <div class="count-icon">
                            <i class="fa fa-users fa-lg"></i>
                        </div>
                    </div>

                    <div class="progress-container">
                        <div class="progress-header">
                        <div class="progress-label">Staff</div>
                        <div class="progress-value" id="jumlah_staff_sa"></div>
                        </div>
                        <div class="progress-bar gray">
                        <div class="progress-indicator staff-sa"></div>
                        </div>
                    </div>

                    <div class="progress-container">
                        <div class="progress-header">
                        <div class="progress-label">Non Staff</div>
                        <div class="progress-value" id="jumlah_nonstaff_sa"></div>
                        </div>
                        <div class="progress-bar gray">
                        <div class="progress-indicator non-staff-sa"></div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

         <div class="row mt-3">
                <div class="col-md-3 gap-0 m-0 p-0">
                <div class="jml-karyawan-card">
                    <div class="px-4 py-2" >
                        <div class="employee-count">
                            <div>
                                <div class="date-badge-card-jabatan">
                                <div style="">Aktif</div>
                                </div>
                               <div class="count-value">{{ number_format($total_semua_aktif, 0, ',', ',') }}</div>
                                <div class="count-label">Jumlah Karyawan Aktif</div>
                            </div>
                            <div class="count-icon">
                                <i class="fa fa-user-circle-o fa-lg"></i>
                            </div>
                        </div>
                       @foreach ($data_jabatan_aktif as $value)
                            @php
                                $persentase = $total_semua_aktif > 0
                                    ? round(($value->total / $total_semua_aktif) * 100, 2)
                                    : 0;
                            @endphp

                            <div class="progress-container">
                                <div class="progress-header">
                                    <div class="progress-label">{{ $value->status_jabatan }}</div>
                                    <div class="progress-value">{{ $value->total }} ({{ $persentase }}%)</div>
                                </div>
                                <div class="progress-bar gray">
                                    <div class="progress-indicator indicator-jabatan-aktif"
                                        style="width: {{ $persentase }}%;">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
            <div class="col-md-3 gap-0 m-0 p-0">
               <div class="jml-karyawan-card">
                    <div class="px-4 py-2" >
                    <div class="employee-count">
                        <div>
                            <div class="date-badge-card-jabatan" style="background-color: red; color: white;">
                            <div style="">Non Aktif</div>
                            </div>
                            <div class="count-value">{{ number_format($total_semua_non_aktif, 0, ',', ',') }}</div>
                            <div class="count-label">Jumlah Karyawan Non-Aktif</div>
                        </div>
                        <div class="count-icon">
                            <i class="fa fa-user-times fa-lg"></i>
                        </div>
                    </div>

                    @foreach ($data_jabatan_non_aktif as $value)
                            @php
                                $persentase = $total_semua_non_aktif > 0
                                    ? round(($value->total / $total_semua_non_aktif) * 100, 2)
                                    : 0;
                            @endphp

                            <div class="progress-container">
                                <div class="progress-header">
                                    <div class="progress-label">{{ $value->status_jabatan }}</div>
                                    <div class="progress-value">{{ $value->total }} ({{ $persentase }}%)</div>
                                </div>
                                <div class="progress-bar gray">
                                    <div class="progress-indicator indicator-jabatan-aktif"
                                        style="width: {{ $persentase }}%;">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>


        </div>

</div>

<!-- row end -->

@endsection

@section('footerjs')

<!--Jquery Sparkline js-->
<script src="{{URL::asset('assets/plugins/vendors/jquery.sparkline.min.js')}}"></script>

<!-- Chart Circle js-->
<script src="{{URL::asset('assets/plugins/vendors/circle-progress.min.js')}}"></script>

<!--Time Counter js-->
<script src="{{URL::asset('assets/plugins/counters/jquery.missofis-countdown.js')}}"></script>
<script src="{{URL::asset('assets/plugins/counters/counter.js')}}"></script>

<!--Morris  Charts js-->
<script src="{{URL::asset('assets/plugins/morris/raphael-min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/morris/morris.js')}}"></script>

<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>
    // Format tanggal ke "DD MMMM YYYY" dalam bahasa Indonesia
    const today = new Date();
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    const formattedDate = today.toLocaleDateString('id-ID', options);

    // Set tanggal ke elemen span
    document.querySelector('.date-badge span').textContent = formattedDate;
  </script>
<script>

var series;
var root = am5.Root.new("chartdiv");
    am5.ready(function() {

    root._logo.dispose();
    root.setThemes([
      am5themes_Animated.new(root)
    ]);

    root.container.set("layout", root.verticalLayout);

    var chartContainer = root.container.children.push(am5.Container.new(root, {
      layout: root.horizontalLayout,
      width: am5.p100,
      height: am5.p100
    }));

    var chart = chartContainer.children.push(
      am5percent.PieChart.new(root, {
        endAngle: 270,
        innerRadius: am5.percent(80)
      })
    );

    series = chart.series.push(
      am5percent.PieSeries.new(root, {
        valueField: "value",
        endAngle: 270,
        alignLabels: false
      })
    );



    series.labels.template.set("visible", false);
    series.ticks.template.set("visible", false);

    // Label persentase besar


    series.children.push(am5.Label.new(root, {
    text: "Kehadiran",
    fontSize: 14,
    fill: am5.color(0x6b7280), // abu gelap (seperti Tailwind slate-500)
    centerX: am5.percent(50),
    centerY: am5.percent(-50), // sedikit di bawah tengah
    populateText: true
    }));

    series.slices.template.setAll({
      cornerRadius: 8
    })

    series.states.create("hidden", {
      endAngle: -90
    });

    series.labels.template.setAll({
      textType: "circular"
    });


    series.events.on("datavalidated", function() {
        series.slices.each((slice, i) => {
            const warna = i === 0 ? 0x10b981 : 0xe0e0e0; // hijau & abu
            slice.set("fill", am5.color(warna));
            slice.set("stroke", am5.color(warna));
        });
    });



    series.appear(1000, 100);

    });

    function getTanggalKehadiranSekarang () {
        $.ajax({
            type:"POST",
            url: "{{route('hris.mdabsenhadir.ajax_getTanggalKehadiranSekarang')}}",
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: 'json',
            success: function(res){
                $('#tanggal_kehadiran_sekarang').html(res);
            }
        });

    }

    /*---- morrisBar9----*/
    function getKehadiranValue () {
        $.ajax({
            type:"POST",
            url: "{{route('hris.mdabsenhadir.ajax_getdashkehadiran')}}",
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: 'json',
            success: function(data){
                const data_factory = data.data_factory;
                const res = data.data;
                var primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary').trim();

                series.children.push(am5.Label.new(root, {
                    text: res[0]['persentase_kehadiran'] + '%', // ubah sesuai nilai dinamis jika perlu
                    fontSize: 32,
                    fontWeight: "bold",
                    fill: am5.color(0x10b981), // warna hijau
                    centerX: am5.percent(50),
                    centerY: am5.percent(45), // sedikit naik dari tengah
                    populateText: true
                    }
                ));

                series.data.setAll([{
                    category: "Hadir",
                    value: res[0]['jumlah_karyawan_masuk_number'],
                    color: am5.color(0x81c784)
                    }, {
                    category: "Tidak Hadir",
                    value: res[0]['total_karyawan_number'] - res[0]['jumlah_karyawan_masuk_number'],
                    color: am5.color(0xe0e0e0)
                }]);

                $('#jumlah_karyawan_masuk').html(res[0]['jumlah_karyawan_masuk']);
                $('#jumlah_karyawan_tidak_masuk').html(res[0]['total_karyawan_number'] - res[0]['jumlah_karyawan_masuk_number']);
                $('#total_karyawan_aktif').html(res[0]['total_karyawan_aktif']);

                $('#absen_tl_hari_kemarin').html(res[0]['absen_tl_hari_kemarin']);
                $('#absen_m_hari_ini').html(res[0]['absen_m_hari_ini']);
                $('#absen_m_weekly').html(res[0]['absen_m_weekly']);

                // GUNAJAYA SANTOSA
                $('#total_karyawan_aktif_gs').html(data_factory.GS ? (data_factory.GS.non_staff + data_factory.GS.staff) : 0);

                $('#total_karyawan_aktif_nagd').html(data_factory.NAGD ? (data_factory.NAGD.non_staff + data_factory.NAGD.staff) : 0);
                $('#jumlah_staff_nagd').html(data_factory.NAGD ?  data_factory.NAGD.staff : 0);
                $('#jumlah_nonstaff_nagd').html(data_factory.NAGD ? data_factory.NAGD.non_staff : 0);

                $('#total_karyawan_aktif_nak').html(data_factory.NAK ? (data_factory.NAK.non_staff + data_factory.NAK.staff) : 0);
                $('#jumlah_staff_nak').html(data_factory.NAK ?  data_factory.NAK.staff : 0);
                $('#jumlah_nonstaff_nak').html(data_factory.NAK ? data_factory.NAK.non_staff : 0);

                $('#total_karyawan_aktif_sa').html(data_factory.SA ? (data_factory.SA.non_staff + data_factory.SA.staff) : 0);
                $('#jumlah_staff_sa').html(data_factory.SA ?  data_factory.SA.staff : 0);
                $('#jumlah_nonstaff_sa').html(data_factory.SA ? data_factory.SA.non_staff : 0);

                var jumlahStaffGS = parseInt(data_factory?.GS?.staff) || 0;
                var jumlahNonStaffGS = parseInt(data_factory?.GS?.non_staff) || 0;
                var totalKaryawanGS = jumlahStaffGS + jumlahNonStaffGS;

                if (totalKaryawanGS > 0) {
                    var staffPercentage = (jumlahStaffGS / totalKaryawanGS) * 100;
                    var nonStaffPercentage = (jumlahNonStaffGS / totalKaryawanGS) * 100;

                    $('#jumlah_staff_gs').html(jumlahStaffGS + ' (' + staffPercentage.toFixed(1) + '%)');
                    $('#jumlah_nonstaff_gs').html(jumlahNonStaffGS + ' (' + nonStaffPercentage.toFixed(1) + '%)');
                    $('.progress-indicator.staff-gs').css('width', staffPercentage + '%');
                    $('.progress-indicator.non-staff-gs').css('width', nonStaffPercentage + '%');
                } else {
                    var staffPercentage = (jumlahStaffGS / totalKaryawanGS) * 100;
                    var nonStaffPercentage = (jumlahNonStaffGS / totalKaryawanGS) * 100;

                    $('#jumlah_staff_gs').html(jumlahStaffGS + ' (0%)');
                    $('#jumlah_nonstaff_gs').html(jumlahNonStaffGS + ' (0%)');
                    // Jika total = 0, atur jadi 0% semua
                    $('.progress-indicator.staff-gs').css('width', '0%');
                    $('.progress-indicator.non-staff-gs').css('width', '0%');
                }


                var jumlahStaffNAGD = parseInt(data_factory?.NAGD?.staff) || 0;
                var jumlahNonStaffNAGD = parseInt(data_factory?.NAGD?.non_staff) || 0;
                var totalKaryawanNAGD = jumlahStaffNAGD + jumlahNonStaffNAGD;

                if (totalKaryawanNAGD > 0) {
                    var staffPercentage = (jumlahStaffNAGD / totalKaryawanNAGD) * 100;
                    var nonStaffPercentage = (jumlahNonStaffNAGD / totalKaryawanNAGD) * 100;

                    $('#jumlah_staff_nagd').html(jumlahStaffNAGD + ' (' + staffPercentage.toFixed(1) + '%)');
                    $('#jumlah_nonstaff_nagd').html(jumlahNonStaffNAGD + ' (' + nonStaffPercentage.toFixed(1) + '%)');
                    $('.progress-indicator.staff-nagd').css('width', staffPercentage + '%');
                    $('.progress-indicator.non-staff-nagd').css('width', nonStaffPercentage + '%');
                } else {
                    var staffPercentage = (jumlahStaffNAGD / totalKaryawanNAGD) * 100;
                    var nonStaffPercentage = (jumlahNonStaffNAGD / totalKaryawanNAGD) * 100;

                    $('#jumlah_staff_nagd').html(jumlahStaffNAGD + ' (0%)');
                    $('#jumlah_nonstaff_nagd').html(jumlahNonStaffNAGD + ' (0%)');
                    // Jika total = 0, atur jadi 0% semua
                    $('.progress-indicator.staff-nagd').css('width', '0%');
                    $('.progress-indicator.non-staff-nagd').css('width', '0%');
                }


                var jumlahStaffNAK = parseInt(data_factory?.NAK?.staff) || 0;
                var jumlahNonStaffNAK = parseInt(data_factory?.NAK?.non_staff) || 0;
                var totalKaryawanNAK = jumlahStaffNAK + jumlahNonStaffNAK;

                if (totalKaryawanNAK > 0) {
                    var staffPercentage = (jumlahStaffNAK / totalKaryawanNAK) * 100;
                    var nonStaffPercentage = (jumlahNonStaffNAK / totalKaryawanNAK) * 100;

                    $('#jumlah_staff_nak').html(jumlahStaffNAK + ' (' + staffPercentage.toFixed(1) + '%)');
                    $('#jumlah_nonstaff_nak').html(jumlahNonStaffNAK + ' (' + nonStaffPercentage.toFixed(1) + '%)');
                    $('.progress-indicator.staff-nak').css('width', staffPercentage + '%');
                    $('.progress-indicator.non-staff-nak').css('width', nonStaffPercentage + '%');
                } else {
                    var staffPercentage = (jumlahStaffNAK / totalKaryawanNAK) * 100;
                    var nonStaffPercentage = (jumlahNonStaffNAK / totalKaryawanNAK) * 100;

                    $('#jumlah_staff_nak').html(jumlahStaffNAK + ' (0%)');
                    $('#jumlah_nonstaff_nak').html(jumlahNonStaffNAK + ' (0%)');
                    // Jika total = 0, atur jadi 0% semua
                    $('.progress-indicator.staff-nak').css('width', '0%');
                    $('.progress-indicator.non-staff-nak').css('width', '0%');
                }

                var jumlahStaffSA = parseInt(data_factory?.SA?.staff) || 0;
                var jumlahNonStaffSA = parseInt(data_factory?.SA?.non_staff) || 0;
                var totalKaryawanSA = jumlahStaffSA + jumlahNonStaffSA;

                if (totalKaryawanSA > 0) {
                    var staffPercentage = (jumlahStaffSA / totalKaryawanSA) * 100;
                    var nonStaffPercentage = (jumlahNonStaffSA / totalKaryawanSA) * 100;

                    $('#jumlah_staff_sa').html(jumlahStaffSA + ' (' + staffPercentage.toFixed(1) + '%)');
                    $('#jumlah_nonstaff_sa').html(jumlahNonStaffSA + ' (' + nonStaffPercentage.toFixed(1) + '%)');
                    $('.progress-indicator.staff-sa').css('width', staffPercentage + '%');
                    $('.progress-indicator.non-staff-sa').css('width', nonStaffPercentage + '%');
                } else {
                    var staffPercentage = (jumlahStaffSA / totalKaryawanSA) * 100;
                    var nonStaffPercentage = (jumlahNonStaffSA / totalKaryawanSA) * 100;

                    $('#jumlah_staff_sa').html(jumlahStaffSA + ' (0%)');
                    $('#jumlah_nonstaff_sa').html(jumlahNonStaffSA + ' (0%)');
                    // Jika total = 0, atur jadi 0% semua
                    $('.progress-indicator.staff-sa').css('width', '0%');
                    $('.progress-indicator.non-staff-sa').css('width', '0%');
                }


                // Ambil nilai dari response
                var jumlahStaff = parseInt(res[0]['jumlah_staff_number']) || 0;
                var jumlahNonStaff = parseInt(res[0]['jumlah_nonstaff_number']) || 0;
                var totalKaryawan = jumlahStaff + jumlahNonStaff;

                if (totalKaryawan > 0) {
                    var staffPercentage = (jumlahStaff / totalKaryawan) * 100;
                    var nonStaffPercentage = (jumlahNonStaff / totalKaryawan) * 100;

                    $('.progress-indicator.staff').css('width', staffPercentage + '%');
                    $('.progress-indicator.non-staff').css('width', nonStaffPercentage + '%');

                    $('#jumlah_staff').html(jumlahStaff + ' (' + staffPercentage.toFixed(1) + '%)');
                    $('#jumlah_nonstaff').html(jumlahNonStaff + ' (' + nonStaffPercentage.toFixed(1) + '%)');
                } else {
                    // Jika total = 0, atur jadi 0% semua
                    $('.progress-indicator.staff').css('width', '0%');
                    $('.progress-indicator.non-staff').css('width', '0%');
                }


            },
            error: function(res){

            }
        });
    }


    getKehadiranValue();
    getTanggalKehadiranSekarang();


    setInterval(function() {
        getKehadiranValue();
        getTanggalKehadiranSekarang();

    }, 60000);



</script>

@endsection





