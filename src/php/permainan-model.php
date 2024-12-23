<?php
require 'connect.php';

function getBatakWords($conn)
{
    try {
        $stmt = $conn->prepare("SELECT word FROM batak_words");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        return null;
    }
}

function updateScore($conn, $firebaseUid, $score)
{
    try {
        $stmt = $conn->prepare("UPDATE users SET total_score = total_score + :score WHERE firebase_uid = :firebase_uid");
        $stmt->execute(['score' => $score, 'firebase_uid' => $firebaseUid]);
        
        // Tambahkan log
        error_log("Update Score - UID: $firebaseUid, Skor: $score, Rows Affected: " . $stmt->rowCount());
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        error_log("Error Update Score: " . $e->getMessage());
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['json'])) {
    header("Content-Type: application/json");
    $words = getBatakWords($conn);
    if ($words) {
        echo json_encode(['success' => true, 'words' => $words]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Kesalahan mengambil kata Batak.']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['json'])) {
    header("Content-Type: application/json");
    $words = getBatakWords($conn);
    if ($words) {
        echo json_encode(['success' => true, 'words' => $words]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Kesalahan mengambil kata Batak.']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['score'])) {
        $firebaseUid = $data['firebase_uid'] ?? null;
        $score = (int) $data['score'];

        if (!$firebaseUid || !$score) {
            echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
            exit;
        }

        if (updateScore($conn, $firebaseUid, $score)) {
            echo json_encode(['success' => true, 'message' => 'Skor berhasil diperbarui']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui skor']);
        }
        exit;
    } elseif (isset($data['firebase_uid'])) {
        $firebaseUid = $data['firebase_uid'];
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
        } else {
            echo json_encode(["success" => false, "message" => "Firebase UID tidak ditemukan."]);
        }
        exit;
    }
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
    <style>
        .used-word {
            background-color: #5E9B8B;
            color: white;
            padding: 6px;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-custom-radial font-inter flex flex-col min-h-screen">

    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div class="logo font-irish m-0 text-2xl">dialek.id</div>
        <div class="flex items-center m-0 font-semibold text-custom2">
            <p id="account-username" class="px-4 text-xl">loading...</p>
            <i class="fa-solid fa-user text-2xl"></i>
        </div>
    </nav>

    <main class="flex flex-col items-center flex-grow">
        <h1 class="text-4xl font-bold text-gradient">Sambung Kata</h1>

        <div class="text-4xl py-6 text-green-900" id="timer" style="font-weight: bold;">01:15</div>

        <div class="flex space-x-24 items-start">
            <div class="p-4 rounded-lg shadow-lg w-64" style="background-color: rgba(218, 230, 217, 0.4)">
                <p class="font-extrabold text-xl text-green-900">Kamu</p>
                <div class="text-green-900 px-2 text-2xl font-bold mb-4 w-1/3" id="player-score" style="background-color: #acd3c6;">0</div>
                <input type="text" id="word-input" placeholder="Masukkan kata" class="w-full p-2 mb-2 rounded-lg border border-gray-300 focus:outline-none" />
                <p id="error-message" class="text-red-500 text-sm hidden">Masukkan kata yang valid!</p>
                <div id="player-words" class="mt-4 text-sm min-h-7"></div>
            </div>

            <div class="p-4 rounded-lg shadow-lg w-64" style="background-color: rgba(218, 230, 217, 0.4)">
                <p class="font-extrabold text-xl text-green-900">Dia</p>
                <div class="text-green-900 px-2 text-2xl font-bold mb-4 w-1/3" id="ai-score" style="background-color: #acd3c6;">0</div>
                <input type="text" disabled placeholder="Menunggu giliran..." class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 cursor-not-allowed" />
                <div id="ai-words" class="min-h-7 mt-4 text-sm"></div>
            </div>
        </div>

        <div id="popup" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50" style="display: none;">
            <div class="p-6 rounded-lg shadow-lg text-center" style="background-color: #CBEADF;">
                <p id="popup-message" class="text-lg font-bold mb-4"></p>
                <button id="popup-close" class="bg-green-900 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">OK</button>
            </div>
        </div>
    </main>

    <script>
         document.addEventListener("DOMContentLoaded", async () => {
            const wordInput = document.getElementById("word-input");
            const playerWords = document.getElementById("player-words");
            const aiWords = document.getElementById("ai-words");
            const errorMessage = document.getElementById("error-message");
            const playerScoreDisplay = document.getElementById("player-score");
            const aiScoreDisplay = document.getElementById("ai-score");
            const timerDisplay = document.getElementById("timer");
            const firebaseUid = localStorage.getItem("firebase_uid");
            const accountUsername = document.getElementById("account-username");

            let playerScore = 0;
            let aiScore = 0;
            let lastWord = "";
            let usedWords = new Set();
            let timer = 75;
            let countdown;
            let playerTurn = true;
            let failedWords = 0;
            let batakTobaWords = [];


            if (firebaseUid) {
            try {
                const response = await fetch(window.location.href, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ firebase_uid: firebaseUid }),
                });

                const result = await response.json();
                if (result.success && result.user.username) {
                    accountUsername.textContent = `${result.user.username}`;
                } else {
                    accountUsername.textContent = "Gagal memuat username.";
                }
            } catch (error) {
                console.error("Kesalahan memuat pengguna:", error);
                accountUsername.textContent = "Kesalahan memuat.";
            }
        } else {
            alert("Silakan login terlebih dahulu.");
            window.location.href = "login.php";
        }

            try {
                const response = await fetch("?json=true");
                const data = await response.json();
                if (data.success) {
                    batakTobaWords = data.words.map(word => word.toLowerCase());
                } else {
                    alert("Gagal memuat kata Batak: " + data.message);
                    return;
                }
            } catch (error) {
                alert("Terjadi kesalahan saat memuat kata Batak: " + error.message);
                return;
            }

            function updateScores() {
                playerScoreDisplay.textContent = playerScore;
                aiScoreDisplay.textContent = aiScore;
            }

            function updateTimerDisplay() {
                const minutes = Math.floor(timer / 60);
                const seconds = timer % 60;
                timerDisplay.textContent = `${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
            }

            function startTimer() {
                countdown = setInterval(() => {
                    timer--;
                    updateTimerDisplay();
                    if (timer <= 0) {
                        clearInterval(countdown);
                        endGame(`Waktu habis! Skor akhir - Kamu: ${playerScore}, Dia: ${aiScore}`);
                    }
                }, 1000);
            }

            async function endGame(message) {
                wordInput.disabled = true;
                clearInterval(countdown);
                showResultPopup(message);

                localStorage.setItem("successfulWordsCount", playerScore / 10);
                localStorage.setItem("failedWordsCount", failedWords);
                localStorage.setItem("totalScore", playerScore);
                await updateScore(playerScore);
            }

            async function updateScore(score) {
            const firebaseUid = localStorage.getItem("firebase_uid");
            if (!firebaseUid) {
                console.error("Firebase UID tidak ditemukan.");
                return;
            }

            try {
                const response = await fetch("", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ firebase_uid: firebaseUid, score }),
                });

                const result = await response.json();
                if (result.success) {
                    console.log("Skor berhasil diperbarui.");
                } else {
                    console.error("Gagal memperbarui skor:", result.message);
                }
            } catch (error) {
                console.error("Kesalahan saat memperbarui skor:", error.message);
            }
        }

            function showResultPopup(message) {
                document.getElementById("popup-message").textContent = message;
                document.getElementById("popup").style.display = "flex";
            }

            document.getElementById("popup-close").addEventListener("click", () => {
                document.getElementById("popup").style.display = "none";
                window.location.href = "./permainan-hasil.php";
            });

            function isValidWord(word) {
            const lowercaseWord = word.toLowerCase();
            if (!batakTobaWords.includes(lowercaseWord)) return false; 
            if (usedWords.has(lowercaseWord)) return "reused"; 
            if (lastWord && lowercaseWord[0] !== lastWord.slice(-1)) return false; 
            return "new";
        }

            function handlePlayerTurn() {
            const word = wordInput.value.trim().toLowerCase();
            wordInput.value = "";
            errorMessage.classList.add("hidden"); 

            const validity = isValidWord(word);
            if (!validity ) {
                errorMessage.textContent ="Masukkan kata yang valid!";
                errorMessage.classList.remove("hidden");
                failedWords++; 
                return;
            }

            function savePlayerWordToLocalStorage(word) {
            const usedVocabulary = JSON.parse(localStorage.getItem("usedVocabulary")) || [];
            usedVocabulary.push(word);
            localStorage.setItem("usedVocabulary", JSON.stringify(usedVocabulary));
        }

            const wordElement = document.createElement("div");
            wordElement.textContent = word;
            wordElement.classList.add("used-word");
            playerWords.appendChild(wordElement);

            savePlayerWordToLocalStorage(word);

            playerScore += validity === "new" ? 10 : 5;
            usedWords.add(word); 
            lastWord = word; 

            playerTurn = false; 
            setTimeout(handleAITurn, 1000); 
            updateScores(); 
        }

            function handleAITurn() {
                const aiWord = generateAIWord();
                if (aiWord) {
                    aiScore += usedWords.has(aiWord) ? 5 : 10;
                    usedWords.add(aiWord);

                    const aiWordElement = document.createElement("div");
                    aiWordElement.textContent = aiWord;
                    aiWordElement.classList.add("used-word");
                    aiWords.appendChild(aiWordElement);

                    lastWord = aiWord;
                } else {
                    endGame("Dia tidak bisa menemukan kata yang valid! Kamu menang!");
                    return;
                }

                playerTurn = true;
                updateScores();
            }

            function generateAIWord() {
                const validWords = batakTobaWords.filter(word => word.startsWith(lastWord.slice(-1)));
                return validWords.length ? validWords[Math.floor(Math.random() * validWords.length)] : null;
            }

            wordInput.addEventListener("keydown", event => {
                if (event.key === "Enter" && playerTurn) handlePlayerTurn();
            });

            startTimer();
        });
    </script>
</body>

</html>
