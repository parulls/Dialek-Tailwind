<?php 
    include("../../connect.php");
    
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
    <link rel="stylesheet" href="../../../styles/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body class="bg-custom-radial font-inter flex flex-col min-h-screen">
    <nav class="flex items-center justify-between w-full px-12 py-12">
        <div class="logo font-irish m-0 text-2xl">dialek.id</div>
        <div id="profile-button" class="flex items-center m-0 font-semibold text-custom2">
            <p id="account-username" class="px-4 text-xl">memuat...</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>

    <section class="main flex flex-col mx-auto w-11/12 sm:w-4/5 items-center flex-grow space-y-8 sm:space-y-16">
        <div id="soal" class="flex flex-col items-center text-sm p-4 text-custom2 bg-white bg-opacity-30 rounded-3xl shadow-lg min-h-[300px] sm:min-h-[500px] h-auto w-full border border-custom2 justify-center">
            <!-- Judul Soal -->
            <div class="flex justify-center items-center font-bold text-xl sm:text-2xl md:text-3xl pt-8 sm:pt-12 font-poppins text-center">
                <p>Pilih sambungan kata yang tepat</p>
            </div>
            <div class="bg-custom7 w-full sm:w-4/5 lg:w-[70%] flex justify-start items-center mb-8 sm:mb-12 mt-16 sm:mt-28 px-4 py-3 rounded-lg">
                <!-- Bagian PHP untuk menampilkan soal -->
                <?php
                // Query untuk mengambil soal dengan id = 1
                $question_id = 5; // ID soal yang ingin diambil
                $sql = "SELECT id, question_text FROM questions WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $question_id, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Menampilkan soal
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo "<p class='text-base sm:text-lg font-semibold p-2'>" . $row['question_text'] . "</p>";
                } else {
                    echo "<p class='text-base sm:text-lg font-semibold p-2'>Soal dengan ID $question_id tidak ditemukan</p>";
                }
                ?>
            </div>

            <div class="flex flex-col sm:flex-row sm:justify-between items-center w-full sm:w-4/5 lg:w-[70%] space-y-4 sm:space-y-0 sm:space-x-4 mb-20 sm:mb-40">
                <!-- Bagian PHP untuk menampilkan pilihan jawaban -->
                <?php
                // Query untuk mengambil jawaban berdasarkan question_id
                $sql_answers = "SELECT id, answer_text FROM answers WHERE question_id = :question_id";
                $stmt_answers = $conn->prepare($sql_answers);
                $stmt_answers->bindParam(':question_id', $question_id, PDO::PARAM_INT);
                $stmt_answers->execute();

                if ($stmt_answers->rowCount() > 0) {
                    while ($answer = $stmt_answers->fetch(PDO::FETCH_ASSOC)) {
                        // Membuat formulir untuk setiap jawaban
                        echo "<form method='POST' action='' class='inline-block'>";
                        echo "<input type='hidden' name='user_id' value='1'>"; // Sesuaikan user_id sesuai dengan sesi login
                        echo "<input type='hidden' name='question_id' value='$question_id'>";
                        echo "<input type='hidden' name='answer_id' value='" . $answer['id'] . "'>";
                        echo "<button type='submit' class='option button-option w-28 sm:w-32 h-10 sm:h-11 text-sm sm:text-lg'>" . $answer['answer_text'] . "</button>";
                        echo "</form>";
                    }
                } else {
                    echo "<p>Tidak ada pilihan jawaban</p>";
                }

                $conn = null;
                ?>
            </div>
        </div>
    </section>

    <footer class="flex items-center justify-between w-full px-4 py-4">
        <button id="kembali-button" class="button-custom2 text-sm mx-6">Kembali</button>
        <button id="selanjutnya-button" class="button-custom2 text-sm mx-6">Selanjutnya</button>
    </footer>

    <script>
        const kembaliButton = document.getElementById("kembali-button");
        const selanjutnyaButton = document.getElementById("selanjutnya-button");

        kembaliButton.addEventListener("click", () => {
            window.location.href = "nomor4.php";
        });

        selanjutnyaButton.addEventListener("click", () => {
            window.location.href = "../../simpan_rating.php";
        });

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
        alert("Terjadi kesalahan saat memuat data pengguna.");
    }

    document.getElementById("loading-bar").style.width = "0";

});
    </script>
</body>
</html>
