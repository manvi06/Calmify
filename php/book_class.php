<?php
session_start();
include 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $class_id = $_POST["class_id"];
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, class_id, status) VALUES (?, ?, 'confirmed')");
    $stmt->bind_param("ii", $user_id, $class_id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Booking failed: " . $stmt->error]); 
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request or not logged in."]);
}
$conn->close();
?>