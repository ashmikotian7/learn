<?php
// Database connection
$host = "localhost";  // Change if your database is hosted elsewhere
$user = "root";       // Your database username
$pass = "";           // Your database password
$dbname = "quiz_management"; // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$name = $_POST['name'];
$email = $_POST['email'];
$department = $_POST['department'];
$password = $_POST['password'];
$fav_color = $_POST['fav_color'];

// Validate email format and domain restriction
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/@sode-edu\.in$/", $email)) {
    echo "<script>alert('Only @sode-edu.in email addresses are allowed!'); window.location.href='faculty-login.html';</script>";
    exit;
}

// Check if the email already exists
$sql = "SELECT * FROM faculty WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Email already registered! Try logging in.'); window.location.href='faculty-login.html';</script>";
} else {
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert data into the database
    $sql = "INSERT INTO faculty (name, email, department, password, fav_color) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $department, $hashed_password, $fav_color);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful! Please login.'); window.location.href='faculty-login.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Close the database connection
$conn->close();
?>
