<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require "../connect.php";

$data = json_decode(file_get_contents("php://input"), true);
$subject_id = intval($data['subject_id'] ?? 0);

if ($subject_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Subject ID is required']);
    exit;
}

try { 
    $check = $pdo->prepare("SELECT COUNT(*) FROM subject_tbl WHERE subject_id = ?");
    $check->execute([$subject_id]);
    if ($check->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'Subject not found']);
        exit;
    }

    
    $used = $pdo->prepare("SELECT COUNT(*) FROM student_load WHERE subject_id = ?");
    $used->execute([$subject_id]);
    if ($used->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete subject: it is already assigned to students']);
        exit;
    }

    
    $stmt = $pdo->prepare("DELETE FROM subject_tbl WHERE subject_id = ?");
    $stmt->execute([$subject_id]);

    echo json_encode(['success' => true, 'message' => 'Subject deleted successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to delete subject: ' . $e->getMessage()]);
}
?>
