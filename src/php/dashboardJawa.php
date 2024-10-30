<?php
    require '.functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <title>Dialek.id</title>
    <link rel="stylesheet" href="../styles/style2.css">
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full px-4 py-4">
        <div class="logo font-irish text-2xl">dialek.id</div>
        <div class="flex items-center font-semibold text-custom2">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>

<section>
    <header>
    <div id="hero">
    <div class="container-fluid flex flex-wrap">
        <div class="row-span-12 md:row-span-4 ml-12 w-full md:w-1/3 flex justify-center">
            <div class="flex flex-col">
                <h1 class="text-5xl font-bold text-gradient font-poppins ml-9 mt-5">Bahasa Jawa</h1>
                <a href="tanya.html">
                    <img src="../assets/ForumDiskusi.webp" alt="ForumDiskusi" class="max-w-sm flex hover:opacity-80 cursor-pointer transition duration-300" />
                </a>
            </div>
        </div>

        <div class="row-span-12 md:row-span-8 bg-white bg-opacity-30 rounded-3xl shadow-lg font-inter text-custom6 font-semibold p-6 max-w-3xl w-full md:w-2/3 max-h-64 ml-20">
            <div class="flex flex-row justify-around items-center font-inter space-x-7">
                
                <!-- Kosa Kata Mingguan -->
                <div class="flex flex-col items-center">
                    <h6 class="text-xl font-bold text-custom6 text-center flex items-center justify-center">Kosa Kata<br>Mingguan</h6>
                    <div class="flex items-center justify-center w-24 h-24 rounded-full border-4 border-gray-700 mt-3">
                        <p class="text-3xl font-bold text-custom4">40%</p>
                    </div>
                </div>
            
                <!-- Level Budaya -->
                <div class="flex flex-col items-center">
                    <h6 class="text-xl font-bold text-custom6 text-center flex items-center justify-center" style="min-height: 60px;">Level Budaya</h6>
                    <div class="flex items-center justify-center w-24 h-24 rounded-full border-4 border-gray-700 mt-3">
                        <p class="text-3xl font-bold text-custom7">16</p>
                    </div>
                </div>
            
                <!-- Peringkat Game Sambung Kata -->
                <div class="flex flex-col items-center">
                    <h6 class="text-xl font-bold text-custom6 text-center flex items-center justify-center" style="min-height: 60px;">Peringkat Game<br>Sambung Kata</h6>
                    <div class="flex items-center justify-center w-24 h-24 rounded-full border-4 border-gray-700 mt-3">
                        <p class="text-3xl font-bold text-custom7">16</p>
                    </div>
                </div>
            
                <!-- Latihan Soal -->
                <div class="flex flex-col items-center">
                    <h6 class="text-xl font-bold text-custom6 text-center flex items-center justify-center" style="min-height: 60px;">Latihan Soal</h6>
                    <div class="flex items-center justify-center w-24 h-24 rounded-full border-4 border-gray-700 mt-3">
                        <p class="text-3xl font-bold text-custom7">80%</p>
                    </div>
                </div>
            </div>
            <p class="text-xs text-custom2 mt-2 font-light mt-5 ">*persentase berdasarkan target mingguan</p>
        </div>
    </div>
</div>
    </header>

<!-- EXP BAR -->
<div class="mt-16">
    <div class="w-full max-w-lg ml-[520px]">
        <span class="text-green-800 font-bold">EXP</span>
        <span class="text-green-800 font-bold">45</span>
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

<div class="flex justify-center mt-20">
    <h1 class="text-4xl font-bold text-gradient items-center text-center">Mulai Belajar</h1>
</div>

<!-- CONTENT UTAMA -->

<!-- CONTENT 1 MATERI -->
<div class="container max-w-5xl bg-white bg-opacity-30 rounded-3xl shadow-lg font-inter border-[1.5px] border-bar p-6 mt-10 h-80">
    <ul>
        <li class="flex items-center space-x-2 mt-7">
            <!-- Gambar Kiri -->
            <div class="w-1/2 flex justify-center items-center">
                <div class="text-center h-1/2 w-1/2 flex items-center justify-center">
                    <img src="../assets/materiPreview.webp" alt="materiPreview" class="max-w-56"/>
                </div>
            </div>
            <!-- Konten Kanan -->
            <div class="w-2/3">
                <h2 class="text-4xl font-medium text-green-900 mb-2">Materi</h2>
                <p class="text-gray-600 mb-4">
                    Bahasa yang dipelajari saat ini, yaitu wfgflf rucehfqliha weufpqqiufq mfqunuudqwuoiwo [idonqidwon]; jdaq NUQ QQUQQ QUYQLLu
                </p>
                <a href="Materi.html">
                    <button class="button-custom py-2 px-4 rounded-full hover:bg-lime-900">pelajari</button>
                </a>
            </div>
        </li>
    </ul>
