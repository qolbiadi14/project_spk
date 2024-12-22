<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Dashboard 3</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url('adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('adminlte/dist/css/adminlte.min.css') ?>">

</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->

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

        <!-- Main Sidebar Container -->
        <?= $this->include('sidebar') ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Data Alternatif</h3>
                                <div class="card-tools">
                                    <button type="submit" form="nilaiForm" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <form id="nilaiForm" action="<?= base_url('perangkingan-alternatif') ?>" method="post">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Alternatif</th>
                                                <th>Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($alternatif as $index => $alt): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= $alt['nama_alternatif'] ?></td>
                                                    <td>
                                                        <?php foreach ($kriteria as $k): ?>
                                                            <label><?= $k['nama_kriteria'] ?></label>
                                                            <?php 
                                                                $nilaiItem = array_filter($nilai ?? [], function($n) use ($alt, $k) {
                                                                    return $n['id_alternatif'] == $alt['id_alternatif'] && $n['id_kriteria'] == $k['id_kriteria'];
                                                                });
                                                                $nilaiItem = reset($nilaiItem);
                                                            ?>
                                                            <input type="text" name="criteria_<?= $k['id_kriteria'] ?>_<?= $alt['id_alternatif'] ?>" value="<?= $nilaiItem ? $nilaiItem['value'] : '' ?>" required>
                                                        <?php endforeach; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <?php if (isset($normalisasi)): ?>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Normalisasi Alternatif</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nama Alternatif</th>
                                            <?php foreach ($kriteria as $k): ?>
                                                <th><?= $k['nama_kriteria'] ?> (<?= $k['tipe_kriteria'] ?>)</th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($alternatif as $alt): ?>
                                            <tr>
                                                <td><?= $alt['nama_alternatif'] ?></td>
                                                <?php foreach ($kriteria as $k): ?>
                                                    <td><?= $normalisasi[$alt['id_alternatif']][$k['id_kriteria']] ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Skor Akhir</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nama Alternatif</th>
                                            <?php foreach ($kriteria as $k): ?>
                                                <th><?= $k['nama_kriteria'] ?></th>
                                            <?php endforeach; ?>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($scores as $id_alternatif => $values): ?>
                                            <tr>
                                                <td><?= $alternatif[array_search($id_alternatif, array_column($alternatif, 'id_alternatif'))]['nama_alternatif'] ?></td>
                                                <?php foreach ($values as $id_kriteria => $score): ?>
                                                    <?php if ($id_kriteria !== 'total'): ?>
                                                        <td><?= $score ?></td>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                <td><?= $values['total'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Perangkingan Alternatif</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Rank</th>
                                            <th>Nama Alternatif</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ranking as $index => $rank): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= $rank['nama_alternatif'] ?></td>
                                                <td><?= $rank['total'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

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

    <!-- jQuery -->
    <script src="<?= base_url('adminlte/plugins/jquery/jquery.min.js') ?>"></script>
    <!-- Bootstrap -->
    <script src="<?= base_url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- AdminLTE -->
    <script src="<?= base_url('adminlte/dist/js/adminlte.js') ?>"></script>

    <!-- OPTIONAL SCRIPTS -->
    <script src="<?= base_url('adminlte/plugins/chart.js/Chart.min.js') ?>"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?= base_url('adminlte/dist/js/demo.js') ?>"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="<?= base_url('adminlte/dist/js/pages/dashboard3.js') ?>"></script>
</body>

</html>