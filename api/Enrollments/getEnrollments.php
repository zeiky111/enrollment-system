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
    $sql = "SELECT e.enrollment_id, 
                   s.stud_id, 
                   CONCAT(s.first_name, ' ', s.middle_name, ' ', s.last_name) AS student_name, 
                   sub.subject_id, sub.subject_name, 
                   sem.sem_name
            FROM enrollment_tbl e
            JOIN student_tbl s ON e.stud_id = s.stud_id
            JOIN subject_tbl sub ON e.subject_id = sub.subject_id
            JOIN semester_tbl sem ON sub.sem_id = sem.sem_id
            ORDER BY e.enrollment_id";

    $stmt = $pdo->query($sql);
    $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $enrollments
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to fetch enrollments"
    ]);
}
?>

