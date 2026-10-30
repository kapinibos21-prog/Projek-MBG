<?php
include 'koneksi.php';

$pesan = "";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("ID siswa tidak valid.");
}

// Ambil data siswa
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM tb_siswa WHERE id_siswa = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$siswa) {
    die("Data siswa tidak ditemukan.");
}

// Proses perubahan data
if (isset($_POST['ubah'])) {
    $nis = trim($_POST['nis'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');

    if ($nis == "" || $nama == "" || $kelas == "") {
        $pesan = "Semua kolom wajib diisi!";
    } else {
        // Periksa NIS duplikat pada siswa lain
        $cek = mysqli_prepare(
            $koneksi,
            "SELECT id_siswa FROM tb_siswa
             WHERE nis = ? AND id_siswa != ?"
        );

        mysqli_stmt_bind_param($cek, "si", $nis, $id);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);

        if (mysqli_stmt_num_rows($cek) > 0) {
            $pesan = "NIS sudah digunakan siswa lain!";
        } else {
            $update = mysqli_prepare(
                $koneksi,
                "UPDATE tb_siswa
                 SET nis = ?, nama = ?, kelas = ?
                 WHERE id_siswa = ?"
            );

            mysqli_stmt_bind_param(
                $update,
                "sssi",
                $nis,
                $nama,
                $kelas,
                $id
            );

            if (mysqli_stmt_execute($update)) {
                mysqli_stmt_close($update);
                mysqli_stmt_close($cek);

                // Redirect tanpa header warning
                header("Location: tampilSiswa.php");
                exit;
            } else {
                $pesan = "Gagal mengubah data: " .
                         mysqli_stmt_error($update);
            }

            mysqli_stmt_close($update);
        }

        mysqli_stmt_close($cek);
    }

    // Tampilkan kembali data yang baru diisi
    $siswa['nis'] = $nis;
    $siswa['nama'] = $nama;
    $siswa['kelas'] = $kelas;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title>Edit Siswa</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f7;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        button {
            background: #087f5b;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
        }

        .pesan {
            color: red;
        }
    </style>
</head>

<body>
<div class="container">

    <h2>Edit Siswa</h2>

    <?php if ($pesan != ""): ?>
        <p class="pesan">
            <?= htmlspecialchars($pesan) ?>
        </p>
    <?php endif; ?>

    <form method="post"
          action="editSiswa.php?id=<?= $id ?>">

        <label>NIS:</label>
        <input type="text" name="nis"
               value="<?= htmlspecialchars($siswa['nis']) ?>"
               required>

        <br><br>

        <label>Nama Siswa:</label>
        <input type="text" name="nama"
               value="<?= htmlspecialchars($siswa['nama']) ?>"
               required>

        <br><br>

        <label>Kelas:</label>
        <input type="text" name="kelas"
               value="<?= htmlspecialchars($siswa['kelas']) ?>"
               required>

        <br><br>

        <button type="submit" name="ubah">
            Simpan Perubahan
        </button>
    </form>

    <br>
    <a href="tampilSiswa.php">Kembali</a>

</div>
</body>
</html>