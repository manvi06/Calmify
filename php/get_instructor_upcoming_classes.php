<?php
session_start();
header('Content-Type: application/json');
include 'config.php';

$classes = [];
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'instructor') {
    $instructor_user_id = $_SESSION['user_id'];

    // instructor_id from the instructors table using the user_id
    $stmt_instructor_id = $conn->prepare("SELECT instructor_id FROM instructors WHERE user_id = ?");
    if ($stmt_instructor_id) {
        $stmt_instructor_id->bind_param("i", $instructor_user_id);
        $stmt_instructor_id->execute();
        $result_instructor_id = $stmt_instructor_id->get_result();
        $instructor_id_row = $result_instructor_id->fetch_assoc();
        $stmt_instructor_id->close();

        if ($instructor_id_row) {
            $instructor_id = $instructor_id_row['instructor_id'];

            // upcoming classes for instructor
            $query = "SELECT class_id, title, style, time, location
                      FROM classes
                      WHERE instructor_id = ? AND time > NOW()
                      ORDER BY time ASC";
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("i", $instructor_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $classes[] = $row;
                }
                $stmt->close();
            } else {
                error_log("Error preparing get_instructor_upcoming_classes statement: " . $conn->error);
            }
        }
    } else {
        error_log("Error preparing instructor_id lookup statement: " . $conn->error);
    }
}
echo json_encode($classes);
$conn->close();
?>
