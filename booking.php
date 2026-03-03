<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);

if (!$is_logged_in) { 
    header("Location: index.php?prompt=login"); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Class - Calmify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
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

    <div class="container mt-4">
        <h2 class="mt-4">Book a Class</h2>
        <select id="style-filter" class="form-control mb-3">
            <option value="">All Styles</option>
            <option value="Vinyasa">Vinyasa</option>
            <option value="Hatha">Hatha</option>
            <option value="Kundalini">Kundalini</option>
        </select>
        <div id="map" class="map" style="height: 400px;"></div>
        <div id="class-list" class="row mt-4"></div>
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
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="js/script.js"></script>
    <script>
        var map = L.map('map').setView([-33.8688, 151.2093], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
        fetch('php/get_studios.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(studio => {
                    L.marker([studio.lat, studio.lng])
                        .addTo(map)
                        .bindPopup(`<b>${studio.title}</b><br><a href="booking.php?class_id=${studio.class_id}">Book Now</a>`);
                });
            });

        function loadClasses() {
            $.ajax({
                url: 'php/get_classes.php',
                method: 'GET',
                data: {
                    style: $('#style-filter').val(),
                    instructor: new URLSearchParams(window.location.search).get('instructor') || '',
                    trial: new URLSearchParams(window.location.search).get('trial') || ''
                },
                dataType: 'json',
                success: function(data) {
                    let html = '';
                    if (Array.isArray(data)) {
                        if (data.length > 0) {
                            data.forEach(c => {
                                html += `<div class="col-md-4 mb-4 px-3"><div class="card"><div class="card-body">
                                    <h5>${c.title || 'Untitled'}</h5><p>${c.style || 'N/A'} - ${c.time || 'N/A'}</p>
                                    <button type="button" class="btn btn-primary" onclick="bookClass(${c.class_id || 0})">Book</button>
                                </div></div></div>`;
                            });
                        } else {
                            html = '<p class="no-classes-message">No classes available.</p>';
                        }
                    } else {
                        console.error('Invalid data format:', data);
                        html = '<p class="text-danger">Error: Invalid data received from server.</p>';
                    }
                    $('#class-list').html(html);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    $('#class-list').html('<p class="text-danger">Error loading classes.</p>');
                }
            });
        }

        function bookClass(classId) {
            $.ajax({
                url: 'php/book_class.php',
                method: 'POST',
                data: { class_id: classId },
                dataType: 'json',
                success: function(response) {
                    alert(response.status === 'success' ? 'Booking successful!' : (response.message || 'Booking failed.'));
                    loadClasses(); // Refresh list
                },
                error: function(xhr, status, error) {
                    console.error('Booking Error:', status, error);
                    alert('An error occurred while booking.');
                }
            });
        }

        $(document).ready(function() {
            $('#style-filter').on('change', loadClasses);
            loadClasses(); // Initial load
            setActiveNavItem(); // active class for nav item
        });
    </script>
</body>
</html>