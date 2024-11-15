<?php
// Pastikan untuk menyertakan koneksi ke database
include("connect.php");

// Cek apakah koneksi berhasil
if (!$conn) {
    echo "<p>Koneksi database gagal</p>";
    exit;
}

// Query untuk mengambil semua data dari tabel 'materi'
$query = "SELECT id_materi, judul_materi, isi_materi FROM materi";
$result = $conn->query($query);

// Cek apakah ada hasil yang ditemukan
if ($result->num_rows > 0) {
    // Jika ada, ambil setiap baris hasil dan simpan ke array materi
    $materi = [];
    while ($row = $result->fetch_assoc()) {
        $materi[] = $row;
    }
} else {
    // Jika tidak ada data
    $materi = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dialek.Id</title>
    <link rel="stylesheet" href="../styles/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #b4b4b464;
        }
    </style>
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div class="logo font-irish m-0 text-2xl">dialek.id</div>
        <div class="flex items-center m-0 font-semibold text-custom2">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>

    <section class="main flex flex-col mx-auto w-4/5 items-center flex-grow space-y-16 text-xl p-8 text-custom2 min-h-[500px] h-full drop-shadow-2xl" id="content-section">
        <!-- Menampilkan materi yang diambil dari database -->
        <?php if (!empty($materi)): ?>
            <?php foreach ($materi as $item): ?>
                <div class="materi-item">
                    <h2><?= htmlspecialchars($item['judul_materi']); ?></h2>
                    <p><?= nl2br(htmlspecialchars($item['isi_materi'])); ?></p>
                </div>
                <hr />
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tidak ada materi ditemukan.</p>
        <?php endif; ?>
    </section>

    <footer class="flex items-center justify-between w-full px-4 py-4">
        <button id="kembali-button" class="button-custom2 text-sm mx-6">
            Kembali
        </button>
        <button id="selanjutnya-button" class="button-custom2 text-sm mx-6">
            Selanjutnya
        </button>
    </footer>

    <script>
        // Mendapatkan elemen tombol
        const kembaliButton = document.getElementById("kembali-button");
        const selanjutnyaButton = document.getElementById("selanjutnya-button");

        // Event listener untuk tombol "Kembali"
        kembaliButton.addEventListener("click", function() {
            window.location.href = "../html/Materi.html";  // Mengarahkan ke halaman Materi.html
        });

        // Event listener untuk tombol "Selanjutnya"
        selanjutnyaButton.addEventListener("click", function() {
            window.location.href = "../html/MateriRating.html";  // Mengarahkan ke halaman MateriRating.html
        });
    </script>

</body>
</html>
