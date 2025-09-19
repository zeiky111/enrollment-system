<?php
header('Content-Type: application/json');
require '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
$load_id = intval($data['load_id'] ?? 0);

if ($load_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Enrollment ID is required']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM student_load WHERE load_id = ?");
    $stmt->execute([$load_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Enrollment removed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Enrollment not found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to remove enrollment: ' . $e->getMessage()]);
}
?>
