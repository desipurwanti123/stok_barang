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
    $img = '<img class="card-img-top" src="images/' . $gambar . '" alt="Card image" style="width:100%">';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Menampilkan Barang</title>
</head>

<body>
    <div class="container mt-3">
        <h2>Detail Barang:</h2>
        <div class="card" style="width:400px">
            <?= $img ?>
            <div class="card-body">
                <h4 class="card-title"><?= $nama_barang ?></h4>
                <p class="card-text"><?= $deskripsi ?></p>
                <p class="card-text"><?= $stok ?></p>
                <!-- <a href="#" class="btn btn-primary">See Profile</a> -->
            </div>
        </div>
    </div>
</body>

</html>