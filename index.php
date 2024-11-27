<?php
  require 'src\php\functions.php';
  $user = query("SELECT * FROM user");
  query("SELECT * FROM user");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dialek.Id</title>
  <link rel="stylesheet" href="./src/styles/style2.css">
</head>
<body class="bg-custom-radial font-inter flex flex-col">
<nav class="logo font-irish">
  <div>
    <p>dialek.id</p>
  </div>
</nav>

<section class="main flex flex-col mx-auto w-4/5 min-h-screen flex-grow">
  <main class="mainMateri flex flex-col my-12">
    <header class="header items-center text-center">
      <h1 class="h1 font-bold text-gradient">
        Belajar Bahasa Daerah dengan Menyenangkan
      </h1>
      <p class="text-xl mt-5">
        Kami akan membantu Anda dalam mempelajari bahasa daerah dan memahami tradisi serta budaya suku-suku di Indonesia.
      </p>
    </header>
    <div class="image w-full flex justify-center mt-6 mb-6">
      <img src="./src/assets/Pulau.webp" alt="Pulau" class="container" />
    </div>

    <div class="container bg-white bg-opacity-30 rounded-1 shadow-lg font-inter text-custom2">
      <div class="pt-5 pb-10">
        <div class="w-full p-5 pb-7">
          <h6 class="h6 pb-2">Materi</h6>
          <ul class="list-none">
            <li class="opacity-80 text-lg">Materi pembelajaran yang lengkap dan seru</li>
          </ul>
        </div>

        <hr class="border-t border-border mt-0 w-[98%] mx-auto" />

        <div class="w-full p-5 pb-7">
          <h6 class="h6 pb-2">Permainan</h6>
          <ul class="list-none">
            <li class="opacity-80 text-lg">Permainan yang dapat mengukur kemampuan berbahasamu</li>
          </ul>
        </div>

        <hr class="border-t border-border mt-0 w-[98%] mx-auto" />

        <div class="w-full p-5 pb-7">
          <h6 class="h6 pb-2">Literasi Budaya dan Latihan</h6>
          <ul class="list-none">
            <li class="opacity-80 text-lg">Pengenalan budaya dengan cerita daerah dan latihan harian</li>
          </ul>
        </div>

        <hr class="border-t border-border mt-0 w-[98%] mx-auto" />

        <div class="w-full p-5 pb-7">
          <h6 class="h6 pb-2">Komunitas</h6>
          <ul class="list-none">
            <li class="opacity-80 text-lg">Diskusikan pertanyaanmu dengan pengguna lainnya</li>
          </ul>
        </div>

        <hr class="border-t border-border mt-0 w-[98%] mx-auto" />
      </div>
    </div>

    <div class="button-display flex flex-col items-center justify-center m-20">
      <button class="button text-white bg-custom1" onclick="navigateTo('/daftar')">Daftar</button>
      <button class="button text-black bg-white" onclick="navigateTo('/masuk')">Masuk</button>
      <p class="text-lg text-custom5 mt-1">Mulai perjalananmu sekarang!</p>
    </div>
  </main>
</section>

<footer class="footer text-center">
  <p>&copy; 2024 Dialek.id. Semua hak dilindungi.</p>
</footer>

<script>
  function navigateTo(path) {
    if (path === '/daftar') {
      window.location.href = './src/html/SignUp.php';
    } else if (path === '/masuk') {
      window.location.href = 'src/html/LogIn.php';
    }
  }
</script>
</body>
</html>