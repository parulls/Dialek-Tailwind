<?php 
    include("connect.php");
    // Periksa apakah ini adalah permintaan POST
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
    <style>
    @media (max-width: 768px) {
        /* Compact layout on tablets */
        .header h1 {
            font-size: 1.5rem;
        }

        .box-custom1 {
            width: 90%;
        }

        textarea, .box-custom1, .header h1 {
            font-size: 0.875rem;
            padding: 0.75rem;
        }

        .button-custom, .button-custom2 {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 640px) {
        /* Mobile screen layout adjustments */
        body {
            font-size: 0.875rem;
        }

        nav {
            flex-direction: flex;
            align-items: flex-start;
        }

        .logo {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .box-custom1 {
            padding: 1rem;
            width: 95%;
        }

        .header h1 {
            font-size: 1.25rem;
            text-align: center;
        }

        textarea{
            font-size: 0.75rem;
        }
    }
</style>
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div class="logo font-irish m-0 text-2xl cursor-pointer" onclick="toggleSidebar()">dialek.id</div>
        <div id="profile-button" class="flex items-center m-0 font-semibold text-custom2 cursor-pointer">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>

    <main class="flex flex-col items-center flex-grow">
        <!-- Judul -->
        <div class="flex items-center w-full justify-center mb-10">
            <header class="header text-center">
                <h1 class="h1 font-bold text-gradient">
                  Wadah Diskusi
                </h1>
            </header>
        </div>

        <!-- Box Pertanyaan di tengah -->
        <div class="p-8 rounded-lg box-custom1 w-11/12 md:w-8/12 lg:w-6/12 mx-auto" style="background-color: rgba(212, 229, 221, 0.4)">
            <!-- Tempat Pertanyaan -->
            <section id="questionSection" class="mb-6 flex items-start">
                <img src="../assets/tandatanya.webp" alt="user" class="w-9 h-9 rounded-full mr-2" />
                <div id="questionDisplay" class="flex-grow p-6 rounded-lg text-white" style="background-color: #4C968B;">
                </div>
            </section>

            <!-- Input Jawaban -->
            <section id="answerInputSection" class="mb-10 flex">
                <textarea id="answerInput" class="w-full p-2 rounded-lg border-none focus:outline-none mt-4" rows="5" style="background-color: #4C968B; color: white;"></textarea> 
                <div class="flex justify-end mt-6 p-2">
                    <button id="submitAnswerButton"><img src="../assets/tomboltambahjawab.webp" alt="" class="w-12 h-12 rounded-full mr-2"/></button>
                </div>
            </section>

            <!-- List Jawaban -->
            <section id="answerListSection">
                <h2 class="text-xl font-bold mb-4 text-green-800">Jawaban</h2>
                <div id="answersList" class="space-y-4">
                </div>
            </section>
        </div>
    </main>

    <script>
        const profile = document.getElementById("profile-button");
        profile.addEventListener("click", () => {
            window.location.href = "./akun-pengguna.php";
        });
        const home = document.querySelector('.logo');
        home.addEventListener("click", () => {
            window.location.href = "./dashboard-batak.php";
        });
        // Tampilkan pertanyaan
        const urlParams = new URLSearchParams(window.location.search);
        const questionText = urlParams.get('question');
        document.getElementById('questionDisplay').textContent = questionText;

        const answersList = document.getElementById('answersList');
        const answerInput = document.getElementById('answerInput');
        const submitAnswerButton = document.getElementById('submitAnswerButton');

        // Tambah jawaban
        submitAnswerButton.addEventListener('click', () => {
            const answerText = answerInput.value.trim();
            if (answerText) {
                const answerElement = document.createElement('div');
                answerElement.className = 'flex flex-col p-4 bg-green-200 rounded-lg text-white mb-4';
                answerElement.style.backgroundColor = '#68A192';

                answerElement.likes = 0;
                answerElement.comments = 0;
                answerElement.liked = false;

                answerElement.innerHTML = `
                    <div class="flex items-center">
                        <img src="https://img.icons8.com/color/48/000000/user.png" alt="user" class="w-8 h-8 rounded-full mr-4" />
                        <div class="flex-grow">
                            <span class="font-semibold text-white">username</span>
                            <p>${answerText}</p>
                        </div>

                        <button class="like-button text-white text-sm w-8 h-8 rounded-full flex items-center justify-center ml-3">
                            <i class="fas fa-heart"></i>
                            <span class="like-count text-white ml-2">0</span>
                        </button>

                        <button class="comment-toggle-button text-white text-sm w-8 h-8 rounded-full flex items-center justify-center ml-3">
                        <i class="fas fa-comment"></i>
                        <span class="comment-count text-white ml-2">0</span>
                        </button>

                    </div>
                    <div class="comments-list hidden mt-2 ml-12 space-y-2">
                        <div class="comment-input-box flex items-center mt-2">
                            <input type="text" placeholder="Tulis komentar Anda..." class="comment-input w-full p-2 rounded-lg focus:outline-none text-black mr-2">
                            <button class="submit-comment text-white bg-green-900 rounded-lg p-2">Kirim</button>
                        </div>
                    </div>
                `;

                answersList.appendChild(answerElement);
                answerInput.value = '';

                // Like button
                const likeButton = answerElement.querySelector('.like-button');
                const heartIcon = likeButton.querySelector('.fa-heart'); 
                const likeCount = answerElement.querySelector('.like-count');

                likeButton.addEventListener('click', () => {
                    if (!answerElement.liked) {
                        answerElement.likes += 1;
                        answerElement.liked = true;
                        heartIcon.classList.add('text-green-800');
                    } else {
                        answerElement.likes -= 1;
                        answerElement.liked = false;
                        heartIcon.classList.remove('text-green-800');
                    }
                    likeCount.textContent = answerElement.likes;
                });

                // Komentar
                const commentToggleButton = answerElement.querySelector('.comment-toggle-button');
                const commentCount = answerElement.querySelector('.comment-count');
                const commentsList = answerElement.querySelector('.comments-list');

                commentToggleButton.addEventListener('click', () => {
                    commentsList.classList.toggle('hidden');
                });

                // Tambah komentar
                const submitCommentButton = answerElement.querySelector('.submit-comment');
                const commentInput = answerElement.querySelector('.comment-input');

                submitCommentButton.addEventListener('click', () => {
                    const commentText = commentInput.value.trim();
                    if (commentText) {
                        const commentElement = document.createElement('div');
                        commentElement.className = 'flex items-center text-white p-2 rounded-md';
                        commentElement.style.backgroundColor = '#5A897C';
                        commentElement.innerHTML = `
                            <img src="https://img.icons8.com/color/48/000000/user.png" alt="user" class="w-6 h-6 rounded-full mr-2" />
                            <div class="flex-grow">
                                <span class="font-semibold text-white">username</span>
                                <p>${commentText}</p>
                            </div>
                        `;
                        commentsList.insertBefore(commentElement, commentsList.querySelector('.comment-input-box'));
                        commentInput.value = ''; 

                        // Hitung komentar
                        answerElement.comments += 1;
                        commentCount.textContent = answerElement.comments;
                    }
                });
            } else {
                alert('Tolong masukkan jawaban!');
            }
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
                document.getElementById("account-username").textContent = `@${userData.username || "username"}`;

            } else {
                alert("Gagal memuat data pengguna: " + result.message);
                window.location.href = "login.php";
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
