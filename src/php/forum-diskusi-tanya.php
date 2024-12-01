<?php 
    include("connect.php");

       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header("Content-Type: application/json");
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
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div  id="home" class="logo font-irish m-0 text-2xl">dialek.id</div>
        <div id="profile-button" class="flex items-center m-0 font-semibold text-custom2">
            <p id="account-username" class="px-4 text-xl">memuat...</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>

    <main class="flex flex-col items-center">
        <div class="flex items-center w-full justify-center mb-10">
            <header class="header text-center">
                <h1 class="h1 font-bold text-gradient">
                  Wadah Diskusi
                </h1>
            </header>
        </div>

        <div class="flex items-start w-full px-12 relative"> 
            <div class="mx-auto p-4 rounded-lg box-custom1 w-10/12 md:w-8/12 lg:w-6/12" style="background-color: rgba(212, 229, 221, 40);"> 
                <div class="flex space-x-2 mb-4">
                    <div id="bahasaBatak" class="button-custom flex items-center justify-center hover:opacity-100 text-sm py-2 px-4 rounded-full max-w-44 bg-blue-500 text-white">Bahasa Batak Toba </div>
                </div>

                <label for="pertanyaanInput" class="text-lg font-semibold text-green-800">Tulis pertanyaanmu:</label>
                <textarea id="pertanyaanInput" class="w-full p-3 rounded-lg border-none focus:outline-none text-white" rows="5" style="background-color: #4C968B;"></textarea> 

                <div class="flex justify-end mt-2">
                    <button id="tanyaButton" class="bg-green-800 text-white py-2 px-4 rounded-full shadow-md hover:bg-green-700">Tanya</button> 
                </div>
            </div>
        </div>

        <div class="mt-6 w-10/12 md:w-8/12 lg:w-6/12"> 
            <div class="flex justify-between items-center mb-2"> 
                <h2 class="text-lg font-semibold text-green-800">Pertanyaan Lain:</h2>
                <div class="flex space-x-1">
                    <button id="popularButton" class="button-custom2 text-white">Popular</button>
                    <button id="terbaruButton" class="button-custom2 text-white">Terbaru</button>
                </div>
            </div>

            <div id="pertanyaanList" class="space-y-2"></div> 
        </div>
    </main>

    <script>
        const kembaliButton = document.querySelector('button.bg-green-800');
        kembaliButton.addEventListener('click', () => {
            window.location.href = './dashboard-batak.php';
        });
        const profile = document.getElementById("profile-button");
        profile.addEventListener("click", () => {
            window.location.href = "./akun-pengguna.php";
        });
        const home = document.querySelector('.logo');
        home.addEventListener("click", () => {
            window.location.href = "./dashboard-batak.php";
        });

        const pertanyaanInput = document.getElementById('pertanyaanInput');
        const tanyaButton = document.getElementById('tanyaButton');
        const pertanyaanList = document.getElementById('pertanyaanList');
        const popularButton = document.getElementById('popularButton');
        const terbaruButton = document.getElementById('terbaruButton');

        let pertanyaanArray = [];

        function renderPertanyaan(sortedArray) {
            pertanyaanList.innerHTML = '';
            sortedArray.forEach((pertanyaan) => {
                const questionElement = document.createElement('div');
                questionElement.className = 'flex items-center p-3 rounded-lg shadow-md text-white';
                questionElement.style.backgroundColor = '#4C968B';
                questionElement.innerHTML = `
                    <img src="https://img.icons8.com/color/48/000000/user.png" alt="user" class="w-8 h-8 rounded-full mr-2" />
                    <div class="flex-grow">
                        <span class="font-semibold text-white">username</span>
                        <p>${pertanyaan.text}</p>
                    </div>
                    <button class="jawab-button bg-green-800 text-white py-1 px-3 rounded-full shadow-md hover:bg-green-600">Jawab</button>
                    <button class="like-button text-white text-sm ml-2 w-8 h-8 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart"></i>
                    </button>
                    <span class="like-count text-white ml-2">${pertanyaan.likes}</span>
                `;

                pertanyaanList.appendChild(questionElement);

                const jawabButton = questionElement.querySelector('.jawab-button');
                jawabButton.addEventListener('click', () => {
                    window.open(`forum-diskusi-jawab.php?question=${encodeURIComponent(pertanyaan.text)}`, '_blank');
                });

                const likeButton = questionElement.querySelector('.like-button');
                const heartIcon = likeButton.querySelector('i');  
                const likeCountElement = questionElement.querySelector('.like-count');

                likeButton.addEventListener('click', () => {
                    pertanyaan.liked = !pertanyaan.liked;
                    heartIcon.classList.toggle('text-green-800', pertanyaan.liked);
                    heartIcon.classList.toggle('text-white', !pertanyaan.liked);
                    pertanyaan.likes += pertanyaan.liked ? 1 : -1;
                    likeCountElement.textContent = pertanyaan.likes;
                });
            });
        }

        tanyaButton.addEventListener('click', () => {
            const pertanyaanText = pertanyaanInput.value.trim();
            if (pertanyaanText) {
                const pertanyaanData = {
                    text: pertanyaanText,
                    likes: 0,
                    liked: false,
                    timestamp: Date.now()
                };
                pertanyaanArray.push(pertanyaanData);
                pertanyaanInput.value = '';
                renderPertanyaan(pertanyaanArray.slice().sort((a, b) => b.timestamp - a.timestamp));
            } else {
                alert('Tolong masukkan pertanyaan!');
            }
        });

        popularButton.addEventListener('click', () => {
            renderPertanyaan(pertanyaanArray.slice().sort((a, b) => b.likes - a.likes));
        });

        terbaruButton.addEventListener('click', () => {
            renderPertanyaan(pertanyaanArray.slice().sort((a, b) => b.timestamp - a.timestamp));
        });

        home.addEventListener('click', () => {
            window.location.href = './dashboard-batak.php';
        });

        profile.addEventListener("click", () => {
            window.location.href = "./akun-pengguna.php";
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
            document.getElementById("account-username").textContent = `${userData.username || "username"}`;

        } else {
            alert("Gagal memuat data pengguna: " + result.message);
            window.location.href = "./masuk.php";
        }
    } catch (error) {
        console.error("Fetch Error:", error);
        alert("Terjadi kesalahan saat memuat data pengguna.");
    }

    document.getElementById("loading-bar").style.width = "0";

});
    </script>
</body>
</html>
