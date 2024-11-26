<?php
include("connect.php");
header("Content-Type: application/json");

function generateRandomUsername($length = 7) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomUsername = '';
    for ($i = 0; $i < $length; $i++) {
        $randomUsername .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomUsername;
}

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firebaseUid = $data['firebase_uid'] ?? null;
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? null;
        $username = trim($data['username'] ?? generateRandomUsername());

        if (!$name || !$email || !$username) {
            echo json_encode(["success" => false, "message" => "Data tidak lengkap!"]);
            exit;
        }

        $hashedPassword = $password ? password_hash($password, PASSWORD_BCRYPT) : "GOOGLE_SIGNUP";

        // Periksa apakah email atau username sudah ada
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
        $stmt->execute([':email' => $email, ':username' => $username]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => false, "message" => "Email atau username sudah digunakan!"]);
            exit;
        }

        // Simpan data pengguna ke database
        $stmt = $conn->prepare("INSERT INTO users (firebase_uid, name, email, password, username) VALUES (:firebase_uid, :name, :email, :password, :username)");
        $stmt->execute([
            ':firebase_uid' => $firebaseUid,
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':username' => $username,
        ]);

        echo json_encode(["success" => true, "message" => "Signup berhasil!", "username" => $username]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request method."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
