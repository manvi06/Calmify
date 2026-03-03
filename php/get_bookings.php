<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION["user_id"];
$query = "SELECT b.booking_id, c.title, c.style, c.time, b.status
          FROM bookings b
          JOIN classes c ON b.class_id = c.class_id
          WHERE b.user_id = ?
          ORDER BY c.time DESC";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["error" => "Error preparing statement: " . $conn->error]);
    exit;
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode($bookings);

$stmt->close();
$conn->close();
?>