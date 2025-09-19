<?php 
header("Content-Type: application/json");
include("db.php");  

try {
    $stmt = $conn->query("SELECT * FROM year_tbl ORDER BY year_id ASC");
    $years = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $years
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch years: " . $e->getMessage()
    ]);
}
?>
