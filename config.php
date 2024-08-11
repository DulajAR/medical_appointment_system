<?php
$servername = "localhost";
$username = "root";  // Update if necessary
$password = "1234";      // Update if necessary
$dbname = "medical_appointment_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
