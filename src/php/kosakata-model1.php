<?php
session_start();
include('connect.php');

if (!isset($_SESSION['start_time'])) {
    $_SESSION['start_time'] = time();
}

$time_limit = 60;

// Menghitung waktu yang sudah berlalu
$elapsed_time = time() - $_SESSION['start_time'];

// Mengecek apakah waktu habis
if ($elapsed_time > $time_limit) {
    $message = "Waktu habis! Silakan coba lagi.";
    $message_class = 'text-red-600';
    session_destroy();
} else {
    $message = "Tantangan masih aktif!";
    $message_class = 'text-green-600';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_word'])) {
    $userWord = filter_input(INPUT_POST, 'user_word', FILTER_SANITIZE_STRING);

    $query = $conn->prepare("SELECT * FROM words_kosakata WHERE word = :user_word LIMIT 1");
    $query->bindParam(':user_word', $userWord);
    $query->execute();
    $wordData = $query->fetch(PDO::FETCH_ASSOC);

    if ($wordData) {
        $response = [
            'success' => true,
            'message' => 'Jawaban Anda benar!'
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Jawaban Anda salah, coba lagi!'
        ];
    }

    echo json_encode($response);
    exit;
}

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

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Pengguna Tidak Dikenal';

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
        let timeLeft = <?php echo $time_limit; ?>;

        const countdown = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(countdown);
                document.querySelector('input[name="user_word"]').disabled = true;
                alert("Waktu habis!");
            } else {
                timerElement.textContent = timeLeft;
                timeLeft--;
            }
        }, 1000);

        refreshBtn.addEventListener("click", () => {
            fetch('get_word.php')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('.word-custom').textContent = data.word.split('').sort(() => Math.random() - 0.5).join('');
                    document.querySelector('.hint span').textContent = data.hint;
                    document.querySelector('input[name="correct_word"]').value = data.word;
                    timeLeft = <?php echo $time_limit; ?>;
                    timerElement.textContent = timeLeft;
                })
                .catch(error => console.error('Error fetching new word:', error));
        });

        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("open");
        }

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
                    document.getElementById("account-username").textContent = `${result.user.username}`;
                } else {
                    alert("Gagal memuat data pengguna.");
                    window.location.href = "./masuk.php";
                }
            } catch (error) {
                console.error("Error:", error);
                alert("Kesalahan memuat data.");
            }
        });

        const firebaseUid = localStorage.getItem("firebase_uid") || null;

        if (firebaseUid) {
            // Fetch data pengguna
        } else {
            alert("Pengguna belum login, silakan login terlebih dahulu.");
            window.location.href = "./masuk.php";
        }
    </script>
</body>
</html>
