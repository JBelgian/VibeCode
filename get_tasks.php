<?php
// get_tasks.php
require_once 'session.php';
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    die("User not authenticated");
}

$user_id = $_SESSION['user_id'];
$status = filter_input(INPUT_GET, 'status');
$priority = filter_input(INPUT_GET, 'priority');

$query = "SELECT * FROM tasks WHERE user_id = :user_id";
$params = [':user_id' => $user_id];

if ($status) {
    $query .= " AND status = :status";
    $params[':status'] = $status;
}

if ($priority) {
    $query .= " AND priority = :priority";
    $params[':priority'] = $priority;
}

$query .= " ORDER BY deadline ASC";

$stmt = $conn->prepare($query);
$stmt->execute($params);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($tasks as $task): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($task['title']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($task['description']) ?></p>
            <p>Priority: <?= htmlspecialchars($task['priority']) ?></p>
            <p>Status: <?= htmlspecialchars($task['status']) ?></p>
            <p>Deadline: <?= htmlspecialchars($task['deadline']) ?></p>
            <button class="btn btn-sm btn-primary updateStatus" data-task-id="<?= $task['id'] ?>" data-status="in_progress">Start</button>
            <button class="btn btn-sm btn-warning updateStatus" data-task-id="<?= $task['id'] ?>" data-status="pending">Pause</button>
            <button class="btn btn-sm btn-success updateStatus" data-task-id="<?= $task['id'] ?>" data-status="completed">Complete</button>
        </div>
    </div>
<?php endforeach; ?>