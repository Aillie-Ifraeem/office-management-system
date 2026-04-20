<?php
session_start();
include "../db/connection.php";

/* Allow only TL */
if($_SESSION['role'] != 'team_leader') {
    header("Location: ../index.php");
}


$submission_id = $_POST['submission_id'];

// Update submission status
mysqli_query(
    $conn,
    "UPDATE task_submissions 
     SET forwarded_to_manager = 1 
     WHERE id = $submission_id"
);

// Go back to submission list
echo json_encode([
    "status" => "success",
    "message" => "forwarded successfully"
]);
?>