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

if (!isset($_SESSION["reset_email"])) {
    echo "<script>alert('Session expired! Please restart the process.'); window.location.href='faculty-login.html';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["fav_color"])) {
        $email = $_SESSION["reset_email"];
        $fav_color = strtolower(trim($_POST["fav_color"]));

        $stmt = $conn->prepare("SELECT id FROM faculty WHERE email = ? AND LOWER(fav_color) = ?");
        $stmt->bind_param("ss", $email, $fav_color);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Keep session active and redirect
            echo "<script>alert('Color verified! Proceed to reset your password.'); window.location.href='freset.html';</script>";
        } else {
            echo "<script>alert('Incorrect favorite color! Try again.'); window.location.href='faculty-login.html';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please enter your favorite color!'); window.location.href='fff.html';</script>";
    }
} else {
    echo "<script>alert('Unauthorized access!'); window.location.href='faculty-login.html';</script>";
}

$conn->close();
?>
