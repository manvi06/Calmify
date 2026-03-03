$(document).ready(function() {
    // login form submission inside the modal
    $('#login-form').on('submit', function(e) {
        e.preventDefault();

        const email = $('#email').val();
        const password = $('#password').val();

        $.ajax({
            url: 'php/login.php',
            method: 'POST',
            data: {
                email: email,
                password: password
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // URL stored before showing the modal
                    const intendedUrl = sessionStorage.getItem('intendedUrl');
                    sessionStorage.removeItem('intendedUrl'); 

                    alert('Login successful! Redirecting...');
                    if (intendedUrl) {
                        window.location.href = intendedUrl;
                    } else if (response.role === 'instructor') {
                        window.location.href = 'instructor.php';
                    } else {
                        window.location.href = 'dashboard.php';
                    }
                } else {
                    $('#login-modal-message').text(response.message || 'Login failed. Please check your credentials.');
                    alert(response.message || 'Login failed.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                $('#login-modal-message').text('An error occurred during login. Please try again.');
                alert('An error occurred during login.');
            }
        });
    });
});

// Function to check login status and redirect or prompt
function checkLoginAndRedirect(targetUrl) {
    $.ajax({
        url: 'php/check_session.php', 
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.logged_in) {
                window.location.href = targetUrl;
            } else {
                // Not logged in
                sessionStorage.setItem('intendedUrl', targetUrl);
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                $('#login-modal-message').text('Please log in to access this page.'); 
                loginModal.show();
            }
        },
        error: function(xhr, status, error) {
            console.error('Session check error:', status, error);
            alert('Could not verify login status. Please try again.');
            // Fallback: show login modal anyway and store URL
            sessionStorage.setItem('intendedUrl', targetUrl);
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            $('#login-modal-message').text('An error occurred while checking login status. Please log in.');
            loginModal.show();
        }
    });
    return false; 
}

// Function to set active class on navigation links
function setActiveNavItem() {
    const path = window.location.pathname;
    const page = path.split('/').pop(); // last part of the path

    $('.navbar-nav .nav-link').removeClass('active'); 

    if (page === '' || page === 'index.php') {
        $('.navbar-nav .nav-link[href="index.php"]').addClass('active');
    } else if (page.includes('dashboard.php') || page.includes('user_profile.php')) {
        $('.navbar-nav .nav-link[href="dashboard.php"]').addClass('active');
    } else if (page.includes('booking.php')) {
        $('.navbar-nav .nav-link[href="booking.php"]').addClass('active');
    } else if (page.includes('instructor.php') || page.includes('instructor_bookings.php') || page.includes('insights.php')) {
        $('.navbar-nav .nav-link[href="instructor.php"]').addClass('active');
    }
}

// Function to display instructor details in a modal
function displayInstructorDetails(instructorId, instructorName) {
    $('#modalInstructorImage').attr('src', '');
    $('#modalInstructorName').text('');
    $('#modalInstructorEmail').text('');
    $('#modalInstructorBio').text('');
    $('#modalInstructorQualifications').text('');
    $('#modalInstructorClasses').empty();

    // image names correspond directly to the instructor's name in lowercase
    const imgPath = `images/${instructorName.toLowerCase()}.jpg`;
    $('#modalInstructorImage').attr('src', imgPath);

    // Fetch instructor details and classes
    $.ajax({
        url: 'php/get_instructor_details.php',
        method: 'GET',
        data: { instructor_id: instructorId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success' && response.details) {
                const details = response.details;
                $('#modalInstructorName').text(details.name);
                $('#modalInstructorEmail').text(details.email);
                $('#modalInstructorBio').text(details.bio);
                $('#modalInstructorQualifications').text(details.qualifications);

                let classesHtml = '';
                if (response.classes && response.classes.length > 0) {
                    response.classes.forEach(cls => {
                        classesHtml += `<a href="booking.php" class="list-group-item list-group-item-action flex-column align-items-start">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">${cls.title} (${cls.style})</h5>
                                                <small>${new Date(cls.time).toLocaleString()}</small>
                                            </div>
                                            <p class="mb-1">Location: ${cls.location}</p>
                                        </a>`;
                    });
                } else {
                    classesHtml = '<p class="text-muted">No classes available for this instructor at the moment.</p>';
                }
                $('#modalInstructorClasses').html(classesHtml);

                const instructorDetailModal = new bootstrap.Modal(document.getElementById('instructorDetailModal'));
                instructorDetailModal.show();
            } else {
                alert(response.error || 'Could not fetch instructor details.');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error fetching instructor details:', status, error);
            alert('An error occurred while loading instructor details.');
        }
    });
}