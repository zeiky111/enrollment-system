<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require "../connect.php";

$data = json_decode(file_get_contents('php://input'), true);
$program_id = intval($data['program_id'] ?? 0);
$program_name = trim($data['program_name'] ?? '');
$institute_id = intval($data['institute_id'] ?? 0);

if ($program_id <= 0 || $program_name === '' || $institute_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Program ID, Program Name and Institute are required']);
    exit;
}

try {
   
    $check = $pdo->prepare("SELECT COUNT(*) FROM program_tbl WHERE program_id = ?");
    $check->execute([$program_id]);
    if ($check->fetchColumn() == 0) {
        echo json_encode(['success' => false, 'message' => 'Program not found']);
        exit;
    }

    
    $stmt = $pdo->prepare("UPDATE program_tbl SET program_name = ?, institute_id = ? WHERE program_id = ?");
    $stmt->execute([$program_name, $institute_id, $program_id]);

    echo json_encode(['success' => true, 'message' => 'Program updated successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to update program: ' . $e->getMessage()]);
}
?>
