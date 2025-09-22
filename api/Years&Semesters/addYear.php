<?php
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
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["year_name"]) || empty(trim($data["year_name"]))) {
        echo json_encode([
            "success" => false,
            "message" => "Year name is required"
        ]);
        exit;
    }

    $year_name = trim($data["year_name"]);

  
    $check = $pdo->prepare("SELECT * FROM year_tbl WHERE year_name = ?");
    $check->execute([$year_name]);

    if ($check->rowCount() > 0) {
        echo json_encode([
            "success" => false,
            "message" => "This year already exists"
        ]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO year_tbl (year_name) VALUES (?)");
    $stmt->execute([$year_name]);

    echo json_encode([
        "success" => true,
        "message" => "Year added successfully"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>
