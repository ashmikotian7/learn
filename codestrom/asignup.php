<?php
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_BCRYPT); // Encrypt password
    $fav_color = trim($_POST["fav_color"]);

    // Validate email format and domain restriction
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/@sode-edu\.in$/", $email)) {
    echo "<script>alert('Only @sode-edu.in email addresses are allowed!'); window.location.href='adminlog.html';</script>";
    exit;
}


    // Check if email already exists
    $checkQuery = "SELECT id FROM admin_users WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already exists! Try another one.'); window.location.href='admin_signup.html';</script>";
    } else {
        // Insert into database
        $sql = "INSERT INTO admin_users (name, email, password, fav_color) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $password, $fav_color);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful!'); window.location.href='adminlog.html';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    $stmt->close();
}

$conn->close();
?>
