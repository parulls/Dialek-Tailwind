<?php 
    require '../php/functions.php';

    if (isset($_POST["register"])) {
        if(registrasi($_POST) > 0) {
            echo "<script>
                    alert('User baru berhasil ditambahkan!');
                    window.location.href = 'LogIn.php';
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('User baru gagal ditambahkan!');
                  </script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dialek.id</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="../styles/style2.css">
</head>
<body class="bg-custom-radial font-inter flex flex-col">
<nav class="logo font-irish">
    <div>
        <p>dialek.id</p>
    </div>
</nav>
<section class="main flex flex-col mx-auto w-4/5 min-h-screen flex-grow">
    <div class="flex items-center justify-center px-4">
        <div class="bg-shadow p-6 rounded-lg">
            <h2 class="text-gradient text-4xl font-bold text-center mb-6 w-full">Daftar</h2>
            <form id="signup-form" action="" method="post">
                <!-- Form Fields -->
                <div class="mb-4">
                    <label class="block text-custom2 text-lg font-semibold mb-2" for="nama">Nama</label>
                    <div class="flex items-center bg-bg-form border border-gray-300 rounded-lg">
                        <input type="text" name="name" id="name" placeholder="John Doe" class="w-full text-sm bg-bg-form px-3 py-2 border-none rounded-lg text-form focus:outline-none" aria-label="Name" />
                        <ion-icon name="person" class="pr-4  items-center"></ion-icon>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-custom2 text-lg font-semibold mb-2" for="username">Nama Pengguna</label>
                    <div class="flex items-center bg-bg-form border border-gray-300 rounded-lg">
                        <input type="text" name="username" id="username" placeholder="johndoe_2112" class="w-full text-sm bg-bg-form px-3 py-2 border-none rounded-lg text-form focus:outline-none" aria-label="Username" />
                        <ion-icon name="person" class="pr-4  items-center"></ion-icon>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-custom2 text-lg font-semibold mb-2" for="email">E-mail</label>
                    <div class="flex items-center bg-bg-form border border-gray-300 rounded-lg">
                        <input type="email" name="email" id="email" placeholder="nama@domain.com" class="w-full text-sm bg-bg-form px-3 py-2 border-none rounded-lg text-form focus:outline-none" aria-label="Email" />
                        <ion-icon name="mail" class="pr-4  items-center"></ion-icon>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-custom2 text-lg font-semibold mb-2" for="password">Kata Sandi</label>
                    <div class="flex items-center bg-bg-form border border-gray-300 rounded-lg">
                        <input type="password" name="password" id="password" placeholder="" class="w-full text-sm bg-bg-form px-3 py-2 border-none rounded-lg text-form focus:outline-none" aria-label="Password" />
                        <ion-icon name="lock-closed" class="pr-4  items-center"></ion-icon>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-custom2 text-lg font-semibold mb-2" for="confirmPassword">Konfirmasi Kata Sandi</label>
                    <div class="flex items-center bg-bg-form border border-gray-300 rounded-lg">
                        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="" class="w-full text-sm bg-bg-form px-3 py-2 border-none rounded-lg text-form focus:outline-none" aria-label="Confirm Password" />
                        <ion-icon name="lock-closed" class="pr-4  items-center"></ion-icon>
                    </div>
                </div>
                <!-- Register Button -->
                <div class="text-center">
                    <button type="submit" name="register" class="text-lg py-2 bg-custom-green-button w-40 text-white font-bold rounded-lg shadow-0 hover:bg-custom-green-button hover:opacity-80">Daftar</button>
                </div>
                <!-- Divider -->
                <div class="my-4 flex items-center">
                    <hr class="flex-grow border-t border-black" />
                    <span class="mx-4 text-black">atau</span>
                    <hr class="flex-grow border-t border-black" />
                </div>
                <!-- Social Media Buttons -->
                <div class="flex flex-col space-y-3">
                    <div class="relative w-full">
                        <button type="button" id="googleLogin" class="flex justify-center items-center w-full py-2 border border-green-600 rounded-full text-green-700 hover:bg-green-50 transition duration-300">Daftar dengan akun Google</button>
                        <img src="../assets/Google.webp" alt="Google Logo" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-6 w-6" />
                    </div>
                    <div class="relative w-full">
                        <button type="button" id="facebookLogin" class="flex justify-center items-center w-full py-2 border border-green-600 rounded-full text-green-700 hover:bg-green-50 transition duration-300">Daftar dengan akun Facebook</button>
                        <img src="../assets/Facebook.webp" alt="Google Logo" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-6 w-6" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<footer class="footer text-center">
    <p>&copy; 2024 Dialek.id. Semua hak dilindungi.</p>
</footer>
<script src=".../firebase.js"></script>
</body>
</html>