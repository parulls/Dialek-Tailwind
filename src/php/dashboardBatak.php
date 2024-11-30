<?php
    require 'functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Dialek.id</title>
    <link rel="stylesheet" href="../styles/style2.css">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div id="home" class="logo font-irish m-0 text-2xl cursor-pointer">dialek.id</div>
        <div id="profile-button" class="flex items-center m-0 font-semibold text-custom2 cursor-pointer">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>

    <section class="main flex flex-col mx-auto min-h-screen flex-grow ">
        <header>
            <div id="hero" class="flex flex-col md:flex-row gap-x-28 items-center justify-between">
                <div class="flex w-full md:w-1/3 justify-center mt-14">
                    <div class="flex flex-col">
                        <h1 class="text-4xl font-bold text-gradient font-poppins text-center">Bahasa Batak Toba</h1>
                        <a href="./forum-diskusi-tanya.html">
                            <img src="../assets/ForumDiskusi.webp" alt="ForumDiskusi" class="max-w-sm hover:scale-90 cursor-pointer transition duration-300 transform" />
                        </a>
                    </div>
                </div>

                <!-- Progress Box -->
                <div class=" w-full md:w-5/6 bg-white bg-opacity-30 rounded-3xl shadow-lg font-inter text-custom6 font-semibold p-6">
                    <div class="flex flex-row justify-around items-center font-inter space-x-14">
                        
                        <!-- Kosa Kata Mingguan -->
                        <div class="flex flex-col items-center">
                            <p class="text-lg font-bold text-custom6 text-center flex items-center justify-center">Kosa Kata<br>Mingguan</p>
                            <div class="flex items-center justify-center w-24 h-24 rounded-full border-4 border-gray-700 mt-3">
                                <p class="text-2xl font-bold text-custom4">22%</p>
                            </div>
                        </div>
                    
                        <!-- Level Budaya -->
                        <div class="flex flex-col items-center">
                            <p class="text-lg font-bold text-custom6 text-center flex items-center justify-center min-h-16">Level Budaya</p>
                            <div class="flex items-center justify-center w-24 h-24 rounded-full border-4 border-gray-700 mt-3">
                                <p class="text-2xl font-bold text-custom2">40</p>
                            </div>
                        </div>
                    
                        <!-- Peringkat Game Sambung Kata -->
                        <div class="flex flex-col items-center">
                            <p class="text-lg font-bold text-custom6 text-center flex items-center justify-center min-h-16">Peringkat Game<br>Sambung Kata</p>
                            <div class="flex items-center justify-center w-24 h-24 rounded-full border-4 border-gray-700 mt-3">
                                <p class="text-2xl font-bold text-custom2">2</p>
                            </div>
                        </div>
                    
                        <!-- Latihan Soal -->
                        <div class="flex flex-col items-center">
                            <p class="text-lg font-bold text-custom6 text-center flex items-center justify-center min-h-16">Latihan Soal</p>
                            <div class="flex items-center justify-center w-24 h-24 rounded-full border-4 border-gray-700 mt-3">
                                <p class="text-2xl font-bold text-custom2">75%</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-custom2 font-light mt-5 "><span class="text-custom4">*</span>persentase berdasarkan target mingguan</p>
                </div>
                <!-- progress bar selesai -->
            </div>
        </header>

<!-- EXP BAR -->
<div >
    <div class="w-full max-w-lg ml-[265px]">
        <span class="text-green-800 font-bold">EXP 45</span>
    </div>

    <div class="flex justify-center mt-3">
        <div class="max-w-6xl w-full">
            <div class="bg-slate-700 rounded-full h-6 w-full">
                <div id="loading-bar" class="bg-custom1 h-6 rounded-full w-72"></div>
            </div>
        </div>
    </div>
</div>
<!-- EXP BAR SELESAI -->

<div data-aos="fade-up" class="flex justify-center mt-20 mb-10">
    <h1 class="text-3xl font-bold text-gradient items-center text-center">Mulai Belajar</h1>
</div>

<!-- CONTENT UTAMA -->
<!-- CONTENT 1 MATERI -->
<div data-aos="fade-up" class="container box-custom1 p-4 mt-6 h-72 flex items-center justify-center">
    <ul class="w-full h-full flex items-center justify-center">
        <li class="flex items-center space-x-2 w-full h-full">
            <!-- Gambar Kiri -->
            <div class="w-1/3 flex justify-center items-center">
                <div class="text-center h-56 w-56 flex items-center justify-center">
                    <img src="../assets/materiPreview.webp" alt="literasiPreview" class="w-full max-w-none rounded-full"/>
                </div>
            </div>
            <!-- Konten Kanan -->
            <div class="w-2/3 text-left flex flex-col justify-center">
                <h2 class="text-3xl font-medium text-green-900 mb-2">Materi</h2>
                <p class="text-gray-600 mb-4">
                    Mulai perjalananmu belajar Bahasa Batak dengan materi yang disesuaikan dengan kemampuanmu dan dapat diakses kapan saja!
                </p>
                <button id="goto-materi" class="button-custom py-2 px-4 rounded-full hover:bg-lime-900 w-1/6">Pelajari</button>
            </div>
        </li>
    </ul>
