<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userWord = strtolower(trim($_POST['user_word']));
    $correctWord = strtolower(trim($_POST['correct_word']));

    if ($userWord === $correctWord) {
        echo "<script>alert('Selamat! Kata benar: $correctWord'); window.location.href='./index.php';</script>";
    } else {
        echo "<script>alert('Oops! Kata salah. Jawaban yang benar adalah: $correctWord'); window.location.href='./index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit();
}
?>
