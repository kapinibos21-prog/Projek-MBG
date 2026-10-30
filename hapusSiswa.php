<?php
include 'koneksi.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID siswa tidak valid!");
}

// Pastikan siswa ada di database
$cek = mysqli_prepare(
    $koneksi,
    "SELECT id_siswa FROM tb_siswa WHERE id_siswa = ?"
);

mysqli_stmt_bind_param($cek, "i", $id);
mysqli_stmt_execute($cek);
mysqli_stmt_store_result($cek);

if (mysqli_stmt_num_rows($cek) == 0) {
    mysqli_stmt_close($cek);
    die("Siswa tidak ditemukan. Periksa kolom id_siswa di database.");
}

mysqli_stmt_close($cek);

// Hapus data siswa
$hapus = mysqli_prepare(
    $koneksi,
    "DELETE FROM tb_siswa WHERE id_siswa = ?"
);

mysqli_stmt_bind_param($hapus, "i", $id);

if (mysqli_stmt_execute($hapus)) {
    if (mysqli_stmt_affected_rows($hapus) > 0) {
        mysqli_stmt_close($hapus);
        header("Location: tampilSiswa.php");
        exit;
    }

    echo "Data siswa tidak berhasil dihapus.";
} else {
    $error = mysqli_stmt_errno($hapus);

    if ($error == 1451) {
        echo "Siswa tidak bisa dihapus karena masih terhubung dengan data MBG.";
    } else {
        echo "Gagal menghapus siswa: " .
             htmlspecialchars(mysqli_stmt_error($hapus));
    }
}

mysqli_stmt_close($hapus);
?>