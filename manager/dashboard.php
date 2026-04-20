<?php
session_start();
include "../db/connection.php";

if ($_SESSION['role'] != 'manager') {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            margin-top: 0;
            color: #333;
        }

        .welcome {
            margin-bottom: 20px;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th {
            background: #007bff;
            color: white;
            padding: 10px;
            text-align: left;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        table tr:hover {
            background: #f1f1f1;
        }

        .logout {
            display: inline-block;
            padding: 10px 15px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout:hover {
            background: #c82333;
        }

        .section-title {
            margin-top: 30px;
            margin-bottom: 10px;
            color: #444;
        }
    </style>
</head>
<body>

<div class="container">

    <h2>Manager Dashboard</h2>
    <p class="welcome">Welcome, <strong><?= $_SESSION['name']; ?></strong></p>

    <?php
    // Team Leaders
    $tlquery = "SELECT id, name, email FROM users WHERE role = 'team_leader'";
    $tlresult = mysqli_query($conn, $tlquery);

    // Employees with TL Name (JOIN instead of query inside loop)
    $empQuery = "
        SELECT e.id, e.name, e.email, tl.name AS tl_name
        FROM users e
        LEFT JOIN users tl ON e.tl_id = tl.id
        WHERE e.role = 'employee'
    ";
    $empresult = mysqli_query($conn, $empQuery);
    ?>

    <h3 class="section-title">Team Leaders</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
        </tr>

        <?php while ($tl = mysqli_fetch_assoc($tlresult)) { ?>
        <tr>
            <td><?= $tl['id'] ?></td>
            <td><?= $tl['name'] ?></td>
            <td><?= $tl['email'] ?></td>
        </tr>
        <?php } ?>
    </table>

    <h3 class="section-title">Team Members</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Team Leader</th>
        </tr>

        <?php while ($emp = mysqli_fetch_assoc($empresult)) { ?>
        <tr>
            <td><?= $emp['id'] ?></td>
            <td><?= $emp['name'] ?></td>
            <td><?= $emp['email'] ?></td>
            <td><?= $emp['tl_name'] ? $emp['tl_name'] : "Not Assigned" ?></td>
        </tr>
        <?php } ?>
    </table>

    <a class="logout" href="../logout.php">Logout</a>

</div>

</body>
</html>