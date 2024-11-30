<?php
session_start();

// Inisialisasi level dan task jika belum ada di session
if (!isset($_SESSION['unlocked_levels'])) {
    $_SESSION['unlocked_levels'] = [1]; // Hanya level 1 yang terbuka secara default
}
if (!isset($_SESSION['unlocked_tasks'])) {
    $_SESSION['unlocked_tasks'] = []; // Tidak ada task yang terbuka secara default
}

// Cek apakah level telah dibuka
function isLevelUnlocked($level) {
    return in_array($level, $_SESSION['unlocked_levels']);
}

// Cek apakah task telah dibuka
function isTaskUnlocked($taskNumber) {
    return in_array($taskNumber, $_SESSION['unlocked_tasks']);
}

// Fungsi untuk membuka level selanjutnya
function unlockNextLevel($currentLevel) {
    $nextLevel = $currentLevel + 1;
    if (!in_array($nextLevel, $_SESSION['unlocked_levels'])) {
        $_SESSION['unlocked_levels'][] = $nextLevel;
    }

    // Buka task setiap selesai 2 level
    if ($nextLevel % 2 == 0) {
        unlockTask($nextLevel / 2);
    }
}

// Fungsi untuk membuka task tertentu
function unlockTask($taskNumber) {
    if (!in_array($taskNumber, $_SESSION['unlocked_tasks'])) {
        $_SESSION['unlocked_tasks'][] = $taskNumber;
    }
}

// Logika: Misalnya, jika pengguna selesai membaca level tertentu
if (isset($_GET['complete_level'])) {
    $completedLevel = intval($_GET['complete_level']);
    if (isLevelUnlocked($completedLevel)) {
        unlockNextLevel($completedLevel); // Buka level berikutnya
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dialek.Id - Literasi Budaya</title>
    <link rel="stylesheet" href="../styles/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        #sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            background-color: white;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            transition: left 0.4s ease;
            z-index: 1000;
        }
        #sidebar.open {
            left: 0;
        }
    </style>
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div id="home" class="logo font-irish m-0 text-2xl cursor-pointer">dialek.id</div>
        <div id="profile-button" class="flex items-center m-0 font-semibold text-custom2 cursor-pointer">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>

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

    <!-- Main Content Section -->
    <section class="main flex flex-col mx-auto w-4/5 flex-grow space-y-16">
        <div class="flex flex-col items-center justify-center">
            <header class="header items-center text-center mb-10">
                <h1 class="h1 font-bold text-gradient">
                  Literasi Budaya
                </h1>
            </header>
            <div class="box-custom1 font-medium p-4 text-xl">
                <p>Lakukan literasi budaya Batak Toba dan telusuri sejarahnya, lalu lakukan kuis tiap 2 level yang kamu lewati.</p>
            </div>
        </div>

        <!-- Wrapper container for level boxes -->
        <div class="flex flex-wrap justify-center items-center gap-4 md:gap-8">
            <?php for ($i = 1; $i <= 6; $i++): ?>
                <?php if (isLevelUnlocked($i)): ?>
                    <!-- Level terbuka -->
                    <div class="level cursor-pointer w-10 h-10 sm:w-12 sm:h-12 md:w-16 md:h-16 aspect-square text-center flex items-center justify-center">
                        <a href="literasi-budaya-materi.php?level=<?= $i ?>"> <?= $i ?> </a>
                    </div>
                <?php else: ?>
                    <!-- Level terkunci -->
                    <div class="level-lock w-10 h-10 sm:w-12 sm:h-12 md:w-16 md:h-16 aspect-square text-center flex items-center justify-center">
                        <?= $i ?>
                    </div>
                <?php endif; ?>

                <?php if ($i % 2 == 0): ?>
                    <?php $taskNumber = $i / 2; ?>
                    <?php if (isTaskUnlocked($taskNumber)): ?>
                        <!-- Task terbuka -->
                        <div class="level cursor-pointer w-10 h-10 sm:w-12 sm:h-12 md:w-16 md:h-16 aspect-square text-center flex items-center justify-center">
                            <a href="literasi-budaya-task.php?task=<?= $taskNumber ?>">
                                <i class="fa-solid fa-comment"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- Task terkunci -->
                        <div class="level-lock w-10 h-10 sm:w-12 sm:h-12 md:w-16 md:h-16 aspect-square text-center flex items-center justify-center">
                            <i class="fa-solid fa-comment"></i>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    </section>

    <footer class="flex items-center justify-start w-full px-4 py-4">
        <button id="kembali-button" class="button-custom2 text-sm mx-6">Kembali</button>
    </footer>

    <script>
        const profile = document.getElementById("profile-button");
        profile.addEventListener("click", () => {
            window.location.href = "./profil-pengguna.php";
        });
        const kembaliButton = document.getElementById('kembali-button');
        kembaliButton.addEventListener('click', function() {
            window.location.href = './dashboard-batak.php';
        });
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("open");  // Toggle the 'open' class to show or hide the sidebar
        }
    </script>
</body>
</html>
