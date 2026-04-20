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

$manager_task_id = $_POST['manager_task_id'];
$submission_text = $_POST['submission_text'];
$tl_id = $_SESSION['user_id'];

$filePath = null;

if (!empty($_FILES['submission_file']['name'])) {
    $fileName = time() . "_" . $_FILES['submission_file']['name'];
    $targetPath = "../uploads/tl_submissions/" . $fileName;
    move_uploaded_file($_FILES['submission_file']['tmp_name'], $targetPath);
    $filePath = $fileName;
}




$checkSql = "
    SELECT id FROM tl_task_submissions
    WHERE manager_task_id = '$manager_task_id'
    AND submitted_by_tl = '$tl_id'
    LIMIT 1
";

$checkResult = mysqli_query($conn, $checkSql);

if (mysqli_num_rows($checkResult) > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Task already submitted"
    ]);
    exit;
}




$sql = "
    INSERT INTO tl_task_submissions
    (manager_task_id, submitted_by_tl, submission_text, submission_file)
    VALUES
    ('$manager_task_id', '$tl_id', '$submission_text', '$filePath')
";

mysqli_query($conn, $sql);

$updateSql = "
    UPDATE manager_tasks
    SET status = 'completed'
    WHERE id = '$manager_task_id'
    AND assigned_to_tl = '$tl_id'
";

$updateResult = mysqli_query($conn, $updateSql);

if (!$updateResult) {
    echo json_encode([
        "status" => "error",
        "message" => "Submission saved, but status update failed"
    ]);
    exit;
}


echo json_encode([
    "status" => "success",
    "message" => "successfully submitted"
]);


?>