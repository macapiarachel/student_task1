<?php

session_start();
require_once "config_database.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit();

}


$id = $_GET["id"] ?? 0;


$stmt = $conn->prepare(
    "DELETE FROM tasks
     WHERE id = ?
     AND user_id = ?"
);


$stmt->execute([

    $id,
    $_SESSION["user_id"]

]);


header("Location: tasks.php");
exit();

?>