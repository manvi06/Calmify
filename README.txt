# 🧘 Calmify — Yoga Class Booking Platform

A full-stack web application that connects yoga students with instructors, enabling seamless class discovery, booking management, and instructor analytics.

> *"Book. Breathe. Balance."*

---

## 🌟 Features

### For Students
- Browse and search yoga classes by studio and time
- Book classes with real-time availability
- View and manage upcoming bookings via personal dashboard
- Submit reviews for instructors
- Update personal profile

### For Instructors
- Manage instructor profile and bio
- View all student bookings
- Access insights dashboard with booking statistics and timeslot analytics
- Track upcoming classes and student reviews

### Platform
- Role-based authentication (Student / Instructor)
- Session management for secure access
- Responsive UI built with Bootstrap 5

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP |
| Database | MySQL |
| Frontend | HTML5, CSS3, JavaScript, Bootstrap 5 |
| Icons | FontAwesome |
| JS Library | jQuery 3.7.1 |

---

## 📁 Project Structure

```
calmify/
├── index.php                  # Landing page
├── booking.php                # Class booking page (students)
├── dashboard.php              # Student dashboard
├── instructor.php             # Instructor profile page
├── instructor_bookings.php    # Instructor booking management
├── insights.php               # Instructor analytics dashboard
├── user_profile.php           # User profile management
├── css/
│   └── style.css              # Custom styles
├── js/
│   └── script.js              # Frontend interactions
├── php/
│   ├── config.php             # Database connection
│   ├── login.php / logout.php # Auth handlers
│   ├── book_class.php         # Booking logic
│   ├── get_classes.php        # Class data API
│   ├── get_bookings.php       # Booking data API
│   ├── get_instructor_*.php   # Instructor data APIs
│   ├── submit_review.php      # Review submission
│   ├── get_booking_stats.php  # Analytics data
│   └── update_*_profile.php  # Profile update handlers
├── images/                    # Instructor/UI images
└── calmify.sql                # Full database schema
```

---

## 🚀 Getting Started

### Prerequisites
- XAMPP (or any local PHP/MySQL server)
- PHP 7.4+
- MySQL 5.7+

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/manvi06/Calmify.git

# 2. Move the project folder to your XAMPP htdocs directory
# Windows: C:\xampp\htdocs\calmify

# 3. Start Apache and MySQL in XAMPP Control Panel

# 4. Create the database
# - Open phpMyAdmin (http://localhost/phpmyadmin)
# - Create a new database named 'calmify'
# - Import calmify.sql into the database

# 5. Visit the site
# Open: http://localhost/calmify/
```

### Test Accounts
| Role | Email | Password |
|------|-------|----------|
| Student | testuser@example.com | Test@1234 |
| Instructor | instructortest@example.com | Instructor@1234 |

---

## 📸 Screenshots

> *Coming soon*

---

## 🔮 Future Improvements

- [ ] Deploy to cloud (e.g. Railway, Render, or AWS)
- [ ] Add payment integration for premium class bookings
- [ ] Email notifications for booking confirmations
- [ ] AI-powered class recommendations based on user history
- [ ] Mobile app version

---

## 👩‍💻 Author

**Manvi Singh** — [LinkedIn](https://www.linkedin.com/in/manvi-singh) · [GitHub](https://github.com/manvi06)
