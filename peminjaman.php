<?php
require 'function.php';
require 'cek.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Peminjaman Barang - App Stok Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
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
                            <div class="sb-nav-link-icon"><i class="fas fa-stream"></i></div>
                            Stok Barang
                        </a>
                        <a class="nav-link" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-cloud-download-alt"></i></div>
                            Barang Masuk
                        </a>
                        <a class="nav-link" href="keluar.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                            Barang Keluar
                        </a>
                        <a class="nav-link" href="peminjaman.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                            Peminjaman Barang
                        </a>
                        <a class="nav-link" href="admin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-alt"></i></div>
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
                    <h1 class="mt-4">Peminjaman Barang</h1>
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#myModal">
                                    Tambah Data
                                </button>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Peminjam</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($_POST['filter_tgl'])){
                                            $mulai = $_POST['tgl_mulai'];
                                            $selesai = $_POST['tgl_selesai'];
                                            
                                            if($mulai!=null || $selesai!=null){
                                                $ambilSemuaDataStok = mysqli_query($conn, "SELECT * FROM peminjaman p, stok s WHERE s.id_barang = p.id_barang AND tanggal BETWEEN '$mulai' AND DATE_ADD('$selesai',interval 1 day) ORDER BY id_peminjaman DESC");
                                            } else{
                                                $ambilSemuaDataStok = mysqli_query($conn, "SELECT * FROM peminjaman p, stok s WHERE s.id_barang = p.id_barang ORDER BY id_peminjaman DESC");
                                            }
                                        } else{
                                            $ambilSemuaDataStok = mysqli_query($conn, "SELECT * FROM peminjaman p, stok s WHERE s.id_barang = p.id_barang ORDER BY id_peminjaman DESC");
                                        }

                                        $ambilSemuaDataStok = mysqli_query($conn, "SELECT * FROM peminjaman p, stok s WHERE s.id_barang = p.id_barang ORDER BY id_peminjaman DESC");
                                        while ($data = mysqli_fetch_array($ambilSemuaDataStok)) {
                                            $idp = $data['id_peminjaman'];
                                            $idb = $data['id_barang'];
                                            $tanggal = $data['tanggal_pinjam'];
                                            $nama_barang = $data['nama_barang'];
                                            $qty = $data['qty'];
                                            $peminjam = $data['peminjam'];
                                            $status = $data['status'];
                                            ?>
                                            <tr>
                                                <td><?= $tanggal ?></td>
                                                <td><?= $nama_barang ?></td>
                                                <td><?= $qty ?></td>
                                                <td><?= $peminjam ?></td>
                                                <td><?= $status ?></td>
                                                <td>
                                                    <?php 
                                                        // cek status
                                                        if($status=='Dipinjam'){
                                                            echo '<button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                                data-bs-target="#edit'.$idp.'">
                                                                Selesai
                                                                </button>';
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <!-- The Edit Modal -->
                                            <div class="modal fade" id="edit<?= $idp ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Barang</h4>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <form action="" method="post">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="idb" value="<?= $idb ?>">
                                                                <input type="hidden" name="id_peminjaman" value="<?= $idp ?>">
                                                                Apakah barang ini sudah selesai dipinjam?
                                                            </div>

                                                            <!-- Modal footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" name="barangkembali"
                                                                    class="btn btn-primary">Iya</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
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
<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Data Peminjaman</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <form action="" method="post">
                <div class="modal-body">
                    <div>
                        <label class="form-label">Nama Barang</label>
                        <Select name="barang" class="form-control">
                            <?php
                            $ambilSemuaData = mysqli_query($conn, "SELECT * FROM stok");

                            while ($fetchArray = mysqli_fetch_array($ambilSemuaData)) {
                                $nama_barang = $fetchArray['nama_barang'];
                                $idBarang = $fetchArray['id_barang'];
                                ?>

                                <option value="<?= $idBarang; ?>"><?= $nama_barang ?></option>
                            <?php } ?>
                        </Select>
                    </div>
                    <div>
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="qty" placeholder="Jumlah" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Peminjam</label>
                        <input type="text" name="peminjam" placeholder="Peminjam" class="form-control" required>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" name="pinjam" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>

</html>