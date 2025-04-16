<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch summary data
$total_income = $pdo->query("SELECT SUM(amount) as total FROM invoices WHERE user_id = $user_id AND status = 'paid'")->fetch()['total'] ?? 0;
$total_expenses = $pdo->query("SELECT SUM(amount) as total FROM expenses WHERE user_id = $user_id")->fetch()['total'] ?? 0;
$balance = $total_income - $total_expenses;

// Fetch recent invoices and expenses
$invoices = $pdo->query("SELECT * FROM invoices WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 5")->fetchAll();
$expenses = $pdo->query("SELECT * FROM expenses WHERE user_id = $user_id ORDER BY date DESC LIMIT 5")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickBooks Clone - Dashboard</title>
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
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .dashboard-header h1 {
            color: #2c3e50;
            font-size: 28px;
        }
        .overview {
            display: flex;
            gap: 20px;
            margin-bottom: 40px;
        }
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            flex: 1;
            text-align: center;
        }
        .card h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .card p {
            font-size: 24px;
            color: #0073e6;
        }
        .section {
            margin-bottom: 40px;
        }
        .section h2 {
            color: #2c3e50;
            margin-bottom: 20px;
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
        .btn {
            padding: 10px 20px;
            background: #0073e6;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #005bb5;
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
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <a href="invoices.php" class="btn">Create Invoice</a>
        </div>
        <div class="overview">
            <div class="card">
                <h3>Total Income</h3>
                <p>$<?php echo number_format($total_income, 2); ?></p>
            </div>
            <div class="card">
                <h3>Total Expenses</h3>
                <p>$<?php echo number_format($total_expenses, 2); ?></p>
            </div>
            <div class="card">
                <h3>Account Balance</h3>
                <p>$<?php echo number_format($balance, 2); ?></p>
            </div>
        </div>
        <div class="section">
            <h2>Recent Invoices</h2>
            <table>
                <tr>
                    <th>Client</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><?php echo htmlspecialchars($invoice['client_name']); ?></td>
                    <td>$<?php echo number_format($invoice['amount'], 2); ?></td>
                    <td><?php echo $invoice['due_date']; ?></td>
                    <td><?php echo $invoice['status']; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="section">
            <h2>Recent Expenses</h2>
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
    </div>
</body>
</html>
