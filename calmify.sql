CREATE DATABASE calmify;
USE calmify;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('user', 'instructor') DEFAULT 'user',
    `phone_number` int(10) DEFAULT NULL
);

CREATE TABLE instructors (
    instructor_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    bio TEXT,
    qualifications TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE classes (
    class_id INT AUTO_INCREMENT PRIMARY KEY,
    instructor_id INT,
    title VARCHAR(100),
    level ENUM('beginner', 'intermediate', 'advanced'),
    style VARCHAR(50),
    time DATETIME,
    location VARCHAR(100),
    lat DECIMAL(9,6),
    lng DECIMAL(9,6),
    FOREIGN KEY (instructor_id) REFERENCES instructors(instructor_id)
);

CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    class_id INT,
    status ENUM('confirmed', 'cancelled') DEFAULT 'confirmed',
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (class_id) REFERENCES classes(class_id)
);

CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT,
    user_id INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    FOREIGN KEY (class_id) REFERENCES classes(class_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Sample data
INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `phone_number`) VALUES
(1, 'Ash', 'ash@example.com', '$2y$10$XuLZQTf1kvUf.MqO9wn8TuyAC3bvnHz6PAY.cA58/fPuMsBzyi31y', 'instructor', 498271526),
(2, 'David', 'david@example.com', '$2y$10$7bY0u9v1w2x3y4z5a6b7c8d9e0f1g2h3i4j5k6l7m8n9o0p1q2r3', 'instructor', 492749462),
(3, 'Manvi', 'manvi@example.com', '$2y$10$yr1PfYK8jbBRUWuV7tLQXulu0N56L105yIHwglbhISJee/HI6GcXy', 'instructor', 420393715),
(4, 'TestU', 'testuser@example.com', '$2y$10$3qCiLOvnjdFejDQSsfbmB.qMLEi8Xfo5E60tas5eyUmBVb5aUndVK', 'user', 2147483647),
(5, 'TestI', 'instructortest@example.com', '$2y$10$/mY4OO9AkBuvkUNWyzaxiOMdi.TKjy40Rr2RjaJNzD6i5rUYxR5aa', 'instructor', 473274927),
(6, 'Alice', 'alice@example.com', '$2y$10$HASH_ALICE', 'user', 462837930),
(10, 'Yoga Fan', 'yoga@example.com', '$2y$10$SOME_HASH', 'user', 493821989);

INSERT INTO `instructors` (`instructor_id`, `user_id`, `bio`, `qualifications`) VALUES
(1, 3, 'Experienced yoga instructor with 5 years of teaching.', 'Certified Yoga Alliance'),
(2, 5, 'Experienced yoga instructor.', 'Certified Yoga Teacher.'),
(3, 1, 'Experienced yoga instructor.', 'Certified Yoga Teacher.'),
(4, 2, 'Experienced yoga instructor with 5 years of teaching.', 'Certified Yoga Alliance');

INSERT INTO `classes` (`class_id`, `instructor_id`, `title`, `level`, `style`, `time`, `location`, `lat`, `lng`) VALUES
(1, 3, 'Beginner Vinyasa', 'beginner', 'Vinyasa', '2025-06-05 10:00:00', 'Sydney Studio', -33.868800, 151.209300),
(2, 1, 'Advanced Hatha', 'advanced', 'Hatha', '2025-06-06 15:00:00', 'Bondi Studio', -33.891500, 151.276700),
(3, 1, 'Morning Flow', 'beginner', 'Vinyasa', '2025-06-07 07:00:00', 'Park Studio', -33.870000, 151.210000),
(4, 3, 'Evening Wind-Down', 'beginner', 'Hatha', '2025-06-08 18:30:00', 'Online', 0.000000, 0.000000),
(5, 4, 'Power Vinyasa', 'advanced', 'Vinyasa', '2025-06-09 07:00:00', 'Main Studio', -33.868800, 151.209300);

INSERT INTO `bookings` (`booking_id`, `user_id`, `class_id`, `status`) VALUES
(1, 4, 1, 'confirmed'),
(2, 6, 2, 'confirmed'),
(3, 6, 3, 'confirmed'),
(4, 4, 1, 'confirmed'),
(5, 10, 4, 'confirmed'),
(6, 10, 5, 'confirmed'),
(7, 4, 1, 'confirmed'),
(8, 4, 5, 'confirmed'),
(9, 4, 3, 'confirmed');

INSERT INTO `reviews` (`review_id`, `class_id`, `user_id`, `rating`, `comment`) VALUES
(1, 1, 1, 5, 'Great class!'),
(2, 2, 3, 4, 'Very challenging.');