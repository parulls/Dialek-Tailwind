<?php
include("connect.php");

// Periksa apakah ini adalah permintaan POST
       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header("Content-Type: application/json");
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $firebaseUid = $data['firebase_uid'] ?? null;
    
            if ($firebaseUid) {
                $stmt = $conn->prepare("
                    SELECT name, username, email, phone, 
                           COALESCE(profile_image, '../assets/pp.webp') AS profile_image 
                    FROM users 
                    WHERE firebase_uid = :firebase_uid
                ");
                $stmt->execute([':firebase_uid' => $firebaseUid]);
    
                if ($stmt->rowCount() > 0) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo json_encode(["success" => true, "user" => $user]);
                    exit;
                } else {
                    echo json_encode(["success" => false, "message" => "Firebase UID tidak ditemukan."]);
                    exit;
                }
            } else {
                echo json_encode(["success" => false, "message" => "Firebase UID tidak valid."]);
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "Kesalahan pada database: " . $e->getMessage()]);
            exit;
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => "Terjadi kesalahan: " . $e->getMessage()]);
            exit;
        }
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
        <div id="home" class="logo font-irish m-0 text-2xl cursor-pointer" onclick="toggleSidebar()">dialek.id</div>
        <div id="profile-button" class="flex items-center m-0 font-semibold text-custom2 cursor-pointer">
            <p id="account-username" class="px-4 text-xl">memuat...</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>
    <div id="sidebar" class="fixed bg-white w-60 h-full">
        <div class="flex items-center justify-between w-full px-12 py-12">
            <div id="home" class="logo font-irish m-0 text-2xl cursor-pointer" onclick="toggleSidebar()">dialek.id</div>
        </div>
        <ul class="mt-4 space-y-2">
            <li>
                <a href="../php/dashboard-batak.php" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">dashboard</span>
                    <span class="text-black font-medium">Dasbor</span>
                </a>
            </li>
            <li>
                <a href="../php/forum-diskusi-tanya.php" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
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
                <a href="../php/permainan-hasil.php" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">extension</span>
                    <span class="text-black font-medium">Permainan</span>
                </a>
            </li>
            <li>
                <a href="../php/literasi-budaya-level.php" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">auto_stories</span>
                    <span class="text-black font-medium">Literasi Budaya</span>
                </a>
            </li>
            <li>
                <a href="../php/kosakata-model1.php" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">note_stack</span>
                    <span class="text-black font-medium">Kosakata</span>
                </a>
            </li>
            <li>
                <a id="logout-button" href="../../index.php" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">logout</span>
                    <span class="text-black font-medium">Keluar</span>
                </a>
            </li>
        </ul>
    </div>
    <section class="main flex flex-col mx-auto w-4/5 items-center flex-grow space-y-16">
            <div class="flex justify-center items-center mb-12">
                <h1 class="h3 md:text-4xl lg:text-4xl font-bold text-gradient">Materi</h1>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 md:gap-10 lg:gap-12 w-full px-4">
                <!-- Card 1: Subjek -->
                <div class="flex items-center justify-between px-6 py-6 rounded-2xl border border-bar shadow-md w-full">
                    <img src="..\assets\profileSubjek.webp" alt="profile-subjek" class="w-16 h-16 rounded-full md:w-20 md:h-20 flex items-center justify-center"/>
                    <div class="flex flex-col gap-3 w-1/2 md:w-6/12">
                        <span class="text-2xl md:text-xl font-semibold text-gray-700">Subjek</span>
                        <div class="w-full h-3 bg-gray-200 rounded-md overflow-hidden">
                          <div class="progress-bar w-0 h-full"></div>
                        </div>
                      </div>
                    <button id="masuk-materi" class="button-custom w-1/4">Mulai</button>
                </div>
            
                <!-- Card 2: Kata Kerja -->
                <div class="flex items-center justify-between px-6 py-6 rounded-2xl border border-bar shadow-md w-full">
                    <div class="w-16 h-16 md:w-20 md:h-20 flex items-center justify-center bg-gray-300 rounded-full">
                        <i class="fa-solid fa-lock text-gray-400 text-xl"></i>
                    </div>
                    <div class="flex flex-col gap-3 w-1/2 md:w-6/12">
                        <span class="text-2xl md:text-xl font-semibold text-gray-700">Kata Kerja</span>
                        <div class="w-full h-3 bg-gray-200 rounded-md overflow-hidden">
                          <div class="progress-bar w-0 h-full"></div>
                        </div>
                      </div>
                    <button class="button-lock w-1/4">Mulai</button>
                </div>
            
                <!-- Card 3: Kata Benda -->
                <div class="flex items-center justify-between px-6 py-6 rounded-2xl border border-bar shadow-md w-full">
                    <div class="w-16 h-16 md:w-20 md:h-20 flex items-center justify-center bg-gray-300 rounded-full">
                        <i class="fa-solid fa-lock text-gray-400 text-xl"></i>
                    </div>
                    <div class="flex flex-col gap-3 w-1/2 md:w-6/12">
                        <span class="text-2xl md:text-xl font-semibold text-gray-700">Kata Benda</span>
                        <div class="w-full h-3 bg-gray-200 rounded-md overflow-hidden">
                          <div class="progress-bar w-0 h-full"></div>
                        </div>
                      </div>
                    <button class="button-lock w-1/4">Mulai</button>
                </div>
            
                <!-- Card 4: Angka, Bilangan, dan Satuan -->
                <div class="flex items-center justify-between px-6 py-6 rounded-2xl border border-bar shadow-md w-full">
                    <div class="w-16 h-16 md:w-20 md:h-20 flex items-center justify-center bg-gray-300 rounded-full">
                        <i class="fa-solid fa-lock text-gray-400 text-xl"></i>
                    </div>
                    <div class="flex flex-col gap-3 w-1/2 md:w-6/12">
                        <span class="text-2xl md:text-xl font-semibold text-gray-700">Bilangan</span>
                        <div class="w-full h-3 bg-gray-200 rounded-md overflow-hidden">
                          <div class="progress-bar w-0 h-full"></div>
                        </div>
                      </div>
                    <button class="button-lock w-1/4">Mulai</button>
                </div>
            </div>
    </section>
    <footer class="flex items-center justify-between w-full px-4 py-4">
        <button id="kembali-button" class="button-custom2 text-sm mx-6">
            Kembali
        </button>
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
    const firebaseUid = localStorage.getItem("firebase_uid");

    // Cek apakah firebase_uid ada
    if (!firebaseUid) {
        alert("UID Firebase tidak ditemukan. Harap login kembali.");
        window.location.href = "login.php";
        return; // Stop eksekusi lebih lanjut
    }

    // Log untuk memastikan firebaseUid benar
    console.log("firebase_uid:", firebaseUid);

    try {
        // Menjalankan fetch request ke server
        const response = await fetch(window.location.href, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ firebase_uid: firebaseUid }),
        });

        // Cek status response
        if (!response.ok) {
            throw new Error("Server gagal merespons dengan status " + response.status);
        }

        const result = await response.json();

        // Log untuk melihat isi result dari server
        console.log("Result from server:", result);

        if (result.success) {
            const userData = result.user;
            document.getElementById("account-username").textContent = `${userData.username || "username"}`;
        } else {
            alert("Gagal memuat data pengguna: " + result.message);
            window.location.href = "login.php"; // Arahkan ke login jika gagal
        }
    } catch (error) {
        console.error("Fetch Error:", error);
        alert("Terjadi kesalahan saat memuat data pengguna.");
    }

    document.getElementById("loading-bar").style.width = "0";
});

document.addEventListener("DOMContentLoaded", () => {
    // Tangkap tombol dengan ID tertentu
    const mulaiButton = document.getElementById("masuk-materi");

    // Tambahkan event listener untuk klik tombol
    mulaiButton.addEventListener("click", () => {
        // Arahkan ke halaman tujuan
        window.location.href = "rating_materi/rating_subjek.php";
    });
});

    </script>
</body>