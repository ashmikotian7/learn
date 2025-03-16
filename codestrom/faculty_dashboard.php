<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['faculty_id']) || !isset($_SESSION['faculty_name']) || !isset($_SESSION['faculty_email']) || !isset($_SESSION['faculty_department'])) {
    header("Location: faculty-login.php");
    exit();
}

// Fetch faculty details from session
$facultyID = $_SESSION['faculty_id'];
$name = $_SESSION['faculty_name'];
$email = $_SESSION['faculty_email'];
$department = $_SESSION['faculty_department'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Poppins', sans-serif; 
            background: #0f172a; 
            color: white; 
            text-align: center;
            padding: 20px;
        }

        .container { 
            width: 60%; 
            margin: 20px auto; 
            padding: 20px; 
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px; 
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        }

        .header {
            padding: 15px;
            background: linear-gradient(90deg, rgba(255, 204, 0, 0.8), rgba(255, 165, 0, 0.8));
            color: black;
            font-size: 22px;
            font-weight: 600;
            border-radius: 10px 10px 0 0;
            text-shadow: 1px 1px 8px rgba(255, 255, 255, 0.5);
        }

        h2 { 
            margin: 20px 0; 
            color: #ffcc00;
            text-shadow: 0 2px 10px rgba(255, 204, 0, 0.8);
        }

        .btn { 
            display: block; 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0; 
            text-decoration: none; 
            border-radius: 5px; 
            text-align: center;
            font-weight: 600;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.2);
        }

        .btn.create { background: #b083e0; color: #0f172a; }
        .btn.responses { background: #b2e7be; color: #0f172a; }
        .btn.performance { background: #8dc3e2; color: black; }

        .btn:hover { background: white; color: black; }

        .logout-btn {
            background: linear-gradient(90deg, #ff4b2b, #ff416c);
            color: white;
        }

        .logout-btn:hover {
            background: white;
            color: black;
        }

    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            Teacher Dashboard
        </div>

        <h2>Welcome, <?php echo htmlspecialchars($name); ?></h2>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <p>Department: <?php echo htmlspecialchars($department); ?></p>

        <a href="create_quiz.php" class="btn create">Create Quiz</a>
        <a href="s_res.php" class="btn responses">View Student Responses</a>
        <a href="s_performance.php" class="btn performance">Leader Board</a>
        <a href="logout.html" class="btn logout-btn">Logout</a>
    </div>

</body>
</html>
