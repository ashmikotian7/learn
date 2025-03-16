<?php
session_start();

// Database Connection
$host = "localhost";
$user = "root";
$password = "";
$database = "quiz_management";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    die("Unauthorized access!");
}

// Get student ID and score
$student_id = $_SESSION['student_id'];
$score = isset($_POST['score']) ? intval($_POST['score']) : 0;

// Update the student's score in the database
$query = "UPDATE students SET score = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $score, $student_id);

if ($stmt->execute()) {
    echo "Score updated successfully!";
} else {
    echo "Error updating score: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
