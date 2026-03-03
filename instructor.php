<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);

if (!$is_logged_in || $_SESSION["role"] !== "instructor") { 
    header("Location: index.php?prompt=login"); 
    exit;
}
include 'php/config.php';
$user_id = $_SESSION["user_id"];

// instructor details from db
$stmt = $conn->prepare("SELECT i.bio, i.qualifications, u.name, u.email, u.phone_number FROM instructors i JOIN users u ON i.user_id = u.user_id WHERE i.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$instructor_data_db = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

if ($instructor_data_db) {
    $full_name = htmlspecialchars($instructor_data_db['name']);
    $name_parts = explode(' ', $full_name, 2);
    $first_name = htmlspecialchars($name_parts[0] ?? '');
    $last_name = htmlspecialchars($name_parts[1] ?? '');
    $phone_number = htmlspecialchars($instructor_data_db['phone_number'] ?? 'N/A');
    $email = htmlspecialchars($instructor_data_db['email']);
    $bio = htmlspecialchars($instructor_data_db['bio']);
    $qualifications = htmlspecialchars($instructor_data_db['qualifications']);
} else {
    // Fallback
    $first_name = 'N/A';
    $last_name = 'N/A';
    $phone_number = 'N/A';
    $email = 'N/A';
    $bio = 'No bio available.';
    $qualifications = 'No qualifications listed.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Profile - Calmify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    </head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav w-100 justify-content-around">
                    <li class="nav-item"><a class="nav-link" href="index.php">Homepage</a></li>
                    
                    <li class="nav-item"><a class="nav-link active" href="instructor.php">Instructor Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="instructor_bookings.php" <?php if (!$is_logged_in) echo 'onclick="return checkLoginAndRedirect(\'instructor_bookings.php\')"'; ?>>Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="insights.php" <?php if (!$is_logged_in) echo 'onclick="return checkLoginAndRedirect(\'insights.php\')"'; ?>>Insights</a></li>
                    <li class="nav-item">
                        <?php if ($is_logged_in): ?>
                            <a class="nav-link" href="php/logout.php">Logout</a>
                        <?php else: ?>
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container profile-container text-center">
        <h1 class="mt-4">INSTRUCTOR PROFILE</h1>
        <div class="profile-logo-container mb-4">
            <img src="images/yoga-bg.png" alt="Calmify Logo" class="profile-logo">
        </div>

        <div class="profile-details">
            <div class="profile-detail-row">
                <span class="profile-label">First Name</span>
                <input type="text" class="profile-value-input" id="firstNameValue" value="<?php echo $first_name; ?>" readonly>
            </div>
            <div class="profile-detail-row">
                <span class="profile-label">Last Name</span>
                <input type="text" class="profile-value-input" id="lastNameValue" value="<?php echo $last_name; ?>" readonly>
            </div>
            <div class="profile-detail-row">
                <span class="profile-label">Phone Number</span>
                <input type="text" class="profile-value-input" id="phoneNumberValue" value="<?php echo $phone_number; ?>" readonly>
            </div>
            <div class="profile-detail-row">
                <span class="profile-label">Email</span>
                <input type="email" class="profile-value-input" id="emailValue" value="<?php echo $email; ?>" readonly>
            </div>
            <div class="profile-detail-row">
                <span class="profile-label">Bio</span>
                <textarea class="profile-value-input" id="instructorBio" rows="3"><?php echo $bio; ?></textarea>
            </div>
            <div class="profile-detail-row">
                <span class="profile-label">Qualifications</span>
                <textarea class="profile-value-input" id="instructorQualifications" rows="3"><?php echo $qualifications; ?></textarea>
            </div>
        </div>

        <div class="profile-buttons-container">
            <button type="button" class="btn profile-button-discard" id="discardBtn">Discard</button>
            <button type="button" class="btn profile-button-save" id="saveBtn">Save</button>
        </div>
    </div>

    <footer class="footer mt-5 text-center">
        <div class="footer-content">
            <h3>BE CALM AT CALMIFY</h3>
            <div class="footer-details">
                <p>Main Studio<br>123 Anywhere St. Any City, ST 12345<br>(123) 456-7890<br>hello@reallygreatsite.com<br>@reallygreatsite</p>
                <p>Studio Hours<br>Monday to Sunday 5:00 am to 8:00 pm<br>Special classes available upon request</p>
                <p>Get Social<br>
                    <i class="fa-brands fa-facebook-f"></i>
                    <i class="fa-brands fa-twitter"></i>
                    <i class="fa-brands fa-instagram"></i>
                </p>
            </div>
        </div>
    </footer>

    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        $(document).ready(function() {
            setActiveNavItem(); 

            $('#saveBtn').on('click', function() {
                $.ajax({
                    url: 'php/update_instructor_profile.php', 
                    method: 'POST',
                    data: {
                        bio: $('#instructorBio').val(),
                        qualifications: $('#instructorQualifications').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        alert(response.status === 'success' ? 'Profile updated successfully!' : (response.message || 'Failed to update profile.'));
                        location.reload(); 
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        alert('An error occurred while updating profile.');
                    }
                });
            });

            $('#discardBtn').on('click', function() {
                alert('Changes discarded. Redirecting to Instructor Profile.');
                location.reload();
            });
        });
    </script>
</body>
</html>
