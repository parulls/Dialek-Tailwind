<?php
session_start();
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']); // Email atau username
    $password = $_POST['password'];

    try {
        // Cek apakah input adalah email atau username
        $query = strpos($login, '@') !== false
            ? "SELECT * FROM users WHERE email = :login"
            : "SELECT * FROM users WHERE username = :login";

        $stmt = $conn->prepare($query);
        $stmt->execute([':login' => $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Set session jika login berhasil
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];

            // Redirect ke dashboard
            header("Location: dashboardBatak.html");
            exit();
        } else {
            echo "<script>alert('Email atau kata sandi salah!');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Terjadi kesalahan, coba lagi nanti.');</script>";
    }
}

?>
