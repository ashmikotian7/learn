<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure faculty is logged in
if (!isset($_SESSION['faculty_id']) || !isset($_SESSION['faculty_name'])) {
    header("Location: faculty-login.php");
    exit();
}

$facultyID = $_SESSION['faculty_id'];
$facultyName = $_SESSION['faculty_name'];
$facultyEmail = $_SESSION['faculty_email'] ?? '';
$facultyDepartment = $_SESSION['faculty_department'] ?? '';

// Handle quiz creation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['quizTitle'] ?? '';
    $deadline_date = $_POST['quizDeadline'] ?? '';
    $deadline_time = $_POST['quizTime'] ?? '';
    $duration = $_POST['quizDuration'] ?? '';
    $department = $_POST['quizDepartment'] ?? '';
    $section = $_POST['quizSection'] ?? '';

    if (empty($title) || empty($deadline_date) || empty($deadline_time) || empty($duration) || empty($department) || empty($section)) {
        die("Error: Fill in all details.");
    }

    $sql = "INSERT INTO quizzes (faculty_id, faculty_name, title, deadline_date, deadline_time, duration, department, section) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssisss", $facultyID, $facultyName, $title, $deadline_date, $deadline_time, $duration, $department, $section);

    if ($stmt->execute()) {
        $quizID = $stmt->insert_id;
        $_SESSION['quiz_id'] = $quizID; 
        echo "<script>alert('Quiz created successfully!'); window.location.href = 'add_quesstions.php';</script>";
        exit();
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
    <title>Faculty Dashboard</title>
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
        input, select { width: 100%; padding: 10px; border: none; border-radius: 8px; background: rgba(255, 255, 255, 0.2); color: white; }
        .btn { width: 100%; padding: 12px; background: linear-gradient(90deg, #ff6600, #ffcc00); border: none; border-radius: 12px; font-size: 16px; font-weight: bold; cursor: pointer; }
        .btn:hover { background: linear-gradient(90deg, #ff9900, #ffee00); }
    </style>
</head>
<body>
    <div class="sidebar">
        <button onclick="location.href='faculty_dashboard.php'">Dashboard</button>
        <button onclick="location.href='faculty_quiz_list.php'">Quiz List</button>
    </div>

    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($facultyName); ?></h2>
        <p>Email: <?php echo htmlspecialchars($facultyEmail); ?></p>
        <p>Department: <?php echo htmlspecialchars($facultyDepartment); ?></p>
        <h2>Create a New Quiz</h2>
        <form method="POST">
            <div class="input-box">
                <label>Quiz Title</label>
                <input type="text" name="quizTitle" placeholder="Enter quiz title" required>
            </div>
            <div class="input-box">
                <label>Deadline Date</label>
                <input type="date" name="quizDeadline" required>
            </div>
            <div class="input-box">
                <label>Deadline Time</label>
                <input type="time" name="quizTime" required>
            </div>
            <div class="input-box">
                <label>Quiz Duration (in minutes)</label>
                <input type="number" name="quizDuration" placeholder="Enter duration in minutes" min="1" required>
            </div>
            <div class="input-box">
                <label>Department</label>
                <select name="quizDepartment" required>
                    <option value="CSE">CSE</option>
                    <option value="ECE">ECE</option>
                    <option value="MECH">MECH</option>
                </select>
            </div>
            <div class="input-box">
                <label>Section</label>
                <select name="quizSection" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                </select>
            </div>
            <button class="btn" onclick="saveQuiz()" type="submit">Create Quiz</button>
        </form>
    </div>
    <script>
        function saveQuiz() {
            let title = document.getElementById("quizTitle").value.trim();
            let deadline = document.getElementById("quizDeadline").value;
            let time = document.getElementById("quizTime").value;
            let duration = document.getElementById("quizDuration").value;
            let department = document.getElementById("quizDepartment").value;
            let section = document.getElementById("quizSection").value;

            if (!title || !deadline || !time || !duration || !department || !section) {
                alert("Enter all quiz details.");
                return;
            }

            let quizzes = JSON.parse(localStorage.getItem("quizzes")) || [];
            quizzes.push({ title, deadline, time, duration, department, section });
            localStorage.setItem("quizzes", JSON.stringify(quizzes));

            alert("Quiz Created Successfully!");
            location.href = "add_quesstions.php"; // Redirect to add questions
        }
    </script>
</body>
</html>
