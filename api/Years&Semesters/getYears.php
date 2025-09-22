<?php 

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require "../connect.php";

try {
    $stmt = $pdo->query("SELECT * FROM year_tbl ORDER BY year_id ASC");
    $years = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $years
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to fetch years: " . $e->getMessage()
    ]);
}
?>