README.txt - Calmify Project Setup Instructions

This file describes how to set up and run the Calmify Yoga Class Booking Platform.

1. Project URL (Live Project Website)

As this is a local development setup, your live project URL will depend on your local server configuration.
Assuming you have placed the 'calmify' folder in your web server's document root (e.g., 'htdocs' for XAMPP), the full URL for your site will typically be:

http://localhost/calmify/

2. Database Setup

The site requires a MySQL database.

a.  Database Name: calmify

b.  Database Username/Password:
* Username: root
* Password: `` (empty string)

**Important:** If your MySQL setup uses different credentials (e.g., a password for 'root' or a different user), you MUST update these lines in `php/config.php`:

```php/config.php - 
<?php
$conn = new mysqli("localhost", "your_db_username", "your_db_password", "calmify");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

c.  Create Database and Tables:
* Ensure your MySQL server is running.
* Open phpMyAdmin (usually at http://localhost/phpmyadmin).
* Create a new database named calmify.
* Import your database schema SQL file (e.g., calmify.sql if you have one) into this new calmify database. This file should define the users, instructors, classes, bookings, and reviews tables.

3. Initial User Accounts (Optional but Recommended)

You can create initial user accounts by running the provided setup scripts.

a.  Test User Account (Non-Admin):
* Access this URL in your browser: http://localhost/calmify/php/db_user.php
* This will create a regular user with the following credentials:
* Username (Email): testuser@example.com
* Password: Test@1234

b.  Test Instructor Account (Admin/Instructor Role):
* Access this URL in your browser: http://localhost/calmify/php/db_ins.php
* This will create an instructor user with the following credentials:
* Username (Email): instructortest@example.com
* Password: Instructor@1234

**Important:** These `db_user.php` and `db_ins.php` files are for initial setup only. For a live server, they should be deleted or secured to prevent unauthorized access.

4. Project Files Placement

Place the entire calmify/ project folder (containing all PHP, CSS, JS, and image files) into your web server's document root.

For XAMPP: C:\xampp\htdocs\


5. Usage

Once setup is complete:

Open your web browser and navigate to http://localhost/calmify/.

Use the provided test credentials to log in and explore the user and instructor features.