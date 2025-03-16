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

// Fetch available quizzes
$quizQuery = "SELECT id, title FROM quizzes";
$quizResult = $conn->query($quizQuery);

$quizzes = [];
while ($row = $quizResult->fetch_assoc()) {
    $quizzes[] = $row;
}

// Fetch student quiz reports
$studentQuery = "SELECT name, department, section, score FROM students WHERE score IS NOT NULL ORDER BY score DESC";
$studentResult = mysqli_query($conn, $studentQuery);

// Fetch faculty reports and their quizzes
$facultyQuery = "SELECT id, name, department FROM faculty";
$facultyResult = mysqli_query($conn, $facultyQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Reports</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #0f172a; color: white; text-align: center; padding: 40px; }
        .container {
            width: 80%;
            max-width: 900px;
            margin: auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        }
        h2 { color: #ffcc00; margin-bottom: 20px; }
        .report-box {
            background: rgba(255, 255, 255, 0.2);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            text-align: left;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: #ffcc00;
            color: black;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn:hover { background: white; transform: scale(1.05); }
    </style>
</head>
<body>
    <div class="container">
        <h2>📊 Quiz Reports</h2>

        <h3>📝 Available Quizzes</h3>
        <ul>
            <?php
            if (!empty($quizzes)) {
                foreach ($quizzes as $quiz) {
                    echo "<li><strong>{$quiz['title']}</strong></li>";
                }
            } else {
                echo "<p>No quizzes available.</p>";
            }
            ?>
        </ul>

        <h3>🧑‍🎓 Student Reports</h3>
        <div id="studentReports">
            <?php
            if (mysqli_num_rows($studentResult) > 0) {
                while ($row = mysqli_fetch_assoc($studentResult)) {
                    echo "<div class='report-box'>
                        <p><strong>Name:</strong> {$row['name']}</p>
                        <p><strong>Department:</strong> {$row['department']}</p>
                        <p><strong>Section:</strong> {$row['section']}</p>
                        <p><strong>Score:</strong> {$row['score']}</p>
                    </div>";
                }
            } else {
                echo "<p>No student reports available.</p>";
            }
            ?>
        </div>

        <h3>👨‍🏫 Faculty Reports</h3>
        <div id="facultyReports">
            <?php
            if (mysqli_num_rows($facultyResult) > 0) {
                while ($faculty = mysqli_fetch_assoc($facultyResult)) {
                    echo "<div class='report-box'>
                        <p><strong>Name:</strong> {$faculty['name']}</p>
                        <p><strong>Department:</strong> {$faculty['department']}</p>";
                    
                    // Fetch quizzes created by this faculty
                    $facultyId = $faculty['id'];
                    $quizQuery = "SELECT title FROM quizzes WHERE faculty_id = $facultyId";
                    $quizResult = mysqli_query($conn, $quizQuery);
                    
                    if (mysqli_num_rows($quizResult) > 0) {
                        echo "<p><strong>Quizzes Created:</strong></p><ul>";
                        while ($quiz = mysqli_fetch_assoc($quizResult)) {
                            echo "<li>{$quiz['title']}</li>";
                        }
                        echo "</ul>";
                    }
                    
                    echo "</div>";
                }
            } else {
                echo "<p>No faculty reports available.</p>";
            }
            ?>
        </div>

        <button class="btn" onclick="window.location.href='admin_dashboard.html'">Back to Quizzes</button>
    </div>
</body>
</html>
