<?php
session_start();
include 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "instructor") {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $bio = $_POST["bio"] ?? '';
    $qualifications = $_POST["qualifications"] ?? '';

    // Check if instructor entry exists for this user_id, if not, create it
    $stmt_check = $conn->prepare("SELECT instructor_id FROM instructors WHERE user_id = ?");
    $stmt_check->bind_param("i", $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Update existing instructor profile
        $stmt = $conn->prepare("UPDATE instructors SET bio = ?, qualifications = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $bio, $qualifications, $user_id);
    } else {

        $stmt = $conn->prepare("INSERT INTO instructors (user_id, bio, qualifications) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $bio, $qualifications);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Profile updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update profile: " . $stmt->error]);
    }
    $stmt->close();
    $stmt_check->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
$conn->close();
?>