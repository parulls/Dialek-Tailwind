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
        <div class="logo font-irish m-0 text-2xl cursor-pointer" onclick="toggleSidebar()">dialek.id</div>
        <div id="profile-button" class="flex items-center m-0 font-semibold text-custom2 cursor-pointer">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>

    <!-- sidebar -->
    <div id="sidebar" class="fixed bg-white w-60 h-full">
        <div class="flex items-center justify-between w-full px-12 py-12">
            <div id="home" class="logo font-irish m-0 text-2xl cursor-pointer" onclick="toggleSidebar()">dialek.id</div>
        </div>
        <ul class="mt-4 space-y-2">
            <li>
                <a href="./dashboard-batak.html" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">dashboard</span>
                    <span class="text-black font-medium">Dasbor</span>
                </a>
            </li>
            <li>
                <a href="./forum-diskusi-tanya.html" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">forum</span>
                    <span class="text-black font-medium">Forum Diskusi</span>
                </a>
            </li>
            <li>
                <a href="./materi-pilih-topik.html" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">book</span>
                    <span class="text-black font-medium">Materi</span>
                </a>
            </li>
            <li>
                <a href="./permainan-hasil.html" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">extension</span>
                    <span class="text-black font-medium">Permainan</span>
                </a>
            </li>
            <li>
                <a href="./literasi-budaya-level.html" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">auto_stories</span>
                    <span class="text-black font-medium">Literasi Budaya</span>
                </a>
            </li>
            <li>
                <a href="./kosakata-model1.html" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">note_stack</span>
                    <span class="text-black font-medium">Kosakata</span>
                </a>
            </li>
            <li>
                <a id="logout-button" href="/index.html" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">logout</span>
                    <span class="text-black font-medium">Keluar</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- sidear selesai -->

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
        // Sidebar toggle function
    function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("open");  // Toggle the 'open' class to show or hide the sidebar
        }

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
