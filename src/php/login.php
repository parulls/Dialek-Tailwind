<?php
include("connect.php");
header("Content-Type: application/json");

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firebaseUid = $data['firebase_uid'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if ($firebaseUid) {
            // Login menggunakan Firebase UID (contoh: Google Login)
            $stmt = $conn->prepare("SELECT * FROM users WHERE firebase_uid = :firebase_uid");
            $stmt->execute([':firebase_uid' => $firebaseUid]);

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode(["success" => true, "message" => "Login berhasil!", "user" => $user]);
            } else {
                echo json_encode(["success" => false, "message" => "Akun belum terdaftar."]);
            }
        } else if ($email && $password) {
            // Login menggunakan email atau username dan password
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :email");
            $stmt->execute([':email' => $email]);

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['password'])) {
                    echo json_encode(["success" => true, "message" => "Login berhasil!", "user" => $user]);
                } else {
                    echo json_encode(["success" => false, "message" => "Kata sandi salah."]);
                }
            } else {
                echo json_encode(["success" => false, "message" => "Email atau username tidak ditemukan."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Data tidak lengkap!"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request method."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
