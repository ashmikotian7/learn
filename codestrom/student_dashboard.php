<?php
session_start();

// Database Connection
$host = "localhost";
$user = "root";
$password = "";
$database = "quiz_management";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student-login.html");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student details including score
$query = "SELECT name, department, section, score FROM students WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

$department = htmlspecialchars($student['department']);
$section = $student['section'];
$name = htmlspecialchars($student['name']);
$score = $student['score']; // Get the student's current score

// Fetch quizzes only if score is 0
$quizzes = [];
if ($score == 0) {
    $query = "SELECT title, faculty_name, duration, deadline_date, deadline_time FROM quizzes WHERE department = ? AND section = ? ORDER BY deadline_date ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $department, $section);
    $stmt->execute();
    $result = $stmt->get_result();
    $quizzes = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #0f172a; color: white; padding: 20px; text-align: center; }

        .navbar { display: flex; justify-content: space-between; padding: 15px; background: rgba(255, 255, 255, 0.1); border-radius: 10px; margin-bottom: 20px; }
        .logo { font-size: 22px; font-weight: bold; color: #ffcc00; }
        .nav-links a { color: white; text-decoration: none; margin-left: 20px; font-size: 16px; }
        .nav-links a:hover { color: #ffcc00; }

        .profile-card { background: rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 12px; width: 100%; max-width: 400px; margin: 20px auto; }
        .profile-card h2 { color: #ffcc00; }
        .quiz-container { background: rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 12px; max-width: 600px; margin: 20px auto; }
        .quiz-item { background: rgba(255, 255, 255, 0.2); padding: 15px; border-radius: 5px; cursor: pointer; margin-bottom: 10px; transition: 0.3s; }
        .quiz-item:hover { background: rgba(255, 255, 255, 0.3); transform: scale(1.05); }
        .quiz-title { font-size: 18px; font-weight: bold; color: white; }
        .quiz-deadline { font-size: 14px; color: #ffcc00; }
        .no-access { background: rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 12px; max-width: 600px; margin: 20px auto; color: red; font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1 class="logo">Quiz Management</h1>
        <div class="nav-links">
            <a href="home.html">Home</a>
            <a href="logout.html" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="profile-card">
        <h2><?php echo htmlspecialchars($name); ?></h2>
        <p>Welcome back! Ready to take your quiz?</p>
    </div>

    <h3>Available Quizzes</h3>

    <?php if ($score > 0) { ?>
        <div class="no-access">
            <p>You have already taken the quiz. You cannot attempt it again.</p>
        </div>
    <?php } else { ?>
        <div class="quiz-container">
            <?php if (empty($quizzes)) { ?>
                <p>No quizzes available for your department and section.</p>
            <?php } else {
                foreach ($quizzes as $quiz) { ?>
                    <a href="a_quiz.php?title=<?php echo urlencode($quiz['title']); ?>" style="text-decoration: none;">
                        <div class="quiz-item">
                            <span class="quiz-title"> <?php echo htmlspecialchars($quiz['title']); ?></span><br>
                            <span class="quiz-deadline">Faculty: <?php echo htmlspecialchars($quiz['faculty_name']); ?></span><br>
                            <span class="quiz-deadline">Duration: <?php echo htmlspecialchars($quiz['duration']); ?> mins</span><br>
                            <span class="quiz-deadline">Deadline: <?php echo htmlspecialchars($quiz['deadline_date']) . ' ' . htmlspecialchars($quiz['deadline_time']); ?></span>
                        </div>
                    </a>
            <?php } } ?>
        </div>
    <?php } ?>

</body>
</html>
