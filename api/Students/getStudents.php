<?php
header("Content-Type: application/json");
require "../connect.php";

 
$sql = "SELECT 
            s.stud_id, 
            s.first_name, 
            s.middle_name, 
            s.last_name, 
            s.allowance, 
            p.program_name
        FROM student_tbl s
        JOIN program_tbl p ON s.program_id = p.program_id";

try {
    $stmt = $pdo->query($sql);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
