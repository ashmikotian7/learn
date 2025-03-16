<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quiz_management";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure session exists
if (!isset($_SESSION["reset_email"])) {
    echo "<script>alert('Session expired! Please restart the process.'); window.location.href='faculty-login.html';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $email = $_SESSION["reset_email"];

    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href='freset.html';</script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update password in the database
    $stmt = $conn->prepare("UPDATE faculty SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);

    if ($stmt->execute()) {
        unset($_SESSION["reset_email"]); // Remove session after successful reset
        echo "<script>alert('Password reset successful! Redirecting to login...'); window.location.href='faculty-login.html';</script>";
    } else {
        echo "<script>alert('Something went wrong. Try again!'); window.location.href='freset.html';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
