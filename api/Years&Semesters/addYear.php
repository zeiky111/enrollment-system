<?php
header("Content-Type: application/json");
include("db.php"); 

try {
  
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["year_name"]) || empty(trim($data["year_name"]))) {
        echo json_encode([
            "status" => "error",
            "message" => "Year name is required"
        ]);
        exit;
    }

    $year_name = trim($data["year_name"]);
 
    $check = $conn->prepare("SELECT * FROM year_tbl WHERE year_name = ?");
    $check->execute([$year_name]);

    if ($check->rowCount() > 0) {
        echo json_encode([
            "status" => "error",
            "message" => "This year already exists"
        ]);
        exit;
    }

   
    $stmt = $conn->prepare("INSERT INTO year_tbl (year_name) VALUES (?)");
    $stmt->execute([$year_name]);

    echo json_encode([
        "status" => "success",
        "message" => "Year added successfully"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>
