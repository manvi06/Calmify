<?php
include 'config.php';
header('Content-Type: application/json');

// instructor's user ID 
session_start();
$instructor_user_id = $_SESSION["user_id"] ?? null;

$data = ["class_types" => [], "bookings" => []];

if ($instructor_user_id) {
    $query = "SELECT c.style, COUNT(b.booking_id) as count
              FROM classes c
              JOIN bookings b ON c.class_id = b.class_id
              JOIN instructors i ON c.instructor_id = i.instructor_id
              WHERE i.user_id = ?
              GROUP BY c.style
              ORDER BY count DESC"; 
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $instructor_user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $data["class_types"][] = $row["style"];
            $data["bookings"][] = $row["count"];
        }
        $stmt->close();
    } else {
        error_log("Error preparing get_booking_stats query: " . $conn->error);
    }
} else {
    error_log("Attempted to access get_booking_stats without instructor login.");
}

echo json_encode($data);
$conn->close();
?>