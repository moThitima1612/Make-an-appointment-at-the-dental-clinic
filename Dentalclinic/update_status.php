<?php
include 'index.php';

$id = $_POST['id'];
$status = $_POST['status'];

$q = mysqli_query($db,"UPDATE appointmentsb SET status='$status' WHERE appointment_id='$id'");

echo json_encode(['success'=>$q]);
