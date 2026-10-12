<?php

session_start();
require_once "config_database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {

        $error = "Please enter your email and password.";

    } else {

        $sql = "SELECT * FROM users WHERE email = ?";

        $stmt = $conn->prepare($sql);

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($user && password_verify($password, $user["password"])) {

            // Login successful
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["email"] = $user["email"];

            header("Location: dashboard.php");
            exit();

        } else {

            $error = "Invalid email or password.";

        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Login - Student Task Management</title>

    <link rel="stylesheet" href="style.css">

</head>

<body class="login-body">

<div class="login-container">

    <h1>Student Task</h1>

    <p>Activity Management System</p>


    <?php if ($error): ?>

        <div class="error">

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endif; ?>


    <form method="POST">

        <label>Email</label>

        <input
            type="email"
            name="email"
            placeholder="Enter your email"
            required
        >


        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Enter your password"
            required
        >


        <button type="submit">
            Login
        </button>

    </form>


    <p class="register-link">

        Don't have an account?

        <a href="register.php">
            Create Account
        </a>

    </p>

</div>

</body>

</html>