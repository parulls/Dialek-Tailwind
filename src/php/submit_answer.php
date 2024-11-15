<?php
include("connect.php"); // Pastikan file ini berisi koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $user_id = $_POST['user_id'];
    $question_id = $_POST['question_id'];
    $answer_id = $_POST['answer_id'];

    // Ambil nilai is_correct dari tabel answers berdasarkan answer_id
    $sql_answer = "SELECT is_correct FROM answers WHERE id = $answer_id";
    $result_answer = $conn->query($sql_answer);

    if ($result_answer->num_rows > 0) {
        $answer_data = $result_answer->fetch_assoc();
        $is_correct = $answer_data['is_correct'];

        // Query untuk menyimpan jawaban ke tabel user_answers dengan nilai is_correct
        $sql = "INSERT INTO user_answers (user_id, question_id, answer_id, is_correct, created_at, updated_at)
                VALUES ('$user_id', '$question_id', '$answer_id', '$is_correct', NOW(), NOW())";

        if ($conn->query($sql) === TRUE) {
            echo header('Location: ../html/LatihanSoal2.html');
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: Jawaban tidak ditemukan.";
    }

    $conn->close();
}
?>
