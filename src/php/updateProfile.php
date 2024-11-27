<?php
include("connect.php");
header("Content-Type: application/json");

try {
    // Ambil data JSON dari permintaan
    $data = json_decode(file_get_contents("php://input"), true);

    // Ambil parameter
    $firebaseUid = $data['firebase_uid'] ?? null;
    $name = trim($data['name'] ?? '');
    $username = trim($data['username'] ?? '');
    $email = trim($data['email'] ?? '');
    $phone = trim($data['phone'] ?? '');
    $profileImage = $data['profileImage'] ?? '';

    // Validasi data
    if (!$firebaseUid || !$name || !$username || !$email || !$phone) {
        echo json_encode(["success" => false, "message" => "Semua field wajib diisi."]);
        exit;
    }

    // Periksa apakah firebase_uid valid
    $stmt = $conn->prepare("SELECT firebase_uid FROM users WHERE firebase_uid = :firebase_uid");
    $stmt->execute([':firebase_uid' => $firebaseUid]);

    if ($stmt->rowCount() === 0) {
        echo json_encode(["success" => false, "message" => "Firebase UID tidak valid atau tidak ditemukan."]);
        exit;
    }

    // Periksa apakah username sudah digunakan oleh pengguna lain
    $stmt = $conn->prepare("SELECT id_user FROM users WHERE username = :username AND firebase_uid != :firebase_uid");
    $stmt->execute([':username' => $username, ':firebase_uid' => $firebaseUid]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => false, "message" => "Username sudah digunakan oleh akun lain!"]);
        exit;
    }

    // Update data pengguna
    $stmt = $conn->prepare("
        UPDATE users
        SET name = :name, username = :username, email = :email, phone = :phone, profile_image = :profileImage
        WHERE firebase_uid = :firebase_uid
    ");
    $stmt->execute([
        ':name' => $name,
        ':username' => $username,
        ':email' => $email,
        ':phone' => $phone,
        ':profileImage' => $profileImage,
        ':firebase_uid' => $firebaseUid,
    ]);

    // Periksa apakah ada baris yang diubah
    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Profil berhasil diperbarui!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Tidak ada perubahan yang disimpan."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Kesalahan database: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Terjadi kesalahan: " . $e->getMessage()]);
}
?>

