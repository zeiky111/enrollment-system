<?php
header('Content-Type: application/json');
require '../db.php';

try {
    $sql = "SELECT s.subject_id, s.subject_code, s.subject_name, sem.semester_name, sem.semester_id
            FROM subject_tbl s
            JOIN semester_tbl sem ON s.semester_id = sem.semester_id
            ORDER BY s.subject_id ASC";
    $stmt = $pdo->query($sql);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'data' => $subjects]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch subjects: ' . $e->getMessage()]);
}
?>
