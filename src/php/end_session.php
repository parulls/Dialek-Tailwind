<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_session = $_POST['id_session'];
    $total_score = $_POST['total_score'];
    $successful_words = $_POST['successful_words'];
    $failed_words = $_POST['failed_words'];
    $accuracy = $_POST['accuracy'];
    $result = $_POST['result'];

    try {
        $stmt = $conn->prepare("
            UPDATE game_sessions
            SET total_score = :total_score,
                successful_words = :successful_words,
                failed_words = :failed_words,
                accuracy = :accuracy,
                result = :result
            WHERE id_session = :id_session
        ");
        $stmt->execute([
            'total_score' => $total_score,
            'successful_words' => $successful_words,
            'failed_words' => $failed_words,
            'accuracy' => $accuracy,
            'result' => $result,
            'id_session' => $id_session
        ]);
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
