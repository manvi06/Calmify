<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "user") {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $name = $_POST["name"] ?? '';
    $phoneNumber = $_POST["phone_number"] ?? ''; 

    // Update statement for users table  
    $stmt = $conn->prepare("UPDATE users SET name = ?, phone_number = ?  WHERE user_id = ?");

    $stmt->bind_param("ssi", $name, $phone_number, $user_id);

    if ($stmt->execute()) {
        $_SESSION["user_name"] = $name; 
        echo json_encode(["status" => "success", "message" => "Profile updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update profile: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
$conn->close();
?>