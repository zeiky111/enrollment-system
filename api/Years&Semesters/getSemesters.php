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
    $stmt = $pdo->query("SELECT sem_id AS sem_id, sem_name AS sem_name FROM semester_tbl ORDER BY sem_id ASC");
    $semesters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $semesters
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to fetch semesters: " . $e->getMessage()
    ]);
}
?>