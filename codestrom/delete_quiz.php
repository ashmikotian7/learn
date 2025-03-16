<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_management";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if quiz_id is received via POST
if (isset($_POST['quiz_id'])) {
    $quiz_id = intval($_POST['quiz_id']);

    // Prepare delete statement
    $stmt = $conn->prepare("DELETE FROM quizzes WHERE id = ?");
    $stmt->bind_param("i", $quiz_id);

    if ($stmt->execute()) {
        echo "Quiz deleted successfully!";
    } else {
        echo "Error deleting quiz: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid request!";
}

$conn->close();
?>
