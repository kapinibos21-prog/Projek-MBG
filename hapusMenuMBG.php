<?php
include 'koneksi.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID MBG tidak valid.");
}

$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM tb_menu_mbg WHERE id_menu = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: tampilMenuMBG.php");
    exit;
} else {
    die("Gagal menghapus data: " . mysqli_error($koneksi));
}
?>