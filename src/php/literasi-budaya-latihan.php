<?php
include 'connect.php';

// Mendapatkan ID task dari parameter URL
$task_id = isset($_GET['task_id']) ? intval($_GET['task_id']) : 1; // Default task 1

// Query untuk mengambil data task berdasarkan level 2 dan task_id
$query = "SELECT question, option_a, option_b, correct_option FROM literasi_tasks WHERE level_id = 2 AND id = :task_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
$stmt->execute();
$task_data = $stmt->fetch();

// Jika data task tidak ditemukan, arahkan ke halaman level 2
if (!$task_data) {
    header("Location: literasi-budaya-level.php");
    exit;
}

// Menentukan task selanjutnya
$nextTask = $task_id + 1;

// Periksa apakah task berikutnya ada di database
$query_next_task = "SELECT COUNT(*) FROM literasi_tasks WHERE level_id = 2 AND id = :nextTask";
$stmt_next_task = $conn->prepare($query_next_task);
$stmt_next_task->bindParam(':nextTask', $nextTask, PDO::PARAM_INT);
$stmt_next_task->execute();
$nextTaskExists = $stmt_next_task->fetchColumn() > 0;

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
    <title>Dialek.Id - Pertanyaan</title>
    <link rel="stylesheet" href="../styles/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>
        #sidebar {
            position: fixed;
            top: 0;
            left: -100%; /* Sidebar tersembunyi */
            background-color: white;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            transition: left 0.4s ease;
            z-index: 1000;
        }

        #sidebar.open {
            left: 0; /* Sidebar terbuka */
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
    <div id="sidebar" class="fixed bg-white w-60 h-full">
        <div class="flex items-center justify-between w-full px-12 py-12">
            <div id="home" class="logo font-irish m-0 text-2xl cursor-pointer" onclick="toggleSidebar()">dialek.id</div>
        </div>
        <ul class="mt-4 space-y-2">
            <li>
                <a href="./dashboard-batak.php" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">dashboard</span>
                    <span class="text-black font-medium">Dasbor</span>
                </a>
            </li>
            <li>
                <a href="./forum-diskusi-tanya.php" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">forum</span>
                    <span class="text-black font-medium">Forum Diskusi</span>
                </a>
            </li>
            <li>
                <a href="./materi-pilih-topik.php" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">book</span>
                    <span class="text-black font-medium">Materi</span>
                </a>
            </li>
            <li>
                <a href="./permainan-hasil.php" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">extension</span>
                    <span class="text-black font-medium">Permainan</span>
                </a>
            </li>
            <li>
                <a href="./literasi-budaya-level.php" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
                    <span class="material-symbols-outlined text-custom1">auto_stories</span>
                    <span class="text-black font-medium">Literasi Budaya</span>
                </a>
            </li>
            <li>
                <a href="./kosakata-model1.php" class="flex items-center space-x-3 px-6 py-3 hover:bg-gray-100">
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
    <section class="main flex flex-col mx-auto w-4/5 flex-grow space-y-10">
    <header class="header items-center text-center">
        <h2 class="h2 font-bold text-gradient">
            Tantangan <?php echo htmlspecialchars($level); ?>
        </h2>
    </header>
    <div class="flex flex-col justify-center items-center space-y-4">
        <div class="p-6 bg-custom9 rounded-lg shadow-md w-full max-w-md text-center">
            <p class="text-lg font-semibold text-black">
                <?php echo htmlspecialchars($task_data['question']); ?>
            </p>
        </div>

        <div class="flex flex-col items-center space-y-4 w-full max-w-md">
            <button class="button-option text-white font-medium py-2 px-4 min-w-[60%] rounded-md hover:opacity-80" onclick="validateAnswer('A')">
                <?php echo htmlspecialchars($task_data['option_a']); ?>
            </button>
            <button class="button-option text-white font-medium py-2 px-4 min-w-[60%] rounded-md hover:opacity-80" onclick="validateAnswer('B')">
                <?php echo htmlspecialchars($task_data['option_b']); ?>
            </button>
        </div>

        <!-- Elemen untuk menampilkan pesan hasil jawaban -->
        <p id="feedback-message" class="text-lg font-semibold mt-4"></p>
    </div>
</section>

<footer class="flex items-center justify-between w-full px-4 py-4">
    <button id="kembali-button" class="button-custom2 text-sm mx-6">Kembali</button>
    <button id="selesai-button" class="button-custom2-red text-sm mx-6 cursor-pointer transition duration-300" disabled>Selesai</button>
</footer>

<script>
    const correctOption = "<?php echo htmlspecialchars($task_data['correct_option']); ?>";
    const selesaiButton = document.getElementById('selesai-button');
    const feedbackMessage = document.getElementById('feedback-message');
    const kembaliButton = document.getElementById('kembali-button');

    const profile = document.getElementById("profile-button");
    profile.addEventListener("click", () => {
        window.location.href = "./akun-pengguna.php";
    });

    // Fungsi validasi jawaban
    function validateAnswer(selectedOption) {
        if (selectedOption === correctOption) {
            feedbackMessage.textContent = "Keren! Jawaban Anda Benar";
            feedbackMessage.style.color = "green";
            selesaiButton.disabled = false; // Aktifkan tombol 'Selesai'
        } else {
            feedbackMessage.textContent = "Maaf, Silahkan Coba Lagi";
            feedbackMessage.style.color = "red";
            selesaiButton.disabled = true; // Nonaktifkan tombol 'Selesai'
        }
    }

    // Tombol kembali ke halaman materi
    kembaliButton.addEventListener('click', function () {
        window.location.href = './literasi-budaya-materi.php?level=2';
    });

    // Tombol selesai menuju halaman level
    selesaiButton.addEventListener('click', function () {
        window.location.href = './literasi-budaya-level.php';
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
                window.location.href = "./daftar.php";
            }
        } catch (error) {
            console.error("Fetch Error:", error);
            alert("Terjadi kesalahan saat memuat data pengguna.");
        }
    });

    function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    console.log("Toggling sidebar...");
    sidebar.classList.toggle("open");  // Toggle the 'open' class to show or hide the sidebar
    }
</script>
</body>
</html>
