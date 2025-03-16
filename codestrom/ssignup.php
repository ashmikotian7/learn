<?php
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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $section = $_POST['section'];
    $department = $_POST['department'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security
    $fav_color = $_POST['fav_color'];

    // Validate email format and domain restriction
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/@sode-edu\.in$/", $email)) {
        echo "<script>alert('Only @sode-edu.in email addresses are allowed!'); window.location.href='studentsign.html';</script>";
        exit;
    }

    // Prepare SQL statement to insert data
    $stmt = $conn->prepare("INSERT INTO students (name, email, section, department, password, fav_color) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $section, $department, $password, $fav_color);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Signup successful! You can now log in.'); window.location.href='student-login.html';</script>";
    } else {
        echo "<script>alert('Signup failed. Please try again.'); window.location.href='studentsign.html';</script>";
    }

    // Close statement & connection
    $stmt->close();
}

$conn->close();
?>
