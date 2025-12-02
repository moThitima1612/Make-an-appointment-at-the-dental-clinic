<?php
header("Content-Type: application/json; charset=utf-8");
require_once "index.php";

$data = json_decode(file_get_contents("php://input"), true);

$patient_id = $data["patient_id"] ?? null;
$service_id = $data["service_id"] ?? null;
$dentist_id = $data["dentist_id"] ?? null;
$datetime = $data["appointment_datetime"] ?? null;
$status = $data["status"] ?? "pending";

if (!$patient_id || !$service_id || !$dentist_id || !$datetime) {
    echo json_encode(["success" => false, "message" => "ข้อมูลไม่ครบ"]);
    exit;
}

$sql = "INSERT INTO appointments (patient_id, service_id, dentist_id, appointment_datetime, status)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iiiss", $patient_id, $service_id, $dentist_id, $appointment_datetime, $status);
$stmt->execute();

if ($stmt->execute()) {
    echo json_encode(["success" => true, "appointment_id" => $stmt->insert_id]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}
