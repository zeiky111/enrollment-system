<?php
header('Content-Type: application/json');
require '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
$program_id = intval($data['program_id'] ?? 0);

if ($program_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Program ID is required']);
    exit;
}

try {

    $check = $pdo->prepare("SELECT COUNT(*) FROM program_tbl WHERE program_id = ?");
    $check->execute([$program_id]);
    if ($check->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'Program not found']);
        exit;
    }

    
    $checkStudents = $pdo->prepare("SELECT COUNT(*) FROM student_tbl WHERE program_id = ?");
    $checkStudents->execute([$program_id]);
    if ($checkStudents->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete: students are still enrolled in this program']);
        exit;
    }

    
    $stmt = $pdo->prepare("DELETE FROM program_tbl WHERE program_id = ?");
    $stmt->execute([$program_id]);

    echo json_encode(['success' => true, 'message' => 'Program deleted successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to delete program: ' . $e->getMessage()]);
}
?>
