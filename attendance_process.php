<?php
session_start();


include 'db/connection.php';

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$date = $data['date'];
$time = $data['time'];


// Check if attendance already exists
$check = mysqli_query($conn, "SELECT * FROM attendance WHERE user_id='$user_id' AND date='$date'");
if(mysqli_num_rows($check) > 0){
    echo json_encode([
        "status" => "error",
        "message" => "Attendance already marked for today"
    ]);
    exit;
}


// Insert attendance
$query = "INSERT INTO attendance (user_id, date, time) VALUES ('$user_id', '$date', '$time')";

if (mysqli_query($conn, $query)) {
    echo json_encode([
        "status" => "success",
        "message" => "Attendance marker at $time"
    ]);
}else{
    echo json_encode([
        "status" => "error",
        "message" => "Something went wrong"
    ]);
}
?>