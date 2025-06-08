<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
                {{-- <img src="{{ asset('img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand"
                    height="20" /> --}}
                <h1 class="fs-2 text-white">Penggajian</h1>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                @if (can_access(['admin.dashboard']))
                    <li class="nav-item {{ is_active_route(['admin.dashboard']) }}">
                        <a href="{{ route('admin.dashboard') }}" class="collapsed" aria-expanded="false">
                            <i class="fas fa-th-large"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                @endif
                @if (can_access(['user.dashboard']))
                    <li class="nav-item {{ is_active_route(['user.dashboard']) }}">
                        <a href="{{ route('user.dashboard') }}" class="collapsed" aria-expanded="false">
                            <i class="fas fa-th-large"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                @endif
                @if (can_access(['admin.attendances.index']))
                    <li class="nav-item {{ is_active_route(['admin.attendances.index']) }}">
                        <a href="{{ route('admin.attendances.index') }}">
                            <i class="fas fa-laptop"></i>
                            <p>Absensi</p>
                        </a>
                    </li>
                @endif
                @if (can_access(['user.attendances.index']))
                    <li class="nav-item {{ is_active_route(['user.attendances.index']) }}">
                        <a href="{{ route('user.attendances.index') }}">
                            <i class="fas fa-laptop"></i>
                            <p>Absensi</p>
                        </a>
                    </li>
                @endif
                @if (can_access(['admin.analytics.attendance', 'admin.analytics.salary']))
                    <li
                        class="nav-item {{ is_active_route(['admin.analytics.attendance', 'admin.analytics.salary']) }}">
                        <a data-bs-toggle="collapse" href="#submenu">
                            <i class="fas fa-chart-line"></i>
                            <p>Insight & Analitik</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{ is_active_route(['admin.analytics.attendance', 'admin.analytics.salary']) }}"
                            id="submenu">
                            <ul class="nav nav-collapse">
                                <li class="nav-item  {{ is_active_route(['admin.analytics.attendance']) }}">
                                    <a href="{{ route('admin.analytics.attendance') }}">
                                        <span class="sub-item">Absensi</span>
                                    </a>
                                </li>
                                <li class="nav-item  {{ is_active_route(['admin.analytics.salary']) }}">
                                    <a href="{{ route('admin.analytics.salary') }}">
                                        <span class="sub-item">Gaji dan Pinjaman</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if (can_access(['admin.reports.attendance', 'admin.reports.salary']))
                    <li class="nav-item {{ is_active_route(['admin.reports.attendance', 'admin.reports.salary']) }}">
                        <a data-bs-toggle="collapse" href="#report">
                            <i class="fas fa-file-download"></i>
                            <p>Laporan</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse {{ is_active_route(['admin.reports.attendance', 'admin.reports.salary']) }}"
                            id="report">
                            <ul class="nav nav-collapse">
                                <li class="nav-item {{ is_active_route(['admin.reports.attendance']) }}">
                                    <a href="{{ route('admin.reports.attendance') }}">
                                        <span class="sub-item">Laporan Absensi</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ is_active_route(['admin.reports.salary']) }}">
                                    <a href="{{ route('admin.reports.salary') }}">
                                        <span class="sub-item">Laporan Gaji</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if (can_access(['admin.attendances.index', 'admin.cash_advances.index', 'admin.payrolls.index', 'user.salary','user.cash_advances.index']))
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Keuangan</h4>
                    </li>
                    @if (can_access(['user.salary']))
                        <li class="nav-item {{ is_active_route(['user.salary']) }}">
                            <a href="{{ route('user.salary') }}">
                                <i class="fas fa-wallet"></i>
                                <p>Gaji Saya</p>
                            </a>
                        </li>
                    @endif
                    @if (can_access(['user.cash_advances.index']))
                        <li
                            class="nav-item {{ is_active_route(['user.cash_advances.create', 'user.cash_advances.index', 'user.cash_advances.show', 'user.cash_advances.create']) }}">
                            <a href="{{ route('user.cash_advances.index') }}">
                                <i class="fas fa-money-bill"></i>
                                <p>Ajukan Pinjaman</p>
                            </a>
                        </li>
                    @endif
                    @if (can_access(['admin.cash_advances.index']))
                        <li
                            class="nav-item {{ is_active_route(['admin.cash_advances.index', 'admin.cash_advances.create', 'admin.cash_advances.edit', 'admin.cash_advances.show']) }}">
                            <a href="{{ route('admin.cash_advances.index') }}">
                                <i class="fas fa-money-bill"></i>
                                <p>Pinjaman Karyawan</p>
                            </a>
                        </li>
                    @endif
                    @if (can_access(['admin.payrolls.index']))
                        <li
                            class="nav-item {{ is_active_route(['admin.payrolls.index', 'admin.payrolls.create', 'admin.payrolls.detail']) }}">
                            <a href="{{ route('admin.payrolls.index') }}">
                                <i class="fas fa-dollar-sign"></i>
                                <p>Daftar Gaji</p>
                            </a>
                        </li>
                    @endif
                @endif
                @if (can_access(['admin.users.index', 'admin.job_category.index']))
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Kelola Karyawan</h4>
                    </li>
                    @if (can_access(['admin.users.index']))
                        <li
                            class="nav-item {{ is_active_route(['admin.users.index', 'admin.users.create', 'admin.users.edit']) }}">
                            <a href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users"></i>
                                <p>Daftar User</p>
                            </a>
                        </li>
                    @endif
                    @if (can_access(['admin.job_category.index']))
                        <li
                            class="nav-item {{ is_active_route(['admin.job_category.index', 'admin.job_category.create', 'admin.job_category.edit']) }}">
                            <a href="{{ route('admin.job_category.index', ['job_category']) }}">
                                <i class="fas fa-folder-open"></i>
                                <p>Jabatan</p>
                            </a>
                        </li>
                    @endif
                @endif

            </ul>
        </div>
    </div>
</div>
