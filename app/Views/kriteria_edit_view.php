<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Kriteria</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url('adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('adminlte/dist/css/adminlte.min.css') ?>">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <?= $this->include('sidebar') ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Edit Kriteria</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Form Edit Kriteria</h3>
                                </div>
                                <!-- form start -->
                                <form action="<?= base_url('kriteria/update/' . $kriteria['id_kriteria']) ?>" method="post">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="nama_kriteria">Nama Kriteria</label>
                                            <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria" value="<?= $kriteria['nama_kriteria'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tipe_kriteria">Tipe Kriteria</label>
                                            <select class="form-control" id="tipe_kriteria" name="tipe_kriteria" required>
                                                <option value="benefit" <?= $kriteria['tipe_kriteria'] == 'benefit' ? 'selected' : '' ?>>Benefit</option>
                                                <option value="cost" <?= $kriteria['tipe_kriteria'] == 'cost' ? 'selected' : '' ?>>Cost</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="bobot_kriteria">Bobot Kriteria</label>
                                            <input type="number" step="0.01" class="form-control" id="bobot_kriteria" name="bobot_kriteria" value="<?= $kriteria['bobot_kriteria'] ?>" required>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 3.2.0
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->
    <!-- REQUIRED SCRIPTS -->
    <script src="<?= base_url('adminlte/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('adminlte/dist/js/adminlte.js') ?>"></script>
</body>

</html>