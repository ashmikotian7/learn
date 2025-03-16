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

// Handle question submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveQuestion'])) {
    $quizID = $_POST['quiz_id'];
    $questionText = $_POST['question_text'];
    $options = $_POST['options'] ?? [];
    $correctOptions = $_POST['correct_option'] ?? [];

    // Fetch quiz title based on quiz ID
    $quizQuery = "SELECT title FROM quizzes WHERE id = ?";
    $stmt = $conn->prepare($quizQuery);
    $stmt->bind_param("i", $quizID);
    $stmt->execute();
    $stmt->bind_result($quizTitle);
    $stmt->fetch();
    $stmt->close();

    // Ensure exactly 4 options (fill with NULL if less than 4)
    $optionValues = array_pad($options, 4, null);

    // Get only the correct options text
    $correctAnswers = [];
    foreach ($correctOptions as $correctOption) {
        $correctAnswers[] = $optionValues[$correctOption - 1]; // subtract 1 to match index
    }
    $correctAnswersString = implode(",", $correctAnswers);

    // Insert into questions table with the quiz title
    $stmt = $conn->prepare("INSERT INTO questions (quiz_id, title, question_text, option1, option2, option3, option4, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $quizID, $quizTitle, $questionText, $optionValues[0], $optionValues[1], $optionValues[2], $optionValues[3], $correctAnswersString);

    if ($stmt->execute()) {
        echo "<script>alert('Question Saved!'); window.location.href='add_quesstions.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; background: #121826; color: white; height: 100vh; }
        .sidebar { width: 200px; background: #1f2937; padding: 20px; }
        .sidebar button { width: 100%; padding: 10px; margin-bottom: 10px; background: #ffcc00; border: none; cursor: pointer; font-size: 16px; }
        .sidebar button:hover { background: white; }
        .container { flex: 1; padding: 20px; }
        h2 { color: #facc15; }
        .input-box { margin-bottom: 15px; }
        label { font-weight: bold; }
        input, select, textarea { width: 100%; padding: 10px; border: none; border-radius: 8px; background: rgba(255, 255, 255, 0.2); color: white; }
        .mcq-options { margin-top: 10px; }
        .btn { padding: 12px; background: linear-gradient(90deg, #ff6600, #ffcc00); border: none; border-radius: 12px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .btn:hover { background: linear-gradient(90deg, #ff9900, #ffee00); }
        .button-container { display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <div class="sidebar">
        <button onclick="location.href='faculty_dashboard.php'">Dashboard</button>
        <button onclick="location.href='view_questions.php'">View Questions</button>
    </div>

    <div class="container">
        <h2>Add a Question</h2>
        <form method="POST">
            <div class="input-box">
                <label>Select Quiz</label>
                <select name="quiz_id" required>
                    <?php foreach ($quizzes as $quiz) { ?>
                        <option value="<?= $quiz['id'] ?>"><?= htmlspecialchars($quiz['title']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="input-box">
                <label>Question</label>
                <textarea name="question_text" placeholder="Enter Question" ></textarea>
            </div>

            <div class="mcq-options" id="mcqOptions">
                <label>Options</label>
                <div>
                    <input type="text" name="options[]" placeholder="Option 1" >
                    <input type="checkbox" name="correct_option[]" value="1"> <label>Options</label>
                </div>
                <div>
                    <input type="text" name="options[]" placeholder="Option 2" >
                    <input type="checkbox" name="correct_option[]" value="2"> <label>Options</label>
                </div>
                <button type="button" class="add-option-btn" onclick="addOption()">+ Add Option</button>
            </div>

            <div class="button-container">
                <button type="submit" name="saveQuestion" class="btn">Save Question</button>
                <button type="submit" name="submitQuiz" class="btn" formaction="view_question.php">Submit Quiz</button>
            </div>
        </form>
    </div>

    <script>
        function addOption() {
            let optionsDiv = document.getElementById("mcqOptions");
            let optionCount = optionsDiv.querySelectorAll("input[type='text']").length + 1;
            
            if (optionCount > 4) {
                alert("You can add only 4 options!");
                return;
            }

            let div = document.createElement("div");
            div.innerHTML = `<input type="text" name="options[]" placeholder="Option ${optionCount}" required> <input type="checkbox" name="correct_option[]" value="${optionCount}"> <label>Options</label>`;
            
            optionsDiv.insertBefore(div, optionsDiv.lastElementChild);
        }
    </script>
</body>
</html>
