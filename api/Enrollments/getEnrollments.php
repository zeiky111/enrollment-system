<?php
header("Content-Type: application/json");
require "../connect.php";

try {
    $sql = "SELECT e.enrollment_id, 
                   s.stud_id, 
                   CONCAT(s.first_name, ' ', s.middle_name, ' ', s.last_name) AS student_name, 
                   sub.subject_id, sub.subject_name, 
                   sem.semester_name
            FROM enrollment_tbl e
            JOIN student_tbl s ON e.stud_id = s.stud_id
            JOIN subject_tbl sub ON e.subject_id = sub.subject_id
            JOIN semester_tbl sem ON sub.semester_id = sem.semester_id
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

