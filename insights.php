<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);

if (!$is_logged_in || $_SESSION["role"] !== "instructor") { // Combined check
    header("Location: index.php?prompt=login"); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Insights - Calmify</title>
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
                    <li class="nav-item"><a class="nav-link" href="instructor.php">Instructor Profile</a></li> <li class="nav-item"><a class="nav-link" href="instructor_bookings.php" <?php if (!$is_logged_in) echo 'onclick="return checkLoginAndRedirect(\'instructor_bookings.php\')"'; ?>>Bookings</a></li>
                    <li class="nav-item"><a class="nav-link active" href="insights.php">Insights</a></li>
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
        <h2>Instructor Insights</h2>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="chart-container">
                    <h4>Bookings per Class Type</h4>
                    <canvas id="bookingChartClassType"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="chart-container">
                    <h4>Bookings per Time Slot</h4>
                    <canvas id="bookingChartTimeSlot"></canvas>
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

    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        $(document).ready(function() {
            // bookings per Class Type
            fetch('php/get_booking_stats.php')
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('bookingChartClassType').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.class_types,
                            datasets: [{
                                label: 'Bookings',
                                data: data.bookings,
                                backgroundColor: 'rgba(244, 162, 97, 0.8)',
                                borderColor: 'rgba(244, 162, 97, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    ticks: {
                                        autoSkip: true,
                                        maxRotation: 45,
                                        minRotation: 0
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    min: 0,   
                                    max: 10,  
                                    ticks: {
                                        stepSize: 1 
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching class type stats:', error));

            // bookings per Time Slot
            fetch('php/get_timeslot_stats.php') 
                .then(response => response.json())
                .then(data => {
                    const ctxTime = document.getElementById('bookingChartTimeSlot').getContext('2d');
                    new Chart(ctxTime, {
                        type: 'bar',
                        data: {
                            labels: data.time_slots,
                            datasets: [{
                                label: 'Bookings',
                                data: data.bookings,
                                backgroundColor: 'rgba(233, 196, 106, 0.8)',
                                borderColor: 'rgba(233, 196, 106, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    ticks: {
                                        autoSkip: true,
                                        maxRotation: 45,
                                        minRotation: 0
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    min: 0,   
                                    max: 10,  
                                    ticks: {
                                        stepSize: 1 
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching time slot stats:', error));

            setActiveNavItem();
        });
    </script>
</body>
</html>