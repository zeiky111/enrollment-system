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
$new_stud_id = intval($data['stud_id'] ?? 0);
$new_subject_id = intval($data['subject_id'] ?? 0);

if ($enrollment_id <= 0 || $new_stud_id <= 0 || $new_subject_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Enrollment ID, student and subject are required']);
    exit;
}

try {
     
    $stmt = $pdo->prepare("SELECT stud_id, subject_id FROM enrollment_tbl WHERE enrollment_id = ?");
    $stmt->execute([$enrollment_id]);
    $current = $stmt->fetch();

    if (!$current) {
        echo json_encode(['success' => false, 'message' => 'Enrollment not found']);
        exit;
    }
 
    $check = $pdo->prepare("SELECT COUNT(*) FROM enrollment_tbl WHERE stud_id = ? AND subject_id = ? AND enrollment_id != ?");
    $check->execute([$new_stud_id, $new_subject_id, $enrollment_id]);
    if ($check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'This student is already enrolled in this subject']);
        exit;
    }
 
    $upd = $pdo->prepare("UPDATE enrollment_tbl SET stud_id = ?, subject_id = ? WHERE enrollment_id = ?");
    $upd->execute([$new_stud_id, $new_subject_id, $enrollment_id]);

    echo json_encode(['success' => true, 'message' => 'Enrollment updated successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to update enrollment: ' . $e->getMessage()]);
}
?>