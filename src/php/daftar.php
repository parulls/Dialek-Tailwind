<?php
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");
    $data = json_decode(file_get_contents("php://input"), true);

    function generateRandomUsername($length = 7) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomUsername = '';
        for ($i = 0; $i < $length; $i++) {
            $randomUsername .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomUsername;
    }

    try {
        $firebaseUid = $data['firebase_uid'] ?? null;
        $name = trim($data['name'] ?? '');
        $username = trim($data['username'] ?? generateRandomUsername()); // Generate username jika kosong
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? null;
        $profileImage = $data['profile_image'] ?? 'default_profile_image_url';

        if (!$firebaseUid || !$name || !$username || !$email) {
            echo json_encode(["success" => false, "message" => "Data tidak lengkap!"]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success" => false, "message" => "Email tidak valid!"]);
            exit;
        }

        $hashedPassword = $password ? password_hash($password, PASSWORD_BCRYPT) : "GOOGLE_SIGNUP";

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
        $stmt->execute([':email' => $email, ':username' => $username]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => false, "message" => "Email atau username sudah digunakan!"]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO users (firebase_uid, name, email, password, username, profile_image) VALUES (:firebase_uid, :name, :email, :password, :username, :profileImage)");
        $stmt->execute([
            ':firebase_uid' => $firebaseUid,
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':username' => $username,
            ':profileImage' => $profileImage,
        ]);

        echo json_encode(["success" => true, "message" => "Signup berhasil!"]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
    }
    exit;
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
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .input-container {
            position: relative;
        }
        
        .input-container input {
            background-color: transparent;
            padding-left: 2.5rem; 
            padding-right: 10px; 
        }
        
        .input-container ion-icon {
            position: absolute;
            left: 10px; 
            color: black;
        }
    </style>
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen ">
    <nav class="flex items-center justify-between w-full p-12">
        <div id="landing-page" class="logo font-irish m-0 text-2xl cursor-pointer">dialek.id</div>
    </nav>
<section class="main flex flex-col mx-auto w-2/3 flex-grow">
    <div class="flex items-center justify-center px-4">
        <div class="bg-shadow p-6 rounded-lg">
            <h2 class="text-gradient text-4xl font-bold text-center mb-6 w-full">Daftar</h2>
            <form id="signup-form" >
                <div class="mb-4">
                    <label class="block text-custom2 text-lg font-semibold mb-2" for="nama">Nama</label>
                    <div class="input-container flex items-center bg-bg-form border border-gray-300 rounded-lg">
                        <input type="text" name="name" id="name" placeholder="John Doe" class="w-full text-sm bg-bg-form px-3 py-2 border-none rounded-lg text-form focus:outline-none" aria-label="Name" />
                        <ion-icon name="person" class="pr-4  items-center"></ion-icon>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-custom2 text-lg font-semibold mb-2" for="username">Nama Pengguna</label>
                    <div class="input-container flex items-center bg-bg-form border border-gray-300 rounded-lg">
                        <input type="text" name="username" id="username" placeholder="johndoe_2112" class="w-full text-sm bg-bg-form px-3 py-2 border-none rounded-lg text-form focus:outline-none" aria-label="Username" />
                        <ion-icon name="person" class="pr-4  items-center"></ion-icon>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-custom2 text-lg font-semibold mb-2" for="email">E-mail</label>
                    <div class="input-container flex items-center bg-bg-form border border-gray-300 rounded-lg">
                        <input type="email" name="email" id="email" placeholder="nama@domain.com" class="w-full text-sm bg-bg-form px-3 py-2 border-none rounded-lg text-form focus:outline-none" aria-label="Email" />
                        <ion-icon name="mail" class="pr-4  items-center"></ion-icon>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-custom2 text-lg font-semibold mb-2" for="password">Kata Sandi</label>
                    <div class="input-container flex items-center bg-bg-form border border-gray-300 rounded-lg">
                        <input type="password" name="password" id="password" placeholder="*" class="w-full text-sm bg-bg-form px-3 py-2 border-none rounded-lg text-form focus:outline-none" aria-label="Password" />
                        <ion-icon name="lock-closed" class="pr-4  items-center"></ion-icon>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-custom2 text-lg font-semibold mb-2" for="confirmPassword">Konfirmasi Kata Sandi</label>
                    <div class="input-container flex items-center bg-bg-form border border-gray-300 rounded-lg">
                        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="" class="w-full text-sm bg-bg-form px-3 py-2 border-none rounded-lg text-form focus:outline-none" aria-label="Confirm Password" />
                        <ion-icon name="lock-closed" class="pr-4  items-center"></ion-icon>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" name="register" class="text-lg py-2 bg-custom-green-button w-40 text-white font-bold rounded-lg shadow-0 hover:bg-custom-green-button hover:opacity-80">Daftar</button>
                </div>
                <div class="my-4 flex items-center">
                    <hr class="flex-grow border-t border-black" />
                    <span class="mx-4 text-black">atau</span>
                    <hr class="flex-grow border-t border-black" />
                </div>
                <div class="flex flex-col space-y-3">
                    <div class="relative w-full">
                        <button type="button" id="googleLogin" class="flex justify-center items-center w-full py-2 border border-green-600 rounded-full text-green-700 hover:bg-green-50 transition duration-300">Daftar dengan akun Google</button>
                        <img src="../assets/Google.webp" alt="Google Logo" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-6 w-6" />
                    </div>
                    <div class="relative w-full">
                        <button type="button" id="facebookLogin" class="flex justify-center items-center w-full py-2 border border-green-600 rounded-full text-green-700 hover:bg-green-50 transition duration-300">Daftar dengan akun Facebook</button>
                        <img src="../assets/Facebook.webp" alt="Facebook Logo" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-6 w-6" />
                    </div>
                </div>
                <div class="text-center mt-4">
                    <p class="text-black">Sudah Punya Akun? <a href="masuk.php" class="text-custom1 font-bold hover:underline">Masuk</a></p>
                </div>
            </form>
        </div>
    </div>
</section>
<script type="module">

import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
import { getAuth, createUserWithEmailAndPassword, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";

const firebaseConfig = {
    apiKey: "AIzaSyCrmVDlBwRkkzP_rYY3mXBKw_ihrkV3tVM",
    authDomain: "dialek-6a219.firebaseapp.com",
    projectId: "dialek-6a219",
    storageBucket: "dialek-6a219.appspot.com",
    messagingSenderId: "423916223695",
    appId: "1:423916223695:web:449bd44c54cad998d8cbba",
    measurementId: "G-4SLBH47WTM"
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const googleProvider = new GoogleAuthProvider();

document.getElementById("signup-form").addEventListener("submit", async (event) => {
    event.preventDefault();

    const name = document.getElementById("name").value.trim();
    const username = document.getElementById("username").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const confirmPassword = document.getElementById("confirmPassword").value.trim();

    if (!name || !username || !email || !password || !confirmPassword) {
        alert("Semua field wajib diisi.");
        return;
    }

    if (password !== confirmPassword) {
        alert("Kata sandi dan konfirmasi kata sandi tidak cocok.");
        return;
    }

    try {
        const userCredential = await createUserWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;

        const response = await fetch("./daftar.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                firebase_uid: user.uid,
                name: name,
                username: username,
                email: email,
                profile_image: "default_profile_image_url", // Default profile image
                password: password,
            }),
        });

        const result = await response.json();
        if (result.success) {
            localStorage.setItem("firebase_uid", user.uid);
            localStorage.setItem("username", result.username);
            localStorage.setItem("profileImage", result.profile_image);
            localStorage.setItem("email", email);
            localStorage.setItem("profileName", result.name);

            alert("Signup berhasil!");
            window.location.href = "./dashboard-batak.php"; // Redirect to dashboard
        } else {
            alert(result.message);
        }
    } catch (error) {
        alert("Terjadi kesalahan saat mendaftar: " + error.message);
    }
});

document.getElementById("googleLogin").addEventListener("click", async () => {
    try { 
        const result = await signInWithPopup(auth, googleProvider);
        const user = result.user;

        const response = await fetch("./daftar.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                firebase_uid: user.uid,
                name: user.displayName || "User",
                email: user.email,
                profile_image: user.photoURL || "default_profile_image_url",
                username: null, 
                password: null, 
            }),
        });

        const resultData = await response.json();
        if (resultData.success) {
            localStorage.setItem("firebase_uid", user.uid);
            localStorage.setItem("username", resultData.username);
            localStorage.setItem("profileImage", resultData.profile_image);
            localStorage.setItem("email", user.email);
            localStorage.setItem("profileName", resultData.name);

            alert("Signup berhasil dengan Google!");
            window.location.href = "./dashboard-batak.php"; // Redirect to dashboard
        } else {
            alert(resultData.message);
        }
    } catch (error) {
        alert("Google Signup Error: " + error.message);
    }
});

</script>
</body>
</html>
