<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    $stmt = $pdo->prepare("INSERT INTO expenses (user_id, category, amount, date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $category, $amount, $date]);
    echo "<script>window.location.href='expenses.php';</script>";
}

$expenses = $pdo->query("SELECT * FROM expenses WHERE user_id = $user_id ORDER BY date DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickBooks Clone - Expenses</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background: #f4f7fa;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: #fff;
            height: 100vh;
            position: fixed;
            padding: 20px;
        }
        .sidebar h2 {
            font-size: 24px;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background: #34495e;
        }
        .main-content {
            margin-left: 250px;
            padding: 40px;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }
        .form-container h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .form-container input, .form-container select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-container button {
            padding: 12px;
            background: #0073e6;
            border: none;
            color: #fff;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .form-container button:hover {
            background: #005bb5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #0073e6;
            color: #fff;
        }
        table tr:hover {
            background: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>QuickBooks</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="invoices.php">Invoices</a>
        <a href="expenses.php">Expenses</a>
        <a href="reports.php">Reports</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="main-content">
        <div class="form-container">
            <h2>Add Expense</h2>
            <form method="POST">
                <select name="category" required>
                    <option value="">Select Category</option>
                    <option value="Rent">Rent</option>
                    <option value="Utilities">Utilities</option>
                    <option value="Supplies">Supplies</option>
                    <option value="Other">Other</option>
                </select>
                <input type="number" name="amount" placeholder="Amount" step="0.01" required>
                <input type="date" name="date" required>
                <button type="submit">Add</button>
            </form>
        </div>
        <h2>All Expenses</h2>
        <table>
            <tr>
                <th>Category</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
            <?php foreach ($expenses as $expense): ?>
            <tr>
                <td><?php echo htmlspecialchars($expense['category']); ?></td>
                <td>$<?php echo number_format($expense['amount'], 2); ?></td>
                <td><?php echo $expense['date']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
