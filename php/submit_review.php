<?php
session_start();
include 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $class_id = $_POST["class_id"];
    $rating = $_POST["rating"];
    $comment = $_POST["comment"];
    $stmt = $conn->prepare("INSERT INTO reviews (class_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $class_id, $user_id, $rating, $comment);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Review submission failed: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request or not logged in."]);
}
$conn->close();
?>