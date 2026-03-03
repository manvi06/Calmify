<?php
include 'config.php'; 

// Test instructor credentials
$email = "instructortest@example.com";
$password = "Instructor@1234";
$role = "instructor";
$name = "Manvi"; 

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Prepare and execute the insert statement for users table
$stmt_user = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
if (!$stmt_user) {
    echo "Error preparing user statement: " . $conn->error;
    exit;
}
$stmt_user->bind_param("ssss", $name, $email, $hashedPassword, $role);

if ($stmt_user->execute()) {
    $new_user_id = $stmt_user->insert_id; 
    $stmt_ins = $conn->prepare("INSERT INTO instructors (user_id, bio, qualifications) VALUES (?, ?, ?)");
    $default_bio = "Experienced yoga instructor with 15 years of teaching, specializing in Vinyasa and Hatha flow. My passion is guiding students to find peace and strength through movement.";
    $default_qual = "Certified Yoga Alliance (RYT-500), Vipasana Meditation Practitioner, Advanced Anatomy for Yoga Certification.";
    $stmt_ins->bind_param("iss", $new_user_id, $default_bio, $default_qual);

    if ($stmt_ins->execute()) {
        echo "Test instructor (User ID: $new_user_id) created successfully!<br>";
        echo "Name: $name<br>";
        echo "Email: $email<br>";
        echo "Password: $password<br>";
        echo "Role: $role<br>";
    } else {
        echo "Error creating instructor profile for user ID $new_user_id: " . $stmt_ins->error;
        $conn->query("DELETE FROM users WHERE user_id = $new_user_id");
    }
    $stmt_ins->close();
} else {
    echo "Error creating test instructor user: " . $stmt_user->error;
}

$stmt_user->close();
$conn->close();
?>