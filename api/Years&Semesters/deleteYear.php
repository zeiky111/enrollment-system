<?php 

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require "../connect.php";

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["year_id"]) || empty($data["year_id"])) {
        echo json_encode([
            "success" => false,
            "message" => "Year ID is required"
        ]);
        exit;
    }

    $year_id = $data["year_id"];

   
    $check = $pdo->prepare("SELECT * FROM year_tbl WHERE year_id = ?");
    $check->execute([$year_id]);

    if ($check->rowCount() === 0) {
        echo json_encode([
            "success" => false,
            "message" => "Year not found"
        ]);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM year_tbl WHERE year_id = ?");
    $stmt->execute([$year_id]);

    echo json_encode([
        "success" => true,
        "message" => "Year deleted successfully"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>