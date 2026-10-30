<?php
include 'koneksi.php';

$pesan = "";
$tanggal = "";
$nama_menu = "";

if (isset($_POST['simpan'])) {
    $tanggal = $_POST['tanggal'] ?? '';
    $nama_menu = trim($_POST['nama_menu'] ?? '');

    if ($tanggal == '' || $nama_menu == '') {
        $pesan = "Semua kolom wajib diisi!";
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO tb_menu_mbg (tanggal, nama_menu)
             VALUES (?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ss",
            $tanggal,
            $nama_menu
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: tampilMenuMBG.php");
            exit;
        } else {
            $pesan = "Gagal menyimpan data: " .
                     mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Menu MBG</title>

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

        h1 {
            font-size: 28px;
            color: #1e3a5f;
            margin-bottom: 9px;
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

        .card h2 {
            color: #1e3a5f;
            font-size: 19px;
            margin-bottom: 8px;
        }

        .description {
            color: #8994a5;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .alert {
            padding: 13px 15px;
            margin-bottom: 22px;
            border-radius: 12px;
            font-size: 13px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .error {
            background: #fff1f2;
            color: #be123c;
            border: 1px solid #fecdd3;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            color: #344760;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 9px;
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

        input:focus {
            border-color: #426da8;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(66, 109, 168, 0.12);
        }

        input::placeholder {
            color: #a1aaba;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 28px;
        }

        button,
        .btn-back {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: 0.25s;
        }

        button {
            border: none;
            background: #1e3a5f;
            color: white;
            box-shadow: 0 5px 12px rgba(30, 58, 95, 0.16);
        }

        button:hover {
            background: #294f7d;
            transform: translateY(-2px);
        }

        .btn-back {
            background: white;
            color: #42536b;
            border: 1px solid #dce3ee;
        }

        .btn-back:hover {
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

            h1 {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            <div class="logo">🍱</div>

            <h1>Tambah Menu MBG</h1>

            <p>
                Sistem Pendataan Makan Bergizi Gratis<br>
                Tambahkan data menu makanan baru.
            </p>
        </div>

        <div class="card">

            <h2>Informasi Menu</h2>

            <p class="description">
                Isi tanggal dan nama menu makanan dengan benar.
            </p>

            <?php if ($pesan != ""): ?>
                <div class="alert error">
                    <?= htmlspecialchars(
                        $pesan,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">

                <div class="form-group">
                    <label for="tanggal">Tanggal Menu</label>

                    <div class="input-box">
                        <span class="input-icon">📅</span>

                        <input
                            type="date"
                            id="tanggal"
                            name="tanggal"
                            value="<?= htmlspecialchars(
                                $tanggal,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nama_menu">Nama Menu Makanan</label>

                    <div class="input-box">
                        <span class="input-icon">🍛</span>

                        <input
                            type="text"
                            id="nama_menu"
                            name="nama_menu"
                            value="<?= htmlspecialchars(
                                $nama_menu,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            placeholder="Contoh: Nasi, ayam, dan sayur"
                            required>
                    </div>
                </div>

                <div class="buttons">

                    <button type="submit" name="simpan">
                        + Simpan Menu
                    </button>

                    <a href="tampilMenuMBG.php" class="btn-back">
                        ← Kembali ke Data Menu
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