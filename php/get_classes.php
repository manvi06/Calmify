<?php
header('Content-Type: application/json');
include 'config.php';

$style = isset($_GET['style']) ? $_GET['style'] : '';
$instructorName = isset($_GET['instructor']) ? $_GET['instructor'] : '';
$isTrial = isset($_GET['trial']) && $_GET['trial'] === 'true';

$query = "SELECT cl.class_id, cl.title, cl.style, cl.level, cl.time, cl.location, u.name as instructor_name
          FROM classes cl
          JOIN instructors i ON cl.instructor_id = i.instructor_id
          JOIN users u ON i.user_id = u.user_id
          WHERE 1=1";

$params = [];
$types = "";

if ($style) {
    $query .= " AND cl.style LIKE ?";
    $params[] = "%" . $style . "%";
    $types .= "s";
}
if ($instructorName) {
    $query .= " AND u.name LIKE ?";
    $params[] = "%" . $instructorName . "%";
    $types .= "s";
}
// You might define "trial classes" by a specific style, or a separate column, or just the first class for an instructor.
// For this example, let's assume trial classes are just "Beginner" level classes for simplicity or specific class IDs.
if ($isTrial) {
    $query .= " AND cl.level = 'beginner'"; // Example: trial classes are beginner level
}

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(["error" => "Error preparing statement: " . $conn->error]);
    exit;
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$classes = [];
while ($row = $result->fetch_assoc()) {
    $classes[] = $row;
}

echo json_encode($classes);
$stmt->close();
$conn->close();
?>