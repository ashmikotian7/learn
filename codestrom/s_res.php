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

// Check if faculty is logged in
if (!isset($_SESSION['faculty_id'])) {
    header("Location: faculty-login.html");
    exit();
}

// Fetch student responses and performance
$query = "SELECT name, department, section, score FROM students ORDER BY score DESC";
$result = $conn->query($query);

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student Performance</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { 
            margin: 0; padding: 0; box-sizing: border-box; 
            font-family: 'Poppins', sans-serif; 
        }
        body { 
            background: linear-gradient(135deg, #112242, #7b1476); 
            text-align: center; 
            color: white; 
            padding: 40px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container { 
            width: 90%; 
            max-width: 700px; 
            padding: 25px; 
            background: rgba(255, 255, 255, 0.15); 
            border-radius: 15px; 
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3); 
            backdrop-filter: blur(12px);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .container:hover { 
            transform: scale(1.02); 
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.4); 
        }
        h2 { 
            color: #ffcc00; 
            margin-bottom: 15px; 
            font-size: 28px;
            font-weight: 600;
        }
        .response-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        .response-item {
            background: rgba(255, 255, 255, 0.2); 
            padding: 12px; 
            border-radius: 8px; 
            margin: 10px 0; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); 
            font-size: 18px;
            font-weight: 500;
        }
        .score-high { color: #00ff00; font-weight: bold; }
        .score-medium { color: #ffcc00; font-weight: bold; }
        .score-low { color: #ff5555; font-weight: bold; }
        .btn {
            display: inline-block;
            padding: 12px 22px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }
        .back-btn {
            background: linear-gradient(135deg, #ffcc00, #ff9900);
            color: #333;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 25px;
            box-shadow: 0 5px 10px rgba(255, 204, 0, 0.3);
        }
        .back-btn:hover { 
            background: linear-gradient(135deg, #ff9900, #ffcc00);
            transform: scale(1.08); 
            box-shadow: 0 8px 16px rgba(255, 204, 0, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Performance 📊</h2>
        <ul class="response-list">
            <?php if (empty($students)) { ?>
                <p style='opacity: 0.7;'>No student responses available.</p>
            <?php } else {
                foreach ($students as $student) { 
                    $score = $student['score'];
                    $performance = "<span class='score-low'>Needs Improvement ⬇</span>";
                    if ($score >= 80) {
                        $performance = "<span class='score-high'>Excellent Performance ✅</span>";
                    } elseif ($score >= 50) {
                        $performance = "<span class='score-medium'>Good Performance 👍</span>";
                    }
            ?>
                <li class="response-item">
                    <strong><?php echo htmlspecialchars($student['name']); ?></strong> (<?php echo htmlspecialchars($student['department']) . " - " . htmlspecialchars($student['section']); ?>) 
                    <br> Score: <?php echo htmlspecialchars($score); ?> 
                    <br> <?php echo $performance; ?>
                </li>
            <?php } } ?>
        </ul>
        <button class="btn back-btn" onclick="location.href='faculty_dashboard.php'">Back To Faculty</button>
    </div>
</body>
</html>
