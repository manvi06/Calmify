<?php
session_start();
// user is logged in or not 
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calmify - Book. Breathe. Balance.</title>
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

    <div class="hero text-center text-white d-flex align-items-center justify-content-center">
        <div>
            <h1>Book. Breathe. Balance</h1>
            <p class="lead">Get started on the path to spiritual wellness.</p>
            <div class="calmify-logo-container mb-4">
                <img src="images/yoga-bg.png" alt="Calmify Logo" class="calmify-logo">
            </div>
            <button type="button" class="btn btn-primary mt-3" <?php if (!$is_logged_in) echo 'onclick="return checkLoginAndRedirect(\'booking.php\')"'; ?>>Start Now</button>
        </div>
    </div>

    <div class="content-section text-center d-flex align-items-center justify-content-center">
        <div class="container row align-items-center">
            <div class="col-md-6 text-md-start mb-3 mb-md-0">
                <p class="display-6 content-text">Put your spiritual needs first to live a happy and healthy life. We focus on strength, flexibility, and balance.</p>
                <a href="#featured-classes" class="btn btn-primary mt-3">Join a Trial Class</a>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-center">
                    <img src="images/yoga-pose.png" alt="Yoga Poses" class="img-fluid yoga-poses-img">
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="text-center mb-4 team-heading">Meet the team</h2>
        <div class="row justify-content-center">
            <div class="col-md-4 text-center">
                <div class="instructor-card">
                    <img src="images/ash.jpg" alt="Ash" class="instructor-img">
                    <h5>Beginner Instructor: Ash</h5>
                    <p>I am Ash. I am certified in vipasana and have been teaching yoga classes for over 5 years.</p>
                    <button type="button" class="btn btn-outline-primary mt-2" data-instructor-id="3" data-instructor-name="Ash" onclick="displayInstructorDetails(this.dataset.instructorId, this.dataset.instructorName)">Get Started</button>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="instructor-card">
                    <img src="images/manvi.jpg" alt="Manvi" class="instructor-img">
                    <h5>Expert Instructor: Manvi</h5>
                    <p>I am Manvi. I am certified in vipasana and have been teaching yoga classes for over 15 years.</p>
                    <button type="button" class="btn btn-outline-primary mt-2" data-instructor-id="1" data-instructor-name="Manvi" onclick="displayInstructorDetails(this.dataset.instructorId, this.dataset.instructorName)">Get Started</button>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="instructor-card">
                    <img src="images/david.jpg" alt="David" class="instructor-img">
                    <h5>Experienced Yogi: David</h5>
                    <p>I am David. I am certified in vipasana and have been teaching yoga classes for over 25 years.</p>
                    <button type="button" class="btn btn-outline-primary mt-2" data-instructor-id="4" data-instructor-name="David" onclick="displayInstructorDetails(this.dataset.instructorId, this.dataset.instructorName)">Get Started</button>
                </div>
            </div>
        </div>
    </div>

    <div id="featured-classes" class="container mt-5">
        <h2 class="text-center mb-4 featured-heading">Featured Classes</h2>
        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="card featured-class-card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Morning Flow</h5>
                        <p class="card-text">Vinyasa - 7:00 AM</p>
                        <button type="button" class="btn btn-primary" <?php if (!$is_logged_in) echo 'onclick="return checkLoginAndRedirect(\'booking.php?class_id=1\')"';?>>Book</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card featured-class-card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Evening Serenity</h5>
                        <p class="card-text">Hatha - 6:00 PM</p>
                        <button type="button" class="btn btn-primary" <?php if (!$is_logged_in) echo 'onclick="return checkLoginAndRedirect(\'booking.php?class_id=2\')"';?>>Book</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card featured-class-card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Power Yoga</h5>
                        <p class="card-text">Vinyasa - 5:30 PM</p>
                        <button type="button" class="btn btn-primary" <?php if (!$is_logged_in) echo 'onclick="return checkLoginAndRedirect(\'booking.php?class_id=3\')"'; ?>>Book</button>
                    </div>
                </div>
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

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login Required</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="login-modal-message">Please log in to access this page.</p>
                    <form id="login-form">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="instructorDetailModal" tabindex="-1" aria-labelledby="instructorDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="instructorDetailModalLabel">Instructor Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalInstructorImage" src="" alt="Instructor" class="instructor-img mb-3">
                    <h4 id="modalInstructorName"></h4>
                    <p class="text-muted" id="modalInstructorEmail"></p>
                    <p id="modalInstructorBio" class="text-start"></p>
                    <p id="modalInstructorQualifications" class="text-start"></p>
                    <hr>
                    <h5 class="text-start">Available Classes:</h5>
                    <div id="modalInstructorClasses" class="list-group">
                        </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        $(document).ready(function() {
            // Check if redirect from protected page (e.g., booking.php)
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('prompt') === 'login') {
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                $('#login-modal-message').text('You need to be logged in to access that page.');
                loginModal.show();
            }

            setActiveNavItem(); 
        });
    </script>
</body>
</html>