<?php
session_start();

// Membuat koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "stok_barang");

// Menambah barang baru
if (isset($_POST['addnewbarang'])) {
    $nama_barang = $_POST['nama_barang'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];

    // gambar
    $allowed_extention = array('png', 'jpg','jpeg');
    $nama = $_FILES['file']['name']; //ngambil nama gambar
    $dot = explode('.', $nama);
    $ekstensi = strtolower(end($dot)); // ngambil ekstensi
    $ukuran = $_FILES['file']['size']; //ngambil sizenya
    $file_tmp = $_FILES['file']['tmp_name']; //ngambil lokasi file

    // penamaan file -> enkripsi
    $image = md5(uniqid($nama, true) . time()) . '.' . $ekstensi; //menggambungkan nama file yang dienkripsi dengan ekstensinya

    // proses upload gambar
    if (in_array($ekstensi, $allowed_extention) === true) {
        // validasi ukuran file
        if ($ukuran < 15000000) {
            move_uploaded_file($file_tmp, 'images/' . $image);

            $addToTable = mysqli_query($conn, "INSERT INTO stok (nama_barang, deskripsi, stok, image) VALUES ('$nama_barang', '$deskripsi', '$stok', '$image')");
            if ($addToTable) {
                header("location:index.php");
            } else {
                echo 'Gagal';
                header("location:index.php");
            }
        } else {
            // kalau file lebih dari 1.5 mb
            echo '
            <script>
                alert("Ukuran terlalu besar");
                window.location.href="keluar.php";
            </script>';
        }
    } else {
        // kalau filenya tidak png/jpg/jpeg
        echo '
        <script>
            alert("file harus png/jpg/jpeg");
            window.location.href="keluar.php";
        </script>';
    }
}

