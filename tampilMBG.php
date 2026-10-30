
<?php
include 'koneksi.php';

$sql = "SELECT tb_mbg.*, tb_siswa.nis, tb_siswa.nama, tb_siswa.kelas
        FROM tb_mbg
        JOIN tb_siswa ON tb_mbg.id_siswa = tb_siswa.id_siswa
        ORDER BY tb_mbg.tanggal DESC";

$data = mysqli_query($koneksi, $sql);

if (!$data) {
    die("Query error: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penerimaan MBG</title>

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
            max-width: 1200px;
            margin: auto;
        }

        h2 {
            color: #1e293b;
            font-size: 26px;
            margin: 0 0 10px;
        }

        .deskripsi {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .menu {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .menu a {
            display: inline-block;
            padding: 12px 16px;
            background-color: #2563eb;
            color: white;
            text-decoration: none;
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
            min-width: 650px;
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

        .status {
            display: inline-block;
            padding: 6px 11px;
            background-color: #dbeafe;
            color: #1d4ed8;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
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
            padding: 30px;
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

    <h2>Data Penerimaan MBG</h2>

    <p class="deskripsi">
        Daftar penerimaan makanan bergizi gratis siswa.
    </p>

    <div class="menu">
        <a href="index.php">« Menu Utama</a>
        <a href="tambahMBG.php">+ Tambah Penerimaan MBG</a>
    </div>

    <div class="card">

        <div class="card-header">
            Tabel Data Penerimaan Siswa
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                $no = 1;

                if (mysqli_num_rows($data) > 0) {
                    while ($row = mysqli_fetch_assoc($data)) {
                ?>

                    <tr>
                        <td><?= $no++ ?></td>

                        <td>
                            <?= htmlspecialchars($row['nama']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['kelas']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['tanggal']) ?>
                        </td>

                        <td>
                            <span class="status">
                                <?= htmlspecialchars($row['status_terima']) ?>
                            </span>
                        </td>

                        <td class="aksi">
                            <a href="editMBG.php?id=<?= (int)$row['id_mbg'] ?>">
                                Edit
                            </a>

                            <a class="hapus"
                               href="hapusMBG.php?id=<?= (int)$row['id_mbg'] ?>"
                               onclick="return confirm('Yakin ingin menghapus data ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>

                <?php
                    }
                } else {
                ?>

                    <tr>
                        <td colspan="6" class="kosong">
                            Belum ada data penerimaan MBG.
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
