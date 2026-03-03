<?php
session_start();
header('Content-Type: application/json');
include 'config.php';

$reviews = [];
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

            // reviews for this instructor
            $query = "SELECT r.rating, r.comment, u.name as user_name, c.title as class_title
                      FROM reviews r
                      JOIN classes c ON r.class_id = c.class_id
                      JOIN instructors i ON c.instructor_id = i.instructor_id
                      JOIN users u ON r.user_id = u.user_id
                      WHERE i.instructor_id = ?
                      ORDER BY r.review_id DESC"; 
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param("i", $instructor_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $reviews[] = $row;
                }
                $stmt->close();
            } else {
                error_log("Error preparing get_instructor_reviews statement: " . $conn->error);
            }
        }
    } else {
        error_log("Error preparing instructor_id lookup statement for reviews: " . $conn->error);
    }
}
echo json_encode($reviews);
$conn->close();
?>
