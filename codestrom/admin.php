<?php
session_start();
$servername = "localhost"; // Change if needed
$username = "root"; // Default for XAMPP
$password = ""; // Default for XAMPP
$dbname = "quiz_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT id, password FROM admin_users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION["admin_id"] = $id;
            $_SESSION["admin_email"] = $email;
            header("Location: admin_dashboard.html"); // Redirect to dashboard
            exit();
        } else {
            echo "<script>alert('Invalid email or password!'); window.location.href='adminlog.html';</script>";
        }
    } else {
        echo "<script>alert('Invalid email or password!'); window.location.href='adminlog.html';</script>";
    }
    $stmt->close();
}
$conn->close();
?>