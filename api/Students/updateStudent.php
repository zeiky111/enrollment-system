<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require "../connect.php";

$data = json_decode(file_get_contents("php://input"), true);

$stud_id      = intval($data['stud_id'] ?? 0);
$first_name   = trim($data['first_name'] ?? '');
$middle_name  = trim($data['middle_name'] ?? '');
$last_name    = trim($data['last_name'] ?? '');
$program_id   = intval($data['program_id'] ?? 0);
$allowance    = floatval($data['allowance'] ?? 0);

if ($stud_id <= 0 || $first_name === '' || $last_name === '' || $program_id <= 0) {
    echo json_encode([
        "success" => false, 
        "message" => "Student ID, First name, Last name, and Program are required"
    ]);
    exit;
}

 
$check = $pdo->prepare("SELECT COUNT(*) FROM student_tbl WHERE stud_id = ?");
$check->execute([$stud_id]);
if ($check->fetchColumn() == 0) {
    echo json_encode(["success" => false, "message" => "Student not found"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        UPDATE student_tbl 
        SET first_name = ?, middle_name = ?, last_name = ?, program_id = ?, allowance = ? 
        WHERE stud_id = ?
    ");
    $stmt->execute([$first_name, $middle_name, $last_name, $program_id, $allowance, $stud_id]);

    echo json_encode(["success" => true, "message" => "Student updated successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Failed to update student"]);
}
?>
