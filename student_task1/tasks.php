<?php

session_start();
require_once "config_database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}

$user_id = $_SESSION["user_id"];

$search = trim($_GET["search"] ?? "");
$status = $_GET["status"] ?? "";


$sql = "SELECT *
        FROM tasks
        WHERE user_id = ?";

$params = [$user_id];


if ($search !== "") {

    $sql .= " AND
             (title LIKE ?
              OR subject LIKE ?)";

    $params[] = "%$search%";
    $params[] = "%$search%";

}


if ($status !== "") {

    $sql .= " AND status = ?";

    $params[] = $status;

}


$sql .= " ORDER BY deadline ASC";


$stmt = $conn->prepare($sql);

$stmt->execute($params);

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html>

<head>

    <title>My Tasks</title>

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

        <h1>My Tasks</h1>

    </div>


    <div class="content-box">


        <form
            method="GET"
            class="search-form"
        >

            <input
                type="text"
                name="search"
                placeholder="Search task or subject..."
                value="<?= htmlspecialchars($search) ?>"
            >


            <select name="status">

                <option value="">
                    All Status
                </option>

                <option value="Pending"
                    <?= $status == "Pending" ? "selected" : "" ?>>
                    Pending
                </option>

                <option value="In Progress"
                    <?= $status == "In Progress" ? "selected" : "" ?>>
                    In Progress
                </option>

                <option value="Completed"
                    <?= $status == "Completed" ? "selected" : "" ?>>
                    Completed
                </option>

            </select>


            <button type="submit">
                Search
            </button>

        </form>


        <br>


        <a
            class="add-button"
            href="add_task.php"
        >
            + Add New Task
        </a>


        <table>

            <tr>

                <th>Task</th>

                <th>Subject</th>

                <th>Deadline</th>

                <th>Priority</th>

                <th>Status</th>

                <th>Action</th>

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
                            <?= htmlspecialchars($task["priority"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($task["status"]) ?>
                        </td>

                        <td>

                            <a href="edit_task.php?id=<?= $task["id"] ?>">
                                Edit
                            </a>

                            |

                            <a
                                href="delete_task.php?id=<?= $task["id"] ?>"
                                onclick="return confirm('Delete this task?')"
                            >
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td colspan="6">
                    </td>

                </tr>

            <?php endif; ?>

        </table>

    </div>

</div>

</body>

</html>