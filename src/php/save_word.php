<?php
include('/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_session = $_POST['id_session'];
    $word = $_POST['word'];
    $type = $_POST['type']; // 'player' atau 'AI'
    $is_success = $_POST['is_success'];

    try {
        $stmt = $conn->prepare("INSERT INTO game_words (id_session, word, type, is_success) VALUES (:id_session, :word, :type, :is_success)");
        $stmt->execute([
            'id_session' => $id_session,
            'word' => $word,
            'type' => $type,
            'is_success' => $is_success
        ]);
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>
