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

    <!-- tombol kembali -->
    <button class="bg-green-800 hover:bg-green-700 text-white font-bold w-12 h-12 rounded-full flex items-center justify-center ml-7 mb-4">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>

    <main class="flex flex-col items-center flex-grow">
        <h1 class="text-4xl font-bold py-5 text-gradient">Wadah Diskusi</h1>

        <!-- atur box-->
        <div class="p-8 rounded-lg shadow-md w-11/12 md:w-8/12 lg:w-8/12" style="background-color: rgba(212, 229, 221, 0.4)">

            <!-- tenpat pertanyaan-->
            <section id="questionSection" class="mb-6 flex items-start">
                <img src="../assets/tandatanya.png" alt="user" class="w-9 h-9 rounded-full mr-2" />
                <div id="questionDisplay" class="flex-grow p-6 rounded-lg text-white" style="background-color: #4C968B;">
                </div>
            </section>

            <!-- input jawaban-->
            <section id="answerInputSection" class="mb-10 flex">
                <textarea id="answerInput" class="w-full p-2 rounded-lg border-none focus:outline-none mt-4" rows="5" style="background-color: #4C968B; color: white;"></textarea> 
                <div class="flex justify-end mt-6 p-2">
                    <button id="submitAnswerButton"><img src="../assets/tomboltambahjawab.png" alt="" class="w-12 h-12 rounded-full mr-2"/></button>
                </div>
            </section>

            <!-- tempat list jawaban -->
            <section id="answerListSection">
                <h2 class="text-xl font-bold mb-4 text-green-800">Jawaban Lain:</h2>
                <div id="answersList" class="space-y-4">
                </div>
            </section>

        </div>
    </main>

    <script>
        // tampikan pertanyaan
        const urlParams = new URLSearchParams(window.location.search);
        const questionText = urlParams.get('question');
        document.getElementById('questionDisplay').textContent = questionText;

        const answersList = document.getElementById('answersList');
        const answerInput = document.getElementById('answerInput');
        const submitAnswerButton = document.getElementById('submitAnswerButton');

        // tambah jawaban
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

                // Like  button
                const likeButton = answerElement.querySelector('.like-button');
                const heartIcon = likeButton.querySelector('.fa-heart'); 
                const likeCount = answerElement.querySelector('.like-count');
                // Inisialisasi 
                answerElement.liked = answerElement.liked || false;
                answerElement.likes = answerElement.likes || 0;

                likeButton.addEventListener('click', () => {
                if (!answerElement.liked) {
                  // Like 
                         answerElement.likes += 1;
                        answerElement.liked = true;
                        heartIcon.classList.add('text-green-800');
                    } else {
                        // Unlike 
                        answerElement.likes -= 1;
                        answerElement.liked = false;
                        heartIcon.classList.remove('text-green-800');
                    }
                    likeCount.textContent = answerElement.likes;
                });

                // komentar 
                const commentToggleButton = answerElement.querySelector('.comment-toggle-button');
                const commentCount = answerElement.querySelector('.comment-count');
                const commentsList = answerElement.querySelector('.comments-list');
                commentToggleButton.addEventListener('click', () => {
                commentsList.classList.toggle('hidden');
                });


                // tambah komentar
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
                    <span class="font-semibold text-white">username </span>
                    <p>${commentText}</p>
                    </div>
                `;
                commentsList.insertBefore(commentElement, commentsList.querySelector('.comment-input-box'));
                commentInput.value = ''; 

                //hitung komentar
                answerElement.comments += 1;
                commentCount.textContent = answerElement.comments;
            }
        });
            } else {
                alert('Tolong masukkan jawaban!');
            }
        });
    </script>
</body>
</html>