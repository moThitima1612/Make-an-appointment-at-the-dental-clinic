<?php
include 'index.php';

$service = $_GET['service_id'];

$q = mysqli_query($db, "SELECT dentist_id AS id, name AS name FROM dentists WHERE service_id='$service'");

$data = [];
while($r=mysqli_fetch_assoc($q)){
    $data[] = $r;
}
echo json_encode($data);
