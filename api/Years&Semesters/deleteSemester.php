<?php
 

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require "../connect.php";

try {
    $data = json_decode(file_get_contents("php://input"), true);

     
    if (!isset($data["sem_id"]) || empty($data["sem_id"])) {
        echo json_encode([
            "success" => false,
            "message" => "Semester ID is required"
        ]);
        exit;
    }

    $sem_id = $data["sem_id"];
 
    $check = $pdo->prepare("SELECT * FROM semester_tbl WHERE sem_id = ?");
    $check->execute([$sem_id]);

    if ($check->rowCount() === 0) {
        echo json_encode([
            "success" => false,
            "message" => "Semester not found"
        ]);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM semester_tbl WHERE sem_id = ?");
    $stmt->execute([$sem_id]);

    echo json_encode([
        "success" => true,
        "message" => "Semester deleted successfully"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>