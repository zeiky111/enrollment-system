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
$subject_code = trim($data['subject_id'] ?? '');
$subject_name = trim($data['subject_name'] ?? '');
$semester_id = intval($data['semester_id'] ?? 0);

if ($subject_code === '' || $subject_name === '' || $semester_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Subject id, name, and semester are required']);
    exit;
}

try {
     
    $checkSemester = $pdo->prepare("SELECT COUNT(*) FROM semester_tbl WHERE sem_id = ?");
    $checkSemester->execute([$semester_id]);
    if ($checkSemester->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'Semester not found']);
        exit;
    }

    
    $stmt = $pdo->prepare("INSERT INTO subject_tbl (subject_code, subject_name, sem_id) VALUES (?, ?, ?)");
    $stmt->execute([$subject_code, $subject_name, $semester_id]);

    echo json_encode(['success' => true, 'message' => 'Subject added successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to add subject: ' . $e->getMessage()]);
}
?>
