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
    <title>Dashboard - App Stok Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        .zoomable{
            width: 100px;
        }
        .zoomable:hover{
            transform: scale(1.5);
            transition: 0.5s ease;
        }
        a{
            text-decoration: none;
            color: black;
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
                    <h1 class="mt-4">Stok Barang</h1>
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#myModal">
                                    Tambah Barang
                                </button>
                                <a href="export.php" class="btn btn-info">Export Data</a>
                            </div>
                            <div class="card-body">
                                <?php 
                                    $ambildatastok = mysqli_query($conn,"SELECT * FROM stok WHERE stok < 1");

                                    while($fetch=mysqli_fetch_array($ambildatastok)){
                                        $barang = $fetch['nama_barang'];
                                    
                                ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    <strong>Perhatian!</strong> Stok barang <?= $barang ?> telah habis
                                </div>
                                <?php } ?>
                                <table id="datatablesSimple" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Gambar</th>
                                            <th>Nama Barang</th>
                                            <th>Deskripsi</th>
                                            <th>Stok</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ambilSemuaDataStok = mysqli_query($conn, "SELECT * FROM stok");
                                        while ($data = mysqli_fetch_array($ambilSemuaDataStok)) {
                                            $i = 1;
                                            $nama_barang = $data['nama_barang'];
                                            $deskripsi = $data['deskripsi'];
                                            $stok = $data['stok'];
                                            $idb = $data['id_barang'];

                                            // cek ada gambar atau tidak
                                            $gambar = $data['image']; // ambil gambar
                                            if($gambar==null){
                                                // jika tidak ada gambar
                                                $img = 'No Photo';
                                            } else{
                                                // jika ada gambar
                                                $img = '<img src="images/'.$gambar.'" class="zoomable">';
                                            }
                                        ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $img ?></td>
                                                <td><a href="detail.php?id=<?= $idb ?>"><?= $nama_barang ?></a></td>
                                                <td><?= $deskripsi ?></td>
                                                <td><?= $stok ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#edit<?= $idb ?>">
                                                        Edit
                                                    </button>
                                                    <input type="hidden" name="idBrgYgDihps" value="<?= $idb ?>">
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#delete<?= $idb ?>">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- The Edit Modal -->
                                            <div class="modal fade" id="edit<?= $idb ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Barang</h4>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <form action="" method="post" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="idb" value="<?= $idb ?>">
                                                                <div>
                                                                    <label class="form-label">Nama Barang</label>
                                                                    <input type="text" name="nama_barang"
                                                                        value="<?= $nama_barang ?>" class="form-control"
                                                                        required>
                                                                </div>
                                                                <div>
                                                                    <label class="form-label">Deskripsi</label>
                                                                    <input type="text" name="deskripsi"
                                                                        value="<?= $deskripsi ?>" class="form-control"
                                                                        required>
                                                                </div>
                                                                <div>
                                                                    <label class="form-label">Gambar</label>
                                                                    <input type="file" name="file" class="form-control">
                                                                </div>
                                                            </div>

                                                            <!-- Modal footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" name="updatebarang"
                                                                    class="btn btn-primary">Simpan</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                            <!-- The Delete Modal -->
                                            <div class="modal fade" id="delete<?= $idb ?>">
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
                                                            </div>

                                                            <!-- Modal footer -->
                                                            <div class="modal-footer">
                                                                <button type="submit" name="hapusbarang"
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