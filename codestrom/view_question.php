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

// Check if 'deadline' column exists
$checkColumnQuery = "SHOW COLUMNS FROM quizzes LIKE 'deadline'";
$columnResult = $conn->query($checkColumnQuery);
$hasDeadlineColumn = $columnResult->num_rows > 0;

// Fetch quizzes with faculty names
$quizQuery = "SELECT quizzes.id, quizzes.title, faculty.name AS faculty_name";
if ($hasDeadlineColumn) {
    $quizQuery .= ", quizzes.deadline";
}
$quizQuery .= " FROM quizzes JOIN faculty ON quizzes.faculty_id = faculty.id";

$quizResult = $conn->query($quizQuery);

$quizzes = [];
while ($row = $quizResult->fetch_assoc()) {
    $quizzes[] = $row;
}

// Fetch questions
$questionsQuery = "SELECT * FROM questions";
$questionsResult = $conn->query($questionsQuery);
$questions = [];
while ($row = $questionsResult->fetch_assoc()) {
    $questions[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Questions</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { display: flex; height: 100vh; background: #121826; color: #fff; }
        .sidebar { width: 250px; background: #1a2332; padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .sidebar h2 { font-size: 20px; color: #00A9FF; margin-bottom: 30px; }
        .sidebar button { width: 100%; padding: 12px; border-radius: 8px; border: none; background: rgba(255, 255, 255, 0.2); color: white; font-weight: bold; margin: 5px 0; cursor: pointer; transition: 0.3s; }
        .sidebar button:hover { background: #00A9FF; transform: scale(1.05); }
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        h2 { font-size: 24px; margin-bottom: 20px; color: #00A9FF; }
        .quiz-list { list-style: none; }
        .quiz-item { background: rgba(255, 255, 255, 0.1); padding: 15px; border-radius: 8px; margin-bottom: 10px; cursor: pointer; transition: 0.3s; }
        .quiz-item:hover { background: rgba(255, 255, 255, 0.2); }
        .quiz-title { font-size: 18px; font-weight: bold; color: #FFD700; }
        .faculty-name { font-size: 14px; color: #A9A9A9; }
        .question-list { list-style: none; padding: 10px; margin-top: 10px; display: none; }
        .question-item { background: rgba(255, 255, 255, 0.1); padding: 12px; border-radius: 8px; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Quiz System</h2>
        <button onclick="window.location.href='faculty_dashboard.php'">← Faculty Dashboard</button>
        <button onclick="window.location.href='add_quesstions.php'">➕ Add Question</button>
    </div>
    
    <div class="main-content">
        <h2>View Questions</h2>
        <ul class="quiz-list">
            <?php foreach ($quizzes as $quiz) { ?>
                <li class="quiz-item" onclick="toggleQuestions(<?= $quiz['id'] ?>)">
                    <div class="quiz-title"> 
                        <?= htmlspecialchars($quiz['title']) ?>
                        <?php if ($hasDeadlineColumn && !empty($quiz['deadline'])): ?>
                            (Deadline: <?= htmlspecialchars($quiz['deadline']) ?>)
                        <?php endif; ?>
                    </div>
                    <div class="faculty-name"> Created by: <?= htmlspecialchars($quiz['faculty_name']) ?></div>
                    <ul class="question-list" id="questions-<?= $quiz['id'] ?>">
                        <?php foreach ($questions as $question) {
                            if ($question['quiz_id'] == $quiz['id']) { ?>
                                <li class="question-item">
                                    <?= htmlspecialchars($question['question_text']) ?> 
                                    <?php if (isset($question['question_type'])): ?>
                                        (<?= htmlspecialchars($question['question_type']) ?>)
                                    <?php else: ?>
                                        
                                    <?php endif; ?>
                                </li>
                        <?php }} ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </div>
    
    <script>
        function toggleQuestions(quizId) {
            let questionsContainer = document.getElementById(`questions-${quizId}`);
            if (questionsContainer.style.display === "none" || questionsContainer.style.display === "") {
                questionsContainer.style.display = "block";
            } else {
                questionsContainer.style.display = "none";
            }
        }
    </script>
</body>
</html>
