<?php


header("Content-Type: application/json");
include("db.php"); 

try {
    
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["semester_id"]) || empty($data["semester_id"])) {
        echo json_encode([
            "status" => "error",
            "message" => "Semester ID is required"
        ]);
        exit;
    }

    $semester_id = $data["semester_id"];

     
    $check = $conn->prepare("SELECT * FROM semester_tbl WHERE semester_id = ?");
    $check->execute([$semester_id]);

    if ($check->rowCount() === 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Semester not found"
        ]);
        exit;
    }

     
    $stmt = $conn->prepare("DELETE FROM semester_tbl WHERE semester_id = ?");
    $stmt->execute([$semester_id]);

    echo json_encode([
        "status" => "success",
        "message" => "Semester deleted successfully"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>
