<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require "../connect.php";

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