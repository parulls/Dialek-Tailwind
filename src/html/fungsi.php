<?php
function registrasi($data) {
    global $conn;

    // Ambil dan filter data dari input
    $firebaseUid = trim($data['firebase_uid']);
    $name = trim($data['name']);
    $username = trim($data['username']);
    $email = trim($data['email']);
    $password = trim($data['password']);

    // Validasi input
    if (empty($firebaseUid) || empty($name) || empty($username) || empty($email) || empty($password)) {
        echo "<script>alert('Semua kolom harus diisi!');</script>";
        return false;
    }

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid!');</script>";
        return false;
    }

    // Validasi panjang password
    if (strlen($password) < 6) {
        echo "<script>alert('Password harus memiliki panjang minimal 6 karakter!');</script>";
        return false;
    }

    try {
        // Cek apakah username atau email sudah terdaftar
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
        ]);
        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Username atau email sudah terdaftar!');</script>";
            return false;
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Masukkan data ke database
        $stmt = $conn->prepare("INSERT INTO users (firebase_uid, name, username, email, password) 
                                VALUES (:firebase_uid, :name, :username, :email, :password)");
        $stmt->execute([
            ':firebase_uid' => $firebaseUid,
            ':name' => $name,
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
        ]);

        echo "<script>alert('Registrasi berhasil!');</script>";
        return true;

    } catch (PDOException $e) {
        // Tampilkan pesan error jika ada masalah pada database
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        return false;
    }
}
?>
