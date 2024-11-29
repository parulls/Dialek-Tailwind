<?php
// Konfigurasi Koneksi Database
$host = 'aws-0-ap-southeast-1.pooler.supabase.com';
$user = 'postgres.ifhedwymtdwjybimejrq';
$password = 'dialekdevwomen';
$port = '6543';
$dbname = 'postgres';

try {
    // Buat koneksi menggunakan PDO PostgreSQL
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Fungsi Query
function query($query) {
    global $conn;

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Query gagal: " . $e->getMessage());
    }
}

// Fungsi Registrasi
function registrasi($data) {
    global $conn;

    $name = strtolower(trim($data["name"]));
    $username = strtolower(trim($data["username"]));
    $email = strtolower(trim($data["email"]));
    $password = $data["password"];
    $confirmPassword = $data["confirmPassword"];

    // Cek apakah ada kolom yang kosong
    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "<script>
                alert('Semua kolom harus diisi!');
              </script>";
        return false;
    }

    // Cek apakah password dan confirm password sesuai
    if ($password !== $confirmPassword) {
        echo "<script>
                alert('Konfirmasi password tidak sesuai!');
              </script>";
        return false;
    }

    // Cek apakah username sudah ada
    $stmt = $conn->prepare("SELECT username FROM user WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<script>
                alert('Username sudah terdaftar!');
              </script>";
        return false;
    }

    // Enkripsi password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Masukkan data ke database
    $stmt = $conn->prepare("INSERT INTO user (name, username, email, password) 
                            VALUES (:name, :username, :email, :password)");
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
