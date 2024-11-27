<?php
function registrasi($data) {
    global $conn;

    $firebaseUid = $data['firebase_uid'];
    $name = $data['name'];
    $username = $data['username'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_BCRYPT);

    // Validasi input
    if (empty($firebaseUid) || empty($name) || empty($username) || empty($email)) {
        echo "<script>alert('Semua kolom harus diisi!');</script>";
        return false;
    }

    try {
        // Masukkan data ke database
        $stmt = $conn->prepare("INSERT INTO users (firebase_uid, name, username, email, password) VALUES (:firebase_uid, :name, :username, :email, :password)");
        $stmt->execute([
            ':firebase_uid' => $firebaseUid,
            ':name' => $name,
            ':username' => $username,
            ':email' => $email,
            ':password' => $password,
        ]);
        return true;
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        return false;
    }
}

?>
