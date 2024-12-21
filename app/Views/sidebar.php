<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?= base_url('adminlte/dist/img/AdminLTELogo.png') ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Master Data
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url("kriteria") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Kriteria</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("perbandingan-kriteria") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Perbandingan Kriteria</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("alternatif") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Alternatif</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("perangkingan-alternatif") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Perangkingan Alternatif</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>