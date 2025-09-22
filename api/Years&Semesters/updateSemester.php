<?php  

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require "../connect.php";

$data = json_decode(file_get_contents("php://input"), true);

 
$sem_id   = intval($data['sem_id'] ?? 0);
$sem_name = trim($data['sem_name'] ?? '');

if ($sem_id <= 0 || $sem_name === '') {
    echo json_encode([
        "success" => false,
        "message" => "Semester ID and name are required"
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE semester_tbl SET sem_name = ? WHERE sem_id = ?");
    $stmt->execute([$sem_name, $sem_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "success" => true,
            "message" => "Semester updated successfully"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "No changes made or semester not found"
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to update semester: " . $e->getMessage()
    ]);
}
?>