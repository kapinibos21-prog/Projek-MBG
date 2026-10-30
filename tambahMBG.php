<?php
include 'koneksi.php';

$pesan = "";

// Simpan data MBG
if (isset($_POST['simpan'])) {
    $id_siswa = isset($_POST['id_siswa'])
        ? (int) $_POST['id_siswa'] : 0;

    $tanggal = $_POST['tanggal'] ?? '';
    $status = $_POST['status_terima'] ?? '';

    if (
        $id_siswa > 0 &&
        $tanggal != "" &&
        in_array($status, ['diterima', 'tidak diterima'], true)
    ) {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO tb_mbg
            (id_siswa, tanggal, status_terima)
            VALUES (?, ?, ?)"
        );

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "iss",
                $id_siswa,
                $tanggal,
                $status
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: tampilMBG.php");
                exit;
            } else {
                $pesan = "Gagal menyimpan data MBG.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $pesan = "Gagal menyiapkan query penyimpanan.";
        }
    } else {
        $pesan = "Semua kolom wajib diisi!";
    }
}

// Ambil daftar siswa
$siswa = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_siswa ORDER BY nama"
);

if (!$siswa) {
    die("Gagal mengambil data siswa: " .
        mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Penerimaan MBG</title>

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

        input,
        select {
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

        select {
            appearance: none;
            cursor: pointer;
            padding-right: 38px;
        }

        .select-box::after {
            content: "⌄";
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-55%);
            color: #7b8798;
            font-size: 19px;
            pointer-events: none;
        }

        input:focus,
        select:focus {
            border-color: #426da8;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(66, 109, 168, 0.12);
        }

        input::placeholder {
            color: #a1aaba;
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

        .btn-kembali {
            background: #ffffff;
            color: #42536b;
            border: 1px solid #dce3ee;
        }

        .btn-kembali:hover {
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
            <div class="logo">🥗</div>

            <h1>Tambah Penerimaan MBG</h1>

            <p>
                Sistem Pendataan Makan Bergizi Gratis<br>
                Tambahkan data penerimaan makanan siswa.
            </p>
        </div>

        <div class="card">

            <h2 class="card-title">Informasi Penerimaan</h2>

            <p class="description">
                Isi semua data di bawah ini dengan benar.
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

            <form method="post" action="">

                <div class="form-group">
                    <label for="id_siswa">Pilih Siswa</label>

                    <div class="input-box select-box">
                        <span class="input-icon">👨‍🎓</span>

                        <select name="id_siswa" id="id_siswa" required>
                            <option value="">-- Pilih Siswa --</option>

                            <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>
                                <option
                                    value="<?= (int) $s['id_siswa'] ?>"
                                    <?= (
                                        isset($_POST['id_siswa']) &&
                                        (int) $_POST['id_siswa'] ===
                                        (int) $s['id_siswa']
                                    ) ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars(
                                        $s['nama'] . " - " . $s['kelas'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tanggal">Tanggal Penerimaan</label>

                    <div class="input-box">
                        <span class="input-icon">📅</span>

                        <input
                            type="date"
                            name="tanggal"
                            id="tanggal"
                            value="<?= htmlspecialchars(
                                $_POST['tanggal'] ?? '',
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status_terima">Status Penerimaan</label>

                    <div class="input-box select-box">
                        <span class="input-icon">📋</span>

                        <select
                            name="status_terima"
                            id="status_terima"
                            required>

                            <option value="">-- Pilih Status --</option>

                            <option
                                value="diterima"
                                <?= (
                                    ($_POST['status_terima'] ?? '') === 'diterima'
                                ) ? 'selected' : '' ?>
                            >
                                Diterima
                            </option>

                            <option
                                value="tidak diterima"
                                <?= (
                                    ($_POST['status_terima'] ?? '') === 'tidak diterima'
                                ) ? 'selected' : '' ?>
                            >
                                Tidak diterima
                            </option>

                        </select>
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
                        href="tampilMBG.php"
                        class="btn btn-kembali">
                        ← Kembali ke Data MBG
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