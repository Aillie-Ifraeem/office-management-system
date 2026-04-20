<?php
session_start();
include '../db/connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized"
    ]);
    exit;
}

$task_id = $_POST['task_id'];
$remarks = $_POST['remarks'] ?? "";
$user_id = $_SESSION['user_id'];

$file = $_FILES['task_file'];

$filename = time() . "_" . basename($file['name']);
$targetPath = "../uploads/task_submissions/" . $filename;

/*scurity check: check if the file is already there or not*/
if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode(["status"=>"error","message"=>"File upload failed"]);
    exit;
}


/* Security check: task belongs to this employee */
$check = mysqli_query(
    $conn,
    "SELECT id FROM tasks WHERE id = $task_id AND assigned_to = $user_id"
);

if (mysqli_num_rows($check) === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid task"
    ]);
    exit;
}

/* Insert submission (file comes later) */
mysqli_query($conn, "
    INSERT INTO task_submissions 
    (task_id, submitted_by, file_path, remarks, submitted_at)
    VALUES ($task_id, $user_id,'$targetPath','$remarks', NOW())
");

/* Update task status */
mysqli_query($conn, "
    UPDATE tasks 
    SET status = 'submitted'
    WHERE id = $task_id
");

echo json_encode([
    "status" => "success",
    "message" => "Task submitted successfully"
]);
