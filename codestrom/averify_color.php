<?php
session_start(); // Start the session

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "quiz_management";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["reset_email"])) {
        $email = $_SESSION["reset_email"];
        $fav_color = strtolower(trim($_POST["fav_color"])); // Convert to lowercase

        // Check if the favorite color matches
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE email = ? AND LOWER(fav_color) = ?");
        $stmt->bind_param("ss", $email, $fav_color);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Color verified! Proceed to reset your password.'); window.location.href='areset.html';</script>";
        } else {
            echo "<script>alert('Incorrect favorite color! Try again.'); window.location.href='aff.html';</script>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<script>alert('Session expired! Try again.'); window.location.href='af.html';</script>";
    }
} else {
    echo "<script>alert('Unauthorized access!'); window.location.href='adminlog.html';</script>";
}
?>
