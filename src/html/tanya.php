<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wadah Diskusi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body class="bg-custom-radial font-inter flex flex-col">
    <nav class="logo font-irish">
        <div>
            <p>dialek.id</p>
        </div>
    </nav>

    <div class="min-h-screen">
        <main class="flex flex-col items-center mt-10">
            <h1 class="text-4xl font-bold py-5 text-gradient">Wadah Diskusi</h1>
            
            <div class="p-6 rounded-lg shadow-md w-11/12 md:w-8/12 lg:w-6/12" style="background-color: rgba(212, 229, 221, 40);">

                <div class="flex space-x-4 mb-4">
                    <button id="bahasaJawa" class="py-2 bg-custom-green-button w-full text-white font-bold rounded-full">Bahasa Jawa</button>
                    <button id="bahasaBatak" class="py-2 bg-custom-green-button w-full text-white font-bold rounded-full">Bahasa Batak Toba</button>
                    
                </div>


                <label for="pertanyaanInput" class="text-lg font-semibold text-green-800">Tulis pertanyaanmu:</label>
                <textarea id="pertanyaanInput" class="w-full p-4 rounded-lg border-none focus:outline-none text-white" rows="5" style="background-color: #4C968B;"></textarea>


                <div class="flex justify-end mt-4">
                    <button id="tanyaButton" class="bg-green-800 text-white py-2 px-6 rounded-full shadow-md hover:bg-green-700">Tanya</button>
                </div>
            </div>


            <div class="mt-10 w-11/12 md:w-8/12 lg:w-6/12">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-green-800">Pertanyaan Lain:</h2>
                    <!-- Tombol untuk mengurutkan pertanyaan -->
                    <div class="flex space-x-2">
                        <button id="popularButton" class="bg-custom-green-button text-white py-2 px-4 rounded-full">Popular</button>
                        <button id="terbaruButton" class="bg-green-100 bg-custom-green-button text-white py-2 px-4 rounded-full">Terbaru</button>
                    </div>
                </div>

                <div id="pertanyaanList" class="space-y-4">

                </div>
            </div>
        </main>
    </div>

    <script>
        const pertanyaanInput = document.getElementById('pertanyaanInput');
        const tanyaButton = document.getElementById('tanyaButton');
        const pertanyaanList = document.getElementById('pertanyaanList');
        const popularButton = document.getElementById('popularButton');
        const terbaruButton = document.getElementById('terbaruButton');
        const bahasaJawaButton = document.getElementById('bahasaJawa');
        const bahasaBatakButton = document.getElementById('bahasaBatak');
       
        let pertanyaanArray = [];
    

        function renderPertanyaan(sortedArray) {
            pertanyaanList.innerHTML = '';
            sortedArray.forEach((pertanyaan, index) => {
                const questionElement = document.createElement('div');
                questionElement.className = 'flex items-center p-4 rounded-full shadow-md text-white';
                questionElement.style.backgroundColor = '#4C968B';
                questionElement.innerHTML = `
                    <img src="https://img.icons8.com/color/48/000000/user.png" alt="user" class="w-8 h-8 rounded-full mr-4" />
                    <div class="flex-grow">
                        <span class="font-semibold text-white">username</span>
                        <p>${pertanyaan.text}</p>
                    </div>
                    <button class="bg-green-800 text-white py-1 px-4 rounded-full shadow-md hover:bg-green-600">Jawab</button>
                    <button class="like-button text-white text-sm ml-3 w-8 h-8 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart"></i>
                    </button>
                    <span class="like-count text-white ml-4">${pertanyaan.likes}</span>
                `;
                
                pertanyaanList.appendChild(questionElement);
    
                const likeButton = questionElement.querySelector('.like-button');
                const heartIcon = likeButton.querySelector('i');  
                const likeCountElement = questionElement.querySelector('.like-count');
    

                likeButton.addEventListener('click', () => {
                    if (!pertanyaan.liked) {
                        heartIcon.classList.add('text-green-800'); 
                        heartIcon.classList.remove('text-white'); 
                        pertanyaan.liked = true; // Set status liked ke true
                        pertanyaan.likes++;
                    } else {
                        heartIcon.classList.add('text-white');   
                        heartIcon.classList.remove('text-green-800');
                        pertanyaan.liked = false; // Reset status liked ke false
                        pertanyaan.likes--;
                    }
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
            popularButton.classList.add('bg-custom-green-button', 'text-white');
            terbaruButton.classList.remove('bg-custom-green-button', 'text-white');
            terbaruButton.classList.add('bg-green-800', 'text-white');
            renderPertanyaan(pertanyaanArray.slice().sort((a, b) => b.likes - a.likes));
        });
    

        terbaruButton.addEventListener('click', () => {
            terbaruButton.classList.add('bg-custom-green-button', 'text-white');
            popularButton.classList.remove('bg-custom-green-button', 'text-white');
            popularButton.classList.add('bg-green-800', 'text-white');
            renderPertanyaan(pertanyaanArray.slice().sort((a, b) => b.timestamp - a.timestamp));
        });
    

        bahasaJawaButton.addEventListener('click', () => {
            bahasaJawaButton.classList.add('bg-custom-green-button', 'text-white');
            bahasaBatakButton.classList.remove('bg-custom-green-button', 'text-white');
            bahasaBatakButton.classList.add('bg-green-800', 'text-white');
        });
    
        bahasaBatakButton.addEventListener('click', () => {
            bahasaBatakButton.classList.add('bg-custom-green-button', 'text-white');
            bahasaJawaButton.classList.remove('bg-custom-green-button', 'text-white');
            bahasaJawaButton.classList.add('bg-green-800', 'text-white');
        });
    </script>
</body>
</html>