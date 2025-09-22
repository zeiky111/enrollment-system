<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require "../connect.php";

$data = json_decode(file_get_contents("php://input"), true);
$sem_name = trim($data['sem_name'] ?? '');

if ($sem_name === '') {
    echo json_encode(['success' => false, 'message' => 'Semester name is required']);
    exit;
}

try {
    $check = $pdo->prepare("SELECT COUNT(*) FROM semester_tbl WHERE sem_name = ?");
    $check->execute([$sem_name]);
    if ($check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Semester already exists']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO semester_tbl (sem_name) VALUES (?)");
    $stmt->execute([$sem_name]);

    echo json_encode(['success' => true, 'message' => 'Semester added successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to add semester: ' . $e->getMessage()]);
}
?>
