<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch report data
$total_income = $pdo->query("SELECT SUM(amount) as total FROM invoices WHERE user_id = $user_id AND status = 'paid'")->fetch()['total'] ?? 0;
$total_expenses = $pdo->query("SELECT SUM(amount) as total FROM expenses WHERE user_id = $user_id")->fetch()['total'] ?? 0;
$balance = $total_income - $total_expenses;

$income_by_month = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(amount) as total FROM invoices WHERE user_id = $user_id AND status = 'paid' GROUP BY month")->fetchAll();
$expenses_by_category = $pdo->query("SELECT category, SUM(amount) as total FROM expenses WHERE user_id = $user_id GROUP BY category")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickBooks Clone - Reports</title>
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
        .section {
            margin-bottom: 40px;
        }
        .section h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .card p {
            font-size: 24px;
            color: #0073e6;
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
        <div class="section">
            <h2>Financial Summary</h2>
            <div class="card">
                <h3>Total Income</h3>
                <p>$<?php echo number_format($total_income, 2); ?></p>
            </div>
            <div class="card">
                <h3>Total Expenses</h3>
                <p>$<?php echo number_format($total_expenses, 2); ?></p>
            </div>
            <div class="card">
                <h3>Net Balance</h3>
                <p>$<?php echo number_format($balance, 2); ?></p>
            </div>
        </div>
        <div class="section">
            <h2>Income by Month</h2>
            <table>
                <tr>
                    <th>Month</th>
                    <th>Total</th>
                </tr>
                <?php foreach ($income_by_month as $row): ?>
                <tr>
                    <td><?php echo $row['month']; ?></td>
                    <td>$<?php echo number_format($row['total'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="section">
            <h2>Expenses by Category</h2>
            <table>
                <tr>
                    <th>Category</th>
                    <th>Total</th>
                </tr>
                <?php foreach ($expenses_by_category as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td>$<?php echo number_format($row['total'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
