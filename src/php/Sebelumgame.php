<?php
    require '.functions.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dialek.id</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="flex items-center justify-between w-full px-4 py-4">
        <div class="logo font-irish text-2xl">dialek.id</div>
        <div class="flex items-center font-semibold">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl text-custom2"></i>
        </div>
    </nav>

    <!-- Tombol Kembali -->
    <button class="bg-green-800 hover:bg-green-700 text-white font-bold w-12 h-12 rounded-full flex items-center justify-center ml-7 mb-4" aria-label="Kembali">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>


    <main class="flex flex-col items-center flex-grow">
    <!-- Judul -->
    <h1 class="text-4xl font-bold text-gradient text-center py-6 ">Sambung Kata</h1>

    
     
            <!-- Kosakata yang Digunakan -->
            <div class="col-span-2 p-4 rounded-lg shadow-lg w-7/12 " style="background-color: rgba(243, 245, 243, 0.4)">
                <div id="used-vocabulary" class="mt-4 overflow-y-auto h-72"></div>

             
            </div>
             <!--button mulai-->
             <div class="justify-end mt-4">
                <button id="startbutton" class="bg-green-800 text-white py-2 px-6 rounded-full shadow-md hover:bg-green-700">
                   Mulai</button>
            </div>

          
            <div class="grid grid-cols-2 gap-8 w-7/12 py-6">
            <!-- Peringkat Harian -->
            <div class="rounded-lg shadow-lg p-4" style="background-color: rgba(243, 245, 243, 0.4)">
                <h2 class="text-2xl font-extrabold text-center" 
                style="background-image: linear-gradient(180deg, #14856D 0%, #067A32 50%, #16572B 87%, #1B4B29 100%);
                 -webkit-background-clip: text;
                color: transparent;">
                Peringkat Harian
                </h2>
                <ul id="daily-rankings" class="mt-4">
                    <li>
                        <div class="flex items-center space-x-2">
                            <p class="font-semibold text-green-900 text-md " >1.</p>
                            <img src="https://img.icons8.com/color/48/000000/user.png" alt="user" class="w-8 h-8 rounded-full mr-4 ml-4" />
                            <p class="font-semibold text-green-900 flex-grow text-md md:text-base flex-1">username</p>
                        </div></li>
                    <li><div class="flex items-center space-x-2 space-y-2">
                        <p class="font-semibold text-green-900 text-md " >2.</p>
                        <img src="https://img.icons8.com/color/48/000000/user.png" alt="user" class="w-8 h-8 rounded-full mr-4 ml-4" />
                        <p class="font-semibold text-green-900 flex-grow text-md md:text-base flex-1">username</p>
                    </div></li>
                    <li><div class="flex items-center space-x-2 space-y-2">
                        <p class="font-semibold text-green-900 text-md " >3.</p>
                        <img src="https://img.icons8.com/color/48/000000/user.png" alt="user" class="w-8 h-8 rounded-full mr-4 ml-4" />
                        <p class="font-semibold text-green-900 flex-grow text-md md:text-base flex-1">username</p>
                    </div></li>
                </ul>
            </div>

            <!-- Skor -->
            <div class="rounded-lg shadow-lg p-4" style="background-color: rgba(243, 245, 243, 0.4)">
                <div class="flex">
                <h2 class="text-2xl font-extrabold" style="background-image: linear-gradient(180deg, #14856D 0%, #067A32 50%, #16572B 87%, #1B4B29 100%);
                -webkit-background-clip: text;
               color: transparent;">Skor :</h2>
                <div class=" px-2 text-2xl font-extrabold" style="background-image: linear-gradient(180deg, #14856D 0%, #067A32 50%, #16572B 87%, #1B4B29 100%);
                -webkit-background-clip: text;
               color: transparent;" id="total-score">0</div>
            </div>
            <div class="py-3">
                <img src="../assets/f8f5ff28-84c5-4fa3-80d1-32a40fad6c61.webp">

            </div>
            
            </div>

            <!-- Statistik Permainan -->
            <div class="rounded-lg shadow-lg p-4 col-span-2" style="background-color: rgba(243, 245, 243, 0.4)">
                <div class="flex justify-between mt-4">
                    <div class="rounded-lg p-4" style="background-color: #daead1;">
                        <p class="text-xl font-bold text-green-900">Kata Berhasil:</p>
                        <p id="successful-words" class="text-2xl font-bold text-center text-green-900">0</p>
                    </div>
                    <div class="rounded-lg p-4" style="background-color: #daead1;">
                        <p class="text-xl font-bold text-green-900">Kata Gagal:</p>
                        <p id="failed-words" class="text-2xl font-bold text-center text-green-900">0</p>
                    </div>
                    <div class="rounded-lg p-4" style="background-color: #daead1;">
                        <p class="text-xl font-bold text-green-900">Akurasi:</p>
                        <p id="accuracy" class="text-2xl font-bold text-center text-green-900">0%</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Ambil data permainan dari local storage
        const successfulWordsCount = parseInt(localStorage.getItem('successfulWordsCount') || '0', 10);
        const failedWordsCount = parseInt(localStorage.getItem('failedWordsCount') || '0', 10);
        const totalScore = parseInt(localStorage.getItem('totalScore') || '0', 10);
        const usedVocabulary = JSON.parse(localStorage.getItem('usedVocabulary') || '[]');

        // Hitung akurasi
        const totalAttempts = successfulWordsCount + failedWordsCount;
        const accuracy = totalAttempts > 0 ? Math.round((successfulWordsCount / totalAttempts) * 100) : 0;

        // Tampilkan data permainan di halaman
        document.getElementById("total-score").textContent = totalScore;
        document.getElementById("successful-words").textContent = successfulWordsCount;
        document.getElementById("failed-words").textContent = failedWordsCount;
        document.getElementById("accuracy").textContent = accuracy + "%";

        // Tampilkan kosakata yang digunakan
        const usedVocabularyContainer = document.getElementById("used-vocabulary");
        usedVocabulary.forEach(word => {
            const wordElement = document.createElement("p");
            wordElement.textContent = word;
            wordElement.className = "text-lg text-green-900";
            usedVocabularyContainer.appendChild(wordElement);
        });

        const mulai = document.getElementById("startbutton");

        startbutton.addEventListener("click", () => {
            window.location.href = "Permainan.html";
        });

        // Hapus data dari local storage setelah ditampilkan
        localStorage.removeItem('successfulWordsCount');
        localStorage.removeItem('failedWordsCount');
        localStorage.removeItem('totalScore');
        localStorage.removeItem('usedVocabulary');
    </script>

</body>
</html>
