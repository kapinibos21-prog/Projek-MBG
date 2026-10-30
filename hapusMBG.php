<?php
include 'koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("ID MBG tidak valid.");
}

$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM tb_mbg WHERE id_mbg = ?"
);

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header("Location: tampilMBG.php");
    exit;
} else {
    $pesan = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);

    die("Gagal menghapus data MBG: " . htmlspecialchars($pesan));
}
?>