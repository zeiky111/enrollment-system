<?php 
header("Content-Type: application/json");
include("db.php");  

try {
     
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["year_id"]) || empty($data["year_id"])) {
        echo json_encode([
            "status" => "error",
            "message" => "Year ID is required"
        ]);
        exit;
    }

    $year_id = $data["year_id"];
 
    $check = $conn->prepare("SELECT * FROM year_tbl WHERE year_id = ?");
    $check->execute([$year_id]);

    if ($check->rowCount() === 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Year not found"
        ]);
        exit;
    }

   
    $stmt = $conn->prepare("DELETE FROM year_tbl WHERE year_id = ?");
    $stmt->execute([$year_id]);

    echo json_encode([
        "status" => "success",
        "message" => "Year deleted successfully"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>
