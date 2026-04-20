<?php
session_start();
include '../db/connection.php';

// Only allow employees
if ($_SESSION['role'] !== 'employee') {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get tasks assigned to this employee along with their latest submission info
$query = "
SELECT 
    t.id, 
    t.title, 
    t.description, 
    t.status, 
    ts.file_path, 
    ts.submitted_at
FROM tasks t
LEFT JOIN task_submissions ts
    ON t.id = ts.task_id
    AND ts.submitted_by = $user_id
    AND ts.submitted_at = (
        SELECT MAX(ts2.submitted_at)
        FROM task_submissions ts2
        WHERE ts2.task_id = t.id AND ts2.submitted_by = $user_id
    )
WHERE t.assigned_to = $user_id
ORDER BY t.created_at DESC
";

$result = mysqli_query($conn, $query);

$tasks = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tasks[] = $row;
}

// Send JSON response
echo json_encode([
    "status" => "success",
    "tasks" => $tasks
]);
?>
