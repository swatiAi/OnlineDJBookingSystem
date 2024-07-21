# Online DJ Booking System

## Overview

The Online DJ Booking System is a web-based application designed to facilitate the booking of DJs for events. This system allows users to browse through available DJs, check their availability, and book them for their events. The system also provides DJs with the capability to manage their bookings and schedules.

## Features

- **User Registration and Login**: Users can create an account and log in to access the booking system.
- **DJ Profiles**: Users can view profiles of DJs, including their experience, music genres, and availability.
- **Booking Management**: Users can book DJs for specific dates and events.
- **Schedule Management**: DJs can manage their availability and view their bookings.
- **Admin Panel**: Admins can manage users, DJs, and bookings.

## Technologies Used

- **Frontend**: HTML, CSS
- **Backend**: PHP
- **Database**: MySQL

## Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (e.g., Apache, Nginx)

### Steps

1. **Clone the repository**:
    ```sh
    git clone https://github.com/swatiAi/OnlineDjBookingSystem.git
    cd OnlineDjBookingSystem
    ```

2. **Set up the database**:
    - Create a new MySQL database.
    - Use the following schema to create the necessary tables:

    ```sql
    CREATE DATABASE dj_booking_system;

    USE dj_booking_system;

    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        role ENUM('user', 'dj', 'admin') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE djs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        name VARCHAR(100) NOT NULL,
        bio TEXT,
        genres VARCHAR(255),
        experience INT,
        availability TEXT,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );

    CREATE TABLE bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        dj_id INT NOT NULL,
        event_date DATE NOT NULL,
        event_details TEXT,
        status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (dj_id) REFERENCES djs(id)
    );
    ```

3. **Configure the database connection**:
    - Open the `config.php` file in the project root.
    - Update the database connection details:
      ```php
      <?php
      $servername = "your_servername";
      $username = "your_username";
      $password = "your_password";
      $dbname = "dj_booking_system";
      ?>
      ```

4. **Start the web server**:
    - If using Apache, place the project directory in the `htdocs` folder.
    - Start your web server and navigate to `http://localhost/OnlineDjBookingSystem` in your web browser.

## Usage

- **Register** as a new user or **log in** with your existing credentials.
- Browse through the list of available DJs and view their profiles.
- Select a DJ and book them for your event by specifying the date and event details.
- DJs can log in to manage their availability and view upcoming bookings.
- Admins can log in to the admin panel to manage users, DJs, and bookings.

## Project Structure

- `index.php`: The main landing page.
- `register.php`: User registration page.
- `login.php`: User login page.
- `dashboard.php`: User dashboard after logging in.
- `profile.php`: DJ profile page.
- `book.php`: Booking page.
- `admin/`: Admin panel files.
- `config.php`: Database configuration file.
- `db/`: Database files.

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your changes.


