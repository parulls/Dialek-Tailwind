<?php
include("connect.php");
header("Content-Type: application/json");

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firebaseUid = $data['firebase_uid'] ?? null;
        $manualLogin = $data['manual_login'] ?? false; // Indikator apakah login manual

        if ($manualLogin) {
            $email = $data['email'] ?? null;

            // Validasi input login manual
            if (!$email || !$firebaseUid) {
                echo json_encode(["success" => false, "message" => "Email dan UID Firebase diperlukan!"]);
                exit;
            }

            // Cek apakah email ada di database
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND firebase_uid = :firebase_uid");
            $stmt->execute([':email' => $email, ':firebase_uid' => $firebaseUid]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Simpan sesi pengguna
                session_start();
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];

                echo json_encode(["success" => true, "message" => "Login berhasil!"]);
            } else {
                echo json_encode(["success" => false, "message" => "Email atau UID tidak ditemukan!"]);
            }
        } else {
            // Login Google: hanya gunakan UID untuk mencocokkan data
            if (!$firebaseUid) {
                echo json_encode(["success" => false, "message" => "UID Firebase diperlukan!"]);
                exit;
            }

            // Periksa UID Firebase
            $stmt = $conn->prepare("SELECT * FROM users WHERE firebase_uid = :firebase_uid");
            $stmt->execute([':firebase_uid' => $firebaseUid]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Simpan sesi pengguna
                session_start();
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];

                echo json_encode(["success" => true, "message" => "Login berhasil!"]);
            } else {
                echo json_encode(["success" => false, "message" => "UID tidak ditemukan di database."]);
            }
        }
    } else {
        echo json_encode(["success" => false, "message" => "Metode request tidak valid."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
