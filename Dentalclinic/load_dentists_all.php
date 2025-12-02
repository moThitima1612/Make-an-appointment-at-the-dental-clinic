<?php
include 'index.php';

$date = date("Y-m-d");

$sql = "
SELECT d.dentist_id AS id,
       d.name AS name,
       d.specialty1,
       d.photo_url AS photo,
       d.rating,
       d.max_queue_per_day,
       COUNT(a.appointment_id) AS queue_today
FROM dentists d
LEFT JOIN appointmentsb a
    ON d.dentist_id = a.dentist_id
    AND a.appointment_date = '$date'
    AND a.status <> 'cancelled'
GROUP BY d.dentist_id
ORDER BY d.name ASC";

$q = mysqli_query($db, $sql);

$output = [];
while($r = mysqli_fetch_assoc($q)){

    // สถานะ ว่าง/เต็ม
    $r['status'] = ($r['queue_today'] < $r['max_queue_per_day']) ? "available" : "full";

    // รูป default
    if(!$r['photo']) $r['photo'] = "noimage.png";

    // แสดงคิววันนี้
    $timeSQL = mysqli_query($db,
        "SELECT appointment_time
         FROM appointmentsb
         WHERE dentist_id='{$r['id']}'
           AND appointment_date='$date'
           AND status <> 'cancelled'
         ORDER BY appointment_time ASC"
    );

    $times = [];
    while($t = mysqli_fetch_assoc($timeSQL)){
        $times[] = substr($t['appointment_time'],0,5);
    }

    $r['today_times'] = $times;

    $output[] = $r;
}

echo json_encode($output);
