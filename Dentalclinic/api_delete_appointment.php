<?php
header("Content-Type: application/json; charset=utf-8");
require_once "index.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["appointment_id"] ?? null;

if (!$id) {
    echo json_encode(["success" => false, "message" => "ไม่มี appointment_id"]);
    exit;
}

$sql = "DELETE FROM appointments WHERE appointment_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}
