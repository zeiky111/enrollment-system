<?php
header("Content-Type: application/json");
require "../connect.php";

$data = json_decode(file_get_contents("php://input"), true);

$stud_id   = intval($data['stud_id'] ?? 0);
$allowance = floatval($data['allowance'] ?? 0);

if ($stud_id <= 0) {
    echo json_encode(["success" => false, "message" => "Student ID is required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE student_tbl SET allowance = ? WHERE stud_id = ?");
    $stmt->execute([$allowance, $stud_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Allowance updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "No changes made or student not found"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Failed to update allowance"]);
}
?>
