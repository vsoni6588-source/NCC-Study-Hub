# NCC Study Hub

A modern PHP-based learning and training platform designed for National Cadet Corps (NCC) cadets. The system provides structured academic content, exam preparation, wing-based learning modules, progress tracking, and an administrative dashboard for managing subjects, tests, exams, news, camps, and users.

## Overview

Live demo: [NCC Study Hub](https://nccstudyhub.freedev.app/)

NCC Study Hub is built to help cadets access training resources, track their learning progress, and prepare for NCC-related evaluation through a user-friendly web portal. It supports:

- Cadet registration and login
- Wing-specific learning content
- Training, drill, leadership, and exam resources
- User progress tracking
- Admin management panel for content and users

## Tech Stack

- PHP 8+
- MySQL
- Apache via XAMPP
- HTML, CSS, JavaScript
- Session-based authentication

## Project Structure

```text
ncc_study_hub/
├── Admin/                  # Admin dashboard and management pages
├── assets/                 # CSS and frontend assets
├── includes/               # Shared config and navigation
├── images/                 # Static images
├── uploads/                # Uploaded content storage
├── index.php               # Public landing page
├── login.php               # User login page
├── register.php            # User registration page
├── home.php                # User dashboard
├── my_training.php         # Training modules page
├── my_progress.php         # Progress tracking page
├── result.php              # Test result page
├── submit_test.php         # Test submission handler
├── download.php            # Download resources page
└── README.md               # Project documentation
```

## Features

### User Features
- Secure cadet registration with wing and rank selection
- Login-based access with session management
- Access to wing-specific study material and practice tests
- Progress monitoring across subjects and training modules
- Downloadable content and learning resources

### Admin Features
- Subject management for Army, Navy, Air Force, Leadership, Training, and Drill/GOH modules
- Exam and test management
- Camp management
- News management
- User management
- Logout and secure admin access control

## Database Setup

This project uses a MySQL database named:

```text
ncc_study_hub
```

The application connects to this database using the configuration in:

- [includes/config.php](includes/config.php)
- [Admin/config.php](Admin/config.php)

Default connection settings used in the project:

```php
$conn = mysqli_connect("localhost", "root", "", "ncc_study_hub");
```

### 1. Create the Database

1. Start Apache and MySQL in XAMPP.
2. Open phpMyAdmin.
3. Create a new database named `ncc_study_hub`.
4. Select the newly created database.

### 2. Create Required Tables

After creating the database, create the necessary tables used by the application.

A typical setup includes tables such as:

- `users`
- `subjects`
- `resources`
- `questions`
- `options`
- `news`
- `camp_material`
- `user_answers`

Example table creation format:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    wing VARCHAR(100),
    rank VARCHAR(100)
);
```

You can add the remaining tables based on the application modules you want to use.

### 3. Import or Seed Data

If your project includes a schema file or seed data, import it into the `ncc_study_hub` database after the tables are created.

### 4. Verify Connection

Once the database is ready, open the application in your browser:

```text
http://localhost/ncc_study_hub/
```

If the database connection is successful, the login and admin screens will load correctly.

## Installation

### Prerequisites

- XAMPP or any PHP + MySQL local server stack
- PHP 8.x
- MySQL database server

### Steps

1. Clone or download the project into your local web root:

```text
C:\xampp\htdocs\ncc_study_hub
```

2. Start Apache and MySQL from XAMPP.
3. Create the `ncc_study_hub` database in phpMyAdmin.
4. Import the required SQL schema.
5. Open the project in your browser:

```text
http://localhost/ncc_study_hub/
```

## Admin Access

The admin login page is available here:

```text
http://localhost/ncc_study_hub/Admin/Alogin.php
```

Default credentials used in the source code:

- Email: `admin@nccstudyhub.com`
- Password: `Admin@123`

> Please change these credentials after deployment for security purposes.

## Usage

### For Cadets
1. Register a new account.
2. Log in using the registered email and password.
3. Explore wing-specific content, tests, and training pages.
4. Track progress from the dashboard.

### For Administrators
1. Log in to the admin portal.
2. Manage subjects, tests, exams, news, and camps.
3. Review user information and update training content.

## Important Notes

- This project uses plain PHP and MySQL without a framework.
- Session-based authentication is handled using PHP `$_SESSION`.
- For production deployment, secure the admin credentials and sanitize input thoroughly.
- Consider using prepared statements and stronger password handling for production-ready security.

## Security Recommendations

Before using this project in a live environment, it is strongly recommended to:

- Replace the default admin password
- Use environment-based database configuration
- Add prepared statements for all SQL operations
- Validate and sanitize all user inputs
- Enable HTTPS and secure hosting practices

## License

This project is intended for educational and institutional use. Please check the repository or project owner for specific licensing terms.

## Contributor Notes

This repository is suitable for academic, training, and internal institutional deployment. It can also be extended with:

- online quiz grading
- certificate generation
- notification system
- enhanced reporting dashboards
- role-based access control
