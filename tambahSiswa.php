<?php
include 'koneksi.php';

$pesan = "";
$nis = "";
$nama = "";
$kelas = "";

if (isset($_POST['simpan'])) {
    $nis = trim($_POST['nis'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');

    if ($nis == "" || $nama == "" || $kelas == "") {
        $pesan = "Semua kolom wajib diisi!";
    } else {
        $cek = mysqli_prepare(
            $koneksi,
            "SELECT nis FROM tb_siswa WHERE nis = ?"
        );

        mysqli_stmt_bind_param($cek, "s", $nis);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);

        if (mysqli_stmt_num_rows($cek) > 0) {
            $pesan = "NIS sudah terdaftar! Gunakan NIS yang berbeda.";
        } else {
            $stmt = mysqli_prepare(
                $koneksi,
                "INSERT INTO tb_siswa (nis, nama, kelas)
                 VALUES (?, ?, ?)"
            );

            if ($stmt) {
                mysqli_stmt_bind_param(
                    $stmt,
                    "sss",
                    $nis,
                    $nama,
                    $kelas
                );

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    mysqli_stmt_close($cek);

                    header("Location: tampilSiswa.php");
                    exit;
                } else {
                    if (mysqli_stmt_errno($stmt) == 1062) {
                        $pesan = "NIS sudah digunakan!";
                    } else {
                        $pesan = "Gagal menyimpan data siswa.";
                    }
                }

                mysqli_stmt_close($stmt);
            } else {
                $pesan = "Gagal menyiapkan query penyimpanan.";
            }
        }

        mysqli_stmt_close($cek);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Siswa - Aplikasi MBG</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #edf3fc, #dce7f5);
            padding: 30px 16px;
            color: #243b59;
        }

        .container {
            max-width: 520px;
            width: 100%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo {
            width: 76px;
            height: 76px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 16px;
            background: #1e3a5f;
            border-radius: 22px;
            font-size: 36px;
            box-shadow: 0 8px 20px rgba(30, 58, 95, 0.2);
        }

        .header h1 {
            font-size: 27px;
            color: #1e3a5f;
            margin-bottom: 9px;
            font-weight: 700;
        }

        .header p {
            font-size: 14px;
            color: #7b8798;
            line-height: 1.7;
        }

        .card {
            background: #ffffff;
            padding: 30px;
            border-radius: 22px;
            border: 1px solid #e5ebf5;
            box-shadow: 0 15px 40px rgba(30, 58, 95, 0.10);
        }

        .card-title {
            color: #1e3a5f;
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .description {
            color: #8994a5;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .pesan {
            background: #fff1f2;
            color: #be123c;
            border: 1px solid #fecdd3;
            padding: 13px 15px;
            border-radius: 12px;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 22px;
            overflow-wrap: anywhere;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 9px;
            font-size: 14px;
            font-weight: 600;
            color: #344760;
        }

        .input-box {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            pointer-events: none;
        }

        input {
            width: 100%;
            padding: 14px 15px 14px 46px;
            border: 1px solid #dce3ee;
            border-radius: 12px;
            background: #f8faff;
            font-family: inherit;
            font-size: 14px;
            color: #243b59;
            outline: none;
            transition: 0.25s;
        }

        input::placeholder {
            color: #a1aaba;
        }

        input:focus {
            border-color: #426da8;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(66, 109, 168, 0.12);
        }

        .button-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 28px;
        }

        .btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .btn-simpan {
            border: 1px solid #1e3a5f;
            background: #1e3a5f;
            color: #ffffff;
            box-shadow: 0 5px 12px rgba(30, 58, 95, 0.16);
        }

        .btn-simpan:hover {
            background: #294f7d;
            border-color: #294f7d;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(30, 58, 95, 0.2);
        }

        .btn-batal {
            background: #ffffff;
            color: #42536b;
            border: 1px solid #dce3ee;
        }

        .btn-batal:hover {
            background: #f1f5fa;
            transform: translateY(-2px);
        }

        footer {
            text-align: center;
            margin-top: 25px;
            color: #8c98aa;
            font-size: 12px;
            line-height: 1.8;
        }

        footer span {
            color: #1e3a5f;
            font-weight: 600;
        }

        @media (max-width: 480px) {
            body {
                padding: 25px 14px;
            }

            .card {
                padding: 23px 19px;
                border-radius: 18px;
            }

            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            <div class="logo">👨‍🎓</div>

            <h1>Tambah Siswa</h1>

            <p>
                Sistem Pendataan Makan Bergizi Gratis<br>
                Tambahkan data siswa baru dengan mudah.
            </p>
        </div>

        <div class="card">

            <h2 class="card-title">Informasi Siswa</h2>

            <p class="description">
                Isi seluruh kolom di bawah ini dengan data yang benar.
            </p>

            <?php if ($pesan != ""): ?>
                <div class="pesan">
                    <?= htmlspecialchars(
                        $pesan,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">

                <div class="form-group">
                    <label for="nis">Nomor Induk Siswa (NIS)</label>

                    <div class="input-box">
                        <span class="input-icon">🪪</span>

                        <input
                            type="text"
                            id="nis"
                            name="nis"
                            placeholder="Masukkan NIS siswa"
                            value="<?= htmlspecialchars(
                                $nis,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>

                    <div class="input-box">
                        <span class="input-icon">👤</span>

                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            placeholder="Masukkan nama lengkap"
                            value="<?= htmlspecialchars(
                                $nama,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="kelas">Kelas</label>

                    <div class="input-box">
                        <span class="input-icon">🏫</span>

                        <input
                            type="text"
                            id="kelas"
                            name="kelas"
                            placeholder="Contoh: XI RPL 1"
                            value="<?= htmlspecialchars(
                                $kelas,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required>
                    </div>
                </div>

                <div class="button-group">

                    <button
                        type="submit"
                        name="simpan"
                        class="btn btn-simpan">
                        ✓ Simpan Data
                    </button>

                    <a
                        href="tampilSiswa.php"
                        class="btn btn-batal">
                        ← Kembali ke Data Siswa
                    </a>

                </div>

            </form>

        </div>

        <footer>
            &copy; 2026 <span>Aplikasi Pendataan MBG</span>
            <br>
            Sistem Informasi Pendataan
        </footer>

    </div>

</body>
</html>