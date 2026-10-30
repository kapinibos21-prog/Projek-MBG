<?php
include 'koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("ID MBG tidak valid.");
}

$pesan = "";

// Proses update sebelum HTML
if (isset($_POST['ubah'])) {
    $id_siswa = (int)($_POST['id_siswa'] ?? 0);
    $tanggal = $_POST['tanggal'] ?? '';
    $status = $_POST['status_terima'] ?? '';

    if (
        $id_siswa > 0 &&
        $tanggal != "" &&
        in_array($status, ['diterima', 'tidak diterima'], true)
    ) {
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE tb_mbg
             SET id_siswa = ?, tanggal = ?, status_terima = ?
             WHERE id_mbg = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "issi",
            $id_siswa,
            $tanggal,
            $status,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: tampilMBG.php");
            exit;
        } else {
            $pesan = "Gagal mengubah data: " .
                mysqli_error($koneksi);
            mysqli_stmt_close($stmt);
        }
    } else {
        $pesan = "Semua kolom wajib diisi!";
    }
}

// Ambil data MBG
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM tb_mbg WHERE id_mbg = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Data MBG tidak ditemukan.");
}

// Ambil daftar siswa
$siswa = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_siswa ORDER BY nama"
);

if (!$siswa) {
    die("Gagal mengambil siswa: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Penerimaan MBG</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-100 px-4 py-8 sm:py-12">

    <div class="mx-auto max-w-lg">

        <!-- Header -->
        <div class="mb-6 text-center">
            <div class="mx-auto mb-3 flex h-16 w-16
                        items-center justify-center rounded-2xl
                        bg-emerald-600 text-3xl text-white shadow-lg">
                🍱
            </div>

            <h1 class="text-2xl font-bold text-slate-800">
                Edit Penerimaan MBG
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Perbarui data penerimaan makanan siswa.
            </p>
        </div>

        <!-- Form Card -->
        <div class="rounded-2xl bg-white p-5 shadow-lg
                    ring-1 ring-slate-200 sm:p-8">

            <?php if ($pesan != ""): ?>
                <div class="mb-5 rounded-xl border border-red-200
                            bg-red-50 p-3 text-sm text-red-700">
                    <?= htmlspecialchars($pesan) ?>
                </div>
            <?php endif; ?>

            <form method="post"
                  action="editMBG.php?id=<?= $id ?>"
                  class="space-y-5">

                <!-- Nama Siswa -->
                <div>
                    <label for="id_siswa"
                           class="mb-2 block text-sm font-semibold
                                  text-slate-700">
                        Nama Siswa
                    </label>

                    <select
                        name="id_siswa"
                        id="id_siswa"
                        required
                        class="w-full rounded-xl border border-slate-300
                               bg-slate-50 px-4 py-3 text-base
                               text-slate-800 outline-none
                               transition focus:border-emerald-500
                               focus:bg-white focus:ring-4
                               focus:ring-emerald-100">

                        <option value="">-- Pilih Siswa --</option>

                        <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                            <option
                                value="<?= (int)$s['id_siswa'] ?>"
                                <?= ((int)$s['id_siswa'] ===
                                    (int)$data['id_siswa'])
                                    ? 'selected' : '' ?>>

                                <?= htmlspecialchars(
                                    $s['nama'] . ' - ' . $s['kelas']
                                ) ?>

                            </option>
                        <?php endwhile; ?>

                    </select>
                </div>

                <!-- Tanggal -->
                <div>
                    <label for="tanggal"
                           class="mb-2 block text-sm font-semibold
                                  text-slate-700">
                        Tanggal Penerimaan
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        id="tanggal"
                        value="<?= htmlspecialchars($data['tanggal']) ?>"
                        required
                        class="w-full rounded-xl border border-slate-300
                               bg-slate-50 px-4 py-3 text-base
                               text-slate-800 outline-none
                               transition focus:border-emerald-500
                               focus:bg-white focus:ring-4
                               focus:ring-emerald-100">
                </div>

                <!-- Status -->
                <div>
                    <label for="status_terima"
                           class="mb-2 block text-sm font-semibold
                                  text-slate-700">
                        Status Penerimaan
                    </label>

                    <select
                        name="status_terima"
                        id="status_terima"
                        required
                        class="w-full rounded-xl border border-slate-300
                               bg-slate-50 px-4 py-3 text-base
                               text-slate-800 outline-none
                               transition focus:border-emerald-500
                               focus:bg-white focus:ring-4
                               focus:ring-emerald-100">

                        <option value="diterima"
                            <?= $data['status_terima'] == 'diterima'
                                ? 'selected' : '' ?>>
                            Diterima
                        </option>

                        <option value="tidak diterima"
                            <?= $data['status_terima'] == 'tidak diterima'
                                ? 'selected' : '' ?>>
                            Tidak Diterima
                        </option>

                    </select>
                </div>

                <!-- Tombol -->
                <div class="flex flex-col gap-3 pt-2 sm:flex-row">

                    <button
                        type="submit"
                        name="ubah"
                        class="w-full rounded-xl bg-emerald-600
                               px-5 py-3 font-semibold text-white
                               shadow-md transition
                               hover:bg-emerald-700
                               active:scale-[0.98] sm:flex-1">
                        Simpan Perubahan
                    </button>

                    <a
                        href="tampilMBG.php"
                        class="w-full rounded-xl border border-slate-300
                               bg-white px-5 py-3 text-center
                               font-semibold text-slate-700
                               transition hover:bg-slate-100
                               sm:w-auto">
                        Kembali
                    </a>

                </div>

            </form>
        </div>

        <!-- Footer -->
        <p class="mt-6 text-center text-xs text-slate-400">
            Aplikasi Pendataan MBG
        </p>

    </div>

</body>
</html>