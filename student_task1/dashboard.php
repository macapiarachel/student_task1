<?php

session_start();
require_once "config_database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

$user_id = $_SESSION["user_id"];
$name = $_SESSION["name"];


$total = $conn->prepare(
    "SELECT COUNT(*) FROM tasks WHERE user_id = ?"
);

$total->execute([$user_id]);

$total_tasks = $total->fetchColumn();


$pending = $conn->prepare(
    "SELECT COUNT(*) FROM tasks
     WHERE user_id = ?
     AND status = 'Pending'"
);

$pending->execute([$user_id]);

$pending_tasks = $pending->fetchColumn();


$progress = $conn->prepare(
    "SELECT COUNT(*) FROM tasks
     WHERE user_id = ?
     AND status = 'In Progress'"
);

$progress->execute([$user_id]);

$in_progress = $progress->fetchColumn();


$completed = $conn->prepare(
    "SELECT COUNT(*) FROM tasks
     WHERE user_id = ?
     AND status = 'Completed'"
);

$completed->execute([$user_id]);

$completed_tasks = $completed->fetchColumn();


$upcoming = $conn->prepare(
    "SELECT *
     FROM tasks
     WHERE user_id = ?
     AND status != 'Completed'
     ORDER BY deadline ASC
     LIMIT 5"
);

$upcoming->execute([$user_id]);

$tasks = $upcoming->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html>

<head>

    <title>Dashboard</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>


<div class="sidebar">

    <h2>Student Task</h2>

    <a href="dashboard.php">Dashboard</a>

    <a href="tasks.php">My Tasks</a>

    <a href="add_task.php">Add Task</a>

    <a href="logout.php">Logout</a>

</div>


<div class="main">

    <div class="topbar">

        <h1>Dashboard</h1>

        <span>
            Welcome,
            <?= htmlspecialchars($name) ?>
        </span>

    </div>


    <div class="cards">

        <div class="card">

            <h3>Total Tasks</h3>

            <strong>
                <?= $total_tasks ?>
            </strong>

        </div>


        <div class="card">

            <h3>Pending</h3>

            <strong>
                <?= $pending_tasks ?>
            </strong>

        </div>


        <div class="card">

            <h3>In Progress</h3>

            <strong>
                <?= $in_progress ?>
            </strong>

        </div>


        <div class="card">

            <h3>Completed</h3>

            <strong>
                <?= $completed_tasks ?>
            </strong>

        </div>

    </div>


    <div class="content-box">

        <div class="box-header">

            <h2>Upcoming Tasks</h2>

            <a
                class="add-button"
                href="add_task.php"
            >
                + Add Task
            </a>

        </div>


        <table>

            <tr>

                <th>Task</th>

                <th>Subject</th>

                <th>Deadline</th>

                <th>Priority</th>

                <th>Status</th>

            </tr>


            <?php if (count($tasks) > 0): ?>

                <?php foreach ($tasks as $task): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($task["title"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($task["subject"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($task["deadline"]) ?>
                        </td>

                        <td>

                            <span class="priority-<?= strtolower($task["priority"]) ?>">

                                <?= htmlspecialchars($task["priority"]) ?>

                            </span>

                        </td>

                        <td>
                            <?= htmlspecialchars($task["status"]) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td colspan="5">
                    </td>

                </tr>

            <?php endif; ?>

        </table>

    </div>

</div>

</body>

</html>