</div>

<!-- CONTENT 2 GAME -->
<div data-aos="fade-up" class="container box-custom1 p-4 mt-6 h-72">
<ul>
    <li class="flex items-center space-x-2 w-full h-full">
        <!-- Konten kiri -->
            <div class="w-2/3 text-right flex flex-col justify-center items-end">
                <h2 class="text-2xl font-medium text-green-900 mb-2">Permainan</h2>
                <p class="text-gray-600 mb-2">
                    Asah keterampilanmu dengan permainan sambung kata melawan pengguna lain. Kembangkan kemampuanmu dan perluas kosa katamu!
                </p>
                <button id="goto-game" class="button-custom py-2 px-4 rounded-full hover:bg-lime-900 w-1/6">Pelajari</button>
            </div>
            <!-- Gambar kanan -->
            <div class="w-1/3 flex justify-center items-center">
                <div class="text-center h-56 w-56 flex items-center justify-center">
                    <img src="../assets/gamePreview.webp" alt="kosakataPreview" class="w-full max-w-none rounded-full"/>
                </div>
            </div>
        </li>
    </ul>
</div>

<!-- CONTENT 3 LITERASI BUDAYA -->
<div data-aos="fade-up" class="container box-custom1 p-4 mt-6 h-72 flex items-center justify-center">
    <ul class="w-full h-full flex items-center justify-center">
        <li class="flex items-center space-x-2 w-full h-full">
            <!-- Gambar Kiri -->
            <div class="w-1/3 flex justify-center items-center">
                <div class="text-center h-56 w-56 flex items-center justify-center">
                    <img src="../assets/literasiPreview.webp" alt="literasiPreview" class="w-full max-w-none rounded-full"/>
                </div>
            </div>
            <!-- Konten Kanan -->
            <div class="w-2/3 text-left flex flex-col justify-center">
                <h2 class="text-2xl font-medium text-green-900 mb-2">Literasi Budaya</h2>
                <p class="text-gray-600 mb-4">
                    Kenali budaya lebih dalam dengan cerita daerah dan latihan harian yang dapat membantumu merasa lebih dekat dengan budaya Batak!
                </p>
                <button id="goto-literasi" class="button-custom py-2 px-4 rounded-full hover:bg-lime-900 w-1/6">Pelajari</button>
            </div>
        </li>
    </ul>
</div>

<!-- CONTENT 4 KOSA KATA -->
<div data-aos="fade-up" class="container box-custom1 p-4 mt-6 mb-6 h-72 flex items-center justify-center">
    <ul class="w-full h-full flex items-center justify-center">
        <li class="flex items-center space-x-2 w-full h-full">
            <!-- Konten Kiri -->
            <div class="flex flex-col w-2/3 text-right justify-center items-end">
                <h2 class="text-2xl font-medium text-green-900 mb-2">Kosa Kata</h2>
                <p class="text-gray-600 mb-4">
                    Perbanyak kosa katamu dengan latihan yang menantang dan dapatkan pengetahuan baru yang dapat digunakan sehari-hari!
                </p>
                <button id="goto-kosaKata" class="button-custom py-2 px-4 rounded-full hover:bg-lime-900 w-1/6">Pelajari</button>
            </div>
            <!-- Gambar kanan -->
            <div class="w-1/3 flex justify-center items-center">
                <div class="text-center h-56 w-56 flex items-center justify-center">
                    <img src="../assets/kosakataPreview.webp" alt="kosakataPreview" class="w-full max-w-none rounded-full"/>
                </div>
            </div>
        </li>
    </ul>
</div>


<!-- CONTENT UTAMA SELESAI -->
</section>
<script>
    AOS.init();

    const profile = document.getElementById("profile-button");
    const Materi = document.getElementById("goto-materi");
    const Game = document.getElementById("goto-game");
    const Literasi = document.getElementById("goto-literasi");
    const KosaKata = document.getElementById("goto-kosaKata");
    profile.addEventListener("click", () => {
            window.location.href = "./AkunUser.php";
        });
    Materi.addEventListener("click", () => {
        window.location.href = "./PilihMateri.php";
    });
    Game.addEventListener("click", () => {
        window.location.href = "Permainan1.php";
    });
    Literasi.addEventListener("click", () => {
        window.location.href = "./LiterasiBudaya.php";
    });
    KosaKata.addEventListener("click", () => {
        window.location.href = "./Kosakata1.php";
    });

    document.addEventListener("DOMContentLoaded", () => {
    console.log("Script berjalan");
    const username = localStorage.getItem("username");
    console.log("Username dari localStorage:", username);

    // Periksa apakah username tersedia
    if (username) {
        document.getElementById("account-username").textContent = username;
    } else {
        alert("Silakan login terlebih dahulu.");
        window.location.href = "login.php"; 
    }
});
    
</script>
</body>
</html>