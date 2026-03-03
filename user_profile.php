<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);

if (!$is_logged_in) {
    header("Location: index.php?prompt=login"); 
    exit();
}

include 'php/config.php'; 
$user_id = $_SESSION['user_id'];

// user data from the db
$stmt = $conn->prepare("SELECT name, email, phone_number FROM users WHERE user_id = ?"); 
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data_db = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

if ($user_data_db) {
    $full_name = htmlspecialchars($user_data_db['name']);
    $name_parts = explode(' ', $full_name, 2); 
    $first_name = htmlspecialchars($name_parts[0] ?? '');
    $last_name = htmlspecialchars($name_parts[1] ?? '');

    $phone_number = htmlspecialchars($user_data_db['phone_number'] ?? 'N/A'); // phone null
    $email = htmlspecialchars($user_data_db['email']);
} else {
    // Fallback 
    $first_name = 'N/A';
    $last_name = 'N/A';
    $phone_number = 'N/A';
    $email = 'N/A';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calmify - User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand d-lg-none" href="index.php">Calmify</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav w-100 justify-content-around">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Homepage</a></li>
                    <?php if ($is_logged_in && $_SESSION["role"] !== "instructor"): ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">User Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="booking.php">Booking Page</a></li>
                    <?php endif; ?>
                    <?php if ($is_logged_in && $_SESSION["role"] === "instructor"): ?>
                        <li class="nav-item"><a class="nav-link" href="instructor.php">Instructor Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="instructor_bookings.php">Bookings</a></li>
                        <li class="nav-item"><a class="nav-link" href="insights.php">Insights</a></li>
                    <?php endif; ?>
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
        <h1 class="mt-4">PROFILE</h1>
        <div class="profile-logo-container mb-4">
            <img src="images/yoga-bg.png" alt="Calmify Logo" class="profile-logo">
        </div>

        <div class="profile-details">
            <div class="profile-detail-row">
                <span class="profile-label">First Name</span>
                <input type="text" class="profile-value-input" id="firstNameValue" value="<?php echo $first_name; ?>">
            </div>
            <div class="profile-detail-row">
                <span class="profile-label">Last Name</span>
                <input type="text" class="profile-value-input" id="lastNameValue" value="<?php echo $last_name; ?>">
            </div>
            <div class="profile-detail-row">
                <span class="profile-label">Phone Number</span>
                <input type="text" class="profile-value-input" id="phoneNumberValue" value="<?php echo $phone_number; ?>">
            </div>
            <div class="profile-detail-row">
                <span class="profile-label">Email</span>
                <input type="email" class="profile-value-input" id="emailValue" value="<?php echo $email; ?>">
            </div>
        </div>

        <div class="profile-buttons-container">
            <button type="button" class="btn profile-button-discard" id="discardBtn">Discard</button>
            <button type="button" class="btn profile-button-save" id="saveBtn">Save</button>
        </div>
    </div>

    <footer class="footer mt-auto">
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
            setActiveNavItem(); // Set active class

            $('#saveBtn').on('click', function() {
                // var firstName = $('#firstNameValue').val();
                // var lastName = $('#lastNameValue').val();
                // ... PHP script via AJAX for database update.

                alert('Profile data would be saved here. (Actual saving functionality not yet implemented). Redirecting to User Dashboard.');
                window.location.href = 'dashboard.php';
            });

            $('#discardBtn').on('click', function() {
                //simply redirect without saving.

                alert('Changes discarded. Redirecting to User Dashboard.');
                window.location.href = 'dashboard.php';
            });
        });
    </script>
</body>
</html>