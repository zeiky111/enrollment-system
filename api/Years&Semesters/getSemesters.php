<?php 
header("Content-Type: application/json");
include("db.php");  

try {
    $stmt = $conn->query("SELECT * FROM semester_tbl ORDER BY semester_id ASC");
    $semesters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $semesters
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch semesters: " . $e->getMessage()
    ]);
}
?>
