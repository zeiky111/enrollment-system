<?php  
header("Content-Type: application/json");
include("db.php"); 

$data = json_decode(file_get_contents("php://input"), true);

$semester_id   = intval($data['semester_id'] ?? 0);
$semester_name = trim($data['semester_name'] ?? '');

if ($semester_id <= 0 || $semester_name === '') {
    echo json_encode([
        "status" => "error",
        "message" => "Semester ID and name are required"
    ]);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE semester_tbl SET semester_name = ? WHERE semester_id = ?");
    $stmt->execute([$semester_name, $semester_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Semester updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No changes made or semester not found"
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to update semester: " . $e->getMessage()
    ]);
}
?>
