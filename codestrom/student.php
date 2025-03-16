<?php
session_start();
$host = "localhost"; // Change if using a remote database
$user = "root";      // Change if using a different database user
$password = "";      // Change if database has a password
$dbname = "quiz_management"; // Change to your database name

// Create a connection to MySQL
$conn = new mysqli($host, $user, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim($_POST['password']);
    
    // Prepare SQL statement to fetch the user
    $stmt = $conn->prepare("SELECT id, password FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();                                                                                                                                                                                                                                                                                                                                                                    
    // Check if email exists in the database
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        
        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['student_id'] = $id; // Store session for logged-in user
            header("Location: student_dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            echo "<script>alert('Invalid email or password. Please try again.'); window.location.href='student-login.html';</script>";
        }
    } else {
        echo "<script>alert('Invalid email or password. Please try again.'); window.location.href='student-login.html';</script>";
    }
    
    // Close statement & connection
    $stmt->close();
}

$conn->close();
?>
