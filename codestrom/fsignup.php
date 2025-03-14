<?php
require 'db_config.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT); // Hashing password for security
    $fav_color = trim($_POST['fav_color']);

    // Check if email already exists
    $check_email = $conn->prepare("SELECT id FROM faculty WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();
    
    if ($check_email->num_rows > 0) {
        echo "<script>alert('Email already exists. Please use a different email.'); window.location.href='fsignup.html';</script>";
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO faculty (name, email, password, fav_color) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $fav_color);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful! You can now login.'); window.location.href='faculty-login.html';</script>";
        } else {
            echo "<script>alert('Error in signup. Please try again.'); window.location.href='fsignup.html';</script>";
        }
        $stmt->close();
    }
    $check_email->close();
}
$conn->close();
?>
