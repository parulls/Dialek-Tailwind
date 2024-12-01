<?php
include 'connect.php';

// Mendapatkan level dan ID task dari parameter URL
$level = isset($_GET['level']) ? intval($_GET['level']) : 1; // Default level 1 jika tidak ada parameter
$task_id = isset($_GET['task_id']) ? intval($_GET['task_id']) : 1; // Default task 1

// Query untuk mengambil data task berdasarkan level dan task_id
$query = "SELECT question, option_a, option_b, correct_option FROM literasi_tasks WHERE level_id = :level AND id = :task_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':level', $level, PDO::PARAM_INT);
$stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
$stmt->execute();
$task_data = $stmt->fetch();

// Jika data task tidak ditemukan, arahkan ke halaman level
if (!$task_data) {
    header("Location: literasi-budaya-level.php");
    exit;
}

// Menentukan task dan level sebelumnya
$prevTask = $task_id > 1 ? $task_id - 1 : 1; // Jika task_id <= 1 tetap di task pertama
$prevLevel = $level > 1 ? $level - 1 : 1; // Level minimal adalah 1

// Menentukan task selanjutnya
$nextTask = $task_id + 1;

// Periksa apakah task berikutnya ada di database
$query_next_task = "SELECT COUNT(*) FROM literasi_tasks WHERE level_id = :level AND id = :nextTask";
$stmt_next_task = $conn->prepare($query_next_task);
$stmt_next_task->bindParam(':level', $level, PDO::PARAM_INT);
$stmt_next_task->bindParam(':nextTask', $nextTask, PDO::PARAM_INT);
$stmt_next_task->execute();
$nextTaskExists = $stmt_next_task->fetchColumn() > 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dialek.Id - Pertanyaan</title>
    <link rel="stylesheet" href="../styles/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div id="home" class="logo font-irish m-0 text-2xl cursor-pointer">dialek.id</div>
        <div id="profile-button" class="flex items-center m-0 font-semibold text-custom2 cursor-pointer">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i>
        </div>
    </nav>

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
    <button id="selesai-button" class="button-custom2-red text-sm mx-6" disabled>Selesai</button>
</footer>

<script>
    const correctOption = "<?php echo htmlspecialchars($task_data['correct_option']); ?>";
    const selesaiButton = document.getElementById('selesai-button');
    const feedbackMessage = document.getElementById('feedback-message');
    const kembaliButton = document.getElementById('kembali-button');

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
        if (<?php echo $level; ?> === 2) {
            window.location.href = './literasi-budaya-materi.php?level=2';
        } else {
            window.location.href = './literasi-budaya-materi.php?level=<?php echo $prevLevel; ?>';
        }
    });

    // Tombol selesai menuju halaman level
    selesaiButton.addEventListener('click', function () {
        window.location.href = './literasi-budaya-level.php';
    });
</script>
</body>
</html>