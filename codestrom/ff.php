<?php
session_start(); // Start session at the very beginning

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "quiz_management"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    
    if (!empty($email)) {
        $sql = "SELECT * FROM faculty WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $_SESSION['reset_email'] = $email; // Store email in session before redirect
            header("Location: fff.html"); 
            exit();
        } else {
            echo "<script>alert('Email does not exist.'); window.location.href='faculty-login.html';</script>";
        }
    } else {
        echo "<script>alert('Please enter your email.'); window.location.href='fff.html';</script>";
    }
}
?>
