
<?php
include 'koneksi.php';

$query = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_menu_mbg ORDER BY id_menu DESC"
);

if (!$query) {
    die("Query error: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Menu MBG</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 30px;
            color: #334155;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .header {
            margin-bottom: 25px;
        }

        h2 {
            color: #1e293b;
            font-size: 26px;
            margin: 0 0 10px;
        }

        .deskripsi {
            color: #64748b;
            font-size: 14px;
            margin: 0;
        }

        .menu {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .menu a {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: bold;
            transition: 0.3s;
        }

        .menu a:hover {
            background-color: #1d4ed8;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #e2e8f0;
            color: #1e293b;
            font-size: 16px;
            font-weight: bold;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 500px;
            border-collapse: collapse;
        }

        th {
            background-color: #2563eb;
            color: white;
            padding: 15px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 14px 15px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .aksi {
            white-space: nowrap;
        }

        .aksi a {
            display: inline-block;
            padding: 7px 11px;
            background-color: #0f766e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 12px;
            margin: 2px;
        }

        .aksi a:hover {
            opacity: 0.8;
        }

        .aksi .hapus {
            background-color: #dc2626;
        }

        .kosong {
            text-align: center;
            padding: 25px;
            color: #64748b;
        }

        .footer {
            padding: 15px 20px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            body {
                padding: 16px;
            }

            h2 {
                font-size: 22px;
            }

            .menu a {
                padding: 10px 12px;
                font-size: 12px;
            }

            th, td {
                padding: 11px 10px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h2>Data Menu MBG</h2>
        <p class="deskripsi">
            Kelola daftar menu Makanan Bergizi Gratis.
        </p>
    </div>

    <div class="menu">
        <a href="index.php">« Menu Utama</a>
        <a href="tambahMenuMBG.php">+ Tambah Menu MBG</a>
    </div>

    <div class="card">

        <div class="card-header">
            Daftar Menu MBG
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Menu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                $no = 1;

                if (mysqli_num_rows($query) > 0) {
                    while ($data = mysqli_fetch_assoc($query)) {
                ?>

                    <tr>
                        <td><?= $no++; ?></td>

                        <td>
                            <?= htmlspecialchars($data['tanggal']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($data['nama_menu']); ?>
                        </td>

                        <td class="aksi">
                            <a href="editMenuMBG.php?id=<?= (int)$data['id_menu']; ?>">
                                Edit
                            </a>

                            <a class="hapus"
                               href="hapusMenuMBG.php?id=<?= (int)$data['id_menu']; ?>"
                               onclick="return confirm('Yakin ingin menghapus menu ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>

                <?php
                    }
                } else {
                ?>

                    <tr>
                        <td colspan="4" class="kosong">
                            Belum ada data menu MBG.
                        </td>
                    </tr>

                <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="footer">
            Sistem Pendataan Makanan Bergizi Gratis
        </div>

    </div>

</div>

</body>
</html>
