<?php
require 'connect.php'; // Menghubungkan dengan database

// Initialize variables
$word = '';
$hint = '';
$message = '';
$message_class = ''; // Kelas CSS untuk warna pesan
$time_limit = 60; // Set the time limit in seconds

// Check if the form has been submitted to check the answer
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userWord = trim($_POST['user_word']);
    $correctWord = trim($_POST['correct_word']);

    // Check the user's input against the correct word
    if (empty($userWord)) {
        $message = "Masukkan kata yang akan diperiksa.";
        $message_class = 'text-yellow-600'; // Kuning untuk peringatan
    } elseif (strcasecmp($userWord, $correctWord) === 0) {
        $message = "Selamat! Jawaban Anda benar.";
        $message_class = 'text-green-600'; // Hijau untuk benar
    } else {
        $message = "Oops! Kata yang kamu masukkan salah.";
        $message_class = 'text-red-600'; // Merah untuk salah
    }
}

// Fetch a random word and hint from the database
try {
    $query = $conn->query("SELECT word, hint FROM words_kosakata ORDER BY RANDOM() LIMIT 1");
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $word = $result['word'];
        $hint = $result['hint'];
    }
} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}

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
    <title>Dialek.Id</title>
    <link rel="stylesheet" href="../styles/style2.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div id="home" class="logo font-irish m-0 text-2xl cursor-pointer" onclick="toggleSidebar()">dialek.id</div>
        <div id="profile-button" class="flex items-center m-0 font-semibold text-custom2 cursor-pointer">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>
    <section class="main flex flex-col mx-auto w-4/5 items-center flex-grow space-y-16">
        <div id="soal" class="flex flex-col items-center p-4 min-h-[500px] h-full w-full">
            <div class="flex justify-center items-center font-bold">
                <p class="text-4xl text-custom2">Belajar Kosakata</p>
            </div>
            <div class="content-custom">
                <!-- Tampilkan pesan jika ada -->
                <?php if (!empty($message)): ?>
                    <p class="text-lg <?php echo $message_class; ?> mb-4"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
                <!-- Tampilkan kata acak -->
                <p class="word-custom text-3xl font-bold"><?php echo str_shuffle($word); ?></p>
                <div class="detail-custom my-5">
                    <!-- Tampilkan petunjuk -->
                    <p class="hint text-lg mb-2">Petunjuk : <span><?php echo htmlspecialchars($hint); ?></span></p>
                    <p class="time text-lg">Sisa Waktu : <span><b class="font-bold" id="timer"><?php echo $time_limit; ?></b>s</span></p>
                </div>
                <form method="post" action="" class="flex flex-col items-center w-11/12">
                    <!-- Input jawaban -->
                    <input type="text" name="user_word" placeholder="Masukkan kata yang sesuai" 
                        class="w-11/12 h-14 py-4 px-4 rounded-lg border-solid border-black outline-none" required>
                    <!-- Simpan kata yang benar -->
                    <input type="hidden" name="correct_word" value="<?php echo htmlspecialchars($word); ?>">
                    <div class="button flex flex-row text-sm space-x-4 w-11/12">
                        <button type="button" id="refresh-word" class="refresh-word button-kosakata w-1/2 py-3 font-medium rounded-xl">Ganti Kata</button>
                        <button type="submit" class="check-word button-kosakata w-1/2 py-3 font-medium rounded-xl">Periksa</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <footer class="flex items-center justify-between w-full px-4 py-4">
        <a href="./dashboardBatak.html" class="button-custom2 text-sm mx-6">Kembali</a>
        <a href="./Kosakata2.html" class="button-custom2 text-sm mx-6">Selanjutnya</a>
    </footer>
    <script>
        const refreshBtn = document.getElementById("refresh-word");
        const timerElement = document.getElementById("timer");
        const profile = document.getElementById("profile-button");
        const home = document.getElementById("home");
        let timeLeft = <?php echo $time_limit; ?>;

        home.addEventListener("click", () => {
            window.location.href = "./dashboard-batak.php";
        });

        profile.addEventListener("click", () => {
            window.location.href = "./akun-pengguna.php";
        });

        const countdown = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(countdown);
                document.querySelector('input[name="user_word"]').disabled = true; // Disable input
                alert("Waktu habis!"); // Alert user
            } else {
                timerElement.textContent = timeLeft;
                timeLeft--;
            }
        }, 1000);

        refreshBtn.addEventListener("click", () => {
            // AJAX request to fetch a new word
            fetch('get_word.php')
                .then(response => response.json())
                .then(data => {
                    // Update the displayed word and hint
                    document.querySelector('.word-custom').textContent = data.word.split('').sort(() => Math.random() - 0.5).join('');
                    document.querySelector('.hint span').textContent = data.hint;
                    // Update the hidden input with the new correct word
                    document.querySelector('input[name="correct_word"]').value = data.word;
                    // Reset the timer
                    timeLeft = <?php echo $time_limit; ?>;
                    timerElement.textContent = timeLeft;
                })
                .catch(error => console.error('Error fetching new word:', error));
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
    </script>
</body>
</html>
