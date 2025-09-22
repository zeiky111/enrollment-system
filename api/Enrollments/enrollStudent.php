<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require "../connect.php";

$data = json_decode(file_get_contents('php://input'), true);
$stud_id = intval($data['stud_id'] ?? 0);
$subject_id = intval($data['subject_id'] ?? 0);

if ($stud_id <= 0 || $subject_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Student ID and Subject ID are required']);
    exit;
}

try {
     
    $studentCheck = $pdo->prepare("SELECT COUNT(*) FROM student_tbl WHERE stud_id = ?");
    $studentCheck->execute([$stud_id]);
    if ($studentCheck->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'Student not found']);
        exit;
    }

     
    $subjectCheck = $pdo->prepare("SELECT COUNT(*) FROM subject_tbl WHERE subject_id = ?");
    $subjectCheck->execute([$subject_id]);
    if ($subjectCheck->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'Subject not found']);
        exit;
    }

     
    $check = $pdo->prepare("SELECT COUNT(*) FROM enrollment_tbl WHERE stud_id = ? AND subject_id = ?");
    $check->execute([$stud_id, $subject_id]);
    if ($check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Student already enrolled in this subject']);
        exit;
    }

     
    $stmt = $pdo->prepare("INSERT INTO enrollment_tbl (stud_id, subject_id) VALUES (?, ?)");
    $stmt->execute([$stud_id, $subject_id]);
    $enrollment_id = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'Enrollment successful',
        'enrollment_id' => $enrollment_id
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to enroll student: ' . $e->getMessage()
    ]);
}
?>