<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
require "../connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  
    http_response_code(200);
    exit;
}
try {
    $sql = "SELECT s.subject_id, s.subject_name, sem.sem_name, sem.sem_id
            FROM subject_tbl s
            JOIN semester_tbl sem ON s.sem_id = sem.sem_id
            ORDER BY s.subject_id ASC";
    $stmt = $pdo->query($sql);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $subjects]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch subjects: ' . $e->getMessage()]);
}
?>
