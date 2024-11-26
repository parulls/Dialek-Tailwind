<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id']; // ID pengguna (dikirim dari frontend)

    try {
        $stmt = $conn->prepare("INSERT INTO game_sessions (id_user, date_time) VALUES (:user_id, NOW())");
        $stmt->execute(['user_id' => $user_id]);

        echo json_encode(['status' => 'success', 'session_id' => $conn->lastInsertId()]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
