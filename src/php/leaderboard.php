<?php
include('connect.php');

try {
    $stmt = $conn->query("
        SELECT u.username, MAX(s.total_score) AS highest_score
        FROM game_sessions s
        JOIN users u ON s.id_user = u.id_user
        GROUP BY u.username
        ORDER BY highest_score DESC
        LIMIT 10
    ");
    $leaderboard = $stmt->fetchAll();
    echo json_encode(['status' => 'success', 'data' => $leaderboard]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
