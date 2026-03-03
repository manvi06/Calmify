<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

if (!isset($_GET['instructor_id'])) {
    echo json_encode(["error" => "Instructor ID is required."]);
    exit;
}

$instructor_id = $_GET['instructor_id'];

// instructor details
$query = "SELECT u.name, u.email, i.bio, i.qualifications
          FROM users u
          JOIN instructors i ON u.user_id = i.user_id
          WHERE i.instructor_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["error" => "Error preparing instructor details statement: " . $conn->error]);
    exit;
}
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$instructor_details = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$instructor_details) {
    echo json_encode(["error" => "Instructor not found."]);
    exit;
}

// classes offered by instructor
$classes_query = "SELECT title, style, time, location
                  FROM classes
                  WHERE instructor_id = ?
                  ORDER BY time ASC";
$stmt_classes = $conn->prepare($classes_query);
if (!$stmt_classes) {
    echo json_encode(["error" => "Error preparing classes statement: " . $conn->error]);
    exit;
}
$stmt_classes->bind_param("i", $instructor_id);
$stmt_classes->execute();
$classes_result = $stmt_classes->get_result();

$instructor_classes = [];
while ($row = $classes_result->fetch_assoc()) {
    $instructor_classes[] = $row;
}
$stmt_classes->close();

echo json_encode([
    "status" => "success",
    "details" => $instructor_details,
    "classes" => $instructor_classes
]);

$conn->close();
?>