// Menambah barang masuk
if (isset($_POST['addbarangmasuk'])) {
    $barang_masuk = $_POST['barang_masuk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekStokSekarang = mysqli_query($conn, "SELECT * FROM stok WHERE id_barang = '$barang_masuk'");
    $ambilData = mysqli_fetch_array($cekStokSekarang);

    $stokSekarang = $ambilData['stok'];
    $stokYangSudahDitambah = $stokSekarang + $qty;

    $addToMasuk = mysqli_query($conn, "INSERT INTO masuk (id_barang, keterangan, qty) VALUES ('$barang_masuk', '$penerima', '$qty')");
    $updateStokMasuk = mysqli_query($conn, "UPDATE stok SET stok ='$stokYangSudahDitambah' WHERE id_barang='$barang_masuk'");
    if ($addToMasuk && $updateStokMasuk) {
        header("location:masuk.php");
    } else {
        echo 'Gagal';
        header("location:masuk.php");
    }
}

// Menambah barang keluar
if (isset($_POST['addbarangkeluar'])) {
    $barang_keluar = $_POST['barang_keluar'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekStokSekarang = mysqli_query($conn, "SELECT * FROM stok WHERE id_barang = '$barang_keluar'");
    $ambilData = mysqli_fetch_array($cekStokSekarang);

    $stokSekarang = $ambilData['stok'];

    if ($stokSekarang >= $qty) {
        // kalau stok barangnya cukup
        $stokYangSudahDikurang = $stokSekarang - $qty;

        $addToKeluar = mysqli_query($conn, "INSERT INTO keluar (id_barang, penerima, qty) VALUES ('$barang_keluar', '$penerima', '$qty')");
        $updateStokKeluar = mysqli_query($conn, "UPDATE stok SET stok ='$stokYangSudahDikurang' WHERE id_barang='$barang_keluar'");
        if ($addToKeluar && $updateStokKeluar) {
            header("location:keluar.php");
        } else {
            echo 'Gagal';
            header("location:keluar.php");
        }
    } else {
        // kalau stok barang tidak cukup
        echo '
        <script>
            alert("Stok saat ini tidak mencukupi");
            window.location.href="keluar.php";
        </script>';
    }
}

// Update info barang
if (isset($_POST['updatebarang'])) {
    $idb = $_POST['idb'];
    $nama_barang = $_POST['nama_barang'];
    $deskripsi = $_POST['deskripsi'];

    // gambar
    $allowed_extention = array('png', 'jpg','jpeg');
    $nama = $_FILES['file']['name']; //ngambil nama gambar
    $dot = explode('.', $nama);
    $ekstensi = strtolower(end($dot)); // ngambil ekstensi
    $ukuran = $_FILES['file']['size']; //ngambil sizenya
    $file_tmp = $_FILES['file']['tmp_name']; //ngambil lokasi file

    // penamaan file -> enkripsi
    $image = md5(uniqid($nama, true) . time()) . '.' . $ekstensi; //menggambungkan nama file yang dienkripsi dengan ekstensinya

    if($ukuran==0){
        // jika tidak ingin upload
        $update = mysqli_query($conn, "UPDATE stok SET nama_barang='$nama_barang', deskripsi='$deskripsi' WHERE id_barang = '$idb'");
        if ($update) {
            header("location:index.php");
        } else {
            echo 'Gagal';
            header("location:index.php");
        }
    } else{
        // jika ingin
        move_uploaded_file($file_tmp, 'images/' . $image);
        $update = mysqli_query($conn, "UPDATE stok SET nama_barang='$nama_barang', deskripsi='$deskripsi', image='$image' WHERE id_barang = '$idb'");
        if ($update) {
            header("location:index.php");
        } else {
            echo 'Gagal';
            header("location:index.php");
        }
    }
}

// Hapus barang
if (isset($_POST['hapusbarang'])) {
    $idb = $_POST['idb']; //id barang

    $gambar = mysqli_query($conn,"SELECT * FROM stok WHERE id_barang='$idb'");
    $get = mysqli_fetch_array($gambar);
    $img = 'images/'.$get['image'];
    unlink($img);

    $hapus = mysqli_query($conn, "DELETE FROM stok WHERE id_barang = '$idb'");
    if ($hapus) {
        header("location:index.php");
    } else {
        echo 'Gagal';
        header("location:index.php");
    }
}

// Ubah data barang masuk
if (isset($_POST['updatebarangmasuk'])) {
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $keterangan = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstok = mysqli_query($conn, "SELECT * FROM stok WHERE id_barang = '$idb'");
    $stok = mysqli_fetch_array($lihatstok);
    $stokskrg = $stok['stok'];

    $qtyskrg = mysqli_query($conn, "SELECT * FROM masuk WHERE id_masuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangin = $stokskrg - $selisih;
        $kuranginstoknya = mysqli_query($conn, "UPDATE stok SET stok='$kurangin' WHERE id_barang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$keterangan' WHERE id_masuk='$idm'");
        if ($kuranginstoknya && $updatenya) {
            header("location:masuk.php");
        } else {
            echo 'Gagal';
            header("location:masuk.php");
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stokskrg + $selisih;
        $kuranginstoknya = mysqli_query($conn, "UPDATE stok SET stok='$kurangin' WHERE id_barang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty='$qty', keterangan='$keterangan' WHERE id_masuk='$idm'");
        if ($kuranginstoknya && $updatenya) {
            header("location:masuk.php");
        } else {
            echo 'Gagal';
            header("location:masuk.php");
        }
    }
}

// Hapus barang masuk
if (isset($_POST['hapusbarangmasuk'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['qty'];
    $idm = $_POST['idm'];

    $getdatastok = mysqli_query($conn, "SELECT * FROM stok WHERE id_barang = '$idb'");
    $data = mysqli_fetch_array($getdatastok);
    $stok = $data['stok'];

    $selisih = $stok + $qty;

    $update = mysqli_query($conn, "UPDATE stok SET stok='$selisih' WHERE id_barang='$idb'");
    $hapusdata = mysqli_query($conn, "DELETE FROM masuk WHERE id_masuk='$idm'");

    if ($update && $hapusdata) {
        header("location:masuk.php");
    } else {
        echo 'Gagal';
        header("location:masuk.php");
    }
}

// Ubah data barang keluar
if (isset($_POST['updatebarangkeluar'])) {
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstok = mysqli_query($conn, "SELECT * FROM stok WHERE id_barang = '$idb'");
    $stok = mysqli_fetch_array($lihatstok);
    $stokskrg = $stok['stok'];

    $qtyskrg = mysqli_query($conn, "SELECT * FROM keluar WHERE id_keluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if ($qty > $qtyskrg) {
        $selisih = $qty - $qtyskrg;
        $kurangin = $stokskrg - $selisih;
        $kuranginstoknya = mysqli_query($conn, "UPDATE stok SET stok='$kurangin' WHERE id_barang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE keluar SET qty='$qty', penerima='$penerima' WHERE id_keluar='$idk'");
        if ($kuranginstoknya && $updatenya) {
            header("location:keluar.php");
        } else {
            echo 'Gagal';
            header("location:keluar.php");
        }
    } else {
        $selisih = $qtyskrg - $qty;
        $kurangin = $stokskrg + $selisih;
        $kuranginstoknya = mysqli_query($conn, "UPDATE stok SET stok='$kurangin' WHERE id_barang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE keluar SET qty='$qty', penerima='$penerima' WHERE id_keluar='$idk'");
        if ($kuranginstoknya && $updatenya) {
            header("location:keluar.php");
        } else {
            echo 'Gagal';
            header("location:keluar.php");
        }
    }
}

// Hapus barang keluar
if (isset($_POST['hapusbarangkeluar'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['qty'];
    $idk = $_POST['idk'];

    $getdatastok = mysqli_query($conn, "SELECT * FROM stok WHERE id_barang = '$idb'");
    $data = mysqli_fetch_array($getdatastok);
    $stok = $data['stok'];

    $selisih = $stok + $qty;

    $update = mysqli_query($conn, "UPDATE stok SET stok='$selisih' WHERE id_barang='$idb'");
    $hapusdata = mysqli_query($conn, "DELETE FROM keluar WHERE id_keluar='$idk'");

    if ($update && $hapusdata) {
        header("location:keluar.php");
    } else {
        echo 'Gagal';
        header("location:keluar.php");
    }
}

// Tambah User
if (isset($_POST['addadmin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $queryinsert = mysqli_query($conn, "INSERT INTO login (email, password) VALUES ('$email','$password')");

    if ($queryinsert) {
        header("location:admin.php");
    } else {
        header("location:admin.php");
    }
}

// Edit User
if (isset($_POST['updateadmin'])) {
    $emailbaru = $_POST['emailbaru'];
    $passwordbaru = $_POST['passwordbaru'];
    $id_user = $_POST['id'];

    $queryupdate = mysqli_query($conn, "UPDATE login SET email='$emailbaru', password='$passwordbaru' WHERE id_user='$id_user'");

    if ($queryupdate) {
        header("location:admin.php");
    } else {
        header("location:admin.php");
    }
}

// Hapus user
if (isset($_POST['hapusadmin'])) {
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn, "DELETE FROM login WHERE id_user='$id'");

    if ($querydelete) {
        header("location:admin.php");
    } else {
        header("location:admin.php");
    }
}

// Meminjam barang
if(isset($_POST['pinjam'])){
    $id_barang = $_POST['barang']; //mengambil id_barang
    $qty = $_POST['qty']; // mengambil jumlah
    $peminjam = $_POST['peminjam']; // mengambil nama peminjam

    // Ambil stok sekarang
    $stok_saat_ini = mysqli_query($conn, "SELECT * FROM stok WHERE id_barang='$id_barang'");
    $stok_nya = mysqli_fetch_array($stok_saat_ini);
    $stok = $stok_nya['stok']; // ini value nya

    // Kurangi stoknya
    $new_stok = $stok-$qty;

    // Query insert
    $insertpinjam = mysqli_query($conn, "INSERT INTO peminjaman (id_barang, qty, peminjam) VALUES ('$id_barang','$qty','$peminjam')");

    // Mengurangi stok di tabel stok
    $kurangi_stok = mysqli_query($conn, "UPDATE stok SET stok='$new_stok' WHERE id_barang='$id_barang'");

    if($insertpinjam&&$kurangi_stok){
        // jika berhasil
        echo '
        <script>
            alert("Berhasil");
            window.location.href="peminjaman.php";
        </script>';
    } else{
        // jika gagal
        echo '
        <script>
            alert("Gagal");
            window.location.href="peminjaman.php";
        </script>';
    }
}

// Menyelesaikan pinjaman
if(isset($_POST['barangkembali'])){
    $id_peminjaman = $_POST['id_peminjaman'];
    $id_barang = $_POST['idb'];

    // eksekusi
    $update_status = mysqli_query($conn, "UPDATE peminjaman SET status='Kembali' WHERE id_peminjaman='$id_peminjaman'");

    // Ambil stok sekarang
    $stok_saat_ini = mysqli_query($conn, "SELECT * FROM stok WHERE id_barang='$id_barang'");
    $stok_nya = mysqli_fetch_array($stok_saat_ini);
    $stok = $stok_nya['stok']; // ini value nya

    // Ambil qty dari id_peminjaman sekarang
    $stok_saat_ini1 = mysqli_query($conn, "SELECT * FROM peminjaman WHERE id_peminjaman='$id_peminjaman'");
    $stok_nya1 = mysqli_fetch_array($stok_saat_ini1);
    $stok1 = $stok_nya1['qty']; // ini value nya

    // Tambahkan stoknya
    $new_stok = $stok+$stok1;

    // Kembalikan stoknya
    $kembalikan_stok = mysqli_query($conn, "UPDATE stok SET stok='$new_stok' WHERE id_barang='$id_barang'");

    if($update_status&&$kembalikan_stok){
        // jika berhasil
        echo '
        <script>
            alert("Berhasil");
            window.location.href="peminjaman.php";
        </script>';
    } else{
        // jika gagal
        echo '
        <script>
            alert("Gagal");
            window.location.href="peminjaman.php";
        </script>';
    }
}

?>