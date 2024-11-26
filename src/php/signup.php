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

        if (!$firebaseUid || !$name || !$email) {
            echo json_encode(["success" => false, "message" => "Data tidak lengkap!"]);
            exit;
        }

        // Jika password tidak ada (Google Signup), gunakan placeholder
        $hashedPassword = $password ? password_hash($password, PASSWORD_BCRYPT) : "GOOGLE_SIGNUP";

        // Buat username acak jika mendaftar menggunakan Google
        $username = $password ? trim($data['username'] ?? '') : generateRandomUsername();

        // Periksa apakah pengguna sudah terdaftar
        $stmt = $conn->prepare("SELECT * FROM users WHERE firebase_uid = :firebase_uid OR email = :email");
        $stmt->execute([':firebase_uid' => $firebaseUid, ':email' => $email]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => false, "message" => "Pengguna sudah terdaftar!"]);
            exit;
        }

        // Simpan data ke database
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
