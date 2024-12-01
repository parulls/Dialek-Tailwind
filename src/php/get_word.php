<?php
require 'connect.php';

header('Content-Type: application/json');

try {
    $query = $conn->query("SELECT word, hint FROM words_kosakata ORDER BY RANDOM() LIMIT 1");
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode([
            'word' => $result['word'],
            'hint' => $result['hint']
        ]);
    } else {
        echo json_encode(['error' => 'No words found']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
