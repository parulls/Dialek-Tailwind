<?php
include("connect.php");
header("Content-Type: application/json");

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firebaseUid = $data['firebase_uid'] ?? null;
        $name = $data['name'] ?? null;
        $username = $data['username'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        // Validasi input
        if (!$firebaseUid || !$name || !$username || !$email || !$password) {
            echo json_encode(["success" => false, "message" => "Semua data harus diisi!"]);
            exit;
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Masukkan data ke database
        $stmt = $conn->prepare("INSERT INTO users (firebase_uid, name, username, email, password) VALUES (:firebase_uid, :name, :username, :email, :password)");
        $stmt->execute([
            ':firebase_uid' => $firebaseUid,
            ':name' => $name,
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
        ]);

        echo json_encode(["success" => true, "message" => "Pendaftaran berhasil!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request method."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
