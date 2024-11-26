<?php 
    include("connect.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
        $name = $conn->quote($_POST['name']);
        $username = $conn->quote($_POST['username']);
        $email = $conn->quote($_POST['email']);
        $password = $conn->quote($_POST['password']);
        $confirmPassword = $conn->quote($_POST['confirmPassword']);

        if ($password !== $confirmPassword) {
            echo "<script>alert('Kata sandi tidak cocok!');</script>";
        } else {
            // Enkripsi kata sandi
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Masukkan data ke database
            $sql = "INSERT INTO users (name, username, email, password) VALUES ('$name', '$username', '$email', '$hashedPassword')";
            if ($conn->exec($sql)) {
                echo "<script>alert('Pendaftaran berhasil!'); window.location.href='./dashboardBatak.html';</script>";
            } else {
                $errorInfo = $conn->errorInfo();
                echo "<script>alert('Registrasi gagal: " . $errorInfo[2] . "');</script>";
            }
        }
    }

    header("Content-Type: application/json");

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
        $data = json_decode(file_get_contents("php://input"), true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firebaseUid = $data['firebase_uid'] ?? null;
            $name = trim($data['name'] ?? '');
            $email = trim($data['email'] ?? '');
            $password = $data['password'] ?? null;
            $username = trim($data['username'] ?? generateRandomUsername());
            $profileImage = $data['profile_image'] ?? 'default_profile_image_url';

            if (!$name || !$email || !$username) {
                echo json_encode(["success" => false, "message" => "Data tidak lengkap!"]);
                exit;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(["success" => false, "message" => "Email tidak valid!"]);
                exit;
            }

            $hashedPassword = $password ? password_hash($password, PASSWORD_BCRYPT) : "GOOGLE_SIGNUP";

            // Cek apakah email atau username sudah ada
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

            // Get the last inserted id
            $id_user = $conn->lastInsertId();

            echo json_encode(["success" => true, "message" => "Signup berhasil!", "id_user" => $id_user, "username" => $username, "profile_image" => $profileImage]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request method."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
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
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div class="logo font-irish m-0 text-2xl">dialek.id</div>
    </nav>
<section class="main flex flex-col mx-auto w-2/3 flex-grow">
    <div class="flex items-center justify-center px-4">
        <div class="bg-shadow p-6 rounded-lg">
            <h2 class="text-gradient text-4xl font-bold text-center mb-6 w-full">Daftar</h2>
            <form id="signup-form">
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
                        <input type="password" name="password" id="password" placeholder="*********" class="w-full text-sm bg-bg-form px-3 py-2 border-none rounded-lg text-form focus:outline-none" aria-label="Password" />
                        <ion-icon name="lock-closed" class="pr-4  items-center"></ion-icon>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-custom2 text-lg font-semibold mb-2" for="confirmPassword">Konfirmasi Kata Sandi</label>
                    <div class="input-container flex items-center bg-bg-form border border-gray-300 rounded-lg">
                        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="********" class="w-full text-sm bg-bg-form px-3 py-2 border-none rounded-lg text-form focus:outline-none" aria-label="Confirm Password" />
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
            </form>
        </div>
    </div>
</section>
<script type="module">

    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
    import { getAuth, GoogleAuthProvider, signInWithPopup, createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";
    import { getFirestore, doc, setDoc } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-firestore.js";
    
    const firebaseConfig = {
        apiKey: "AIzaSyCrmVDlBwRkkzP_rYY3mXBKw_ihrkV3tVM",
        authDomain: "dialek-6a219.firebaseapp.com",
        projectId: "dialek-6a219",
        storageBucket: "dialek-6a219.firebasestorage.app",
        messagingSenderId: "423916223695",
        appId: "1:423916223695:web:449bd44c54cad998d8cbba",
        measurementId: "G-4SLBH47WTM"
    };
    
    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const db = getFirestore(app);
    const googleProvider = new GoogleAuthProvider();

    // Signup form
    document.getElementById('signup-form').addEventListener('submit', async (event) => {
        event.preventDefault();

        const name = document.getElementById('name').value;
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (password !== confirmPassword) {
            alert("Kata sandi tidak cocok!");
            return;
        }

        try {
            const userCredential = await createUserWithEmailAndPassword(auth, email, password);
            const user = userCredential.user;

            // Simpan data pengguna ke Firestore
            await setDoc(doc(db, "users", user.uid), {
                name: name,
                username: username,
                email: email,
                userId: user.uid
            });

            alert("Pendaftaran berhasil!");
            window.location.href = './dashboardBatak.html';
        } catch (error) {
            console.error("Masalah error:", error.message);
            alert("Registrasi Gagal: " + error.message);
        }
    });

    document.getElementById('googleLogin').addEventListener('click', () => {
        signInWithPopup(auth, googleProvider)
            .then((result) => {
                const user = result.user;
                console.log("Pengguna google terdaftar:", user);
                window.location.href = './dashboardBatak.html';
            })
            .catch((error) => {
                console.error("Error with Google sign-in:", error.message);
                alert("Login dengan google gagal: " + error.message);
            });
    });
</script>
</body>
</html
