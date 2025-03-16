<?php
session_start();
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
    $password = trim($_POST['password']);

    $sql = "SELECT id, name, email, department, password FROM faculty WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) { // Ensure passwords are hashed
            $_SESSION['faculty_id'] = $row['id'];
            $_SESSION['faculty_name'] = $row['name'];
            $_SESSION['faculty_email'] = $row['email'];
            $_SESSION['faculty_department'] = $row['department'];

            header("Location: faculty_dashboard.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "Invalid email.";
    }

    $stmt->close();
}

$conn->close();
?>