</div>

<!-- CONTENT 1 MATERI SELESAI -->
<!-- CONTENT 2 GAME -->
<div class="container max-w-5xl bg-white bg-opacity-30 rounded-3xl shadow-lg font-inter border-[1.5px] border-bar p-6 mt-10 h-80">
<ul>
    <li class="flex items-center space-x-2 mt-7">
        <!-- Konten kiri -->
        <div class="w-2/3 text-right">
            <h2 class="text-4xl font-medium text-green-900 mb-2 ">Game</h2>
            <p class="text-gray-600 mb-4">
                Bahasa yang dipelajari saat ini, yaitu wfgflf rucehfqliha weufpqqiufq mfqunuudqwuoiwo [idonqidwon]; jdaq NUQ QQUQQ QUYQLLu
            </p>
            <a href=" ">
                <button class="button-custom py-2 px-4 rounded-full hover:bg-lime-900">pelajari</button>
            </a>
        </div>
        <!-- Gambar kanan -->
        <div class="w-1/2 flex justify-center">
            <div class="text-center h-1/2 w-1/2">
                <img src="../assets/gamePreview.webp" alt="gamePreview" class="max-w-56"/>
            </div>
        </div>
    </li>
</ul>
</div>
<!-- CONTENT 2 GAME SELESAI -->
<!-- CONTENT 3 LITERASI BUDAYA -->
<div class="container max-w-5xl bg-white bg-opacity-30 rounded-3xl shadow-lg font-inter border-[1.5px] border-bar p-6 mt-10 h-80">
    <ul>
        <li class="flex items-center space-x-2 mt-7">
            <!-- Gambar Kiri -->
            <div class="w-1/2 flex justify-center">
                <div class="text-center h-1/2 w-1/2">
                    <img src="../assets/literasiPreview.webp" alt="literasiPreview" class="max-w-56"/>
                </div>
            </div>
            <!-- Konten Kanan -->
            <div class="w-2/3">
                <h2 class="text-4xl font-medium text-green-900 mb-2">Literasi Budaya</h2>
                <p class="text-gray-600 mb-4">
                    Bahasa yang dipelajari saat ini, yaitu wfgflf rucehfqliha weufpqqiufq mfqunuudqwuoiwo [idonqidwon]; jdaq NUQ QQUQQ QUYQLLu
                </p>
                <a href="">
                    <button class="button-custom py-2 px-4 rounded-full hover:bg-lime-900">pelajari</button>
                </a>
            </div>
        </li>
    </ul>
    </div>
<!-- CONTENT  3 LITERASI BUDAYA -->
<!-- CONTENT 4 KOSA KATA -->
<div class="container max-w-5xl bg-white bg-opacity-30 rounded-3xl shadow-lg font-inter border-[1.5px] border-bar p-6 mt-10 h-80">
    <ul>
        <li class="flex items-center space-x-2 mt-7">
            <!-- Konten kiri -->
            <div class="w-2/3 text-right">
                <h2 class="text-4xl font-medium text-green-900 mb-2">Kosa Kata</h2>
                <p class="text-gray-600 mb-4">
                    Bahasa yang dipelajari saat ini, yaitu wfgflf rucehfqliha weufpqqiufq mfqunuudqwuoiwo [idonqidwon]; jdaq NUQ QQUQQ QUYQLLu
                </p>
                <a href=" ">
                <button class="button-custom py-2 px-4 rounded-full hover:bg-lime-900">pelajari</button>
                </a>
            </div>
            <!-- Gambar kanan -->
            <div class="w-1/2 flex justify-center">
                <div class="text-center h-1/2 w-1/2">
                    <img src="../assets/kosakataPreview.webp" alt="kosakataPreview" class="max-w-56"/>
                </div>
            </div>
        </li>
    </ul>
    </div>
<!-- CONTENT 4 KOSA KATA SELESAI -->

<!-- CONTENT UTAMA SELESAI -->
</section>

</body>
</html>
