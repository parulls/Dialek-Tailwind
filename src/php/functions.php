<?php
    // function query($query) {
    //     global $conn;
    //     $result = mysqli_query($conn, $query);
    //     $rows = [];
    //     while ($row = mysqli_fetch_assoc($result)) {
    //         $rows[] = $row;
    //     }
    //     return $rows;
    // }
    
    function registrasi($data) {
    global $conn;

    $name = strtolower(stripslashes($data["name"]));
    $username = strtolower(stripslashes($data["username"]));
    $email = strtolower(stripslashes($data["email"]));
    $password = $data["password"];
    $confirmPassword = $data["confirmPassword"];

    // cek apakah kolom ada yang kosong
    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "<script>
                alert('semua kolom harus diisi!');
              </script>";
        return false;
    }

    // cek username sudah ada atau belum
    $stmt = $conn->prepare("SELECT username FROM user WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<script>
                alert('username sudah terdaftar!');
              </script>";
        return false;
    }

    // Enkripsi kata sandi
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Masukkan data ke database
    $stmt = $conn->prepare("INSERT INTO user (name, username, email, password) VALUES (:name, :username, :email, :password)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>
                alert('Pendaftaran berhasil!');
              </script>";
        return true;
    } else {
        echo "<script>
                alert('Registrasi gagal!');
              </script>";
        return false;
    }
}

?>