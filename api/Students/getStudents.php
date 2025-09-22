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
$sql = "SELECT
            s.stud_id,
            s.first_name,
            s.middle_name,
            s.last_name,
            s.allowance,
            s.program_id,
            p.program_name
        FROM student_tbl s
        JOIN program_tbl p ON s.program_id = p.program_id";

try {
    $stmt = $pdo->query($sql);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);


    foreach ($students as &$student) {
        $student['name'] = $student['first_name'] . ' ' .
                           ($student['middle_name'] ? $student['middle_name'] . ' ' : '') .
                           $student['last_name'];
    }

    echo json_encode([
        "success" => true,
        "data" => $students
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to fetch students",
        "error" => $e->getMessage()
    ]);
}
?>
