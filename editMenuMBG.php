<?php
include 'koneksi.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID menu MBG tidak valid.");
}

$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM tb_menu_mbg WHERE id_menu = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    die("Data menu MBG tidak ditemukan.");
}

$pesan = "";

if (isset($_POST['ubah'])) {
    $tanggal = $_POST['tanggal'] ?? '';
    $nama_menu = trim($_POST['nama_menu'] ?? '');

    if ($tanggal == '' || $nama_menu == '') {
        $pesan = "Semua kolom wajib diisi!";
    } else {
        $update = mysqli_prepare(
            $koneksi,
            "UPDATE tb_menu_mbg
             SET tanggal = ?, nama_menu = ?
             WHERE id_menu = ?"
        );

        mysqli_stmt_bind_param(
            $update,
            "ssi",
            $tanggal,
            $nama_menu,
            $id
        );

        if (mysqli_stmt_execute($update)) {
            mysqli_stmt_close($update);
            header("Location: tampilMenuMBG.php");
            exit;
        } else {
            $pesan = "Gagal mengubah data: " .
                     mysqli_stmt_error($update);
            mysqli_stmt_close($update);
        }
    }

    // Tampilkan kembali nilai yang dimasukkan
    $data['tanggal'] = $tanggal;
    $data['nama_menu'] = $nama_menu;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Menu MBG</title>

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
                Edit Menu MBG
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Perbarui informasi menu makanan.
            </p>
        </div>

        <!-- Form -->
        <div class="rounded-2xl bg-white p-5 shadow-lg
                    ring-1 ring-slate-200 sm:p-8">

            <?php if ($pesan != ""): ?>
                <div class="mb-5 rounded-xl border border-red-200
                            bg-red-50 p-3 text-sm text-red-700">
                    <?= htmlspecialchars($pesan) ?>
                </div>
            <?php endif; ?>

            <form method="POST"
                  action="editMenuMBG.php?id=<?= $id ?>"
                  class="space-y-5">

                <!-- Tanggal -->
                <div>
                    <label for="tanggal"
                           class="mb-2 block text-sm font-semibold
                                  text-slate-700">
                        Tanggal Menu
                    </label>

                    <input
                        type="date"
                        id="tanggal"
                        name="tanggal"
                        value="<?= htmlspecialchars($data['tanggal']) ?>"
                        required
                        class="w-full rounded-xl border border-slate-300
                               bg-slate-50 px-4 py-3 text-base
                               text-slate-800 outline-none
                               transition focus:border-emerald-500
                               focus:bg-white focus:ring-4
                               focus:ring-emerald-100">
                </div>

                <!-- Nama Menu -->
                <div>
                    <label for="nama_menu"
                           class="mb-2 block text-sm font-semibold
                                  text-slate-700">
                        Nama Menu Makanan
                    </label>

                    <input
                        type="text"
                        id="nama_menu"
                        name="nama_menu"
                        value="<?= htmlspecialchars($data['nama_menu']) ?>"
                        required
                        placeholder="Contoh: Nasi, ayam, dan sayur"
                        class="w-full rounded-xl border border-slate-300
                               bg-slate-50 px-4 py-3 text-base
                               text-slate-800 outline-none
                               transition focus:border-emerald-500
                               focus:bg-white focus:ring-4
                               focus:ring-emerald-100">
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
                        href="tampilMenuMBG.php"
                        class="w-full rounded-xl border border-slate-300
                               bg-white px-5 py-3 text-center
                               font-semibold text-slate-700
                               transition hover:bg-slate-100
                               sm:w-auto">
                        Batal
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