<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root"; // Change as per your DB user
$password = ""; // Change as per your DB password
$dbname = "quiz_management"; // Replace with your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (!empty($email)) {
        // Check if email exists
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION["reset_email"] = $email; // Store email in session

            header("Location: aff.html");
            exit();
        } else {
            echo "<script>alert('Email does not exist. Please enter a registered email.'); window.location.href='adminlog.html';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Please enter an email address.'); window.location.href='adminlog.html';</script>";
        exit();
    }
}
?>
