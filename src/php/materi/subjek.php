<?php
// Pastikan untuk menyertakan koneksi ke database
include("../connect.php");

// Cek apakah koneksi berhasil
if (!$conn) {
    echo "<p>Koneksi database gagal</p>";
    exit;
}

// Query untuk mengambil data dengan id_materi = 1
$id_material = 1; // ID yang ingin diambil
$query = "SELECT id_material, material_title, material_content FROM materials WHERE id_material = :id_material";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id_material', $id_material, PDO::PARAM_INT);
$stmt->execute();
$materials = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <title>Dialek.Id - Detail Materi</title>
    <link rel="stylesheet" href="../../styles/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div id="home" class="logo font-irish m-0 text-2xl">dialek.id</div>
        <div id="profile-button" class="flex items-center m-0 font-semibold text-custom2">
            <p id="account-username" class="px-4 text-xl">memuat...</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>

    <section class="main flex flex-col mx-auto w-11/12 sm:w-4/5 items-center flex-grow space-y-8 sm:space-y-16 text-xl p-8 text-custom2 min-h-[500px] h-full drop-shadow-2xl" id="content-section">
        <!-- Menampilkan materi yang diambil dari database -->
        <?php if (!empty($materials)): ?>
            <div class="materi-item max-w-6xl bg-white bg-opacity-30 rounded-3xl shadow-lg font-inter border-[1.5px] border-bar">
                <p class="flex justify-center text-xl sm:text-2xl md:text-3xl font-semibold pt-4 pb-3"><?= htmlspecialchars($materials['material_title']); ?></p>
                <p class="text-base p-4"><?= nl2br(htmlspecialchars($materials['material_content'])); ?></p>
            </div>
        <?php else: ?>
            <p>Materi tidak ditemukan.</p>
        <?php endif; ?>
    </section>

    <footer class="flex items-center justify-between w-full px-4 py-4">
        <button id="kembali-button" class="button-custom2 text-sm mx-6">Kembali</button>
        <button id="selanjutnya-button" class="button-custom2 text-sm mx-6">Selanjutnya</button>
    </footer>
    
    <script>
        // Mendapatkan elemen tombol
        const kembaliButton = document.getElementById("kembali-button");
        const selanjutnyaButton = document.getElementById("selanjutnya-button");

        // Event listener untuk tombol "Kembali"
        kembaliButton.addEventListener("click", function() {
            window.location.href = "../rating_materi/rating_subjek.php";  // Mengarahkan ke halaman Materi.html
        });
        selanjutnyaButton.addEventListener("click", () => {
            window.location.href = "../latihan/subjek/nomor1.php";
        });

        document.addEventListener("DOMContentLoaded", async () => {
    const firebaseUid = localStorage.getItem("firebase_uid");

    try {
        const response = await fetch(window.location.href, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ firebase_uid: firebaseUid }),
        });

        const result = await response.json();
        if (result.success) {
            const userData = result.user;
            document.getElementById("account-username").textContent = `${userData.username || "username"}`;

        } else {
            alert("Gagal memuat data pengguna: " + result.message);
            window.location.href = "./masuk.php";
        }
    } catch (error) {
        console.error("Fetch Error:", error);
        alert("Terjadi kesalahan saat memuat data pengguna.");
    }

    document.getElementById("loading-bar").style.width = "0";

});
    </script>
</body>
</html>
