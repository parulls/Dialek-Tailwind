<?php
include("connect.php");
header("Content-Type: text/html; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");
    try {
        $data = json_decode(file_get_contents("php://input"), true);
        $action = $data['action'] ?? null;

        if ($action === "getUserData") {
            $firebaseUid = $data['firebase_uid'] ?? null;

            if (!$firebaseUid) {
                echo json_encode(["success" => false, "message" => "Firebase UID tidak valid."]);
                exit;
            }

            $stmt = $conn->prepare("
                SELECT name, username, email, phone, 
                       COALESCE(profile_image, '../assets/pp.webp') AS profile_image 
                FROM users 
                WHERE firebase_uid = :firebase_uid
            ");
            $stmt->execute([':firebase_uid' => $firebaseUid]);

            if ($stmt->rowCount() > 0) {
                $userData = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode(["success" => true, "data" => $userData]);
            } else {
                echo json_encode(["success" => false, "message" => "Pengguna tidak ditemukan."]);
            }
            exit;
        } elseif ($action === "updateProfile") {
            $firebaseUid = $data['firebase_uid'] ?? null;
            $name = trim($data['name'] ?? '');
            $username = trim($data['username'] ?? '');
            $email = trim($data['email'] ?? '');
            $phone = trim($data['phone'] ?? '');
            $profileImage = $data['profileImage'] ?? '../assets/pp.webp';

            if (!$firebaseUid || !$name || !$username || !$email || !$phone) {
                echo json_encode(["success" => false, "message" => "Semua field wajib diisi."]);
                exit;
            }

            $stmt = $conn->prepare("SELECT firebase_uid FROM users WHERE firebase_uid = :firebase_uid");
            $stmt->execute([':firebase_uid' => $firebaseUid]);

            if ($stmt->rowCount() === 0) {
                echo json_encode(["success" => false, "message" => "Firebase UID tidak ditemukan."]);
                exit;
            }

            $stmt = $conn->prepare("SELECT id_user FROM users WHERE username = :username AND firebase_uid != :firebase_uid");
            $stmt->execute([':username' => $username, ':firebase_uid' => $firebaseUid]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => false, "message" => "Username sudah digunakan oleh akun lain."]);
                exit;
            }

            $stmt = $conn->prepare("
                UPDATE users
                SET name = :name, username = :username, email = :email, phone = :phone, profile_image = :profileImage
                WHERE firebase_uid = :firebase_uid
            ");
            $stmt->execute([
                ':name' => $name,
                ':username' => $username,
                ':email' => $email,
                ':phone' => $phone,
                ':profileImage' => $profileImage,
                ':firebase_uid' => $firebaseUid,
            ]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => true, "message" => "Profil berhasil diperbarui."]);
            } else {
                echo json_encode(["success" => false, "message" => "Tidak ada perubahan yang disimpan."]);
            }
            exit;
        }  elseif ($action === "deleteAccount") {
            $firebaseUid = $data['firebase_uid'] ?? null;

            if (!$firebaseUid) {
                echo json_encode(["success" => false, "message" => "Firebase UID tidak valid."]);
                exit;
            }

        // Hapus akun dari database
        $stmt = $conn->prepare("DELETE FROM users WHERE firebase_uid = :firebase_uid");
        $stmt->execute([':firebase_uid' => $firebaseUid]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => true, "message" => "Akun berhasil dihapus."]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal menghapus akun atau akun tidak ditemukan."]);
        }
        exit;
    }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Terjadi kesalahan: " . $e->getMessage()]);
        exit;
    }
    exit;
}  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"> 
    <title>Dialek.id</title>
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div id="home" class="logo font-irish m-0 text-2xl cursor-pointer">dialek.id</div>
    </nav>

    <section class="main flex flex-col mx-auto w-4/5 items-center flex-grow space-y-16">
        <h1 class="text-3xl font-bold text-custom2 mb-6">Pengaturan</h1>
        <div class="flex flex-col items-center p-8 mt-10 w-11/12 md:w-8/12 lg:w-6/12 bg-white bg-opacity-30 rounded-3xl shadow-lg font-inter border-[1.5px] border-bar">
            <div class="relative">
                <label for="profile-picture" class="relative cursor-pointer">
                    <img id="profile-image" src="../assets/pp.webp" alt="Profile Image" class="w-24 h-24 rounded-full bg-gray-200 object-cover">
                    <input type="file" id="profile-picture" accept="image/*" class="hidden" onchange="previewProfilePicture(event)">
                    <span class="absolute bottom-0 right-0 bg-white rounded-full p-1 text-green-600 ml-20">
                        <i class="fa fa-edit"></i>
                    </span>
                </label>
            </div>
            <form id="settings-form" class="w-max mt-6 space-y-4">
                <div class="flex flex-row justify-center space-x-7">
                    <div class="flex flex-col">
                        <label class="text-gray-600" for="name">Nama</label>
                        <input type="text" id="name" class="max-w-96 p-2 border rounded-lg" placeholder="Nama" required>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-gray-600" for="username">Username</label>
                        <input type="text" id="username" class="w-full p-2 border rounded-md" placeholder="Username" required>
                    </div>
                </div>
                <div class="flex flex-row justify-center space-x-7">
                    <div class="flex flex-col">
                        <label class="text-gray-600" for="email">Email</label>
                        <input type="email" id="email" class="w-full p-2 border rounded-md" placeholder="Email">
                    </div>
                    <div class="flex flex-col">
                        <label class="text-gray-600" for="phone">Telepon</label>
                        <input type="tel" id="phone" class="w-full p-2 border rounded-md" placeholder="Telepon" required>
                    </div>
                </div>
            </form>
            <div class="flex justify-between w-full mt-6 space-x-4">
                <button onclick="deleteAccount()" class="flex-1 py-2 bg-custom4 text-white rounded-full hover:bg-red-900">Hapus Akun</button>
                <button onclick="saveProfile()" class="flex-1 py-2 bg-custom1 text-white rounded-full hover:bg-green-900">Simpan</button>
            </div>
        </div>
    </section>

    <script>

        async function loadProfile() {
            const firebaseUid = localStorage.getItem("firebase_uid");
            if (!firebaseUid) {
                alert("Silakan login terlebih dahulu.");
                window.location.href = "./masuk.php";
                return;
            }
            try {
                const response = await fetch(window.location.href, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ action: "getUserData", firebase_uid: firebaseUid }),
                });
                const result = await response.json();
                if (result.success) {
                    const user = result.data;
                    document.getElementById("name").value = user.name || "";
                    document.getElementById("username").value = user.username || "";
                    document.getElementById("email").value = user.email || "";
                    document.getElementById("phone").value = user.phone || "";
                    document.getElementById("profile-image").src = user.profile_image || "../assets/pp.webp";
                } else {
                    alert(result.message);
                }
            } catch (error) {
                alert("Terjadi kesalahan saat memuat profil: " + error.message);
            }
        }

        async function saveProfile() {
            const firebaseUid = localStorage.getItem("firebase_uid");
            const name = document.getElementById("name").value.trim();
            const username = document.getElementById("username").value.trim();
            const email = document.getElementById("email").value.trim();
            const phone = document.getElementById("phone").value.trim();
            const profileImage = document.getElementById("profile-image").src;

            if (!name || !username || !email || !phone) {
                alert("Semua field wajib diisi.");
                return;
            }

            try {
                const response = await fetch(window.location.href, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        action: "updateProfile",
                        firebase_uid: firebaseUid,
                        name,
                        username,
                        email,
                        phone,
                        profileImage,
                    }),
                });
                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    localStorage.setItem("profileName", name);
                    localStorage.setItem("username", username);
                    localStorage.setItem("email", email);
                    localStorage.setItem("phone", phone);
                } else {
                    alert(result.message);
                }
            } catch (error) {
                alert("Terjadi kesalahan saat menyimpan profil: " + error.message);
            }
        }

        function previewProfilePicture(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById("profile-image").src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        async function deleteAccount() {
        if (confirm("Apakah Anda yakin ingin menghapus akun ini?")) {
        const firebaseUid = localStorage.getItem("firebase_uid");

        if (!firebaseUid) {
            alert("Anda belum login. Silakan login terlebih dahulu.");
            window.location.href = "./masuk.php";
            return;
        }

        try {
            const response = await fetch(window.location.href, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ action: "deleteAccount", firebase_uid: firebaseUid }),
            });
            const result = await response.json();

            if (result.success) {
                alert(result.message);
                // Hapus data dari localStorage
                localStorage.clear();
                window.location.href = "./daftar.php"; // Redirect ke halaman daftar
            } else {
                alert(result.message);
            }
        } catch (error) {
            alert("Terjadi kesalahan saat menghapus akun: " + error.message);
        }
     }
    }



        document.getElementById("home").addEventListener("click", () => {
            window.location.href = "./dashboard-batak.php";
        });

        document.addEventListener("DOMContentLoaded", loadProfile);
    </script>
</body>
</html>


