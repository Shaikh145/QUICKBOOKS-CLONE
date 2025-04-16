<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$name, $email, $password]);
        echo "<script>window.location.href='index.php';</script>";
    } catch (PDOException $e) {
        $error = "Email already exists!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickBooks Clone - Sign Up</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #0073e6, #00c4b4);
        }
        .signup-container {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }
        .signup-container h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .signup-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .signup-container button {
            width: 100%;
            padding: 12px;
            background: #0073e6;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .signup-container button:hover {
            background: #005bb5;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        .login-link {
            margin-top: 15px;
            color: #0073e6;
            text-decoration: none;
            font-size: 14px;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1>Sign Up</h1>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <a href="index.php" class="login-link">Already have an account? Login</a>
    </div>
</body>
</html>
