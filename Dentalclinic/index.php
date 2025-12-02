<?php
/* $servername = "localhost";
$username = "root";
$password = "root";
$dbname = "mydb";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully"; */



?>

<?php
$db = mysqli_connect("localhost", "root", "root", "mydb");

if (!$db) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
