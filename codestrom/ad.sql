CREATE DATABASE quiz_management;

USE quiz_management;

CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fav_color VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    department VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    fav_color VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    department VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    fav_color VARCHAR(50) NOT NULL
    section VARCHAR(50) NOT NULL;
    score INT DEFAULT NULL
);

CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT NOT NULL,
    faculty_name VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    deadline_date DATE NOT NULL,
    deadline_time TIME NOT NULL,
    duration INT NOT NULL,
    department VARCHAR(50) NOT NULL,
    section VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id) ON DELETE CASCADE
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    title VARCHAR(255) NOT NULL,
    question_text TEXT NOT NULL,
    option1 TEXT,
    option2 TEXT,
    option3 TEXT,
    option4 TEXT,
    correct_answer TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

CREATE TABLE quiz_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_email VARCHAR(100) NOT NULL,
    quiz_title VARCHAR(255) NOT NULL,
    score INT NOT NULL,
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_email) REFERENCES students(email)
);
