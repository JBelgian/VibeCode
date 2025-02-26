<?php
// update_task.php
require_once 'session.php';
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $task_id = filter_input(INPUT_POST, 'task_id', FILTER_SANITIZE_NUMBER_INT);
    $status = filter_input(INPUT_POST, 'status');

    if (empty($task_id) || empty($status)) {
        die(json_encode(['success' => false, 'message' => 'Task ID and status are required']));
    }

    $stmt = $conn->prepare("UPDATE tasks SET status = :status WHERE id = :task_id AND user_id = :user_id");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':task_id', $task_id);
    $stmt->bindParam(':user_id', $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Task status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update task status']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}