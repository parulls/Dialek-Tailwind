<?php
    require "connect.php";
    require '.functions.php';
    session_start();

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
                $_SESSION['user_id'] = $user['id'];
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
