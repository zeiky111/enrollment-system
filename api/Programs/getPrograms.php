<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require "../connect.php";

try {
    $sql = "SELECT program_id, program_name FROM program_tbl ORDER BY program_id ASC";
    $stmt = $pdo->query($sql);
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $programs]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch programs: ' . $e->getMessage()]);
}
?>
