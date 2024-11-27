<?php
    require '.functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dialek.id</title>
    <link rel="stylesheet" href="../styles/style2.css">
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
<!-- Navigation -->
<nav class="logo font-irish py-4">
    <p>dialek.id</p>
</nav>
<!-- Main Section -->
<section class="main flex flex-col mx-auto w-4/5 flex-grow">
    <header class="flex justify-center items-center">
        <h1 class="text-4xl font-bold text-center text-gradient">Pilih bahasa yang akan dipelajari</h1>
    </header>
    <div class="image flex justify-center gap-8 my-16">
        <div class="w-1/2 flex justify-center" id="bahasa-jawa">
            <img src="../assets/BahasaJawa.webp" alt="Bahasa Jawa" class="hover:opacity-80 cursor-pointer transition duration-300">
        </div>
        <div class="w-1/2 flex justify-center" id="bahasa-batak">
            <img src="../assets/BahasaBatak.webp" alt="Bahasa Batak" class="hover:opacity-80 cursor-pointer transition duration-300">
        </div>
    </div>
</section>
</body>
</html>