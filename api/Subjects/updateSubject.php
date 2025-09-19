<?php
header('Content-Type: application/json');
require '../db.php';

$data = json_decode(file_get_contents("php://input"), true);
$subject_id   = intval($data['subject_id'] ?? 0);
$subject_code = trim($data['subject_code'] ?? '');
$subject_name = trim($data['subject_name'] ?? '');
$semester_id  = intval($data['semester_id'] ?? 0);

if ($subject_id <= 0 || $subject_code === '' || $subject_name === '' || $semester_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Subject ID, code, name, and semester are required']);
    exit;
}

try {
     
    $check = $pdo->prepare("SELECT COUNT(*) FROM subject_tbl WHERE subject_id = ?");
    $check->execute([$subject_id]);
    if ($check->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'Subject not found']);
        exit;
    }

    
    $checkSem = $pdo->prepare("SELECT COUNT(*) FROM semester_tbl WHERE semester_id = ?");
    $checkSem->execute([$semester_id]);
    if ($checkSem->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'Semester not found']);
        exit;
    }

     
    $stmt = $pdo->prepare("UPDATE subject_tbl 
     SET subject_code = ?, subject_name = ?, semester_id = ? 
   WHERE subject_id = ?");
    $stmt->execute([$subject_code, $subject_name, $semester_id, $subject_id]);

    echo json_encode(['success' => true, 'message' => 'Subject updated successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to update subject: ' . $e->getMessage()]);
}
?>
