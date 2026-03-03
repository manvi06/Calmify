<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "instructor") {
    echo json_encode([]);
    exit;
}

$instructor_user_id = $_SESSION["user_id"];

$query = "SELECT b.booking_id, c.title, c.style, c.time, b.status, u.name as user_name
          FROM bookings b
          JOIN classes c ON b.class_id = c.class_id
          JOIN instructors i ON c.instructor_id = i.instructor_id
          JOIN users ins_u ON i.user_id = ins_u.user_id
          JOIN users u ON b.user_id = u.user_id
          WHERE ins_u.user_id = ?
          ORDER BY c.time DESC";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["error" => "Error preparing statement: " . $conn->error]);
    exit;
}
$stmt->bind_param("i", $instructor_user_id);
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