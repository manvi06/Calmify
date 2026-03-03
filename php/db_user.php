<?php
include 'config.php';

// Test user credentials
$email = "testuser@example.com";
$password = "Test@1234";
$role = "user";
$name = "John Doe"; 

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);


$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}
$stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

if ($stmt->execute()) {
    echo "Test user created successfully!<br>";
    echo "Name: $name<br>";
    echo "Email: $email<br>";
    echo "Password: $password<br>";
    echo "Role: $role<br>";
} else {
    echo "Error creating test user: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>