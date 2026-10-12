<?php

session_start();
require_once "config_database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $subject = trim($_POST["subject"]);
    $deadline = $_POST["deadline"];
    $priority = $_POST["priority"];
    $status = $_POST["status"];


    $sql = "INSERT INTO tasks
            (user_id, title, description, subject, deadline, priority, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)";


    $stmt = $conn->prepare($sql);


    $stmt->execute([

        $_SESSION["user_id"],
        $title,
        $description,
        $subject,
        $deadline,
        $priority,
        $status

    ]);


    header("Location: tasks.php");
    exit();

}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Add Task</title>

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

        <h1>Add New Task</h1>

    </div>


    <div class="form-box">

        <form method="POST">


            <label>Task Title</label>

            <input
                type="text"
                name="title"
                required
            >


            <label>Description</label>

            <textarea
                name="description"
            ></textarea>


            <label>Subject</label>

            <input
                type="text"
                name="subject"
                required
            >


            <label>Deadline</label>

            <input
                type="date"
                name="deadline"
                required
            >


            <label>Priority</label>

            <select name="priority">

                <option value="Low">
                    Low
                </option>

                <option value="Medium" selected>
                    Medium
                </option>

                <option value="High">
                    High
                </option>

            </select>


            <label>Status</label>

            <select name="status">

                <option value="Pending">
                    Pending
                </option>

                <option value="In Progress">
                    In Progress
                </option>

                <option value="Completed">
                    Completed
                </option>

            </select>


            <button type="submit">
                Save Task
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