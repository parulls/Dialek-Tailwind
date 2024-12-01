<?php
include 'connect.php';

// Mendapatkan ID level dari parameter URL
$level = isset($_GET['level']) ? intval($_GET['level']) : 1; // Default level 1 jika tidak ada parameter

// Query untuk mengambil data dari database berdasarkan level
$query = "SELECT title, content FROM kosakata_levels WHERE id = :level";
$stmt = $conn->prepare($query);
$stmt->bindParam(':level', $level, PDO::PARAM_INT);
$stmt->execute();

// Fetch data level
$level_data = $stmt->fetch();

// Jika data level tidak ditemukan, arahkan kembali ke halaman level
if (!$level_data) {
    header("Location: literasi-budaya-level.php");
    exit;
}

$title = $level_data['title'];
$content = $level_data['content'];

// Menentukan level selanjutnya dan sebelumnya
$nextLevel = $level + 1;
$prevLevel = $level - 1;

// Periksa apakah level berikutnya ada di database
$query_check_next_level = "SELECT COUNT(*) FROM kosakata_levels WHERE id = :nextLevel"; 
$stmt_check_next_level = $conn->prepare($query_check_next_level);
$stmt_check_next_level->bindParam(':nextLevel', $nextLevel, PDO::PARAM_INT);
$stmt_check_next_level->execute();
$nextLevelExists = $stmt_check_next_level->fetchColumn() > 0;

// Periksa apakah ini adalah permintaan POST untuk data pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");
    include('connect.php');

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
    <title>Dialek.Id - Belajar Kosakata</title>
    <link rel="stylesheet" href="../styles/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <!-- Navigation Bar -->
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div id="home" class="logo font-irish m-0 text-2xl cursor-pointer">dialek.id</div>
        <div id="profile-button" class="flex items-center m-0 font-semibold text-custom2 cursor-pointer">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>

    <!-- Main Content Section -->
    <section class="main flex flex-col mx-auto w-4/5 flex-grow space-y-10">
        <header class="header items-center text-center">
            <h2 class="h2 font-bold text-gradient">
                <?php echo htmlspecialchars($title); ?>
            </h2>
        </header>
        <div class="flex flex-col justify-center items-center space-y-6 font-medium text-black">
            <div class="p-6 bg-custom9 rounded-lg shadow-md">
                <p class="text-lg">
                    <?php echo nl2br(htmlspecialchars($content)); ?>
                </p>
            </div>
        </div>
    </section>

    <!-- Footer with Navigation Buttons -->
    <footer class="flex items-center justify-between w-full px-4 py-4">
        <button id="kembali-button" class="button-custom2 text-sm mx-6">Kembali</button>
        <button id="selanjutnya-button" class="button-custom2 text-sm mx-6">Selanjutnya</button>
    </footer>


    <script>
    const home = document.getElementById("home");
    const profile = document.getElementById("profile-button");

    home.addEventListener("click", () => {
        window.location.href = "./dashboard-batak.php";
    });

    profile.addEventListener("click", () => {
        window.location.href = "./AkunUser.php";
    });
        const kembaliButton = document.getElementById('kembali-button');
        const selanjutnyaButton = document.getElementById('selanjutnya-button');

        kembaliButton.addEventListener('click', function () {
            // Jika level > 1, pergi ke level sebelumnya
            if (<?php echo $level; ?> > 1) {
                window.location.href = './literasi-budaya-materi.php?level=' + (<?php echo $prevLevel; ?>);
            } else {
                // Jika level = 1, kembali ke halaman level
                window.location.href = './literasi-budaya-level.php';
            }
        });

        selanjutnyaButton.addEventListener('click', function () {
            if (<?php echo $level; ?> === 2) {
                // Jika level 2, langsung masuk ke latihan level 2
                window.location.href = './literasi-budaya-latihan.php';
            } else if (<?php echo $nextLevelExists ? 'true' : 'false'; ?>) {
                // Untuk level lainnya, jika level berikutnya ada
                window.location.href = './literasi-budaya-materi.php?level=<?php echo $nextLevel; ?>';
            }
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
                document.getElementById("account-username").textContent = `@${userData.username || "username"}`;

            } else {
                alert("Gagal memuat data pengguna: " + result.message);
                window.location.href = "login.php";
            }
        } catch (error) {
            console.error("Fetch Error:", error);
            alert("Terjadi kesalahan saat memuat data pengguna.");
        }
    });
    </script>
</body>
</html>