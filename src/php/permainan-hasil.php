<?php
require 'connect.php';

// Header untuk memastikan respons adalah JSON jika diakses melalui backend
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['json'])) {
    header('Content-Type: application/json');

    try {
        // Query untuk mendapatkan data
        $stmt = $conn->prepare("
            SELECT 
                username, 
                COALESCE(profile_image, '../assets/pp.webp') AS profile_image, 
                score 
            FROM users 
            WHERE score IS NOT NULL 
            ORDER BY score DESC 
            LIMIT 10
        ");
        $stmt->execute();

        $rankings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Jika ada hasil, kirim JSON yang valid
        if ($rankings) {
            echo json_encode([
                'success' => true,
                'rankings' => $rankings
            ]);
        } else {
            // Jika tidak ada data, tetap kirim JSON yang valid
            echo json_encode([
                'success' => true,
                'rankings' => [] // Berikan array kosong
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Kesalahan saat mengambil data peringkat: ' . $e->getMessage()
        ]);
    }
    exit;
}
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div id="home" class="logo font-irish m-0 text-2xl">dialek.id</div>
        <div id="profile-button" class="flex items-center m-0 font-semibold text-custom2">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>

  


    <main class="flex flex-col items-center flex-grow">
    <!-- Judul -->
    <h1 class="text-4xl font-bold text-gradient text-center py-6 ">Sambung Kata</h1>

    
     
            <!-- Kosakata yang Digunakan -->
            <div class="col-span-2 p-4 rounded-lg shadow-lg w-7/12 " style="background-color: rgba(243, 245, 243, 0.4)">
                <div id="used-vocabulary" class="mt-4 overflow-y-auto h-72"></div>         
            </div>
             <!--button mulai-->
             <div class="flex justify-end mt-4 w-7/12">
                <button id="startbutton" class="bg-green-800 text-white py-2 px-6 rounded-full shadow-md hover:bg-green-700">
                   Mulai</button>
                   <button class="h-6/12 ml-2" id="infoButton">
                   <i class="fa-solid fa-circle-info"></i> 
                    </button>        
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
                    <li></li>
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
                <canvas id="accuracyChart" width="200" height="200"></canvas>
            </div>
            
            </div>

            <!-- Statistik Permainan -->
            <div class="rounded-lg shadow-lg p-4 col-span-2" style="background-color: rgba(243, 245, 243, 0.4)">

                <div class="flex justify-between mt-4">
                    <div class="rounded-lg p-4 mr-2" style="background-color: #daead1;">
                        <p class="text-xl font-bold text-green-900">Kata Berhasil:</p>
                        <p id="successful-words" class="text-2xl font-bold text-center text-green-900">0</p>
                    </div>
                    <div class="rounded-lg p-4" style="background-color: #daead1;">
                        <p class="text-xl font-bold text-green-900">Kata Gagal:</p>
                        <p id="failed-words" class="text-2xl font-bold text-center text-green-900">0</p>
                    </div>
                    <div class="rounded-lg p-4 ml-2" style="background-color: #daead1;">
                        <p class="text-xl font-bold text-green-900">Akurasi:</p>
                        <p id="accuracy" class="text-2xl font-bold text-center text-green-900">0%</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="info-popup" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50" style="display: none;">
            <div class="p-6 rounded-lg shadow-lg text-center" style="background-color: #CBEADF;">
                <p id="popup-message" class="text-lg font-bold mb-4">
                    Instruksi Permainan:
                    <ul class="text-left">
                        <li>1. Klik tombol "Mulai" untuk memulai permainan.</li>
                        <li>2. Ketikkan kata Batak Toba yang valid di kotak input:
                            <ul>
                                <li>Kata harus dimulai dengan huruf terakhir dari kata sebelumnya.</li>
                                <li>Kata harus berasal dari kosakata Batak Toba yang valid.</li>
                                <li>Kata tidak boleh pernah digunakan sebelumnya.</li>
                            </ul>
                        </li>
                        <li>3. Kata valid memberikan +10 poin, kata yang pernah digunakan memberikan +5 poin.</li>
                        <li>4. Setelah giliran Anda, Komputer akan mencoba menemukan kata yang valid.</li>
                        <li>5. Anda menang jika Komputer tidak dapat menemukan kata valid, atau skor Anda lebih tinggi saat waktu habis.</li>
                        <li>6. Waktu bermain adalah 75 detik.</li>
                    </ul>
                </p>
                <button onclick="closePopup()" class="bg-green-900 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">OK</button>
            </div>
        </div>

    </main>

    <script>
document.addEventListener("DOMContentLoaded", async () => {
    const dailyRankingsContainer = document.getElementById("daily-rankings");
    const totalScoreDisplay = document.getElementById("total-score");
    const successfulWordsDisplay = document.getElementById("successful-words");
    const failedWordsDisplay = document.getElementById("failed-words");
    const accuracyDisplay = document.getElementById("accuracy");
    const usedVocabularyContainer = document.getElementById("used-vocabulary");

    // Ambil data permainan dari local storage
    const successfulWordsCount = parseInt(localStorage.getItem("successfulWordsCount") || "0", 10);
    const failedWordsCount = parseInt(localStorage.getItem("failedWordsCount") || "0", 10);
    const totalScore = parseInt(localStorage.getItem("totalScore") || "0", 10);
    const usedVocabulary = JSON.parse(localStorage.getItem("usedVocabulary") || "[]");

    // Ambil username dari localStorage
    const username = localStorage.getItem("username");
    const profileImage = localStorage.getItem("profileImage") || "../assets/pp.webp";

    // Validasi login
    if (username) {
        document.getElementById("account-username").textContent = username;
    } else {
        alert("Silakan login terlebih dahulu.");
        window.location.href = "login.html";
        return;
    }

    const profile = document.getElementById("profile-button");
    const home = document.getElementById("home");

    home.addEventListener("click", () => {
        window.location.href = "./dashboard-batak.php";
    });

    profile.addEventListener("click", () => {
        window.location.href = "./AkunUser.php";
    });

    // Tampilkan statistik permainan
    totalScoreDisplay.textContent = totalScore;
    successfulWordsDisplay.textContent = successfulWordsCount;
    failedWordsDisplay.textContent = failedWordsCount;

    const accuracy = successfulWordsCount + failedWordsCount > 0
        ? Math.round((successfulWordsCount / (successfulWordsCount + failedWordsCount)) * 100)
        : 0;
    accuracyDisplay.textContent = `${accuracy}%`;

    // Render grafik akurasi
    const ctx = document.getElementById("accuracyChart").getContext("2d");
    new Chart(ctx, {
        type: "pie",
        data: {
            labels: ["Kata Berhasil", "Kata Gagal"],
            datasets: [{
                data: [successfulWordsCount, failedWordsCount],
                backgroundColor: ["#ADDFB3", "#62AA6D"],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: "bottom"
                }
            }
        }
    });

    // Tampilkan kosakata di UI
    if (usedVocabulary.length > 0) {
        usedVocabulary.forEach((word) => {
            const wordElement = document.createElement("p");
            wordElement.textContent = word;
            wordElement.classList.add("text-lg", "text-green-900");
            usedVocabularyContainer.appendChild(wordElement);
        });
    } else {
        usedVocabularyContainer.innerHTML = `<p class="text-lg text-red-500"></p>`;
    }

    // Tampilkan peringkat harian
    try {
        const response = await fetch("?json=true");
        const data = await response.json();

        if (data.success && Array.isArray(data.rankings) && data.rankings.length > 0) {
            dailyRankingsContainer.innerHTML = "";
            data.rankings.forEach((rank, index) => {
                const rankElement = document.createElement("li");
                rankElement.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <p class="font-semibold text-green-900 text-md">${index + 1}.</p>
                        <img src="${rank.profile_image}" alt="user" class="w-8 h-8 rounded-full" />
                        <p class="font-semibold text-green-900 text-md flex-1">${rank.username}</p>
                        <p class="font-semibold text-green-900 text-md">${rank.score}</p>
                    </div>`;
                dailyRankingsContainer.appendChild(rankElement);
            });
        } else {
            dailyRankingsContainer.innerHTML = `<li class="text-center text-green-900 font-semibold">Belum ada data peringkat. Jadilah yang pertama bermain!</li>`;
        }
    } catch (error) {
        console.error("Kesalahan saat memuat data peringkat:", error.message);
        dailyRankingsContainer.innerHTML = `<li class="text-center text-red-500 font-semibold">Kesalahan saat memuat data.</li>`;
    }

    // Tombol untuk mulai permainan
    document.getElementById("startbutton").addEventListener("click", () => {
        window.location.href = "permainan-model.php";
    });

    // Tombol info
    document.getElementById("infoButton").addEventListener("click", () => {
        document.getElementById("info-popup").style.display = "flex";
    });

    window.closePopup = () => {
        document.getElementById("info-popup").style.display = "none";
    };

    // Bersihkan localStorage setelah ditampilkan
    localStorage.removeItem("successfulWordsCount");
    localStorage.removeItem("failedWordsCount");
    localStorage.removeItem("totalScore");
    localStorage.removeItem("usedVocabulary");
    
});

    </script>
</body>
</html>
