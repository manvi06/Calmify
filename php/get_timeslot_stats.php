<?php
include 'config.php';
header('Content-Type: application/json');

session_start();
$instructor_user_id = $_SESSION["user_id"] ?? null;

$data = ["time_slots" => [], "bookings" => []];

if ($instructor_user_id) {
    // groups bookings by the hour of the class time
    $query = "SELECT DATE_FORMAT(c.time, '%H:00') as hour_slot, COUNT(b.booking_id) as count
              FROM classes c
              JOIN bookings b ON c.class_id = b.class_id
              JOIN instructors i ON c.instructor_id = i.instructor_id
              WHERE i.user_id = ?
              GROUP BY hour_slot
              ORDER BY hour_slot ASC";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $instructor_user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $data["time_slots"][] = $row["hour_slot"];
            $data["bookings"][] = $row["count"];
        }
        $stmt->close();
    } else {
        error_log("Error preparing get_timeslot_stats query: " . $conn->error);
    }
} else {
    error_log("Attempted to access get_timeslot_stats without instructor login.");
}

echo json_encode($data);
$conn->close();
?>