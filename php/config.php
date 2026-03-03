<?php
$conn = new mysqli("localhost", "", "", "calmify");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>