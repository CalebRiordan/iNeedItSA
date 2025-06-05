<?php

// PHP script to run all SQL scripts and hash passwords with bcrypt

$conn = new mysqli('localhost', 'root', '', 'ineedit_db');

// Hash the plaintext passwords for all mock users
$result = $conn->query("SELECT user_id, password FROM user");

while ($record = $result->fetch_assoc()) {
    $hashedPassword = password_hash($record['password'], PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE user SET password = ? WHERE user_id = ?");
    $stmt->bind_param("si", $hashedPassword, $record['user_id']);
    $stmt->execute();
    $stmt->close();
}

// repeat for employees
$result = $conn->query("SELECT emp_id, password FROM employee");

while ($record = $result->fetch_assoc()) {
    $hashedPassword = password_hash($record['password'], PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE employee SET password = ? WHERE emp_id = ?");
    $stmt->bind_param("si", $hashedPassword, $record['emp_id']);
    $stmt->execute();
    $stmt->close();
}

echo "User passwords hashed!";
$conn->close();
