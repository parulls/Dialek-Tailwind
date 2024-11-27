<?php
require 'connect.php'; // Menghubungkan dengan database

// Initialize variables
$word = '';
$hint = '';
$message = '';
$time_limit = 60; // Set the time limit in seconds

// Check if the form has been submitted to check the answer
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userWord = trim($_POST['user_word']);
    $correctWord = trim($_POST['correct_word']);

    // Check the user's input against the correct word
    if (empty($userWord)) {
        $message = "Masukkan kata yang akan diperiksa.";
    } elseif (strcasecmp($userWord, $correctWord) === 0) {
        $message = "Selamat! Jawaban anda benar.";
    } else {
        $message = "Oops! Kata yang kamu masukkan salah.";
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
    <nav class="flex items-center justify-between w-full p-12">
        <div class="logo font-irish m-0 text-2xl">dialek.id</div>
        <div class="flex items-center m-0 font-semibold text-custom2">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>
    <section class="main flex flex-col mx-auto w-full items-center flex-grow space-y-16">
        <div id="soal" class="flex flex-col items-center p-4 min-h-[500px] h-full w-full">
            <div class="flex justify-center items-center font-bold my-8">
                <p class="text-4xl text-custom2">Belajar Kosakata</p>
            </div>
            <div class="content-custom">
                <!-- Tampilkan pesan jika ada -->
                <?php if (!empty($message)): ?>
                    <p class="text-lg text-red-600 mb-4"><?php echo $message; ?></p>
                <?php endif; ?>
                <!-- Tampilkan kata acak -->
                <p class="word-custom text-3xl font-bold"><?php echo str_shuffle($word); ?></p>
                <div class="detail-custom my-5">
                    <!-- Tampilkan petunjuk -->
                    <p class="hint text-lg mb-2">Petunjuk : <span><?php echo htmlspecialchars($hint); ?></span></p>
                    <p class="time text-lg">Sisa Waktu : <span><b class="font-bold" id="timer"><?php echo $time_limit; ?></b>s</span></p>
                </div>
                <form method="post" action="">
                    <!-- Input jawaban -->
                    <input type="text" name="user_word" placeholder="Masukkan kata yang sesuai" 
                        class="w-full h-14 py-4 px-4 rounded-lg border border-black outline-none" required>
                    <!-- Simpan kata yang benar -->
                    <input type="hidden" name="correct_word" value="<?php echo htmlspecialchars($word); ?>">
                    <div class="button flex flex-row text-sm space-x-4 w-full mt-4">
                        <button type="button" id="refresh-word" class="refresh-word button-kosakata w-1/2 py-3 font-medium rounded-xl bg-blue-500 text-white">Ganti Kata</button>
                        <button type="submit" class="check-word button-kosakata w-1/2 py- 3 font-medium rounded-xl bg-green-500 text-white">Periksa</button>
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
    </script>
</body>
</html>