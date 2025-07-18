<?php
require 'function.php';

// dapetin id barang
$id_barang = $_GET['id']; // get id

// get informasi barang berdasarkan database
$get = mysqli_query($conn, "SELECT * FROM stok WHERE id_barang='$id_barang'");
$fetch = mysqli_fetch_assoc($get);

// set variabel
$nama_barang = $fetch['nama_barang'];
$deskripsi = $fetch['deskripsi'];
$stok = $fetch['stok'];
// cek ada gambar atau tidak
$gambar = $fetch['image']; // ambil gambar
if ($gambar == null) {
    // jika tidak ada gambar
    $img = 'No Photo';
} else {
    // jika ada gambar
    $img = '<img src="images/' . $gambar . '" class="zoomable">';
}

// Generate QR 
$urlview = 'http://localhost/stok_barang/view.php?id='.$id_barang;
$qr = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='.$urlview;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Detail - App Stok Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .zoomable {
            width: 100px;
        }

        .zoomable:hover {
            transform: scale(1.5);
            transition: 0.5s ease;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.php">App Stok Barang</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Stok Barang
                        </a>
                        <a class="nav-link" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Barang Masuk
                        </a>
                        <a class="nav-link" href="keluar.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Barang Keluar
                        </a>
                        <a class="nav-link" href="admin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Kelola Admin
                        </a>
                        <a class="nav-link" href="logout.php">
                            Logout
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Detail Barang</h1>
                    <div class="row">
                        <div class="card mb-4 mt-4">
                            <div class="card-header">
                                <h2><?= $nama_barang ?></h2>
                                <?= $img ?>
                                <img src="<?= $qr ?>" alt="">
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">Deskripsi</div>
                                    <div class="col-md-9">: <?= $deskripsi ?></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">Stok</div>
                                    <div class="col-md-9">: <?= $stok ?></div>
                                </div>
                                <table id="barangmasuk" class="table table-bordered mt-3">
                                    <h3>Barang Masuk</h3>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ambildatamasuk = mysqli_query($conn, "SELECT * FROM masuk WHERE id_barang='$id_barang'");
                                        $i = 1;

                                        while ($fetch = mysqli_fetch_array($ambildatamasuk)) {
                                            $tanggal = $fetch['tanggal'];
                                            $keterangan = $fetch['keterangan'];
                                            $qty = $fetch['qty'];
                                            ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $tanggal ?></td>
                                                <td><?= $deskripsi ?></td>
                                                <td><?= $qty ?></td>
                                            </tr>
                                            <?php
                                        } ?>
                                    </tbody>
                                </table>
                                <table id="barangkeluar" class="table table-bordered mt-3">
                                    <h3>Barang Keluar</h3>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Penerima</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ambildatakeluar = mysqli_query($conn,"SELECT * FROM keluar WHERE id_barang='$id_barang'");
                                        $i = 1;

                                        while($fetch=mysqli_fetch_array($ambildatakeluar)){
                                            $tanggal = $fetch['tanggal'];
                                            $penerima = $fetch['penerima'];
                                            $qty = $fetch['qty'];
                                        ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $tanggal ?></td>
                                                <td><?= $deskripsi ?></td>
                                                <td><?= $qty ?></td>
                                            </tr>
                                            <?php
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
<!-- The Add Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div>
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" placeholder="Nama Barang" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Deskripsi</label>
                        <input type="text" name="deskripsi" placeholder="Deskripsi" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" placeholder="stok" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Gambar</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" name="addnewbarang" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>

</html>