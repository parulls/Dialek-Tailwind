<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login']; // Email atau username
        $password = $_POST['password'];

        try {
            // Periksa apakah input adalah email atau username
            $query = strpos($login, '@') !== false
                ? "SELECT * FROM users WHERE email = :login"
                : "SELECT * FROM users WHERE username = :login";

            $stmt = $conn->prepare($query);
            $stmt->execute([':login' => $login]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['id_user'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect ke halaman dashboard
                header("Location: dashboardBatak.html");
                exit();
            } else {
                $error = "Email atau kata sandi salah.";
            }
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan, coba lagi nanti.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dialek.id - Masuk</title>
    <script type="module" src="https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js"></script>
    <script type="module" src="https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js"></script>
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
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full p-12">
        <div class="logo font-irish m-0 text-2xl">dialek.id</div>
    </nav>
    <section class="main flex flex-col mx-auto w-4/5 flex-grow">
        <div data-aos="fade-up" class="flex items-center justify-center px-4">
            <div class="bg-shadow p-6 rounded-lg">
                <h2 class="text-4xl font-bold text-center text-gradient mb-6 w-full">Masuk</h2>
                <form id="login-form" action="" method="post">
                    <div class="mb-4">
                        <label class="block text-custom2 text-lg font-semibold mb-2" for="login">E-mail atau Username</label>
                        <div class="input-container flex items-center bg-bg-form border border-gray-300 rounded-lg">
                            <input type="text" name="login" id="login" placeholder="nama@domain.com atau username" class="w-full text-sm bg-bg-form px-3 py-2 border-none rounded-lg text-form focus:outline-none" aria-label="Email or Username" />
                            <ion-icon name="mail" class="pr-4 items-center"></ion-icon>
                        </div>
                        <p id="login-error" class="text-red-500 text-sm hidden">Email atau kata sandi salah.</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-custom2 text-lg font-semibold mb-2" for="password">Kata Sandi</label>
                        <div class="input-container flex items-center bg-bg-form border border-gray-300 rounded-lg">
                            <input type="password" name="password" id="password" placeholder="*********" class="w-full text-sm bg-bg-form px-3 py-2 border-none rounded-lg text-form focus:outline-none" aria-label="Password" />
                            <ion-icon name="lock-closed" class="pr-4 items-center"></ion-icon>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" id="submit" class="text-lg py-2 bg-custom-green-button w-40 text-white font-bold rounded-lg shadow-0 hover:bg-custom-green-button hover:opacity-80">Masuk</button>
                    </div>
                    <div class="my-4 flex items-center">
                        <hr class="flex-grow border-t border-black" />
                        <span class="mx-4 text-black">atau</span>
                        <hr class="flex-grow border-t border-black" />
                    </div>
                    <div class="flex flex-col space-y-3">
                        <div class="relative w-full">
                            <button type="button" id="google-login" class="flex justify-center items-center w-full py-2 border border-green-600 rounded-full text-green-700 hover:bg-green-50 transition duration-300">Masuk dengan akun Google</button>
                            <img src="../assets/Google.webp" alt="Google Logo" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-6 w-6" />
                        </div>
                        <div class="relative w-full">
                            <button type="button" id="facebook-login" class="flex justify-center items-center w-full py-2 border border-green-600 rounded-full text-green-700 hover:bg-green-50 transition duration-300">Masuk dengan akun Facebook</button>
                            <img src="../assets/Facebook.webp" alt="Facebook Logo" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-6 w-6" />
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <p class="text-black">Belum Punya Akun? <a href="./SignUp.html" class="text-custom1 font-bold hover:underline">Daftar</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script type="module">
        AOS.init();
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
        import { getAuth, signInWithPopup, signInWithEmailAndPassword, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";
        import { getFirestore, doc, getDoc } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-firestore.js";
    
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
        const db = getFirestore(app);
        const googleProvider = new GoogleAuthProvider();
    
        // Login dengan Google
        document.getElementById('google-login').addEventListener('click', () => {
            signInWithPopup(auth, googleProvider)
                .then((result) => {
                    alert("Login dengan Google berhasil!");
                    window.location.href = './dashboardBatak.html';
                })
                .catch((error) => {
                    console.error("Error with Google sign-in:", error.message);
                    alert("Login Google gagal: " + error.message);
                });
        });
    
        // Login dengan email dan password
        document.getElementById('login-form').addEventListener('submit', async function (event) {
            event.preventDefault();
            const loginInput = document.getElementById('login').value;
            const password = document.getElementById('password').value;

            let email = loginInput;

            // Check if input is a username
            if (!loginInput.includes("@")) {
                try {
                    // Fetch email based on username from Firestore
                    const userDoc = await getDoc(doc(db, "usernames", loginInput));
                    if (userDoc.exists()) {
                        email = userDoc.data().email;
                    } else {
                        document.getElementById('login-error').classList.remove('hidden');
                        document.getElementById('login-error').innerText = "Username tidak ditemukan.";
                        return;
                    }
                } catch (error) {
                    console.error("Error fetching email for username:", error);
                    document.getElementById('login-error').classList.remove('hidden');
                    document.getElementById('login-error').innerText = "Terjadi kesalahan, coba lagi.";
                    return;
                }
            }

            // Firebase sign-in with email and password
            signInWithEmailAndPassword(auth, email, password)
                .then((userCredential) => {
                    alert("Login berhasil!");
                    window.location.href = './dashboardBatak.html';
                })
                .catch((error) => {
                    console.error("Error signing in:", error);
                    document.getElementById('login-error').classList.remove('hidden');
                    document.getElementById('login-error').innerText = error.code === 'auth/too-many-requests' 
                        ? "Terlalu banyak permintaan, coba lagi nanti." 
                        : "Email atau kata sandi salah.";
                });
        });
    </script>
    
</body>
</html>