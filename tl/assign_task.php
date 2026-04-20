<?php
session_start();
include "../db/connection.php";

/* Allow only TL */
if($_SESSION['role'] != 'team_leader') {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized"
    ]);
    exit;
}

$title = $_POST['title'];
$description = $_POST['description'];
$assigned_to = $_POST['assigned_to'];
$assigned_by = $_SESSION['user_id'];

$filePath = NULL;

/* Handle file upload */
if (!empty($_FILES['task_file']['name'])) {
    $fileName = time() . "_" . basename($_FILES['task_file']['name']);
    $targetPath = "../uploads/task_files/" . $fileName;

    if (move_uploaded_file($_FILES['task_file']['tmp_name'], $targetPath)) {
        $filePath = "uploads/task_files/" . $fileName;
    }
}

mysqli_query($conn, "
    INSERT INTO tasks 
    (title, description, task_file, assigned_by, assigned_to, status, created_at)
    VALUES 
    ('$title', '$description', '$filePath', $assigned_by, $assigned_to, 'assigned', NOW())
");

echo json_encode([
    "status" => "success",
    "message" => "task assigned successfully"
]);


?>