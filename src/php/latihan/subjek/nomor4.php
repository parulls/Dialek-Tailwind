<?php 
    include("../../connect.php"); // Pastikan file ini berisi koneksi ke database.
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
        <div class="flex items-center m-0 font-semibold text-custom2">
            <p id="account-username" class="px-4 text-xl">username</p>
            <i class="fa-solid fa-user text-2xl"></i> 
        </div>
    </nav>

    <section class="main flex flex-col mx-auto w-4/5 items-center flex-grow space-y-16">
        <div id="soal" class="flex flex-col items-center text-sm p-4 text-custom2 bg-transparent shadow-2xl opacity-90 min-h-[500px] h-full w-full">
            <div class="flex justify-center items-center font-bold text-lg">
                <p>Latihan Soal</p>
            </div>
            <div class="bg-custom7 w-[70%] flex justify-start items-center mb-12 mt-28 px-4">
                <!-- Bagian PHP untuk menampilkan soal -->
                <?php
                // Query untuk mengambil soal dengan id = 4
                $question_id = 4; // ID soal yang ingin diambil
                $sql = "SELECT id, question_text FROM questions WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $question_id, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Menampilkan soal
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo "<p class='text-lg font-semibold mb-4'>" . $row['question_text'] . "</p>";
                } else {
                    echo "<p class='text-lg font-semibold mb-4'>Soal dengan ID $question_id tidak ditemukan</p>";
                }
                ?>
            </div>

            <div class="flex justify-between w-[70%] mb-40">
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
                        echo "<button type='submit' class='option button-option w-32'>" . $answer['answer_text'] . "</button>";
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
            window.location.href = "nomor3.php";
        });

        selanjutnyaButton.addEventListener("click", () => {
            window.location.href = "nomor5.php";
        });
    </script>
</body>
</html>
