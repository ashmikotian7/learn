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

// Fetch student scores
$query = "SELECT name, department, section, score FROM students ORDER BY score DESC, name ASC";
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
    <title>Leaderboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { 
            margin: 0; padding: 0; box-sizing: border-box; 
            font-family: 'Poppins', sans-serif; 
        }
        body { 
            background: linear-gradient(135deg, #4377d6, #0f3619, #120511); 
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
            max-width: 600px; 
            padding: 25px; 
            background: rgba(255, 255, 255, 0.15); 
            border-radius: 15px; 
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3); 
            backdrop-filter: blur(12px);
        }
        h2 { 
            color: #ffcc00; 
            margin-bottom: 15px; 
            font-size: 30px;
            font-weight: 600;
        }
        .score-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        .score-item {
            background: rgba(255, 255, 255, 0.2); 
            padding: 12px; 
            border-radius: 8px; 
            margin: 10px 0; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); 
            font-size: 18px;
            font-weight: 500;
        }
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
        <h2>Leaderboard 🏆</h2>
        <ul class="score-list">
            <?php if (empty($students)) { ?>
                <p style='opacity: 0.7;'>No scores available.</p>
            <?php } else {
                foreach ($students as $index => $student) { ?>
                    <li class="score-item">
                        <strong>#<?php echo $index + 1; ?> <?php echo htmlspecialchars($student['name']); ?></strong>
                        (<?php echo htmlspecialchars($student['department']) . " - " . htmlspecialchars($student['section']); ?>)
                        <br>Score: <?php echo htmlspecialchars($student['score']); ?>
                    </li>
            <?php } } ?>
        </ul>
        <button class="btn back-btn" onclick="location.href='faculty_dashboard.php'">Back To Dashboard</button>
    </div>
</body>
</html>
