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
try {
    $sql = "SELECT program_id, program_name FROM program_tbl ORDER BY program_id ASC";
    $stmt = $pdo->query($sql);
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $programs]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch programs: ' . $e->getMessage()]);
}
?>
