<?php
require 'connect.php'; // Menghubungkan dengan database

// Mengambil data kata secara acak dari tabel
try {
    $query = $conn->query("SELECT word, hint FROM words_kosakata ORDER BY RANDOM() LIMIT 1");
    $result = $query->fetch(PDO::FETCH_ASSOC);
    $word = $result['word'];
    $hint = $result['hint'];
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
    <section class="main flex flex-col mx-auto w-4/5 items-center flex-grow space-y-16">
        <div id="soal" class="flex flex-col items-center p-4 min-h-[500px] h-full w-full">
            <div class="flex justify-center items-center font-bold my-8">
                <p class="text-4xl text-custom2">Belajar Kosakata</p>
            </div>
            <div class="content-custom">
                <p class="word-custom"><?php echo str_shuffle($word); ?></p>
                <div class="detail-custom my-5">
                    <p class="hint text-lg mb-2">Petunjuk : <span><?php echo htmlspecialchars($hint); ?></span></p>
                    <p class="time text-lg">Sisa Waktu : <span><b class="font-bold">30</b>d</span></p>
                </div>
                <form method="post" action="kosakataCheck.php">
                    <input type="text" name="user_word" placeholder="Masukkan kata yang sesuai" class="w-4/5 h-14 py-4 px-4 rounded-lg border-solid border-black outline-none">
                    <input type="hidden" name="correct_word" value="<?php echo htmlspecialchars($word); ?>">
                    <div class="button flex flex-row text-sm space-x-4 w-4/5">
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
</body>
</html>
