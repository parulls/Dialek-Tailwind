<?php
    include("connect.php");

    // Periksa apakah ini adalah permintaan POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header("Content-Type: application/json");
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $firebaseUid = $data['firebase_uid'] ?? null;
    
            if ($firebaseUid) {
                $stmt = $conn->prepare("
                    SELECT name, username, email, phone, 
                           COALESCE(profile_image, '../assets/pp.webp') AS profile_image 
                    FROM users 
                    WHERE firebase_uid = :firebase_uid
                ");
                $stmt->execute([':firebase_uid' => $firebaseUid]);
    
                if ($stmt->rowCount() > 0) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo json_encode(["success" => true, "user" => $user]);
                    exit;
                } else {
                    echo json_encode(["success" => false, "message" => "Firebase UID tidak ditemukan."]);
                    exit;
                }
            } else {
                echo json_encode(["success" => false, "message" => "Firebase UID tidak valid."]);
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "Kesalahan pada database: " . $e->getMessage()]);
            exit;
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => "Terjadi kesalahan: " . $e->getMessage()]);
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dialek.Id</title>
    <link rel="stylesheet" href="../styles/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        .stars-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .stars-container i {
            border-color: #34343B;
        }
        .stars-container i.active {
            color: #FFD700; 
        }        
    </style>
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div class="logo font-irish m-0 text-2xl">dialek.id</div>
        <div class="flex items-center m-0 font-semibold text-custom2">
            <p id="account-username" class="px-4 text-xl">memuat...</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>
    
    <section class="main flex flex-col mx-auto w-4/5 items-center flex-grow space-y-8">
        <div class="stars-container w-2/4">
            <div class="stars flex justify-center mb-6 md:text-7xl sm:text-5xl">
                <i class="fa-regular fa-star cursor-pointer"></i>
                <i class="fa-regular fa-star cursor-pointer"></i>
                <i class="fa-regular fa-star cursor-pointer"></i>
                <i class="fa-regular fa-star cursor-pointer"></i>
                <i class="fa-regular fa-star cursor-pointer"></i>
            </div>
            <textarea name="komentar" id="komentar-materi" class="bg-custom6 opacity-50 min-w-96 min-h-60 p-4 font-semibold text-xl rounded-3xl placeholder-white text-white border-black focus:outline-none focus:ring-2" placeholder="Tuliskan Komentar"></textarea>
        </div>
        <div class="flex items-center justify-center w-full px-4 py-4">
            <button id="kirim-button" class="button-custom w-1/5 h-10 rounded-md text-sm mx-6">
                Kirim
            </button>
        </div>
    </section>
    <footer class="flex items-center justify-between w-full px-4 py-4">
        <button id="kembali-button" class="button-custom2 text-sm mx-6">
            Kembali
        </button>
        <button id="selanjutnya-button" class="button-custom2 text-sm mx-6">
            Selanjutnya
        </button>
    </footer>
    <script>
        const stars = document.querySelectorAll(".stars i");
        const kembali = document.getElementById("kembali-button");
        const selanjutnya = document.getElementById("selanjutnya-button");
        const kirim = document.getElementById("kirim-button");
        const komentar = document.getElementById("komentar-materi");

        let ratingGiven = false;
        let komentarGiven = false;

        // Kembali ke halaman sebelumnya
        kembali.addEventListener("click", () => {
            window.location.href = "latihan/subjek/nomor5.php";
        });

        // Menandai rating telah diberikan
        stars.forEach((star, index1) => {
            star.addEventListener("click", () => { 
                stars.forEach((star, index2) => {
                    if (index1 >= index2) {
                        star.classList.remove("fa-regular");
                        star.classList.add("fa-solid", "active");
                    } else {
                        star.classList.remove("fa-solid", "active");
                        star.classList.add("fa-regular");
                    }
                });
                ratingGiven = true;
                enableNextButton();
            });
        });

        // Fungsi untuk mengaktifkan tombol Selanjutnya
        function enableNextButton() {
            if (ratingGiven && komentarGiven) {
                selanjutnya.disabled = false;
                selanjutnya.classList.remove("opacity-50");
            } else {
                selanjutnya.disabled = true;
                selanjutnya.classList.add("opacity-50");
            }
        }

        // Validasi untuk mengirim komentar
        kirim.addEventListener("click", () => {
            if (komentar.value.trim() === "") {
                alert("Komentar tidak boleh kosong!");
            } else if (!ratingGiven) {
                alert("Silakan beri rating sebelum mengirim komentar!");
            } else {
                alert("Komentar berhasil dikirim!");
                komentarGiven = true;
                enableNextButton();

                // Reset komentar setelah dikirim
                komentar.value = "";
            }
        });

        // Cek apakah bisa lanjut ke halaman berikutnya
        selanjutnya.addEventListener("click", () => {
            if (ratingGiven && komentarGiven) {
                window.location.href = "dashboard-batak.php";
            } else {
                alert("Harap isi komentar dan beri rating sebelum melanjutkan.");
            }
        });

        // Inisialisasi tombol Selanjutnya saat pertama kali halaman dimuat
        enableNextButton();

        document.addEventListener("DOMContentLoaded", async () => {
        const firebaseUid = localStorage.getItem("firebase_uid");

        try {
            const response = await fetch(window.location.href, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ firebase_uid: firebaseUid }),
            });

            const result = await response.json();
            if (result.success) {
                const userData = result.user;
                document.getElementById("account-username").textContent = `${userData.username || "username"}`;

            } else {
                alert("Gagal memuat data pengguna: " + result.message);
                window.location.href = "login.php";
            }
        } catch (error) {
            console.error("Fetch Error:", error);
        }

        document.getElementById("loading-bar").style.width = "0";

    });
    </script>
</body>
</html>