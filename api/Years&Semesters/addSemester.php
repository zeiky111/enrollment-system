<?php
header('Content-Type: application/json');
require '../db.php';

$data = json_decode(file_get_contents("php://input"), true);
$semester_name = trim($data['semester_name'] ?? '');

if ($semester_name === '') {
    echo json_encode(['success' => false, 'message' => 'Semester name is required']);
    exit;
}

try {
     
    $check = $pdo->prepare("SELECT COUNT(*) FROM semester_tbl WHERE semester_name = ?");
    $check->execute([$semester_name]);
    if ($check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Semester already exists']);
        exit;
    }

 
    $stmt = $pdo->prepare("INSERT INTO semester_tbl (semester_name) VALUES (?)");
    $stmt->execute([$semester_name]);

    echo json_encode(['success' => true, 'message' => 'Semester added successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to add semester: ' . $e->getMessage()]);
}
?>
