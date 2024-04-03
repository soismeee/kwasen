        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
                <img src="{{ asset('assets/img/logo_dinsos.png') }}" alt="" width="20%">
                <div class="sidebar-brand-text mx-3">Bansos Yapi</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="/">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Utama</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="{{ route('periode.index') }}">
                    <i class="fas fa-fw fa-layer-group"></i>
                    <span>Periode</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('kriteria.index') }}">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Kriteria</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('pengajuan') }}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Pengajuan PKH</span>
                </a>
            </li>      
            
            <li class="nav-item">
                <a class="nav-link" href="{{ route('cek') }}">
                    <i class="fas fa-fw fa-plus"></i>
                    <span>Cek status penerima</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('pb') }}">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Penerima Bantuan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('lap') }}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Laporan</span>
                </a>
            </li>

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->