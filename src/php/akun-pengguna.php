<?php
include("connect.php");

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
    <link rel="stylesheet" href="../styles/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Akun Pengguna - Dialek.id</title>
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div id="home" class="logo font-irish m-0 text-2xl cursor-pointer">dialek.id</div>
    </nav>

    <section class="main flex flex-col mx-auto max-w-2xl items-center flex-grow space-y-4 -mt-20">
    <div class="flex flex-col justify-center items-center gap-8 pt-8">
        <!-- Profile Section -->
        <div class="flex flex-col md:flex-row items-center justify-center md:items-start w-full max-w-2xl p-6 mt-10 bg-white bg-opacity-30 rounded-3xl shadow-lg font-inter border-[1.5px] border-bar">
            <!-- Profile Image -->
            <img id="profile-image" src="../assets/pp.webp" alt="Profile Image" class="w-24 h-24 rounded-full bg-gray-200 -mt-5 md:mt-0 md:mr-6">
        
            <!-- Content Section -->
            <div class="flex flex-col w-full">
                <!-- Name and Setting Button -->
                <div class="flex items-center justify-between w-full">
                    <div class="text-center md:text-left">
                        <p id="profile-name" class="text-xl font-semibold text-custom2">Memuat...</p>
                        <p id="profile-username" class="text-gray-500">memuat...</p>
                    </div>
                    <button id="SettingButton" class="text-custom2">
                        <i class="fa-solid fa-gear text-3xl"></i>
                    </button>
                </div>
        
                <!-- Stats Section -->
                <ul class="flex flex-wrap justify-center md:justify-start space-x-4 mt-4 text-xs">
                    <li id="total-exp" class="text-white bg-custom1 rounded-xl w-32 h-12 text-left pl-2 pt-1"><span class="font-semibold">Total Exp</span><br>0</li>
                    <li id="level" class="text-white bg-custom1 rounded-xl w-32 h-12 text-left pl-2 pt-1"><span class="font-semibold">Level</span><br>0</li>
                    <li id="study-duration" class="text-white bg-custom1 rounded-xl w-32 h-12 text-left pl-2 pt-1"><span class="font-semibold">Durasi Belajar</span><br>3 Menit</li>
                </ul>
            </div>
        </div>
        <!-- Profile Section Selesai -->

        <!-- PENCAPAIAN -->
        <!-- <div class="w-full max-w-2xl p-6 bg-white bg-opacity-30 rounded-3xl shadow-lg font-inter border-[1.5px] border-bar">
            <div class="mb-6">
                <p class="text-lg font-semibold mb-2 text-custom2">Pencapaian</p>
            </div>
            <div class="footer text-center">
                <p class="text-custom2"></p>
            </div>
        </div> -->
        <!-- PENCAPAIAN SELESAI -->

        <!-- BAHASA YG DIPELAJARI -->
        <div class="w-full max-w-2xl p-6 bg-white bg-opacity-30 rounded-3xl shadow-lg font-inter border-[1.5px] border-bar"> 
            <p class="text-lg font-semibold mb-2 text-custom2">Bahasa yang Dipelajari</p>
            <div id="language" class="bg-custom1 text-white rounded-full px-4 py-2 max-w-44">
                <h3>Bahasa Batak Toba</h3>
            </div>
        </div>
        <!-- BAHASA YANG DIPELAJARI SELESAI -->
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const firebaseUid = localStorage.getItem("firebase_uid");
    if (!firebaseUid) {
        alert("Silakan login terlebih dahulu.");
        window.location.href = "./masuk.php";
        return;
    }

    try {
        const response = await fetch(window.location.href, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ firebase_uid: firebaseUid }),
        });

        const result = await response.json();
        if (result.success) {
            const userData = result.user;
            document.getElementById("profile-name").textContent = userData.name || "Nama Tidak Ditemukan";
            document.getElementById("profile-username").textContent = `@${userData.username || "username"}`;
            document.getElementById("profile-image").src = userData.profile_image || "../assets/pp.webp";
        } else {
            alert("Gagal memuat data pengguna: " + result.message);
            window.location.href = "./masuk.php";
        }
    } catch (error) {
        console.error("Fetch Error:", error);
        alert("Terjadi kesalahan saat memuat data pengguna.");
    }
});
    const home = document.getElementById("home");
    home.addEventListener("click", () => {
        window.location.href = "./dashboard-batak.php";
    });
    document.getElementById("SettingButton").addEventListener("click", () => {
        window.location.href = "./pengaturan.php";
    });
</script>
</body>
</html>
