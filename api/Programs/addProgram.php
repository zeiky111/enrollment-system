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
$program_name = trim($data['program_name'] ?? '');
$ins_id = intval($data['ins_id'] ?? 0);

if ($program_name === '' || $ins_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Program name and institute are required']);
    exit;
}

try {
    // Check if program already exists for this institute
    $check = $pdo->prepare("SELECT COUNT(*) FROM program_tbl WHERE program_name = ? AND ins_id = ?");
    $check->execute([$program_name, $ins_id]);
    if ($check->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Program already exists for this institute']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO program_tbl (program_name, ins_id) VALUES (?, ?)");
    $stmt->execute([$program_name, $ins_id]);

    echo json_encode(['success' => true, 'message' => 'Program added successfully']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to add program: ' . $e->getMessage()]);
}
?>
