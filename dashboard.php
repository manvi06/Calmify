<?php
session_start();

$is_logged_in = isset($_SESSION['user_id']);
if (!$is_logged_in) {
    header("Location: index.php?prompt=login");
    exit();
}

$username = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User';

include 'php/config.php';

// user's actual bookings from the database
$user_id = $_SESSION["user_id"];
$query = "SELECT b.booking_id, c.title, c.style, c.time, b.status
          FROM bookings b
          JOIN classes c ON b.class_id = c.class_id
          WHERE b.user_id = ?
          ORDER BY c.time DESC";
$stmt = $conn->prepare($query);
$bookings_data = []; 
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $bookings_data[] = $row;
    }
    $stmt->close();
} else {
    error_log("Error preparing get_bookings statement in dashboard.php: " . $conn->error);
}
$conn->close();

$bookings_json = json_encode($bookings_data);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calmify - User Dashboard</title>
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
        <h2 class="mt-4">WELCOME <?php echo strtoupper($username); ?></h2>
        <div class="dashboard-logo-container mb-4">
            <img src="images/yoga-bg.png" alt="Calmify Logo" class="dashboard-logo"> </div>
        <a href="user_profile.php" class="btn profile-button">Manage my Profile</a>

        <div class="dashboard-content">
            <h3 class="dashboard-section-heading">Booking History:</h3>
            <div id="booking-history-list">
                </div>

            <h3 class="dashboard-section-heading mt-5">Upcoming Classes:</h3>
            <div id="upcoming-classes-list">
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
        const bookingsData = <?php echo $bookings_json; ?>;

        let historyHtml = '';
        let upcomingHtml = '';

        if (Array.isArray(bookingsData)) {
            if (bookingsData.length > 0) {
                bookingsData.forEach(b => {
                    const classTime = new Date(b.time);
                    const now = new Date();
                    const bookingCardHtml = `
                    <div class="booking-card">
                        <h5>${htmlspecialchars(b.title)} (${htmlspecialchars(b.style)})</h5>
                        <p>Time: ${classTime.toLocaleString()}</p>
                        <p>Status: ${htmlspecialchars(b.status)}</p>
                    </div>`;
                    if (classTime > now) {
                        upcomingHtml += bookingCardHtml;
                    } else {
                        historyHtml += bookingCardHtml;
                    }
                });

                if (historyHtml === '') {
                    historyHtml = '<p class="no-classes-message">No past bookings.</p>';
                }
                if (upcomingHtml === '') {
                    upcomingHtml = '<p class="no-classes-message">No upcoming classes.</p>';
                }

            } else {
            historyHtml = '<p class="no-classes-message">No past bookings.</p>';
            upcomingHtml = '<p class="no-classes-message">No upcoming classes.</p>';
            }
        } else {
            console.error('Invalid bookings data format:', bookingsData);
            historyHtml = '<p class="text-danger">Error loading booking history.</p>';
            upcomingHtml = '<p class="text-danger">Error loading upcoming classes.</p>';
        }

    $('#booking-history-list').html(historyHtml);
    $('#upcoming-classes-list').html(upcomingHtml);

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