
<?php
include 'koneksi.php';
$data = mysqli_query($koneksi, "SELECT * FROM tb_siswa");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 30px;
            color: #333;
        }

        h2 {
            color: #1e293b;
            margin-bottom: 20px;
        }

        .menu {
            margin-bottom: 20px;
        }

        .menu a {
            display: inline-block;
            text-decoration: none;
            color: white;
            background-color: #2563eb;
            padding: 10px 15px;
            border-radius: 6px;
            margin-right: 8px;
            font-size: 14px;
            transition: 0.3s;
        }

        .menu a:hover {
            background-color: #1d4ed8;
        }

        .table-container {
            overflow-x: auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        th {
            background-color: #2563eb;
            color: white;
            padding: 14px;
            text-align: left;
        }

        td {
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:hover td {
            background-color: #f1f5f9;
        }

        td a {
            display: inline-block;
            text-decoration: none;
            color: white;
            background-color: #0f766e;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 12px;
        }

        td a:hover {
            opacity: 0.8;
        }

        td a.hapus {
            background-color: #dc2626;
        }

        .kosong {
            text-align: center;
            color: #777;
            padding: 20px;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }

            th, td {
                padding: 9px 7px;
                font-size: 12px;
            }

            .menu a {
                margin-bottom: 8px;
            }
        }
    </style>
</head>

<body>

    <h2>Data Siswa</h2>

    <div class="menu">
        <a href="index.php">Menu Utama</a>
        <a href="tambahSiswa.php">+ Tambah Siswa Baru</a>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>

            <?php
            $no = 1;

            if (mysqli_num_rows($data) > 0) {
                while ($row = mysqli_fetch_assoc($data)) {
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nis']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['kelas']) ?></td>
                <td>
                    <a href="editSiswa.php?id=<?= $row['id_siswa'] ?>">
                        Edit
                    </a>

                    <a class="hapus"
                       href="hapusSiswa.php?id=<?= $row['id_siswa'] ?>"
                       onclick="return confirm('Hapus siswa ini?')">
                        Hapus
                    </a>
                </td>
            </tr>
            <?php
                }
            } else {
            ?>
            <tr>
                <td colspan="5" class="kosong">
                    Belum ada data siswa.
                </td>
            </tr>
            <?php } ?>

        </table>
    </div>

</body>
</html>
