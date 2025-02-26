<?php
// create_task.php
require_once 'session.php';
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $title = filter_input(INPUT_POST, 'title');
    $description = filter_input(INPUT_POST, 'description');
    $priority = filter_input(INPUT_POST, 'priority');
    $deadline = filter_input(INPUT_POST, 'deadline');

    if (empty($title) || empty($priority) || empty($deadline)) {
        die("Title, priority, and deadline are required");
    }

    $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, priority, deadline) VALUES (:user_id, :title, :description, :priority, :deadline)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':priority', $priority);
    $stmt->bindParam(':deadline', $deadline);

    if ($stmt->execute()) {
        header("Location: index.php?task_created=success");
    } else {
        echo "Failed to create task. Please try again.";
    }
} else {
    header("Location: index.php");
}