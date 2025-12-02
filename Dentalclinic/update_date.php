<?php
include 'index.php';

$id = $_POST['id'];
$date = $_POST['date'];
$time = $_POST['time'];

$q = mysqli_query($db,"
    UPDATE appointmentsb
    SET appointment_date='$date',
        appointment_time='$time'
    WHERE appointment_id='$id'
");

echo json_encode(['success'=>$q]);
