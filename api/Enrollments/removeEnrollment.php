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
$data = json_decode(file_get_contents('php://input'), true);
$enrollment_id = intval($data['enrollment_id'] ?? 0);

if ($enrollment_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Enrollment ID is required']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM enrollment_tbl WHERE enrollment_id = ?");
    $stmt->execute([$enrollment_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Enrollment removed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Enrollment not found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to remove enrollment: ' . $e->getMessage()]);
}
?>