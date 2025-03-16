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
$quizQuery = "SELECT id, faculty_name, title, deadline_date, deadline_time FROM quizzes";
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
    <title>Manage Quizzes</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { 
            margin: 0; padding: 0; box-sizing: border-box; 
            font-family: 'Poppins', sans-serif; 
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            text-align: center;
            padding: 20px;
            color: white;
        }
        .container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-radius: 12px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .container:hover {
            transform: scale(1.02);
        }
        h2 {
            font-size: 24px;
            font-weight: 600;
            color: #ffcc00;
            text-shadow: 2px 2px 10px rgba(255, 204, 0, 0.6);
            position: sticky;
            top: 0;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #ffcc00, #ff9900);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }
        .btn:hover {
            background: linear-gradient(135deg, #ff9900, #ff6600);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
        }
        .quiz-list {
            margin-top: 10px;
            flex-grow: 1;
        }
        .quiz-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.2);
            padding: 12px;
            border-radius: 8px;
            margin: 8px 0;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            font-size: 16px;
        }
        .delete-btn {
            background: #dc3545;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .delete-btn:hover {
            background: #c82333;
            transform: scale(1.1);
        }
        @media (max-width: 500px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Quizzes</h2>
        <div id="quizList" class="quiz-list">
            <?php if (empty($quizzes)) { ?>
                <p>No quizzes available.</p>
            <?php } else { 
                foreach ($quizzes as $quiz) { 
                    $formattedDeadline = date("D, F j, Y - g:i A", strtotime($quiz['deadline_date'] . ' ' . $quiz['deadline_time']));
            ?>
                <div class="quiz-item">
                    <span>
                        <?= htmlspecialchars($quiz['title']) ?> - 
                        <strong><?= htmlspecialchars($quiz['faculty_name']) ?></strong> -
                        Deadline: <?= $formattedDeadline ?>
                    </span> 
                    <button class="delete-btn" onclick="deleteQuiz(<?= $quiz['id'] ?>)">Delete</button>
                </div>
            <?php } } ?>
        </div>
        <button class="btn" onclick="goToDashboard()">Go Back to Admin Dashboard</button>
    </div>

    <script>
        function deleteQuiz(quizId) {
            if (confirm("Are you sure you want to delete this quiz?")) {
                fetch('delete_quiz.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'quiz_id=' + quizId
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    location.reload();
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function goToDashboard() {
            window.location.href = "admin_dashboard.html"; 
        }
    </script>
</body>
</html>
