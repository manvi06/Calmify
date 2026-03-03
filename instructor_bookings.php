<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);

if (!$is_logged_in || $_SESSION["role"] !== "instructor") { 
    header("Location: index.php?prompt=login"); 
    exit;
}

include 'php/config.php';
$user_id = $_SESSION["user_id"];
$stmt_name = $conn->prepare("SELECT name FROM users WHERE user_id = ?");
$stmt_name->bind_param("i", $user_id);
$stmt_name->execute();
$instructor_name_data = $stmt_name->get_result()->fetch_assoc();
$instructor_name = htmlspecialchars($instructor_name_data['name'] ?? 'Instructor');
$stmt_name->close();
$conn->close(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Bookings - Calmify</title>
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
    <div class="container dashboard-container text-center">
        <h2 class="mt-4"><?php echo strtoupper($instructor_name); ?></h2>
        <div class="dashboard-logo-container mb-4">
            <img src="images/yoga-bg.png" alt="Calmify Logo" class="dashboard-logo">
        </div>

        <div class="dashboard-content">
            <h3 class="dashboard-section-heading">Upcoming Classes:</h3>
            <div id="upcoming-classes-list">
                </div>

            <h3 class="dashboard-section-heading mt-5">Reviews:</h3>
            <div id="reviews-list">
                </div>
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

            // upcoming classes for instructor
            $.ajax({
                url: 'php/get_instructor_upcoming_classes.php', 
                method: 'GET',
                success: function(data) {
                    let upcomingHtml = '';
                    if (Array.isArray(data)) {
                        if (data.length > 0) {
                            data.forEach(c => {
                                upcomingHtml += `
                                    <div class="booking-card">
                                        <h5>${htmlspecialchars(c.title)} (${htmlspecialchars(c.style)})</h5>
                                        <p>Time: ${new Date(c.time).toLocaleString()}</p>
                                        <p>Location: ${htmlspecialchars(c.location || 'N/A')}</p>
                                        </div>
                                `;
                            });
                        } else {
                            upcomingHtml = '<p class="no-classes-message">No upcoming classes.</p>';
                        }
                    } else {
                        console.error('Invalid data format:', data);
                        upcomingHtml = '<p class="text-danger">Error loading upcoming classes.</p>';
                    }
                    $('#upcoming-classes-list').html(upcomingHtml);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    $('#upcoming-classes-list').html('<p class="text-danger">Error loading upcoming classes.</p>');
                }
            });

            // reviews for instructor's classes
            $.ajax({
                url: 'php/get_instructor_reviews.php', 
                method: 'GET',
                success: function(data) {
                    let reviewsHtml = '';
                    if (Array.isArray(data)) {
                        if (data.length > 0) {
                            data.forEach(review => {
                                reviewsHtml += `
                                    <div class="booking-card">
                                        <h5>${htmlspecialchars(review.user_name || 'Anonymous')} - ${htmlspecialchars(review.class_title)}</h5>
                                        <p>Rating: ${review.rating} / 5</p>
                                        <p>${htmlspecialchars(review.comment)}</p>
                                    </div>
                                `;
                            });
                        } else {
                            reviewsHtml = '<p class="no-classes-message">No reviews yet.</p>';
                        }
                    } else {
                        console.error('Invalid data format:', data);
                        reviewsHtml = '<p class="text-danger">Error loading reviews.</p>';
                    }
                    $('#reviews-list').html(reviewsHtml);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    $('#reviews-list').html('<p class="text-danger">Error loading reviews.</p>');
                }
            });

            // for client-side
            function htmlspecialchars(str) {
                var map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return str.replace(/[&<>"']/g, function(m) { return map[m]; });
            }
        });
    </script>
</body>
</html>
