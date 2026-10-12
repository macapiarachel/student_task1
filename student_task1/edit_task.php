<?php

session_start();
require_once "config_database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}


$id = $_GET["id"] ?? 0;


$stmt = $conn->prepare(
    "SELECT *
     FROM tasks
     WHERE id = ?
     AND user_id = ?"
);

$stmt->execute([
    $id,
    $_SESSION["user_id"]
]);


$task = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$task) {

    die("Task not found.");

}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "UPDATE tasks SET
            title = ?,
            description = ?,
            subject = ?,
            deadline = ?,
            priority = ?,
            status = ?
            WHERE id = ?
            AND user_id = ?";


    $stmt = $conn->prepare($sql);


    $stmt->execute([

        trim($_POST["title"]),
        trim($_POST["description"]),
        trim($_POST["subject"]),
        $_POST["deadline"],
        $_POST["priority"],
        $_POST["status"],
        $id,
        $_SESSION["user_id"]

    ]);


    header("Location: tasks.php");
    exit();

}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Edit Task</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>


<div class="sidebar">

    <h2>Student Task</h2>

    <a href="dashboard.php">
        Dashboard
    </a>

    <a href="tasks.php">
        My Tasks
    </a>

    <a href="add_task.php">
        Add Task
    </a>

    <a href="logout.php">
        Logout
    </a>

</div>


<div class="main">

    <div class="topbar">

        <h1>Edit Task</h1>

    </div>


    <div class="form-box">

        <form method="POST">


            <label>Task Title</label>

            <input
                type="text"
                name="title"
                value="<?= htmlspecialchars($task["title"]) ?>"
                required
            >


            <label>Description</label>

            <textarea name="description"><?= htmlspecialchars($task["description"]) ?></textarea>


            <label>Subject</label>

            <input
                type="text"
                name="subject"
                value="<?= htmlspecialchars($task["subject"]) ?>"
                required
            >


            <label>Deadline</label>

            <input
                type="date"
                name="deadline"
                value="<?= htmlspecialchars($task["deadline"]) ?>"
                required
            >


            <label>Priority</label>

            <select name="priority">

                <option value="Low"
                    <?= $task["priority"] == "Low" ? "selected" : "" ?>>
                    Low
                </option>

                <option value="Medium"
                    <?= $task["priority"] == "Medium" ? "selected" : "" ?>>
                    Medium
                </option>

                <option value="High"
                    <?= $task["priority"] == "High" ? "selected" : "" ?>>
                    High
                </option>

            </select>


            <label>Status</label>

            <select name="status">

                <option value="Pending"
                    <?= $task["status"] == "Pending" ? "selected" : "" ?>>
                    Pending
                </option>

                <option value="In Progress"
                    <?= $task["status"] == "In Progress" ? "selected" : "" ?>>
                    In Progress
                </option>

                <option value="Completed"
                    <?= $task["status"] == "Completed" ? "selected" : "" ?>>
                    Completed
                </option>

            </select>


            <button type="submit">
                Update Task
            </button>


            <a
                href="tasks.php"
                class="cancel-button"
            >
                Cancel
            </a>

        </form>

    </div>

</div>

</body>

</html>