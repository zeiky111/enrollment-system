<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require "../connect.php";

 
$data = json_decode(file_get_contents("php://input"), true);
$stud_id = intval($data['stud_id'] ?? 0);

 if ($stud_id <= 0) {
    echo json_encode(["success" => false, "message" => "Student ID is required"]);
    exit;
}

 $check = $pdo->prepare("SELECT COUNT(*) FROM student_tbl WHERE stud_id = ?");
$check->execute([$stud_id]);
if ($check->fetchColumn() == 0) {
    echo json_encode(["success" => false, "message" => "Student not found"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM student_tbl WHERE stud_id = ?");
    $stmt->execute([$stud_id]);
    echo json_encode(["success" => true, "message" => "Student deleted successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Failed to delete student"]);
}
