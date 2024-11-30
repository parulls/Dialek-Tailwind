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
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <style>
    .language-selection-container {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 30px;
      margin: 30px auto 50px;
    }

    .language-option {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      cursor: pointer;
      font-family: Arial, sans-serif;
      color: #4b5563; /* Dark gray */
      width: 100px;
    }

    .language-option img {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      margin-bottom: 8px;
    }

    .language-option p {
      margin: 0;
      font-size: 16px;
      font-weight: 600;
      color: #111827; /* Dark color */
    }

    /* Section Title */
    .section-title {
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      margin-top: 20px;
      margin-bottom: 20px;
      font-family: Arial, sans-serif;
    }
  </style>
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
  <!-- Navbar -->
  <nav class="flex items-center justify-between w-full pb-2 bg-white opacity-80 shadow-md fixed top-0 left-0 z-10 px-6">
    <div class="logo font-irish text-2xl text-custom1">dialek.id</div>
    <ul class="flex items-center font-poppins font-medium space-x-8 text-custom2">
      <li><a href="#home" class="hover:text-custom1">Home</a></li>
      <li><a href="#bahasa" class="hover:text-custom1">Bahasa</a></li>
      <li><a href="#materi" class="hover:text-custom1">Fitur</a></li>
    </ul>
  </nav>

  <!-- Konten Utama -->
  <section id="home" class="main flex flex-col mx-auto w-2/3 flex-grow pt-32 top-2">
    <main class="mainMateri flex flex-col mb-8">
      <header class="header items-center text-center">
        <h1 class="h3 md:text-4xl lg:text-4xl font-bold text-gradient">Belajar Bahasa Daerah dengan Menyenangkan</h1>
        <p class="text-xl mt-5">
          Kami akan membantu Anda dalam mempelajari bahasa daerah dan memahami tradisi serta budaya suku-suku di Indonesia.
        </p>
      </header>

      <!-- Gambar Utama -->
      <div data-aos="fade-up" class="image w-full flex justify-center mt-6 mb-6">
        <img src="./src/assets/Pulau.webp" alt="Pulau" class="container" />
      </div>

      <!-- Section: "Pilih Bahasa Yang Ingin Dipelajari" -->
      <div class="section-title text-lg text-custom2" data-aos="fade-up" id="bahasa">Pilih Bahasa Yang Ingin Dipelajari</div>
      <div class="language-selection-container text-white font-semibold" data-aos="fade-up">
      <div class="language-option w-full flex items-start justify-start space-x-4" data-aos="fade-right" data-aos-delay="200">
        <img src="./src/assets/jawa.webp" alt="Jawa" id="jawa" class="h-16 w-16 md:h-20 md:w-20 lg:h-24 lg:w-24 object-contain">
        <p class="text-base leading-normal align-middle">(Segera Hadir)</p>
      </div>
      <div class="language-option w-full flex items-start justify-start space-x-4" data-aos="fade-left" data-aos-delay="200">
        <img src="./src/assets/batak.webp" alt="Batak" id="batak" class="h-16 w-16 md:h-20 md:w-20 lg:h-24 lg:w-24 object-contain">
        <p class="text-base leading-normal align-middle">Batak Toba</p>
      </div>
      </div>

      <!-- Section: "Lestarikan Budaya Kita" -->
      <div class="text-center p-6 flex flex-row items-center justify-center" data-aos="fade-up" data-aos-delay="300">
        <div>
          <h1 class="text-2xl font-poppins font-bold text-custom1 mb-4">Lestarikan Budaya Kita</h1>
          <p class="text-lg text-gray-700 mb-2">
            Bahasa daerah adalah salah satu harta budaya terbesar kita. Dengan belajar bahasa daerah, kita menjaga identitas dan kekayaan budaya Indonesia.
          </p>
        </div>
        <div>
          <img src="./src/assets/display-one.webp" alt="display 1">
        </div>
      </div>

      <!-- Section: "Mulai dengan dialek.id" -->
      <div class="text-center p-6 flex flex-row items-center justify-center" data-aos="fade-up" data-aos-delay="400">
        <div>
          <img src="./src/assets/display-two.webp" alt="display 2">
        </div>
        <div>
          <h1 class="text-2xl font-poppins font-bold text-custom1 mb-4">Mulai dengan dialek.id</h1>
          <p class="text-lg text-gray-700 mb-2">
            Dengan Dialek.id, kamu bisa belajar bahasa daerah di mana saja dan kapan saja dengan materi yang dirancang khusus untuk memudahkan proses belajarmu!
          </p>
        </div>
      </div>

      <!-- Seksi Materi -->
      <section id="materi" class="main flex flex-col mx-auto w-4/5 flex-grow" data-aos="fade-up" data-aos-delay="100">
        <div class="container bg-white bg-opacity-30 rounded shadow-lg font-inter text-custom2">
          <div class="pt-5 pb-10">
            <div class="w-full p-5 pb-7" data-aos="fade-right" data-aos-delay="600">
              <h6 class="h6 pb-2">Materi</h6>
              <ul class="list-none">
                <li class="opacity-80 text-lg">Materi pembelajaran yang lengkap dan seru</li>
              </ul>
            </div>
            <hr class="border-t border-border mt-0 w-[98%] mx-auto" />
            <div id="permainan" class="w-full p-5 pb-7" data-aos="fade-left" data-aos-delay="700">
              <h6 class="h6 pb-2">Permainan</h6>
              <ul class="list-none">
                <li class="opacity-80 text-lg">Permainan yang dapat mengukur kemampuan berbahasamu</li>
              </ul>
            </div>
            <hr class="border-t border-border mt-0 w-[98%] mx-auto" />
            <div id="komunitas" class="w-full p-5 pb-7" data-aos="fade-right" data-aos-delay="800">
              <h6 class="h6 pb-2">Komunitas</h6>
              <ul class="list-none">
                <li class="opacity-80 text-lg">Diskusikan pertanyaanmu dengan pengguna lainnya</li>
              </ul>
            </div>
            <hr class="border-t border-border mt-0 w-[98%] mx-auto" />
          </div>
        </div>
      </section>
    </main>
  </section>

  <!-- Footer -->
  <footer class="flex flex-col items-center justify-between bg-custom3 text-white py-6">
    <div class="container mx-auto px-6 text-center space-y-4">
      <div class="flex justify-center space-x-4 text-sm font-inter">
        <a href="#" class="text-white hover:text-custom6">Kebijakan Privasi</a>
        <a href="#" class="text-white hover:text-custom6">Syarat & Ketentuan</a>
        <a href="#" class="text-white hover:text-custom6">Keamanan</a>
        <a href="#" class="text-white hover:text-custom6">Hubungi Kami</a>
      </div>
      <div class="flex justify-center space-x-6">
        <a href="#" class="text-white hover:text-custom6 text-lg"><i class="fab fa-facebook"></i></a>
        <a href="#" class="text-white hover:text-custom6 text-lg"><i class="fab fa-twitter"></i></a>
        <a href="#" class="text-white hover:text-custom6 text-lg"><i class="fab fa-instagram"></i></a>
      </div>
      <p class="flex items-center justify-center">&copy; 2024 Dialek.id. Semua hak dilindungi.</p>
    </div>
  </footer>  

  <script>
    // Inisiasi animasi AOS
    AOS.init();
  
    // Script untuk navbar smooth scrolling
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll('nav a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          const targetId = this.getAttribute('href').substring(1);
          const targetElement = document.getElementById(targetId);
  
          if (targetElement) {
            const navbarHeight = document.querySelector('nav').offsetHeight;
            const elementPosition = targetElement.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - navbarHeight;
  
            window.scrollTo({
              top: offsetPosition,
              behavior: 'smooth'
            });
          }
        });
      });
  
      // Redirect ke logIn.html saat elemen dengan ID 'jawa' atau 'batak' diklik
      document.getElementById("jawa").addEventListener("click", function() {
        window.location.href = "./src/html/halaman-segera-hadir.html";
      });
  
      document.getElementById("batak").addEventListener("click", function() {
        window.location.href = "./src/php/login.php";
      });
    });
  </script>
</body>
</html>
