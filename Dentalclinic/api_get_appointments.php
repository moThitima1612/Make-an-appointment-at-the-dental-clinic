<?php
header("Content-Type: application/json; charset=utf-8");
require_once "index.php";

$sql = "
SELECT 
    a.appointment_id,
    a.appointment_datetime,
    a.status,
    p.full_name AS patient_name,
    p.phone AS patient_phone,
    s.service_name,
    d.dentist_name
FROM appointments a
JOIN patients p ON a.patient_id = p.patient_id
JOIN services_v1 s ON a.service_id = s.service_id
JOIN dentists_v1 d ON a.dentist_id = d.dentist_id
ORDER BY a.appointment_datetime ASC
";

$result = $mysqli->query($sql);

$events = [];

while ($row = $result->fetch_assoc()) {

    // สีตามสถานะ
    $color = "#4caf50";
    if ($row['status'] == "pending")   $color = "#ff9800";
    if ($row['status'] == "completed") $color = "#2196f3";
    if ($row['status'] == "cancelled") $color = "#f44336";

    $events[] = [
        "id" => $row["appointment_id"],
        "title" => $row["patient_name"] . " - " . $row["service_name"],
        "start" => $row["appointment_datetime"],
        "backgroundColor" => $color,
        "borderColor" => $color,
        "extendedProps" => [
            "patient" => $row["patient_name"],
            "phone" => $row["patient_phone"],
            "service" => $row["service_name"],
            "dentist" => $row["dentist_name"],
            "status" => $row["status"]
        ]
    ];
}

echo json_encode($events);
