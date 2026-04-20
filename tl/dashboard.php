<?php
session_start();
include "../db/connection.php";

if($_SESSION['role'] != 'team_leader') {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>TL Dashboard</title>

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
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        h1, h2, h3 {
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

        .btn-assign {
            background: #007bff;
            color: white;
        }

        .btn-submit {
            background: #28a745;
            color: white;
        }

        .btn-cancel {
            background: #dc3545;
            color: white;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 12px;
            color: white;
        }

        .pending {
            background: orange;
        }

        .submitted {
            background: green;
        }

        a {
            text-decoration: none;
        }

        .link-btn {
            display: inline-block;
            padding: 10px 15px;
            background: #6c757d;
            color: white;
            border-radius: 5px;
            margin-top: 10px;
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 400px;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card" style="display:flex; justify-content:space-between; align-items:center;">
    <div>
        <h1>Welcome <?= $_SESSION['name'] ?></h1>
        <p>This is your dashboard.</p>
    </div>

    <a href="../logout.php" class="btn btn-cancel">Logout</a>
</div>

    <div class="card">
        <h3>Tasks From Manager</h3>

        <?php
        $tl_id = $_SESSION['user_id'];

        $sql = "
            SELECT 
                mt.id,
                mt.title,
                mt.description,
                mt.file_path,
                mt.status,
                mt.created_at,
                u.name AS manager_name
            FROM manager_tasks mt
            JOIN users u ON mt.assigned_by_manager = u.id
            WHERE mt.assigned_to_tl = $tl_id
            ORDER BY mt.created_at DESC
        ";

        $result = mysqli_query($conn, $sql);
        ?>

        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>File</th>
                <th>Assigned By</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['title'] ?></td>
                <td><?= $row['description'] ?></td>
                <td>
                    <?php if($row['file_path']) { ?>
                        <a href="../uploads/<?= $row['file_path'] ?>" target="_blank">View</a>
                    <?php } else { echo "No File"; } ?>
                </td>
                <td><?= $row['manager_name'] ?></td>
                <td>
                    <span class="badge <?= $row['status'] === 'pending' ? 'pending' : 'submitted' ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <?php if ($row['status'] === 'pending') { ?>
                        <button class="btn btn-submit submitTaskBtn" data-task-id="<?= $row['id'] ?>">
                            Submit
                        </button>
                    <?php } else { echo "Done"; } ?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <div class="card">
        <h2>My Team Members</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>

            <?php
            $query = "SELECT id, name, email FROM users WHERE role='employee' AND tl_id=$tl_id";
            $employeesResult = mysqli_query($conn, $query);

            if (mysqli_num_rows($employeesResult) > 0) {
                while ($emp = mysqli_fetch_assoc($employeesResult)) {
            ?>
            <tr>
                <td><?= $emp['id']; ?></td>
                <td><?= $emp['name']; ?></td>
                <td><?= $emp['email']; ?></td>
                <td>
                    <button class="btn btn-assign assignTaskBtn" data-emp-id="<?= $emp['id']; ?>">
                        Assign
                    </button>
                </td>
            </tr>
            <?php } } else { ?>
            <tr><td colspan="4">No employees found</td></tr>
            <?php } ?>
        </table>
    </div>

    <a class="link-btn" href="task_submissions.php">View Task Submissions</a>

</div>

<!-- Submit Modal -->
<div id="tlSubmitModal" class="modal">
    <div class="modal-content">
        <h3>Submit Task</h3>
        <form id="tlSubmitForm" enctype="multipart/form-data">
            <input type="hidden" name="manager_task_id" id="managerTaskId">

            <label>Notes</label>
            <textarea name="submission_text" required></textarea>

            <label>File</label>
            <input type="file" name="submission_file">

            <button class="btn btn-submit" type="submit">Submit</button>
            <button class="btn btn-cancel" type="button" onclick="closeTLModal()">Cancel</button>
        </form>
    </div>
</div>

<!-- Assign Modal -->
<div id="assignTaskModal" class="modal">
    <div class="modal-content">
        <h3>Assign Task</h3>

        <form id="assignTaskForm" enctype="multipart/form-data">
            <input type="hidden" name="assigned_to" id="assignedTo">

            <label>Title</label>
            <input type="text" name="title" required>

            <label>Description</label>
            <textarea name="description" required></textarea>

            <label>File</label>
            <input type="file" name="task_file">

            <button class="btn btn-assign" type="submit">Assign</button>
            <button class="btn btn-cancel" type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>
</div>

<script src="assign_task.js"></script>
<script src="tl_manager_task.js"></script>


</body>
</html>