<?php
include("connect.php");
header("Content-Type: application/json");

try {
    // Ambil dan validasi firebase_uid dari parameter GET
    $firebaseUid = $_GET['firebase_uid'] ?? null;

    if (!$firebaseUid || empty(trim($firebaseUid))) {
        echo json_encode(["success" => false, "message" => "Firebase UID tidak valid atau tidak ditemukan."]);
        exit;
    }

    // Logging untuk debugging
    error_log("Firebase UID diterima: " . $firebaseUid);

    // Query untuk mendapatkan data pengguna berdasarkan firebase_uid
    $stmt = $conn->prepare("
        SELECT name, username, email, phone, 
               COALESCE(profile_image, '../assets/pp.webp') AS profile_image 
        FROM users 
        WHERE firebase_uid = :firebase_uid
    ");
    $stmt->execute([':firebase_uid' => $firebaseUid]);

    // Periksa apakah data pengguna ditemukan
    if ($stmt->rowCount() > 0) {
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode([
            "success" => true,
            "data" => $userData,
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Pengguna tidak ditemukan."]);
    }
} catch (PDOException $e) {
    // Tangani kesalahan koneksi atau query database
    error_log("Database Error: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Kesalahan pada database: " . $e->getMessage(),
    ]);
} catch (Exception $e) {
    // Tangani kesalahan umum lainnya
    error_log("General Error: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Terjadi kesalahan: " . $e->getMessage(),
    ]);
}
?>
