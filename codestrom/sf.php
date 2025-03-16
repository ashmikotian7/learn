<?php
session_start(); // Start session

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "quiz_management"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    
    if (!empty($email)) {
        // Debugging: Print received email
        error_log("Received email: " . $email);

        // Prepare SQL query to check email existence in students table
        $sql = "SELECT * FROM students WHERE LOWER(email) = LOWER(?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Debugging: Check number of rows found
        error_log("Rows found: " . $result->num_rows);

        if ($result->num_rows > 0) {
            $_SESSION['reset_email'] = $email; // Store email in session before redirect
            header("Location: sff.html"); 
            exit();
        } else {
            echo "<script>alert('Email does not exist.'); window.location.href='student-login.html';</script>";
        }
    } else {
        echo "<script>alert('Please enter your email.'); window.location.href='sf.html';</script>";
    }
}

$conn->close();
?>
