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

// Fetch quizzes from database
$quizQuery = "SELECT id, title, deadline_date, deadline_time, duration, department, section FROM quizzes";
$quizResult = $conn->query($quizQuery);

$quizzes = [];
while ($row = $quizResult->fetch_assoc()) {
    $quizzes[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Quiz List</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; padding: 20px; text-align: center; }
        
        .navbar { 
            width: 100%; 
            background: rgba(255, 255, 255, 0.1); 
            padding: 15px 30px; 
            position: fixed; 
            top: 0; 
            left: 0;
            color: #ffcc00;
            font-size: 20px;
        }

        .container { 
            width: 80%; 
            margin: 100px auto; 
            background: rgba(255, 255, 255, 0.1); 
            padding: 30px; 
            border-radius: 15px; 
            backdrop-filter: blur(10px); 
        }

        h2 { color: #ffcc00; }
        
        .quiz-list { margin-top: 20px; }
        .quiz-item { 
            padding: 15px; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.2); 
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .btn {
            padding: 10px;
            background: #ffcc00;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-btn {
            margin-top: 20px;
            background: #ff5733;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Faculty Quiz List</h2>
    </div>

    <div class="container">
        <h2>Available Quizzes</h2>
        <div class="quiz-list">
            <?php if (empty($quizzes)) { ?>
                <p>No quizzes available.</p>
            <?php } else { 
                foreach ($quizzes as $quiz) { 
                    $formattedDeadline = date("D, F j, Y - g:i A", strtotime($quiz['deadline_date'] . ' ' . $quiz['deadline_time']));
            ?>
                <div class="quiz-item">
                    <h3><?= htmlspecialchars($quiz['title']) ?></h3>
                    <p><strong>Deadline:</strong> <?= $formattedDeadline ?></p>
                    <p><strong>Department:</strong> <?= htmlspecialchars($quiz['department']) ?></p>
                    <p><strong>Section:</strong> <?= htmlspecialchars($quiz['section']) ?></p>
                    <p><strong>Time Limit:</strong> <?= htmlspecialchars($quiz['duration']) ?> mins</p>
                    <button class="btn" onclick="viewQuestions(<?= $quiz['id'] ?>)">View Questions</button>
                </div>
            <?php } } ?>
        </div>
        <button class="back-btn" onclick="goBack()">Back to Dashboard</button>
    </div>

    <script>
        function viewQuestions(quizId) {
            window.location.href = "view_question.php?quiz_id=" + quizId;
        }

        function goBack() {
            window.location.href = "faculty_dashboard.php";
        }
    </script>
</body>
</html>
