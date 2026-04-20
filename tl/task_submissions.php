<?php
session_start();
include "../db/connection.php";

/* Allow only TL */
if($_SESSION['role'] != 'team_leader') {
    header("Location: ../index.php");
    exit;
}

$tl_id = $_SESSION['user_id'];

/* Fetch submissions */
$query = "
SELECT 
    ts.id AS submission_id,
    t.title AS task_title,
    u.name AS employee_name,
    ts.file_path,
    ts.remarks,
    ts.submitted_at,
    ts.forwarded_to_manager
FROM task_submissions ts
INNER JOIN tasks t ON ts.task_id = t.id
INNER JOIN users u ON ts.submitted_by = u.id
WHERE t.assigned_by = $tl_id
ORDER BY ts.submitted_at DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task Submissions</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        h2 {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #007bff;
            color: white;
            padding: 10px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .btn {
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-forward {
            background: #28a745;
            color: white;
        }

        .btn-back {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-back:hover {
            background: #5a6268;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 5px;
            color: white;
            font-size: 12px;
        }

        .forwarded {
            background: green;
        }

        .pending {
            background: orange;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>
</head>

<body>

<div class="container">
    <div class="card">

        <h2>Task Submissions (From Employees)</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Task</th>
                <th>Employee</th>
                <th>File</th>
                <th>Remarks</th>
                <th>Date</th>
                <th>Status</th>
            </tr>

        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?= $row['submission_id']; ?></td>
                <td><?= $row['task_title']; ?></td>
                <td><?= $row['employee_name']; ?></td>
                <td>
                    <?php if ($row['file_path']) { ?>
                        <a href="../<?= $row['file_path']; ?>" target="_blank">View</a>
                    <?php } else { echo "No File"; } ?>
                </td>
                <td><?= $row['remarks']; ?></td>
                <td><?= $row['submitted_at']; ?></td>
                <td>
                    <?php if ($row['forwarded_to_manager'] == 1) { ?>
                        <span class="badge forwarded">Forwarded</span>
                    <?php } else { ?>
                        <form method="POST" class="forwardTask">
                            <input type="hidden" name="submission_id" value="<?= $row['submission_id']; ?>">
                            <button class="btn btn-forward" type="submit">
                                Forward
                            </button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
        <?php
            }
        } else {
        ?>
            <tr>
                <td colspan="7">No submissions found</td>
            </tr>
        <?php } ?>
        </table>

        <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>

    </div>
</div>

<script src="forward_to_manager.js"></script>

</body>
</html>