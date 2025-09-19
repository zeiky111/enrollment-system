<?php
header('Content-Type: application/json');
require '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
$program_name = trim($data['program_name'] ?? '');

if ($program_name === '') {
    echo json_encode(['success' => false, 'message' => 'Program name is required']);
    exit;
}

try {
    // Check if program already exists
    $check = $pdo->prepare("SELECT COUNT(*) FROM program_tbl WHERE program_name = ?");
    $check->execute([$program_name]);
    if ($check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Program already exists']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO program_tbl (program_name) VALUES (?)");
    $stmt->execute([$program_name]);

    echo json_encode(['success' => true, 'message' => 'Program added successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to add program: ' . $e->getMessage()]);
}
?>
