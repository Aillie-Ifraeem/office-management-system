<?php

session_start();

include 'db/connection.php';

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'];
$password = $data['password'];

$query = "SELECT * FROM users WHERE email='$email' AND password = '$password' AND status = 'active'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    $_SESSION ['user_id']= $user['id'];
    $_SESSION ['role']= $user['role'];
    $_SESSION ['name']= $user['name'];
    
    $redirect = "";
    
    switch($user['role']){
            case 'admin': $redirect = "admin/dashboard.php"; break;
            case 'manager': $redirect = "manager/dashboard.php"; break;
            case 'team_leader': $redirect = "tl/dashboard.php"; break;
            case 'employee': $redirect = "employee/dashboard.php"; break;
            case 'finance': $redirect = "finance/dashboard.php"; break;
        }
    
         echo json_encode([
            "status" => "success",
            "redirect" => $redirect
        ]);
}else{
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email or password"
    ]);
}


?>  