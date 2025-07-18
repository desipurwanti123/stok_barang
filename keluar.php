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
    <title>Barang Keluar - App Stok Barang</title>
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
                    <h1 class="mt-4">Barang Keluar</h1>
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#myModal">
                                    Tambah Barang Keluar
                                </button>
                                <div class="row mt-4">
                                    <div class="col">
                                        <form action="" method="post" class="form-inline">
                                            <label for="" class="mb-2">Tanggal Mulai</label>
                                            <input type="date" name="tgl_mulai" class="form-control">
                                            <label for="" class="mt-2">Tanggal Akhir</label>
                                            <input type="date" name="tgl_selesai" class="form-control mt-3">
                                            <button type="submit" name="filter_tgl" class="btn btn-info mt-3">Filter</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Penerima</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if(isset($_POST['filter_tgl'])){
                                            $mulai = $_POST['tgl_mulai'];
                                            $selesai = $_POST['tgl_selesai'];
                                            
                                            if($mulai!=null || $selesai!=null){
                                                $ambilSemuaDataStok = mysqli_query($conn, "SELECT * FROM keluar k, stok s WHERE s.id_barang = k.id_barang AND tanggal BETWEEN '$mulai' AND DATE_ADD('$selesai',interval 1 day) ORDER BY id_keluar DESC");
                                            } else{
                                                $ambilSemuaDataStok = mysqli_query($conn, "SELECT * FROM keluar k, stok s WHERE s.id_barang = k.id_barang ORDER BY id_keluar DESC");
                                            }
                                        } else{
                                            $ambilSemuaDataStok = mysqli_query($conn, "SELECT * FROM keluar k, stok s WHERE s.id_barang = k.id_barang ORDER BY id_keluar DESC");
                                        }

                                        $ambilSemuaDataStok = mysqli_query($conn, "SELECT * FROM keluar k, stok s WHERE s.id_barang = k.id_barang");
                                        while ($data = mysqli_fetch_array($ambilSemuaDataStok)) {
                                            $idk = $data['id_keluar'];
                                            $idb = $data['id_barang'];
                                            $tanggal = $data['tanggal'];
                                            $nama_barang = $data['nama_barang'];
                                            $qty = $data['qty'];
                                            $penerima = $data['penerima'];
                                            ?>
                                            <tr>
                                                <td><?= $tanggal ?></td>
                                                <td><?= $nama_barang ?></td>
                                                <td><?= $qty ?></td>
                                                <td><?= $penerima ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#edit<?= $idk ?>">
                                                        Edit
                                                    </button>
                                                    <input type="hidden" name="idBrgYgDihps" value="<?= $idb ?>">
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#delete<?= $idk ?>">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                            <!-- The Edit Modal -->
                                            <div class="modal fade" id="edit<?= $idk ?>">
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
                                                                <input type="hidden" name="idk" value="<?= $idk ?>">
                                                                <div>
                                                                    <label class="form-label">Penerima</label>
                                                                    <input type="text" name="penerima"
                                                                        value="<?= $penerima ?>" class="form-control"
                                                                        required>
                                                                </div>
                                                                <div>
                                                                    <label class="form-label">Jumlah</label>
                                                                    <input type="number" name="qty" value="<?= $qty ?>"
                                                                        class="form-control" required>
                                                                </div>
                                                            </div>

                                                            <!-- Modal footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" name="updatebarangkeluar"
                                                                    class="btn btn-primary">Simpan</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                            <!-- The Delete Modal -->
                                            <div class="modal fade" id="delete<?= $idk ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hapus Barang</h4>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <form action="" method="post">
                                                            <div class="modal-body">
                                                                Apakah kamu yakin ingin menghapus <?= $nama_barang ?>
                                                                <input type="hidden" name="idb" value="<?= $idb ?>">
                                                                <input type="hidden" name="qty" value="<?= $qty ?>">
                                                                <input type="hidden" name="idk" value="<?= $idk ?>">
                                                            </div>

                                                            <!-- Modal footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" name="hapusbarangkeluar"
                                                                    class="btn btn-primary">Hapus</button>
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
                <h4 class="modal-title">Tambah Barang Keluar</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <form action="" method="post">
                <div class="modal-body">
                    <div>
                        <label class="form-label">Nama Barang</label>
                        <Select name="barang_keluar" class="form-control">
                            <?php
                            $ambilSemuaData = mysqli_query($conn, "SELECT * FROM stok");

                            while ($fetchArray = mysqli_fetch_array($ambilSemuaData)) {
                                $nama_barang_masuk = $fetchArray['nama_barang'];
                                $idBarang = $fetchArray['id_barang'];
                                ?>

                                <option value="<?= $idBarang; ?>"><?= $nama_barang_masuk ?></option>
                            <?php } ?>
                        </Select>
                    </div>
                    <div>
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="qty" placeholder="Jumlah" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Penerima</label>
                        <input type="text" name="penerima" placeholder="Penerima" class="form-control" required>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" name="addbarangkeluar" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>

</html>