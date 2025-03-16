<?php
session_start();

// Redirect if student is not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student-login.html");
    exit();
}

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

// Fetch student details (name & email)
$student_id = $_SESSION['student_id'];
$query = "SELECT name, email FROM students WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$student_name = htmlspecialchars($student['name']);
$student_email = htmlspecialchars($student['email']);

// Validate quiz title
if (!isset($_GET['title']) || empty($_GET['title'])) {
    die("Invalid Quiz");
}
$quiz_title = htmlspecialchars($_GET['title']);
$_SESSION['quiz_title'] = $quiz_title; // Store the quiz title in session

// Fetch quiz duration
$query = "SELECT id, duration FROM quizzes WHERE title = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $quiz_title);
$stmt->execute();
$result = $stmt->get_result();
$quiz_data = $result->fetch_assoc();
$quiz_id = $quiz_data['id'];
$quiz_duration = $quiz_data['duration']; // Duration in minutes

// Fetch questions
$query = "SELECT id, question_text, option1, option2, option3, option4, correct_answer 
          FROM questions WHERE quiz_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();
$questions = [];

while ($row = $result->fetch_assoc()) {
    $options = array_filter([$row['option1'], $row['option2'], $row['option3'], $row['option4']]);
    $row['options'] = $options;
    $questions[] = $row;
}
$stmt->close();
$conn->close();

shuffle($questions);

$total_questions = count($questions);
$_SESSION['questions'] = $questions; 
$_SESSION['score'] = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($quiz_title); ?> - Quiz</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #0f172a; color: white; padding: 20px; text-align: center; }
        .quiz-container { background: rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 12px; max-width: 600px; margin: 20px auto; }
        .question-card { padding: 20px; border-radius: 10px; background: rgba(255, 255, 255, 0.2); }
        .question-text { font-size: 18px; font-weight: bold; }
        .options { margin-top: 15px; }
        .option { padding: 10px; border-radius: 5px; margin: 5px 0; cursor: pointer; background: rgba(255, 255, 255, 0.2); transition: 0.3s; }
        .option:hover { background: rgba(255, 255, 255, 0.3); }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; background: #ffcc00; color: black; font-weight: bold; }
        .btn:disabled { background: grey; cursor: not-allowed; }
        #submitBtn { margin-top: 20px; background: #ff5733; }
        .timer { font-size: 18px; font-weight: bold; color: #ffcc00; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="quiz-container">
        <h2><?php echo htmlspecialchars($quiz_title); ?></h2>
        <p>Student: <b><?php echo $student_name; ?></b> (<?php echo $student_email; ?>)</p>
        <p class="timer">Time Left: <span id="timer"></span></p>
        
        <form id="quizForm">
            <?php foreach ($questions as $index => $question) { ?>
                <div class="question-card" id="question-<?php echo $index; ?>" style="display: <?php echo ($index == 0) ? 'block' : 'none'; ?>;">
                    <span class="question-text"><?php echo ($index + 1) . ". " . htmlspecialchars($question['question_text']); ?></span>
                    <div class="options">
                        <?php foreach ($question['options'] as $option) { ?>
                            <input type="radio" name="answer_<?php echo $index; ?>" value="<?php echo htmlspecialchars($option); ?>"> <?php echo htmlspecialchars($option); ?><br>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <button type="button" class="btn" id="prevBtn" onclick="changeQuestion(-1)" disabled>Previous</button>
            <button type="button" class="btn" id="nextBtn" onclick="changeQuestion(1)">Next</button>
            <button type="button" class="btn" id="submitBtn" onclick="submitQuiz()" style="display:none;">Submit</button>
        </form>
        <h2 id="scoreDisplay" style="display:none;"></h2>
    </div>

    <script>
    let currentQuestion = 0;
    const totalQuestions = <?php echo $total_questions; ?>;
    let timeLeft = <?php echo $quiz_duration * 60; ?>; // Convert minutes to seconds

    function changeQuestion(step) {
        document.getElementById(`question-${currentQuestion}`).style.display = "none";
        currentQuestion += step;
        document.getElementById(`question-${currentQuestion}`).style.display = "block";
        document.getElementById("prevBtn").disabled = (currentQuestion === 0);
        document.getElementById("nextBtn").style.display = (currentQuestion === totalQuestions - 1) ? "none" : "inline-block";
        document.getElementById("submitBtn").style.display = (currentQuestion === totalQuestions - 1) ? "inline-block" : "none";
    }

    function submitQuiz() {
    let formData = new FormData(document.getElementById("quizForm"));
    let score = 0;
    let correctAnswers = <?php echo json_encode(array_column($questions, 'correct_answer')); ?>;
    
    for (let i = 0; i < totalQuestions; i++) {
        let selected = formData.get(`answer_${i}`);
        if (selected === correctAnswers[i]) {
            score++;
        }
    }

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "submit_score.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert(xhr.responseText);
            window.location.href = "student_dashboard.php";
        }
    };
    xhr.send("score=" + score);
}


    function startTimer() {
        let timerDisplay = document.getElementById("timer");
        let timer = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(timer);
                submitQuiz();
            }
            timerDisplay.textContent = Math.floor(timeLeft / 60) + ":" + (timeLeft % 60);
            timeLeft--;
        }, 1000);
    }

    startTimer();
</script>

</body>
</html>
