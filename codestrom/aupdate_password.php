<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "quiz_management"; // Change to your DB name

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["reset_email"]) && !empty($_POST["password"])) {
        $email = $_SESSION["reset_email"];
        $new_password = $_POST["password"];
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Prepare SQL query to update password
        $sql = "UPDATE admin_users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error in preparing statement: " . $conn->error);
        }
        $stmt->bind_param("ss", $hashed_password, $email);

        // Execute and check if successful
        if ($stmt->execute()) {
            unset($_SESSION["reset_email"]); // Remove email from session
            echo "<script>alert('Password updated successfully!'); window.location.href='adminlog.html';</script>";
        } else {
            echo "<script>alert('Error updating password. Try again!'); window.location.href='areset.html';</script>";
        }

        // Close statement and connection
        $stmt->close();
    } else {
        echo "<script>alert('Invalid request. Try again!'); window.location.href='areset.html';</script>";
    }
}

$conn->close();
?>
