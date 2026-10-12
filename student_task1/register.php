<?php

session_start();
require_once "config_database.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $student_id = trim($_POST["student_id"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (
        empty($name) ||
        empty($student_id) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password)
    ) {

        $error = "Please fill in all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } elseif ($password !== $confirm_password) {

        $error = "Passwords do not match.";

    } elseif (strlen($password) < 6) {

        $error = "Password must be at least 6 characters.";

    } else {

        // Check if email already exists
        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->execute([$email]);

        if ($check->fetch()) {

            $error = "Email is already registered.";

        } else {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Insert user
            $sql = "INSERT INTO users
                    (student_id, name, email, password)
                    VALUES (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                $student_id,
                $name,
                $email,
                $hashed_password
            ]);

            $success = "Account created successfully! You can now login.";

        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Register - Student Task Management</title>

    <link rel="stylesheet" href="style.css">

</head>

<body class="login-body">

<div class="login-container">

    <h1>Create Account</h1>

    <p>Student Task and Activity Management System</p>

    <?php if ($error): ?>

        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>


    <?php if ($success): ?>

        <div class="success">
            <?= htmlspecialchars($success) ?>
        </div>

    <?php endif; ?>


    <form method="POST">

        <label>Name</label>

        <input
            type="text"
            name="name"
            placeholder="Enter your name"
            required
        >


        <label>Student ID</label>

        <input
            type="text"
            name="student_id"
            placeholder="Enter your student ID"
            required
        >


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


        <label>Confirm Password</label>

        <input
            type="password"
            name="confirm_password"
            placeholder="Confirm your password"
            required
        >


        <button type="submit">
            Create Account
        </button>

    </form>


    <p class="register-link">

        Already have an account?

        <a href="login.php">
            Login here
        </a>

    </p>

</div>

</body>

</html>