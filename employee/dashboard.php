<?php
session_start();

if($_SESSION['role'] != 'employee') {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        h2, h3 {
            margin-top: 0;
            color: #333;
        }

        .welcome {
            margin-bottom: 10px;
        }

        button {
            padding: 10px 15px;
            background: #28a745;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #218838;
        }

        button:disabled {
            background: #aaa;
            cursor: not-allowed;
        }

        #attendanceMsg {
            margin-top: 10px;
            font-size: 14px;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        #taskList {
            margin-top: 10px;
        }

        .logout {
            display: inline-block;
            padding: 10px 15px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        .logout:hover {
            background: #c82333;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card">
        <h2>Employee Dashboard</h2>
        <p class="welcome">Welcome, <strong><?php echo $_SESSION['name']; ?></strong></p>

        <button id="markAttendance">Mark Attendance</button>
        <p id="attendanceMsg"></p>
    </div>

    <div class="card">
        <h3>My Tasks</h3>
        <div id="taskList">Loading tasks...</div>
    </div>

    <a class="logout" href="../logout.php">Logout</a>

</div>

<script src="tasks_script2.js"></script>
<script src="submit_task.js"></script>

<script>
document.getElementById("markAttendance").addEventListener("click", async () => {

    const btn = document.getElementById("markAttendance");
    const msg = document.getElementById("attendanceMsg");

    btn.disabled = true;
    btn.innerText = "Marking...";

    const now = new Date();
    const date = now.toISOString().split('T')[0];
    const time = now.toLocaleTimeString();

    try {
        const response = await fetch("../attendance_process.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ date, time })
        });

        const result = await response.json();

        msg.innerText = result.message;

        if (result.status === "success") {
            msg.className = "success";
        } else {
            msg.className = "error";
        }

    } catch (err) {
        msg.innerText = "Something went wrong!";
        msg.className = "error";
    }

    btn.disabled = false;
    btn.innerText = "Mark Attendance";
});
</script>

</body>
</html>