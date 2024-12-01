<?php
session_start();
include('connect.php');

// Mengambil waktu mulai jika belum ada
if (!isset($_SESSION['start_time'])) {
    $_SESSION['start_time'] = time();
}

// Waktu batas 1 menit
$time_limit = 60;

// Menghitung waktu yang sudah berlalu
$elapsed_time = time() - $_SESSION['start_time'];

// Mengecek apakah waktu habis
if ($elapsed_time > $time_limit) {
    $message = "Waktu habis! Silakan coba lagi.";
    $message_class = 'text-red-600';
    session_destroy(); // Menghancurkan sesi setelah waktu habis
} else {
    $message_class = 'text-green-600';
}

// Variabel untuk pesan hasil jawaban
$answer_message = '';
$answer_message_class = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_word = $_POST['user_word'] ?? '';
    $correct_word = $_POST['correct_word'] ?? '';

    if (strtolower(trim($user_word)) === strtolower(trim($correct_word))) {
        $answer_message = "Keren! Jawaban Benar!";
        $answer_message_class = 'text-green-600';
    } else {
        $answer_message = "Maaf! Jawaban Salah! Coba lagi.";
        $answer_message_class = 'text-red-600';
    }
}

// Cek apakah ada permintaan untuk mengganti kata
if (isset($_GET['refresh'])) {
    try {
        $query = $conn->query("SELECT word, hint FROM words_kosakata ORDER BY RANDOM() LIMIT 1");
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $shuffledWord = str_shuffle($result['word']);
            echo json_encode(['success' => true, 'word' => $shuffledWord, 'hint' => $result['hint']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Kata tidak ditemukan.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// Ambil username dari session jika tersedia
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Pengguna Tidak Dikenal';

// Ambil kata acak dari database
$query = $conn->query("SELECT word, hint FROM words_kosakata ORDER BY RANDOM() LIMIT 1");
$word_data = $query->fetch(PDO::FETCH_ASSOC);
$word = $word_data['word'] ?? 'kosakata';
$hint = $word_data['hint'] ?? 'tidak ada petunjuk';
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
            <p id="account-username" class="px-4 text-xl">memuat...</p>
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
                <?php if (!empty($message) && isset($_POST['user_word'])): ?>
                    <p class="text-lg <?php echo $message_class; ?> mb-4"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
                
                <!-- Tampilkan pesan hasil jawaban hanya jika ada jawaban -->
                <?php if ($answer_message): ?>
                    <p class="text-lg <?php echo $answer_message_class; ?> mb-4"><?php echo htmlspecialchars($answer_message); ?></p>
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
    <footer class="flex items-center justify-end w-full px-4 py-4">
        <a href="./dashboard-batak.php" class="button-custom2-red text-sm mx-6 cursor-pointer transition duration-300">Keluar</a>
    </footer>
    <script>
        const home = document.querySelector('.logo');
        home.addEventListener("click", () => {
            window.location.href = "./dashboard-batak.php";
        });
        const refreshBtn = document.getElementById("refresh-word");
        const timerElement = document.getElementById("timer");
        let timeLeft = <?php echo $time_limit; ?>;

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
        if (firebaseUid) {
            try {
                const response = await fetch(window.location.href, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ firebase_uid: firebaseUid })
                });

                const result = await response.json();
                if (result.success) {
                    document.getElementById("account-username").textContent = result.user.username || 'Pengguna Tidak Dikenal';
                } else {
                    alert("Gagal memuat data pengguna.");
                    window.location.href = "./masuk.php"; // Arahkan ke halaman login jika gagal
                }
            } catch (error) {
                console.error("Error:", error);
                alert("Kesalahan memuat data.");
            }
        } else {
            alert("Pengguna belum login, silakan login terlebih dahulu.");
            window.location.href = "./masuk.php"; // Arahkan ke halaman login jika belum login
        }

        const home = document.getElementById("home");
        home.addEventListener("click", () => {
            window.location.href = "./dashboard-batak.php";
        });

        const profile = document.getElementById("profile-button");
        profile.addEventListener("click", () => {
            window.location.href = "./akun-pengguna.php";
        });
    });

    </script>
</body>
</html>
