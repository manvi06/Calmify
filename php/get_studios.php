<?php
include 'config.php';
header('Content-Type: application/json'); 
$result = $conn->query("SELECT class_id, title, lat, lng FROM classes");
$studios = [];
while ($row = $result->fetch_assoc()) {
    $studios[] = $row;
}
echo json_encode($studios);
$conn->close();
?>