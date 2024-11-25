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
?>