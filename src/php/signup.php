<?php
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $firebaseUid = $data['firebase_uid'] ?? null;
        $name = trim($data['name'] ?? '');
        $username = trim($data['username'] ?? '');
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

        // Hash password
        $hashedPassword = $password ? password_hash($password, PASSWORD_BCRYPT) : "GOOGLE_SIGNUP";

        // Cek apakah email atau username sudah digunakan
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
        $stmt->execute([':email' => $email, ':username' => $username]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => false, "message" => "Email atau username sudah digunakan!"]);
            exit;
        }

        // Simpan data pengguna ke database
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
    <script type="module" src="https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js"></script>
    <script type="module" src="https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js"></script>
    <script type="module" src="https://www.gstatic.com/firebasejs/11.0.1/firebase-firestore.js"></script>
    <link rel="stylesheet" href="../styles/style2.css">
    <style>
        .input-container {
            position: relative;
        }
        .input-container input {
            background-color: transparent;
            padding-left: 2.5rem;
        }
        .input-container ion-icon {
            position: absolute;
            left: 10px;
            color: black;
        }
    </style>
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div class="logo font-irish m-0 text-2xl">dialek.id</div>
    </nav>
    <section class="main flex flex-col mx-auto w-2/3 flex-grow">
        <div class="flex items-center justify-center px-4">
            <div class="bg-shadow p-6 rounded-lg">
                <h2 class="text-gradient text-4xl font-bold text-center mb-6">Daftar</h2>
                <form id="signup-form">
                    <div class="mb-4">
                        <label class="block text-lg font-semibold mb-2">Nama</label>
                        <div class="input-container">
                            <input type="text" id="name" placeholder="John Doe" class="w-full px-3 py-2 border rounded-lg" />
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-lg font-semibold mb-2">Nama Pengguna</label>
                        <div class="input-container">
                            <input type="text" id="username" placeholder="johndoe_2112" class="w-full px-3 py-2 border rounded-lg" />
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-lg font-semibold mb-2">E-mail</label>
                        <div class="input-container">
                            <input type="email" id="email" placeholder="nama@domain.com" class="w-full px-3 py-2 border rounded-lg" />
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-lg font-semibold mb-2">Kata Sandi</label>
                        <div class="input-container">
                            <input type="password" id="password" placeholder="********" class="w-full px-3 py-2 border rounded-lg" />
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="px-6 py-2 bg-green-500 text-white font-bold rounded-lg">Daftar</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module">
        // Import Firebase modules
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
import { getAuth, createUserWithEmailAndPassword, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";

// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyCrmVDlBwRkkzP_rYY3mXBKw_ihrkV3tVM",
    authDomain: "dialek-6a219.firebaseapp.com",
    projectId: "dialek-6a219",
    storageBucket: "dialek-6a219.appspot.com",
    messagingSenderId: "423916223695",
    appId: "1:423916223695:web:449bd44c54cad998d8cbba",
    measurementId: "G-4SLBH47WTM"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const googleProvider = new GoogleAuthProvider();

// Signup form submission handler
document.getElementById("signup-form").addEventListener("submit", async (event) => {
    event.preventDefault();

    // Get form data
    const name = document.getElementById("name").value.trim();
    const username = document.getElementById("username").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const confirmPassword = document.getElementById("confirmPassword").value.trim();

    // Validate form inputs
    if (!name || !username || !email || !password || !confirmPassword) {
        alert("Semua field wajib diisi.");
        return;
    }
    if (password !== confirmPassword) {
        alert("Kata sandi dan konfirmasi kata sandi tidak cocok.");
        return;
    }

    try {
        // Create user with Firebase Authentication
        const userCredential = await createUserWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;

        // Send user data to backend
        const response = await fetch("http://localhost/your-backend-endpoint.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                firebase_uid: user.uid,
                name: name,
                username: username,
                email: email,
                password: password,
                profile_image: "default_profile_image_url", 
            }),
        });

        const result = await response.json();

        if (result.success) {
            alert("Pendaftaran berhasil!");
            localStorage.setItem("firebase_uid", user.uid);
            localStorage.setItem("name", name);
            localStorage.setItem("username", username);
            localStorage.setItem("email", email);
            localStorage.setItem("profileImage", "default_profile_image_url");
            window.location.href = "dashboardBatak.php";
        } else {
            alert("Pendaftaran gagal: " + result.message);
        }
    } catch (error) {
        console.error("Error during signup:", error.message);
        alert("Terjadi kesalahan: " + error.message);
    }
});

// Google signup/login handler
document.getElementById("googleLogin").addEventListener("click", async () => {
    try {
        // Sign in with Google popup
        const result = await signInWithPopup(auth, googleProvider);
        const user = result.user;

        // Send Google user data to backend
        const response = await fetch("http://localhost/your-backend-endpoint.php", {
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
            alert("Pendaftaran dengan Google berhasil!");
            localStorage.setItem("firebase_uid", user.uid);
            localStorage.setItem("name", user.displayName || "User");
            localStorage.setItem("email", user.email);
            localStorage.setItem("profileImage", user.photoURL || "default_profile_image_url");
            window.location.href = "dashboardBatak.php"; 
        } else {
            alert("Pendaftaran dengan Google gagal: " + resultData.message);
        }
    } catch (error) {
        console.error("Error during Google signup:", error.message);
        alert("Terjadi kesalahan: " + error.message);
    }
});

    </script>
</body>
</html>
