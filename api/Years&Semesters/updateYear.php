<?php  

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require "../connect.php";

$data = json_decode(file_get_contents("php://input"), true);

$year_id   = intval($data['year_id'] ?? 0);
$year_name = trim($data['year_name'] ?? '');

if ($year_id <= 0 || $year_name === '') {
    echo json_encode([
        "success" => false,
        "message" => "Year ID and name are required"
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE year_tbl SET year_name = ? WHERE year_id = ?");
    $stmt->execute([$year_name, $year_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            "success" => true,
            "message" => "Year updated successfully"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "No changes made or year not found"
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to update year: " . $e->getMessage()
    ]);
}
?>