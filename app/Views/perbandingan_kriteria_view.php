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
                                <h3 class="card-title">Perbandingan Kriteria</h3>
                            </div>
                            <div class="card-body">
                                <?php if (session()->getFlashdata('success')) : ?>
                                    <div class="alert alert-success" role="alert" id="success-alert">
                                        <?= session()->getFlashdata('success') ?>
                                    </div>
                                <?php endif; ?>
                                <form action="/perbandingan-kriteria/add" method="post">
                                    <?php foreach ($kriteria as $kiri) : ?>
                                        <?php foreach ($kriteria as $kanan) : ?>
                                            <?php if ($kiri['id_kriteria'] < $kanan['id_kriteria']) : ?>
                                                <table class="table table-striped">
                                                    <tr class="text-center">
                                                        <th rowspan="2"><?= $kiri['nama_kriteria'] ?></th>
                                                        <th>9</th>
                                                        <th>8</th>
                                                        <th>7</th>
                                                        <th>6</th>
                                                        <th>5</th>
                                                        <th>4</th>
                                                        <th>3</th>
                                                        <th>2</th>
                                                        <th>1</th>
                                                        <th>2</th>
                                                        <th>3</th>
                                                        <th>4</th>
                                                        <th>5</th>
                                                        <th>6</th>
                                                        <th>7</th>
                                                        <th>8</th>
                                                        <th>9</th>
                                                        <th rowspan="2"><?= $kanan['nama_kriteria'] ?></th>
                                                    </tr>
                                                    <tr>
                                                        <?php 
                                                        $selectedValue = null;
                                                        foreach ($bobot_kriteria as $bobot) {
                                                            if ($bobot['id_kriteria_kiri'] == $kiri['id_kriteria'] && $bobot['id_kriteria_kanan'] == $kanan['id_kriteria']) {
                                                                $selectedValue = $bobot['is_reverse'] ? "1/{$bobot['value']}" : "{$bobot['value']}/1";
                                                                break;
                                                            }
                                                        }
                                                        ?>
                                                        <?php for ($i = 9; $i >= 1; $i--) : ?>
                                                            <th>
                                                                <label class="radio">
                                                                    <input class="radio" value="<?= $i ?>/1" type="radio" name="<?= $kiri['id_kriteria'] . '-' . $kanan['id_kriteria'] ?>" <?= $selectedValue == "$i/1" ? 'checked' : '' ?>>
                                                                    <span></span>
                                                                </label>
                                                            </th>
                                                        <?php endfor; ?>
                                                        <?php for ($i = 2; $i <= 9; $i++) : ?>
                                                            <th>
                                                                <label class="radio">
                                                                    <input class="radio" value="1/<?= $i ?>" type="radio" name="<?= $kiri['id_kriteria'] . '-' . $kanan['id_kriteria'] ?>" <?= $selectedValue == "1/$i" ? 'checked' : '' ?>>
                                                                    <span></span>
                                                                </label>
                                                            </th>
                                                        <?php endfor; ?>
                                                    </tr>
                                                </table>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Matrix Perbandingan Kriteria Berpasangan</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kriteria</th>
                                            <?php foreach ($kriteria as $k) : ?>
                                                <th><?= $k['nama_kriteria'] ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pairwise_matrix['matrix'] as $i => $row) : ?>
                                            <tr>
                                                <th><?= $kriteria[$i]['nama_kriteria'] ?></th>
                                                <?php foreach ($row as $value) : ?>
                                                    <td><?= $value == intval($value) ? intval($value) : number_format($value, 3) ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Jumlah</th>
                                            <?php foreach ($pairwise_matrix['jumlah'] as $value) : ?>
                                                <th><?= $value == intval($value) ? intval($value) : number_format($value, 3) ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Matriks Normalisasi Nilai Kriteria</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kriteria</th>
                                            <?php foreach ($kriteria as $k) : ?>
                                                <th><?= $k['nama_kriteria'] ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($normalized_matrix as $i => $row) : ?>
                                            <tr>
                                                <th><?= $kriteria[$i]['nama_kriteria'] ?></th>
                                                <?php foreach ($row as $value) : ?>
                                                    <td><?= $value == intval($value) ? intval($value) : number_format($value, 3) ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Perhitungan Rasio Konsistensi</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>Consistency Index</td>
                                            <td><?= $consistency_ratio['ci'] == intval($consistency_ratio['ci']) ? intval($consistency_ratio['ci']) : number_format($consistency_ratio['ci'], 3) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Random Consistency Index</td>
                                            <td><?= $consistency_ratio['ri'] == intval($consistency_ratio['ri']) ? intval($consistency_ratio['ri']) : number_format($consistency_ratio['ri'], 3) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Consistency Ratio</td>
                                            <td><?= $consistency_ratio['cr'] == intval($consistency_ratio['cr']) ? intval($consistency_ratio['cr']) : number_format($consistency_ratio['cr'], 3) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p>Konsistensi: <?= $consistency_ratio['is_consistent'] ? 'Konsisten' : 'Tidak Konsisten' ?></p>
                            </div>
                        </div>
                        <!-- /.card -->
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
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $("#success-alert").fadeOut("slow");
            }, 5000);
        });
    </script>
</body>

</html>