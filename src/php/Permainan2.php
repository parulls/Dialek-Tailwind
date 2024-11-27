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

    <!-- Back Button -->
    <button class="bg-green-800 hover:bg-green-700 text-white font-bold w-12 h-12 rounded-full flex items-center justify-center ml-7 mb-4" aria-label="Kembali">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>

    <main class="flex flex-col items-center flex-grow">
        <!-- Title -->
        <h1 class="text-4xl font-bold text-gradient">Sambung Kata</h1>

        <!-- Timer -->
        <div class="text-4xl py-6 text-green-900" id="timer" style="font-weight: bold;">01:15</div>

        <!-- Game Area -->
        <div class="flex space-x-8 items-start">
            <!-- Player Section -->
            <div class="p-4 rounded-lg shadow-lg w-64" style="background-color: rgba(218, 230, 217, 0.4)">
                <p class="font-extrabold text-xl text-green-900">Kamu</p>
                <div class="text-green-900 px-2 text-2xl font-bold mb-4 w-1/3" id="player-score" style="background-color: #acd3c6;">0</div>
                <input type="text" id="word-input" placeholder="Masukkan kata" class="w-full p-2 mb-2 rounded-lg border border-gray-300 focus:outline-none" />
                <p id="error-message" class="text-red-500 text-sm hidden">Masukkan kata yang valid!</p>
                <div id="player-words" class="mt-4 text-sm min-h-7"></div>
            </div>

            <!-- AI Section -->
            <div class="p-4 rounded-lg shadow-lg w-64" style="background-color: rgba(218, 230, 217, 0.4)">
                <p class="font-extrabold text-xl text-green-900">Dia</p>
                <div class="text-green-900 px-2 text-2xl font-bold mb-4 w-1/3" id="ai-score" style="background-color: #acd3c6;">0</div>
                <input type="text" disabled placeholder="Menunggu giliran..." class="w-full p-2 rounded-lg border border-gray-300 bg-gray-100 cursor-not-allowed" />
                <div id="ai-words" class="min-h-7 mt-4 text-sm"></div>
            </div>
        </div>

        <!-- Pop-up -->
        <div id="popup" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50" style="display: none;">
            <div class="p-6 rounded-lg shadow-lg text-center" style="background-color: #CBEADF;">
                <p id="popup-message" class="text-lg font-bold mb-4"></p>
                <button onclick="closePopup()" class="bg-green-900 hover:bg-green-800 text-white font-bold py-2 px-4 rounded">OK</button>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const wordInput = document.getElementById("word-input");
            const playerWords = document.getElementById("player-words");
            const aiWords = document.getElementById("ai-words");
            const errorMessage = document.getElementById("error-message");
            const playerScoreDisplay = document.getElementById("player-score");
            const aiScoreDisplay = document.getElementById("ai-score");
            const timerDisplay = document.getElementById("timer");
            document.querySelector("#popup button").addEventListener("click", closePopup);

            let playerScore = 0;
            let aiScore = 0;
            let lastWord = "";
            let usedWords = new Set();
            let timer = 75;
            let countdown;
            let playerTurn = true;

            const playerWordsList = [];
            const aiWordsList = [];

            const batakTobaWords = [
            "Ahu", "Au", "Ampun", "Holong", "Modom", "Karejo", "Alani", "Maridi", "Loja", "Las", "Ende", "Etek",
            "Mangan", "Sogot", "Adu", "Aek", "Etong", "Adong", "Sae", "Mardongan", "Mardalani", 
            "Gok", "Mekkel", "Barani", "Mabiar", "Uli", "Roa", "Parsimburu", "Sahata", "Burju", 
            "Pogos", "Hata", "Holit", "Gogo","Mangan", "Suan-suanan", "Pauli", "Sip", "Bagak", 
            "Mora", "Gokhon", "Naposo", "Muruk", "Ulu", "Bohi", "Baba", "Igung", "Pamereng", 
            "Simalolong", "Pinggol", "Osang", "Rungkung", "Tiput", "Pat", "Aha", "Ise", "Boasa", 
            "Songondia", "Boha", "Beha", "Sadihari", "Andigan", "Didia", "Oppung Boru", "Ompu", 
            "Oppung Doli", "Buas", "Bagak", "Datulang", "Dainang", "Damang", "Dahahang", "Daompung", 
            "Gok", "Gokhon", "Holong", "Karejo", "Jungkat", "Loja", "Las", "Mangan", "Maridi", 
            "Mardongan", "Mardalani", "Modom", "Mora", "Naposo", "Parmangan", "Parmuruk", 
            "Parsip", "Parila", "Parengkel", "Parbarani", "Parbiar", "Parkato", "Parhata", 
            "Pauli", "Pangalean", "Panarita", "Mabiae", "Mora", "Gokhon", "Roa", 
            "Manogot", "Arian", "Bodari", "Mangallang","Tugo", "Manjalo","Pamasu", 
            "Manginum", "Mangallang", "Marsuhap", "Mambasu","Tangan", 
            "Mambuat","Parbasuan", "Manapu","Jabu",  
            "Mambaen","Dohot", "Mangalap", "Mangulaho","Horbo",  
            "Mangalului", 
            "Manunggu","Singir", "Marsiruho", "Marsiriho", "Indahan", 
            "Aek", 
            "Mangarobus","Pasahat","Tona", "Paboahon","Habar", "Tongosan"
        ];


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

            function endGame(message) {
                wordInput.disabled = true;
                clearInterval(countdown);
                showResultPopup(message);

                // Update Local Storage with cumulative game data
                const successfulWords = playerWordsList.length;
                const failedWords = 0;
                const totalScore = playerScore; 

                // Simpan ke local storage untuk digunakan dalam Sebelumgame.html
                let usedVocabulary = JSON.parse(localStorage.getItem("usedVocabulary")) || [];
                usedVocabulary = usedVocabulary.concat(playerWordsList);

                localStorage.setItem("successfulWordsCount", successfulWords);
                localStorage.setItem("failedWordsCount", failedWords);
                localStorage.setItem("totalScore", totalScore);
                localStorage.setItem("usedVocabulary", JSON.stringify(usedVocabulary));
            }

            function showResultPopup(message) {
                document.getElementById("popup-message").textContent = message;
                document.getElementById("popup").style.display = "flex";
            }



            function showResultPopup(message) {
                document.getElementById("popup-message").textContent = message;
                document.getElementById("popup").style.display = "flex";
            }

            function closePopup() {
                document.getElementById("popup").style.display = "none";
                window.location.href = "Sebelumgame.php";
            }

            function resetGame() {
                playerScore = 0;
                aiScore = 0;
                lastWord = "";
                usedWords.clear();
                timer = 75;
                playerWords.innerHTML = "";
                aiWords.innerHTML = "";
                errorMessage.classList.add("hidden");
                updateScores();
                updateTimerDisplay();
                wordInput.disabled = false;
                startTimer();
            }

            function isValidWord(word) {
                const lowercaseWord = word.toLowerCase();
                const isInDataset = batakTobaWords.some(w => w.toLowerCase() === lowercaseWord);
                if (!isInDataset) return false;
                if (usedWords.has(lowercaseWord)) return "reused";
                if (lastWord && lowercaseWord[0] !== lastWord.slice(-1)) return false;
                return "new";
            }

            function handlePlayerTurn() {
            const word = wordInput.value.trim();
            wordInput.value = "";
            errorMessage.classList.add("hidden");

            const validity = isValidWord(word);
            if (!validity) {
                errorMessage.textContent = "Masukkan kata yang valid!";
                errorMessage.classList.remove("hidden");
                return;
            }

            // Skor pemain
            playerScore += validity === "new" ? 10 : 5;
            usedWords.add(word.toLowerCase());
            playerWordsList.push(word);
            lastWord = word.toLowerCase();

            const wordElement = document.createElement("div");
            wordElement.textContent = word;
            wordElement.classList.add("used-word");
            playerWords.appendChild(wordElement);

            playerTurn = false;
            setTimeout(aiTurn, 1000);
            updateScores();
        }

        function aiTurn() {
            const aiWord = generateAIWord();
            if (aiWord) {
                const validity = usedWords.has(aiWord.toLowerCase()) ? "reused" : "new";
                
                // Skor AI berdasarkan kata baru atau lama
                aiScore += validity === "new" ? 10 : 5;
                
                usedWords.add(aiWord.toLowerCase());
                aiWordsList.push(aiWord);
                lastWord = aiWord.toLowerCase();

                const aiWordElement = document.createElement("div");
                aiWordElement.textContent = aiWord;
                aiWordElement.classList.add("used-word");
                aiWords.appendChild(aiWordElement);
            } else {
                endGame("Dia tidak bisa menemukan kata yang valid! Kamu menang!");
                return;
            }
            playerTurn = true;
            updateScores();
        }

        function generateAIWord() {
            const validWords = batakTobaWords.filter(word => {
                return word.toLowerCase().startsWith(lastWord.slice(-1));
            });
            return validWords.length ? validWords[Math.floor(Math.random() * validWords.length)] : null;
        }

        wordInput.addEventListener("keydown", (event) => {
            if (event.key === "Enter") {
                handlePlayerTurn();
            }
        });

        startTimer();
    });
    </script>
</body>

</html>