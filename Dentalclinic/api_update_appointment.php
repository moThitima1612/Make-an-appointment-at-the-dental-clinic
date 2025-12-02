<?php
header("Content-Type: application/json; charset=utf-8");
require_once "index.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = $data["appointment_id"] ?? null;
$datetime = $data["appointment_datetime"] ?? null;
$status = $data["status"] ?? null;

if (!$id) {
    echo json_encode(["success" => false, "message" => "ไม่มี appointment_id"]);
    exit;
}

$fields = [];
$params = [];
$types = "";

if ($datetime) {
    $fields[] = "appointment_datetime = ?";
    $params[] = $datetime;
    $types .= "s";
}

if ($status) {
    $fields[] = "status = ?";
    $params[] = $status;
    $types .= "s";
}

if (!$fields) {
    echo json_encode(["success" => false, "message" => "ไม่มีข้อมูลสำหรับอัปเดต"]);
    exit;
}

$params[] = (int)$id;
$types .= "i";

$sql = "UPDATE appointments SET " . implode(", ", $fields) . " WHERE appointment_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}
